<?php

if (!isset($argv[1]) || empty($argv[1]) || $argc != 2)
{
	echo "\033[31mUsage : php setup.php \033[0m<dbname>\n";
}
else
{
	file_put_contents('database.php',
	'<?php

	$DB_NAME = "'.htmlspecialchars($argv[1]).'";
	$DB_DSN = "mysql:localhost";
	$DB_USER = "root";
	$DB_PASSWORD = "root";

	?>');

	include_once('config_functions.php');

	setup_db($argv[1]);
}

?>
