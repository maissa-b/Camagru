<?php

include_once('functions/register_functions.php');
include_once('functions/email_functions.php');
include_once('functions/db_functions.php');
include_once('functions/general_functions.php');

if (isset($_POST['email'])) {
	if (!empty($_POST['email'])) {
		$pdo = connect_db();

		if ($pdo != NULL) {
			if (is_email_used(htmlspecialchars($_POST['email']), $pdo) === true && strlen($_POST['email']) < 255) {
				$token = create_email_token(80);
				$req = $pdo->prepare('UPDATE db_user SET reset_token = ?, reset_expiration = NULL WHERE email = ?');
				$req->execute([htmlspecialchars($token), htmlspecialchars($_POST['email'])]);
				send_resetpass_email(htmlspecialchars($_POST['email']), $token);
				success_msg("Email successfuly sent!");
			} else {
				failure_msg("There isn't account for this email.");
			}
			$pdo = NULL;
		} else {
			failure_msg('Db doesn\'t exists');
		}
	} else {
		failure_msg("Please, enter an email.");
	}
}

include ('views/reset_password_v.php');

?>