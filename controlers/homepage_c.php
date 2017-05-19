<?php

if (!isset($_GET['npage']) || empty($_GET['npage'])) {
	ft_location('page', 'homepage&npage=1');
}

include ('views/homepage_v.php');

?>
