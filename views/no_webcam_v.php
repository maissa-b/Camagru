<?php

include_once('functions/img_functions.php');
include_once('functions/db_functions.php');
include_once('functions/general_functions.php');

$pdo = connect_db();

if ($pdo != NULL) {
	$nb_page = get_nb_page_preview($pdo, 6, NULL);

	$npage = (int)$_GET['npage'];

	if (!isset($npage) || empty($npage) || ($npage < 1 || $npage > $nb_page)) {
		failure_msg('This page doesn\'t exists');
		$npage = 1;
	}
	$uri = $_SERVER['REQUEST_URI'];
	if (strncmp($uri, '/index.php?page=', 16) == 0) {
		if (substr_compare($uri, 'homepage', 16, 8) == 0) {
			$url = ft_subnstr($uri, 16, 8);
		} else if (substr_compare($uri, 'photobooth', 16, 10) == 0) {
			$url = ft_subnstr($uri, 16, 10);
		} else if (substr_compare($uri, 'no_webcam', 16, 9) == 0) {
			$url = ft_subnstr($uri, 16, 9);
		} else {
			$url = NULL;
		}
	}

	$images = get_all_img($pdo);

	?>

	<div class="preview gradient_color">
		<div class="preview_header">
			<div>
				<?php
				if ($url != NULL) {
					switch_prev_page($url, $npage, 'white');
				}
				?>
			</div>
			
			<div>
				<span><?php echo $npage.'/'.$nb_page; ?></span>
			</div>
			
			<div>
			<?php
			if ($url != NULL) {
				switch_next_page($url, $nb_page, $npage, 'white');
			}
			?>
			</div>
		</div>

		<div class="preview_container">
		<?php
			$count = 0;
			while ($count != 6) {
				if ($images[$count + (($npage - 1) * 6)]->img_name) {
					echo '<div><a href="http://localhost:8080/index.php?page=preview_img&img_id='.get_img_id($pdo, ($images[$count + (($npage - 1) * 6)]->img_name)).'"><img src="'.$images[$count + (($npage - 1) * 6)]->img_name.'"/></a></div>';
				} else {
					break ;
				}
				$count ++;
			}
		?>
		</div>
		
		<div class="preview_footer">
			<a href="http://localhost:8080/index.php?page=no_webcam&npage=1">first page</a>
			<a href="http://localhost:8080/index.php?page=no_webcam&npage=<?php echo $nb_page; ?>">last page</a>
		</div>

	</div>

	<?php $pdo = NULL;
} else {
	failure_msg('Db doesn\'t exists');
	}
?>

<div class="photobooth">
	<form action="http://localhost:8080/index.php?page=no_webcam&npage=<?php echo $npage; ?>" method="post" enctype="multipart/form-data">
		<div id="radio_selection">
			<input onchange="switch_button('uploadbutton')" class="montage_frame" type="radio" id="frame1" name="filter" value="img/icons/hat5.png"><img class="preview_montage" src="img/icons/hat5.png">
			<input onchange="switch_button('uploadbutton')" class="montage_frame" type="radio" id="frame2" name="filter" value="img/icons/glasses5.png"><img class="preview_montage" src="img/icons/glasses5.png">
			<input onchange="switch_button('uploadbutton')" class="montage_frame" type="radio" id="frame3" name="filter" value="img/icons/mustache5.png"><img class="preview_montage" src="img/icons/mustache5.png">
		</div>
		<div class="montage">
			<div class="montage_video">
				<canvas id="canvas" class="video"></canvas>
				<img id="montage_frame" src="" class="no_frame"/>
			</div>
		</div>
		<input type="file" onchange="upload_picture()" name="fileToUpload" id="fileToUpload">(only png)
		<div><img style="display: none" id="photo" class="picture_border"></div>
		<div><input id="form" type="hidden" name="img_data" value=""/></div>
		<div><button id="uploadbutton" class="button_disabled" type="submit" name="upload">Upload !</button></div>
		<script type="text/javascript" src="js/switch_button.js"></script>
		<script type="text/javascript" src="js/upload_picture.js"></script>
	</form>
</div>

