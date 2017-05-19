<?php

include_once('functions/db_functions.php');
include_once('functions/general_functions.php');

$user_id = urldecode($_GET['id']);
$token = urldecode($_GET['token']);

$pdo = connect_db();

if ($pdo != NULL) {
	$req = $pdo->prepare('SELECT * FROM db_user WHERE id = ?');
	$req->execute([$user_id]);
	$user = $req->fetch();

	if ($user && $user->validation_token == $token)
	{
		$req = $pdo->prepare('UPDATE db_user SET validation_token = NULL, expiration_token = NOW() WHERE id = ?');
		$req->execute([$user_id]);
		$_SESSION['user_log'] = htmlspecialchars($user->username);
		$_SESSION['username'] = htmlspecialchars($user->username);
		$_SESSION['user_id'] = htmlspecialchars($user->id);
		$_SESSION['email'] = htmlspecialchars($user->email);
		ft_location("page", "homepage&npage=1");
	}
	else
	{
		failure_msg("Error: token already used.");
	}
	
	$pdo = NULL;
} else {
	failure_msg("Db doesn't exists");
}



?>