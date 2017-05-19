<?php

include_once('functions/register_functions.php');
include_once('functions/general_functions.php');

if (isset($_GET['status']) && ($_GET['status'] >= 0 && $_GET['status'] <= 6)) {
	if ($_GET['status'] == 0) {
		success_msg("Password successfuly changed!");
	} else if ($_GET['status'] == 1) {
		failure_msg('Password too weak, it must contains, minimum, the following properties :
						- 2 letters in uppercase;
						- 1 special character (!@#$&*);
						- 2 numerics (between 0 and 9);
						- 3 letters in lowercase;
						- lenght of 8 char or more.');
	} else if ($_GET['status'] == 2) {
		failure_msg("Invalid token or username.");
	} else if ($_GET['status'] == 3) {
		failure_msg("Password can't be changed : wrong username!");
	} else if ($_GET['status'] == 4) {
		failure_msg("Token already used.");
	} else if ($_GET['status'] == 5) {
		failure_msg("Wrong confirmation password");
	} else if ($_GET['status'] == 6) {
		failure_msg("This login does not exists.");
	}
}

if (!empty($_POST))
{
	include_once('functions/db_functions.php');
	
	$pdo = connect_db();

	if ($pdo != NULL) {
		if (is_user_used(htmlspecialchars($_POST['username']), $pdo) === true && strlen($_POST['username']) < 255) {
			if (!empty($_POST['password']) && !empty($_POST['confirm_password']) && strcmp($_POST['password'], $_POST['confirm_password']) == 0 && strlen($_POST['password']) < 255 && strlen($_POST['confirm']) < 255) {
				if (is_password_strong_enought(htmlspecialchars($_POST['password'])) === false) {
					$status = 1;
				} else {
					$req = $pdo->prepare('SELECT reset_token, reset_expiration FROM db_user WHERE username = ?');
					$req->execute([htmlspecialchars($_POST['username'])]);
					$user = $req->fetch();

					if ($user->reset_expiration == NULL || isset($_SESSION['user_log'])) {
						$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_BCRYPT);
						if (!isset($_SESSION['user_log'])) {
							$req = $pdo->prepare('UPDATE db_user SET reset_token = NULL, password = ?, reset_expiration = NOW() WHERE username = ? AND reset_token = ?');
							$req->execute([htmlspecialchars($password), htmlspecialchars($_POST['username']), htmlspecialchars($_POST['reset_token'])]);
							if ($req->rowCount() == 1) {
								$status = 0;
							} else {
								$status = 2;
							}
						} else {
							if (strcmp(htmlspecialchars($_POST['username']), htmlspecialchars($_SESSION['username'])) == 0) {
								$req = $pdo->prepare('UPDATE db_user SET reset_token = NULL, password = ?, reset_expiration = NOW() WHERE username = ?');
								$req->execute([htmlspecialchars($password), htmlspecialchars($_POST['username'])]);
								$status = 0;
						
							} else {
								$status = 3;
							}
						}
					} else {
						$status = 4;
					}
				}
			} else {
				$status = 5;
			}
		} else {
			$status = 6;
		}

		$pdo = NULL;
	} else {
		failure_msg('Db doesn\'t exists');
	}
	if (!isset($_SESSION['user_log'])) {
		ft_location("page", "new_password&email=".htmlspecialchars($_POST['reset_email'])."&token=".htmlspecialchars($_POST['reset_token'])."&status=".$status);
	}
	else {
		ft_location("page", "new_password&status=".$status);
	}
}

include ('views/new_password_v.php');

?>