<?php

include_once('functions/register_functions.php');
include_once('functions/email_functions.php');
include_once('functions/db_functions.php');
include_once('functions/general_functions.php');

if (!isset($_SESSION['user_log'])) {
	if (!empty($_POST)) {
		$pdo = connect_db();
		
		if ($pdo != NULL) {
			$errors = is_form_valid($_POST, $pdo);
			if (empty($errors)) {
				$token = create_email_token(80);
				add_user($_POST, $pdo, $token);
				send_confirmation_email(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['username']), $token, $pdo->lastInsertId());
				success_msg("Email successfuly sent!");
			} else {
				foreach ($errors as $error => $msg) {
					failure_msg($msg);
				}
			}
			$pdo = NULL;
		} else {
			failure_msg('Db doesn\'t exists');
		}
	}
	include ('views/register_v.php');
} else {
	ft_location('page', 'homepage&npage=1');
}

?>