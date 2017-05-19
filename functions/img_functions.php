<?php

function	imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
	$cut = imagecreatetruecolor($src_w, $src_h);
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
	imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

function	merge_img($img1, $img2, $image, $type) {
	$image = $img1;
	$frame = $img2;
	$image = imagecreatefrompng($image);
	$frame = imagecreatefrompng($frame);
	$size = array('width', 'height');
	$size = getimagesize($img1);
	if (strstr($img2, 'hat')) {
		$x = ($size[0] / 2.5);
		$y = ($size[1] - ($size[1] - 6));
	} else if (strstr($img2, 'glasses')) {
		$x = ($size[0] / 2.5);
		$y = ($size[1] - ($size[1] - 66));
	} else {
		$x = ($size[0] / 2.5);
		$y = ($size[1] - ($size[1] - 106));
	}
	$srcname = 'img/pictures/'.uniqid($_SESSION['user_log']).'.png';
	imagecopymerge_alpha($image, $frame, $x,$y,0,0, imagesx($frame), imagesy($frame), 100);
	imagesavealpha($image, true);
	imagepng($image, $srcname);
	imagedestroy($image);
	imagedestroy($frame);
	return ($srcname);
}

function	is_valid_img($img) {
	$filename = 'img/pictures/'.basename($_FILES['fileToUpload']['name']);
	$filetype = pathinfo($filename, PATHINFO_EXTENSION);
	$filesize = getimagesize($_FILES['fileToUpload']['tmp_name']);
	if ($filesize !== false) {
		if (!file_exists($filename)) {
			if ($_FILES['fileToUpload']['size'] < 500000) {
				if ($filetype == 'png') {
					return (true);
				} else {
					failure_msg('Can\'t upload this image : wrong extension, waiting for png only.');
					return (false);
				}
			} else {
				failure_msg('Can\'t upload this image : image too big, max size : 500000 octets.');
				return (false);
			}
		} else {
			failure_msg('Can\'t upload this image : file already exists.');
			return (false);
		}
	} else {
		failure_msg('Can\'t upload this image : image size unavailable.');
		return (false);
	}
}

function	decode_img($data) {
	$img = $data;
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$fileData = base64_decode($img);
	if (!empty($fileData)) {
		return ($fileData);
	}
	else {
		return NULL;
	}
}

function	save_img($data) {
	$fileData = decode_img($data);
	$fileName = 'img/pictures/'.uniqid($_SESSION['user_log']).'.png';

	if ($fileData != NULL) {
		file_put_contents($fileName, $fileData);
		return ($fileName);
	} else {
		return NULL;
	}
}

function	switch_prev_page($url, $current_page, $color_style) {
	if (!empty($url)) {
		$prev_page = $current_page - 1;
		if ($prev_page > 0) {
			echo '<a style="color: '.$color_style.'" href="http://localhost:8080/index.php?page='.$url.'&npage='.$prev_page.'">prev</a>';
		} else {
			echo '<a style="color:black">prev</a>';
		}
	}
}

function	switch_next_page($url, $nb_page, $current_page, $color_style) {
	if (!empty($url) && !empty($nb_page)) {
		$next_page = $current_page + 1;
		if ($next_page <= $nb_page) {
			echo '<a style="color: '.$color_style.'" href="http://localhost:8080/index.php?page='.$url.'&npage='.$next_page.'">next</a>';
		} else {
			echo '<a style="color:black">next</a>';
		}
	}
}

function	get_nb_page_preview($var_pdo, $nb_div, $user_id) {
	if ($var_pdo != NULL) {
		if ($user_id == NULL) {
			$req = $var_pdo->prepare('SELECT img_name FROM `db_img`');
			$req->execute([]);
		} else {
			$req = $var_pdo->prepare('SELECT img_name FROM `db_img` WHERE `db_img`.user_id = ?');
			$req->execute([$user_id]);
		}
		if ($req->rowCount() == 0) {
			$nb_page = 1;
		} else {
			$nb_page = ceil($req->rowCount() / $nb_div);
		}
		return($nb_page);
	}
	return (0);
}

function	is_img_exists($img_id, $var_pdo) {
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT id FROM `db_img` WHERE `db_img`.id = ?');
		$result = $req->execute([$img_id]);
		if ($result === true) {
			return (true);
		} else {
			return (false);
		}
	} else {
		return (false);
	}
}

function	get_all_img($var_pdo) {
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT img_name FROM `db_img`');
		$req->execute([]);
		return ($req->fetchAll());
	} else {
		return (NULL);
	}
}

function	get_nb_max_img($var_pdo) {
	if ($var_pdo != NULL) {
		$req = $var_pdo->prepare('SELECT COUNT(img_name) AS nb_img FROM `db_img`');
		$req->execute([]);
		$result = $req->fetch();
		return ($result->nb_img);
	} else {
		return (NULL);
	}
}

?>