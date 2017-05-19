<?php

function	send_confirmation_email($email, $user, $token, $user_id)
{
	$headers = "Content-type: text/html; charset=UTF-8";
	$title = "Confirmation account";
	$clickable = '<a href="http://localhost:8080/index.php?page=confirm_account&id='.urlencode($user_id).'&token='.urlencode($token).'">Cliquez ici pour activer votre compte</a>';
	$content = "Bonjour ".$user.", merci de confirmer ton inscription en cliquant sur le lien suivant :\n\n".$clickable;

	mail($email, $title, $content, $headers);
}

function	send_resetpass_email($email, $token)
{
	$headers = "Content-type: text/html; charset=UTF-8";
	$title = "Reset password";
	$clickable = '<a href="http://localhost:8080/index.php?page=confirm_reset&email='.urlencode($email).'&token='.urlencode($token).'">Cliquer ici</a>';
	$content = "Bonjour, pour reinitialiser ton mot de passe, merci de cliquer sur ce lien :\n\n".$clickable;
	
	mail($email, $title, $content, $headers);
}

function	send_notification_email($email, $user_photo, $user_action, $notification)
{
	$headers = "Content-type: text/html; charset=UTF-8";
	$title = "New notification !";
	$clickable = '<a href="http://localhost:8080/index.php?page=login">Se connecter</a>';
	$content = "Bonjour ".$user_photo.", tu viens de recevoir un ".$notification." de ".$user_action." sur une de tes photos, connecte toi vite pour le voir : ".$clickable;

	mail($email, $title, $content, $headers);
}

function	create_email_token($length)
{
	$salt = "9HSjJfDefg35";

	return (substr(str_shuffle(str_repeat($salt, $length)), 0, $length));
}

?>