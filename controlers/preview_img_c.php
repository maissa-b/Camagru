<?php

include_once('functions/img_functions.php');

if (isset($_GET['img_id']) && !empty($_GET['img_id']) && $_GET['img_id'] != NULL) {
	include_once('functions/db_functions.php');
	$pdo = connect_db();

	if ($pdo != NULL) {
		$req = $pdo->prepare('SELECT img_name FROM `db_img` WHERE `db_img`.id = ?');
		$result = $req->execute([(int)$_GET['img_id']]);
		
		$imgname = $req->fetch()->img_name;
		if ($result && is_img_exists($imgname, $pdo) !== false) {
			$_SESSION['preview_img'] = $imgname;
			if ($_SESSION['preview_img'] != NULL) {
				$_SESSION['preview_img_id'] = (int)$_GET['img_id'];
				$_SESSION['preview_img_comments'] = get_comments($pdo, $_GET['img_id']);
				$_SESSION['preview_img_nb_likes'] = get_nb_like($pdo, $_GET['img_id']);

				include_once('views/preview_img_v.php');
			}
		} else {
			failure_msg('There is no photo for that id');
		}
		$pdo = NULL;
	}
} else {
	failure_msg('There is no photo for that id');
}

?>