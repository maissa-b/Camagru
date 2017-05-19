<?php

include_once('functions/register_functions.php');
include_once('functions/general_functions.php');

if (isset($_SESSION['user_log'])) {
	ft_location('page', 'homepage&npage=1');
} else {
	if (!empty($_POST)) {
		include_once('functions/db_functions.php');

		$pdo = connect_db();

		if ($pdo != NULL) {
			if (is_user_used(htmlspecialchars($_POST['username']), $pdo) === true && strlen($_POST['username']) < 255) {
				$req = $pdo->prepare('SELECT * FROM db_user WHERE username = ?');
				$req->execute([htmlspecialchars($_POST['username'])]);
				$user = $req->fetch();

				if (password_verify(htmlspecialchars($_POST['password']), htmlspecialchars($user->password)) === true && strlen($_POST['password']) < 255) {
					if ($user->validation_token == NULL) {
						$_SESSION['user_log'] = htmlspecialchars($_POST['username']);
						$_SESSION['username'] = htmlspecialchars($user->username);
						$_SESSION['user_id'] = htmlspecialchars($user->id);
						$_SESSION['email'] = htmlspecialchars($user->email);
						ft_location("page", "homepage&npage=1");
					} else {
						failure_msg("Please, confirm your account first.");
					}
				} else {
					failure_msg("Wrong password.");
				}
			} else {
				failure_msg("Wrong username.");
			}

			$pdo = NULL;
		} else {
			failure_msg('Db doesn\'t exists');
		}
	}
	include ('views/login_v.php');
}

?>