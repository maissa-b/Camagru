<?php

function	del_table_msg($tab_name)
{
	echo "-\033[32m Table \033[0m".$tab_name."\033[32m has been deleted.\033[0m\n";
}

function	add_table_msg($tab_name)
{
	echo "+\033[32m Table \033[0m".$tab_name."\033[32m has been created.\033[0m\n";
}

function	get_db_table_names($db, $var_pdo)
{
	$req = $var_pdo->prepare('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = "BASE TABLE" AND TABLE_SCHEMA = ?');
	$req->execute([$db]);
	$object = $req->fetchAll();
	return ($object);
}

function	get_table_content($table, $var_pdo)
{
	$req = $var_pdo->prepare('SELECT * FROM '.$table.'');
	$req->execute([]);
	$object = $req->fetchAll();
	return ($object);
}

function	drop_table($table, $var_pdo)
{
	$req = $var_pdo->prepare('DROP TABLE '.$table);
	$result = $req->execute();
	return ($result);
}

function	delete_table_content($table, $var_pdo)
{
	$req = $var_pdo->prepare('DELETE FROM '.$table.'');
	$req->execute();
}

function	delete_all_table_content($db, $var_pdo)
{
	$object = get_db_table_names($db, $var_pdo);
	foreach ($object as $obj)
	{
		delete_table_content($obj->TABLE_NAME, $var_pdo);
	}
}

function	switch_db($db, $var_pdo)
{
	$req = $var_pdo->prepare('USE `'.$db.'`');
	$result = $req->execute([]);
	return ($result);
}

function	cpy_directory_content($src, $dst)
{
	$dir = opendir($src);
	while (($file = readdir($dir)) !== FALSE)
	{ 
		if (($file != '.') && ($file != '..'))
		{ 
			if (is_dir($src . '/' . $file))
			{ 
				cpy_directory_content($src . '/' . $file, $dst . '/' . $file); 
			} 
			else
			{
				copy($src . '/' . $file, $dst . '/' . $file); 
			}
		}
	} 
	closedir($dir); 
}

function	fill_img_table($pdo)
{
	cpy_directory_content('../img/ini', '../img/pictures');
	$req = $pdo->prepare('INSERT INTO `db_img` (`id`, `img_name`, `user_id`, `likes`, `creation_date`) VALUES
	(1, "img/pictures/42.png", 1, 0, "2016-09-29"),
	(2, "img/pictures/test57ed1e72c6ed1.png", 1, 0, "2016-09-29"),
	(4, "img/pictures/test57ed1e78ab346.png", 1, 0, "2016-09-29"),
	(5, "img/pictures/test57ed1e7b5590f.png", 1, 0, "2016-09-29"),
	(6, "img/pictures/test57ed1e81dfdff.png", 1, 0, "2016-09-29"),
	(7, "img/pictures/test57ed1e84a5a66.png", 1, 0, "2016-09-29"),
	(9, "img/pictures/test57ed1e8b51465.png", 1, 0, "2016-09-29"),
	(10, "img/pictures/test57ed1e9950e8c.png", 1, 0, "2016-09-29"),
	(11, "img/pictures/test57ed1e9c1febb.png", 1, 0, "2016-09-29"),
	(12, "img/pictures/test57ed1e9f1f4e6.png", 1, 0, "2016-09-29"),
	(13, "img/pictures/test57ed1eab99ed8.png", 1, 0, "2016-09-29"),
	(14, "img/pictures/test57ed1eae89357.png", 1, 0, "2016-09-29"),
	(15, "img/pictures/test57ed1eb4ce51e.png", 1, 0, "2016-09-29");');
	$req->execute([]);

	$req = $pdo->prepare('ALTER TABLE `db_img` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 16');
	$req->execute([]);
}

function	fill_user_table($pdo)
{
	$req = $pdo->prepare('INSERT INTO `db_user`
	(`id`, `username`, `password`, `email`, `validation_token`, `expiration_token`, `reset_token`, `reset_expiration`) VALUES
	(1, "test", "$2y$10$2JZ0CLKiw0GH6rpA5YmDmucszz0I9nUjyqh/ohosGGz/rf7SkKgRO", "mehdi.aissa-brahim@hotmail.fr", NULL, "2016-09-29", NULL, NULL);');
	$req->execute([]);
}

function	is_db_set($db_name, $pdo)
{
	$req = $pdo->prepare('SELECT COUNT(TABLE_NAME) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE="BASE TABLE" AND TABLE_SCHEMA = ?');
	$n = $req->execute([$dbname]);
	if ($n == 0)
	{
		return (FALSE);
	}
	return (TRUE);
}

function	create_db($db, $var_pdo)
{
	try
	{
		$req = $var_pdo->prepare('CREATE DATABASE `'.$db.'`');
		$result = $req->execute();
		if ($result === true)
		{
			echo "++ \033[32mDatabase \033[0m".$db."\033[32m has been created.\033[0m\n";
		}
	}
	catch (PDOException $e)
	{
		;
	}
}

function	delete_tables($db)
{
	include_once('database.php');

	try
	{
		$pdo = new PDO ('mysql:dbname='.$db.';localhost:8080', $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}
	catch (PDOException $e)
	{
		 echo "\033[31mCan't connect to \033[0m".htmlspecialchars($db)."\n";
		 $error = 1;
	}

	if ($error != 1)
	{
		$object = get_db_table_names($db, $pdo);
		if ($object != NULL)
			{
			try
			{
				foreach ($object as $table)
				{
					try
					{
						$result = drop_table($table->TABLE_NAME, $pdo);
						if ($result === true)
						{
							del_table_msg($table->TABLE_NAME);
						}
					}
					catch (PDOException $e)
					{
						;
					}
				}
				echo "-- \033[32mTables been deleted.\033[0m\n";
			}
			catch (PDOException $e)
			{
				echo "\033[31mCan't delete tables.\033[0m\n";
			}
		}
		else
		{
			echo "\033[31mThere isn't any tables.\033[0m\n";
		}
	}

	$pdo = NULL;
	unlink('../img/pictures/*.png');
}

function	delete_db($db)
{
	include_once('database.php');

	try
	{
		$pdo = new PDO ('mysql:localhost:8080', $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}
	catch (PDOException $e)
	{
		echo "\033[31mCan't connect.\033[0m\n";
	}
	try
	{
		switch_db($db, $pdo);
	}
	catch (PDOException $e)
	{
		echo "\033[31mCan't connect to \033[0m".htmlspecialchars($db)."\n";
		$error = 1;
	}

	if ($error != 1)
	{
		$object = get_db_table_names($db, $pdo);
		if ($object != NULL)
		{
			delete_tables($db);
		}
		$req = $pdo->prepare('DROP DATABASE IF EXISTS `'.$db.'`');
		$req->execute([]);
		echo "-- \033[32mDatabase has been deleted.\033[0m\n";
	}

	$pdo = NULL;
}

function	create_comment_table($var_pdo)
{
	try
	{
		$req = $var_pdo->prepare('CREATE TABLE `db_comment` (
		  `id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `img_id` int(11) NOT NULL,
		  `comment_value` varchar(255) NOT NULL,
		  `username` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		)');

		$result = $req->execute();
		if ($result === true)
		{
			add_table_msg("db_comment");
		}
		$req = $var_pdo->prepare('ALTER TABLE `db_comment` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
		$req->execute();
	}
	catch (PDOException $e)
	{
		;
	}
}

function	create_img_table($var_pdo)
{
	try
	{
		$req = $var_pdo->prepare('CREATE TABLE `db_img` (
		  `id` int(11) NOT NULL,
		  `img_name` varchar(64) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `likes` int(11) NOT NULL,
		  `creation_date` DATE NOT NULL,
		  PRIMARY KEY (`id`)
		)');

		$result = $req->execute();
		if ($result)
		{
			add_table_msg("db_img");
		}
		$req = $var_pdo->prepare('ALTER TABLE `db_img` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
		$req->execute();
	}
	catch (PDOException $e)
	{
		;
	}
	shell_exec('rm -rf /nfs/2014/m/maissa-b/http/MyWebSite/img/pictures/*');
}

function	create_likes_table($var_pdo)
{
	try
	{
		$req = $var_pdo->prepare('CREATE TABLE `db_likes` (
		  `id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `img_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		)');

		$result = $req->execute();
		if ($result === true)
		{
			add_table_msg("db_likes");
		}
		$req = $var_pdo->prepare('ALTER TABLE `db_likes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
		$req->execute();
	}
	catch (PDOException $e)
	{
		;
	}
}

function	create_user_table($var_pdo)
{
	try
	{
		$req = $var_pdo->prepare('CREATE TABLE `db_user` (
		  `id` int(11) NOT NULL,
		  `username` varchar(255) NOT NULL,
		  `password` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `validation_token` varchar(100) DEFAULT NULL,
		  `expiration_token` date DEFAULT NULL,
		  `reset_token` varchar(255) DEFAULT NULL,
		  `reset_expiration` date DEFAULT NULL,
		  PRIMARY KEY (`id`)
		)');

		$result = $req->execute();
		if ($result === true)
		{
			add_table_msg("db_user");
		}
		$req = $var_pdo->prepare('ALTER TABLE `db_user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
		$req->execute();
	}
	catch (PDOException $e)
	{
		;
	}	
}

function	create_tables($var_pdo)
{
	create_comment_table($var_pdo);
	create_img_table($var_pdo);
	create_likes_table($var_pdo);
	create_user_table($var_pdo);
}

function	setup_db($db)
{
	include_once('database.php');

	$pdo = new PDO ($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	
	create_db($db, $pdo);
	$result = switch_db($db, $pdo);
	if ($result)
	{
		create_tables($pdo);
	}
	$pdo = NULL;
}

?>