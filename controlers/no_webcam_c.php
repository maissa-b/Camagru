<?php

include_once('functions/general_functions.php');
include_once('functions/img_functions.php');

if (isset($_SESSION['user_log']) && !empty($_SESSION['user_log'])) {
	if (isset($_POST['upload'])) {
		if (isset($_POST['img_data']) && !empty($_POST['img_data']) && isset($_POST['filter']) && !empty($_POST['filter'])) {
			
			include_once('functions/db_functions.php');
			$pdo = connect_db();

			if ($pdo != NULL) {
				$img_data = decode_img($_POST['img_data']);
				if (is_valid_img($img_data) !== false) {
					$img_upload = save_img($_POST['img_data']);
					if ($img_upload != NULL) {
						$filename = merge_img($img_upload, $_POST['filter'], $new_img, 'upload');
						add_img_to_db($filename, $pdo);
						success_msg('Image has been uploaded');
					}
				}
				$pdo = NULL;
			} else {
				failure_msg('Can\'t connect to db.');
			}
		} else {
			failure_msg('Please, upload a picture and apply filter in');
		}
	}
	include_once('views/no_webcam_v.php');
} else {
	ft_location("page", "homepage&npage=1");
}

?>