<?php

include_once('functions/general_functions.php');

if (isset($_SESSION['user_log']))
{
	unset($_SESSION['user_log']);
	// ft_location("page", "logout&npage=1");
}

include ('views/logout_v.php');

?>