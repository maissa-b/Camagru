<?php

include_once('functions/general_functions.php');

if (isset($_SESSION['user_log'])) {
	include_once('functions/db_functions.php');

	$pdo = connect_db();

	if ($pdo != NULL) {
		$req = $pdo->prepare('SELECT img_name FROM `db_user` INNER JOIN `db_img` ON `db_user`.id = `db_img`.user_id WHERE `db_user`.id = ? AND `db_img`.user_id = ? ORDER BY `db_img`.creation_date');
		$req->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
		$array = $req->fetchAll();
		foreach ($array as $object) {
	    	if (file_exists(htmlspecialchars($object->img_name))) {
	    		$result[] = htmlspecialchars($object->img_name);
	    	} else {
	    		$result[] = "img/not_found.png";
	    	}
	    }
		$_SESSION['lst_img'] = $result;
		$pdo = NULL;
	} else {
		failure_msg('Db doesn\'t exists');
	}
	include('views/my_gallery_v.php');
} else {
	ft_location("page", "homepage&npage=1");
}

?>