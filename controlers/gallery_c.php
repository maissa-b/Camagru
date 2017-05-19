<?php

include_once('functions/db_functions.php');
include_once('functions/img_functions.php');

$pdo = connect_db();

if ($pdo != NULL) {

	$images = get_all_img($pdo);

	$request = 'SELECT img_name FROM `db_img` ORDER BY `db_img`.creation_date';
	$req = $pdo->prepare($request);
	$req->execute([]);
	$gallery = $req->fetchAll();
	$result = array();

	foreach ($gallery as $object) {
		if (file_exists($object->img_name)) {
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

include('views/gallery_v.php');

?>