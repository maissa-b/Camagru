<?php

include_once('functions/general_functions.php');
include_once('functions/img_functions.php');

if (isset($_SESSION['user_log']) && !empty($_SESSION['user_log'])) {
	if (isset($_POST['img_data']) && isset($_POST['filter'])) {
		if (!empty($_POST['img_data']) && !empty($_POST['filter']) && strstr($_POST['img_data'], 'data:image/png;base64') && strlen($_POST['img_data']) > 22) {
			
			include_once('functions/db_functions.php');
			$pdo = connect_db();

			if ($pdo != NULL) {
				$img_video = save_img($_POST['img_data']);
				$filename = merge_img($img_video, $_POST['filter'], $new_img, 'video');
				add_img_to_db($filename, $pdo);
				success_msg('Picture uploaded');
				shell_exec('rm -rf '.$img_video.'');

				$pdo = NULL;
			} else {
				failure_msg('Db doesn\'t exists');
			}
		} else {
			failure_msg('Please take a picture');
		}
	} else {
		if (isset($_POST['upload'])) {
			failure_msg('Take a picture before upload');
		}
	}
	include_once('views/photobooth_v.php');
} else {
	ft_location('page', 'homepage&npage=1');
}

?>