<?php

include_once('config_functions.php');
include_once('database.php');

try
{
	$pdo = new PDO ('mysql:dbname='.$DB_NAME.';localhost', $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}
catch (PDOException $e)
{
	echo "\033[31mCan't connect to \033[0m".htmlspecialchars($DB_NAME)."\n";
}

if ($pdo)
{
	$db_ini = is_db_set($DB_NAME, $pdo);
	if ($db_ini === true)
	{
		fill_img_table($pdo);
		fill_user_table($pdo);
	}
}

?>