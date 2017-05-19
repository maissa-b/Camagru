<?php

if (!isset($argv[1]) || empty($argv[1]) || $argc != 2)
{
	echo "\033[31mUsage : php reset_table.php \033[0m<dbname>\n";
}
else
{
	include_once('config_functions.php');

	shell_exec('rm -rf /nfs/2014/m/maissa-b/http/MyWebSite/img/pictures/*');
	delete_tables($argv[1]);
}

?>
