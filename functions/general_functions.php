<?php

function	success_msg($msg)
{
	?> <p class="success"><b><?php echo (htmlspecialchars($msg)); ?></b></p> <?php
}

function	failure_msg($msg)
{
	?> <p class="failure"><b><?php echo (htmlspecialchars($msg)); ?></b></p> <?php
}

function	ft_location($var, $val)
{
	if (isset($var) && !empty($var) && isset($val) && !empty($val)) {
		echo ('<script>window.location="http://localhost:8080/index.php?'.$var.'='.$val.'"</script>');
	}
}

function	ft_subnstr($str, $start, $len)
{
	$j = $start;
	while ($j - $start != $len)
	{
		$tab[] = $str[$j];
		$j = $j + 1;
	}
	$result = implode('', $tab);
	return ($result);
}

?>