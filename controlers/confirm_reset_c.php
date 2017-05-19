<?php

include_once('functions/db_functions.php');
include_once('functions/general_functions.php');

$token = urldecode($_GET['token']);
$email = urldecode($_GET['email']);

$pdo = connect_db();

if ($pdo != NULL) {
	$req = $pdo->prepare('SELECT reset_token, reset_expiration FROM db_user WHERE email = ?');
	$req->execute([$email]);
	$user = $req->fetch();

	$pdo = NULL;

	if ($user && $user->reset_token == $token) {
		ft_location("page", "new_password&email=".htmlspecialchars($email)."&token=".htmlspecialchars($token));
	} else {
		ft_location("page", "homepage&npage=1");
	}
} else {
	failure_msg('Db doesn\'t exists');
}

?>