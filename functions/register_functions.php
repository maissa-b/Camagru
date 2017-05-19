<?php

function	is_user_valid($username)
{
	$pattern = "/^[a-z0-9]*$/";

	if (empty($username) || preg_match($pattern, strtolower($username)) == 0 || strlen($username) > 254) {
		return (false);
	}
	return (true);
}

function	is_user_used($username, $var_pdo)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT id FROM db_user WHERE username = ?');
		$req->execute([$username]);
		$user = $req->fetch();
		if ($user) {
			return (true);
		}
	}
	return (false);
}

function	is_password_valid($password, $confirm)
{
	if (empty($password) || empty($confirm) || strcmp($password, $confirm) != 0 || strlen($password) > 254 || strlen($confirm) > 254) {
		return (false);
	}
	return (true);
}

function	is_email_valid($email)
{
	$reg = "/^[a-zA-Z\-\_0-9\.]+\@[a-zA-Z\-\_0-9\.]+\.[a-zA-Z]{2,}$/";

	if (empty($email) || preg_match($reg, $email) == 0 || strlen($email) > 254) {
		return (false);
	}
	return (true);
}

function	is_email_used($email, $var_pdo)
{
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT id FROM db_user WHERE email = ?');
		$req->execute([$email]);
		$user = $req->fetch();
		if ($user) {
			return (true);
		}
	}
	return (false);
}

function	is_password_strong_enought($password)
{
	$pattern = "/^(?=.*[A-Z]+.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8,}$/";
	if (empty($password) || preg_match($pattern, $password) == 0) {
		return (false);
	}
	return (true);
}

function	is_form_valid($array_reg, $var_pdo)
{
	$errors = array();

	if (is_user_valid($array_reg['username']) === false) {
		$errors['username'] = "Login not valid. Username must only be in alphanumeric format.";
	} else {
		if (is_user_used($array_reg['username'], $var_pdo) === true) {
			$errors['username'] = "This login is already used.";
		}
	}
	if (is_email_valid($array_reg['email']) === false) {
		$errors['email'] = "Invalid email, expected : yourmail@whatever.whatever.";
	} else if (is_email_used($array_reg['email'], $var_pdo) === true) {
		$errors['email'] = "This email is already used for another account.";
	}
	if (is_password_valid($array_reg['password'], $array_reg['confirm_password']) === false) {
		$errors['password'] = "Invalid password.";
	} else if (is_password_strong_enought($array_reg['password']) === false) {
		$errors['strength'] = 'Password too weak, it must contains, minimum, the following properties :
								- 2 letters in uppercase;
								- 1 special character (!@#$&*);
								- 2 numerics (between 0 and 9);
								- 3 letters in lowercase;
								- lenght of 8 char or more.';
	}
	return ($errors);
}

?>