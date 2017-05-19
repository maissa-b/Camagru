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
	
	<h1>Welcome
	<?php
		if ($_SESSION['user_log'])
		{
			echo $_SESSION['user_log'];
		}
		else
		{
			echo "Guest";
		}
	?>
	!</h1>
	
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
					<span>
						<?php echo $npage.'/'.$nb_page; ?>							
					</span>
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
				<a href="http://localhost:8080/index.php?page=homepage&npage=1">first page</a>
				<a href="http://localhost:8080/index.php?page=homepage&npage=<?php echo $nb_page; ?>">last page</a>
			</div>
		</div>

	<?php $pdo = NULL;
} else {
	failure_msg('Db doesn\'t exists');
}

?>
