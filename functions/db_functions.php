<?php

function	connect_db()
{
	include('config/database.php');

	try {
		$pdo = new PDO('mysql:dbname='.$DB_NAME.';host=localhost', $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	} catch (PDOException $e) {
		$pdo = NULL;
	}	
	return ($pdo);
}

function	add_user($array_reg, $var_pdo, $token)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('INSERT INTO `db_user` SET username = ?, password = ?, email = ?, validation_token = ?');
		$password = password_hash(htmlspecialchars($array_reg['password']), PASSWORD_BCRYPT);
		$req->execute([htmlspecialchars($array_reg['username']), htmlspecialchars($password), htmlspecialchars($array_reg['email']), $token]);
	}
}

function	add_img_to_db($filename, $var_pdo)
{
	if ($var_pdo != NULL && $filename != NULL) {
		$req = $var_pdo->prepare('INSERT INTO `db_img` SET img_name = ?, user_id = ?, creation_date = NOW(), likes = 0');
		$req->execute([htmlspecialchars($filename), htmlspecialchars($_SESSION['user_id'])]);
	}
}

function	get_img_id($var_pdo, $name)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT id FROM `db_img` WHERE `db_img`.img_name = ?');
		$req->execute([$name]);
		$id = $req->fetch();
		return ($id->id);
	} else {
		return (NULL);
	}
}

function	get_comments($var_pdo, $id_img)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT comment_value, username FROM `db_comment` WHERE img_id = ?');
		$req->execute([$id_img]);
		$objects = $req->fetchAll();
		return ($objects);
	} else {
		return (NULL);
	}
}

function	delete_img($var_pdo, $id_img, $name_img)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('DELETE FROM `db_comment` WHERE `db_comment`.img_id = ?');
		$req->execute([$id_img]);
		$req = $var_pdo->prepare('DELETE FROM `db_likes` WHERE `db_likes`.img_id = ?');
		$req->execute([$id_img]);
		$req = $var_pdo->prepare('DELETE FROM `db_img` WHERE `db_img`.id = ?');
		$req->execute([$id_img]);
		shell_exec('rm -rf '.$name_img.'');
	}
}

function	add_comment_to_db($var_pdo, $id_img, $comment)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('INSERT INTO `db_comment` SET user_id = ?, img_id = ?, comment_value = ?, username = ?');
		$req->execute([htmlspecialchars($_SESSION['user_id']), htmlspecialchars($id_img), $comment, $_SESSION['username']]);
	}
}

function	get_nb_like($var_pdo, $id_img)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT likes FROM `db_img` WHERE `db_img`.id = ?');
		$req->execute([$id_img]);
		$nb_like = $req->fetch();
		return ($nb_like->likes);
	} else {
		return (NULL);
	}
}

function	is_photo_liked_by($var_pdo, $id_user, $id_img)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT * FROM `db_likes` WHERE `db_likes`.user_id = ? AND `db_likes`.img_id = ?');
		$req->execute([$id_user, $id_img]);
		if ($req->rowCount() != 0) {
			return (true);
		}
	}
	return (false);
}

function	add_like($var_pdo, $user_id, $img_id)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('INSERT INTO `db_likes` (user_id, img_id) VALUES (?, ?)');
		$req->execute([htmlspecialchars($user_id), htmlspecialchars($img_id)]);
		$req = $var_pdo->prepare('UPDATE `db_img` SET `db_img`.likes = `db_img`.likes + 1 WHERE `db_img`.id = ?');
		$req->execute([htmlspecialchars($img_id)]);
	}
}

function	remove_like($var_pdo, $user_id, $img_id)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('DELETE FROM `db_likes` WHERE `db_likes`.user_id = ? AND `db_likes`.img_id = ?');
		$req->execute([$user_id, $img_id]);
		$req = $var_pdo->prepare('UPDATE `db_img` SET `db_img`.likes = `db_img`.likes - 1 WHERE `db_img`.id = ?');
		$req->execute([htmlspecialchars($img_id)]);
	}
}

function	get_imgname_by_id($var_pdo, $img_id)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT img_name FROM `db_img` WHERE `db_img`.id = ?');
		$req->execute([$img_id]);
		$imgname = $req->fetch()->img_name;
		return ($imgname);
	}
}

function	img_can_be_delete($var_pdo, $user_id, $img_id)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT * FROM `db_img` INNER JOIN `db_user` ON `db_user`.id = `db_img`.user_id WHERE `db_user`.id = ? AND `db_img`.id = ?');
		$req->execute([$user_id, $img_id]);
		if ($req->rowCount() != 0)
			return (true);
	}
	return (false);
}

?>