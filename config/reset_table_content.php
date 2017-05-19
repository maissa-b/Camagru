<?php

if (!isset($argv[1]) || empty($argv[1]) || $argc != 2)
{
	echo "\033[31mUsage : php reset_table_content.php \033[0m<tabname>\n";
}
else
{
	include_once('config_functions.php');

	$pdo = new PDO ('mysql:localhost:8080', $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

	switch_db($DB_NAME, $pdo);

	delete_table_content($argv[1], $pdo);

	$pdo = NULL;
}

?>