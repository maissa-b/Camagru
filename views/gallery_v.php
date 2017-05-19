<?php

echo '<h1>General gallery</h1>';

if ($_SESSION['lst_img']) {
	include_once('functions/general_functions.php');
	include_once('functions/db_functions.php');
	include_once('functions/email_functions.php');
	include_once('functions/img_functions.php');

	$pdo = connect_db();

	if ($pdo != NULL) {
		if (isset($_POST['comment']) && isset($_GET['img_id']) && isset($_SESSION['user_log'])) {
			if (!empty(trim($_POST['comment']))) {
				if (strlen($_POST['comment']) < 255) {
					$img_id = $_GET['img_id'];
					add_comment_to_db($pdo, $img_id, $_POST['comment']);
					$req = $pdo->prepare('SELECT username, email FROM `db_user` INNER JOIN `db_img` ON `db_user`.id = `db_img`.user_id WHERE `db_img`.id = ?');
					$req->execute([$img_id]);
					$result = $req->fetch();
					if ($result->username != $_SESSION['username']) {
						send_notification_email($result->email, $result->username, $_SESSION['username'], "commentaire");
					}
				} else {
					failure_msg('Comment must have a max-length of 255');
				}
			} else {
				failure_msg('You need to have a non-empty comment');
			}
		}
		if (isset($_GET['like']) && isset($_GET['img_id']) && isset($_SESSION['user_log']) && !empty($_SESSION['user_log'])) {
			if (is_photo_liked_by($pdo, $_SESSION['user_id'], $_GET['img_id']) === true) {
				remove_like($pdo, $_SESSION['user_id'], $_GET['img_id']);
			} else {
				add_like($pdo, $_SESSION['user_id'], $_GET['img_id']);
			}
		}

		$nb_page = get_nb_page_preview($pdo, 11, NULL);
		$npage = (int)$_GET['npage'];

		if (!isset($npage) || empty($npage) || ($npage < 1 || $npage > $nb_page)) {
			$npage = 1;
			ft_location('page', 'gallery&npage=1');
		} else {
			$uri = $_SERVER['REQUEST_URI'];
			if (strncmp($uri, '/index.php?page=', 16) == 0) {
				if (substr_compare($uri, 'gallery', 16, 7) == 0) {
					$url = ft_subnstr($uri, 16, 7);
				} else {
					$url = NULL;
				}
			}
		}
		?>
			<div>
				<span><?php switch_prev_page($url, $npage, 'blue'); ?></span>
				<span><?php echo $npage.'/'.$nb_page; ?></span>
				<span><?php switch_next_page($url, $nb_page, $npage, 'blue');?></span>
			</div>

		<?php
		
		if (isset($_GET['delete']) && isset($_GET['img_id']) && !empty($_GET['img_id']) && isset($_SESSION['user_log']) && !empty($_SESSION['user_log'])) {
			if (img_can_be_delete($pdo, $_SESSION['user_id'], $_GET['img_id']) === true) {
				delete_img($pdo, $_GET['img_id'], get_imgname_by_id($pdo, $_GET['img_id']));
				ft_location('page', 'gallery&npage='.$npage);
			}
		}
		$count = 0;
		$calc = 0;
		while ($count < 11 && isset($_SESSION['lst_img'][$count + (($npage - 1) * 11)]) && !empty($_SESSION['lst_img'][$count + (($npage - 1) * 11)]) && $_SESSION['lst_img'][$count + (($npage - 1) * 11)] != NULL) {
			$calc = $count + (($npage - 1) * 11);
			$id_img = get_img_id($pdo, $_SESSION['lst_img'][$calc]);
			$comments = get_comments($pdo, $id_img);
			
			if (isset($_SESSION['user_log']) && !empty($_SESSION['user_log'])) {
				if (is_photo_liked_by($pdo, $_SESSION['user_id'], $id_img) === true) {
					$like = 'img/icons/like.png';
				} else {
					$like = 'img/icons/unlike.png';
				}

				?>
				<span class="img_gallery">
					<form action="http://localhost:8080/index.php?page=gallery&npage=<?php echo $npage?>&img_id=<?php echo($id_img); ?>" method="post">
						<div>
							<img class="img_gallery_alone" src="<?php echo $_SESSION['lst_img'][$calc]?>" width="300" height="225">
							<?php
									if (img_can_be_delete($pdo, $_SESSION['user_id'], $id_img) === true) {
										echo '<a href="index.php?page=gallery&npage='.$npage.'&img_id='.$id_img.'&delete=true"><img class="img_delete" src="img/icons/delete.png"></a>';
									}
							?>
						</div>
						<div display="inline-block"><a href="index.php?page=gallery&npage=<?php echo $npage;?>&img_id=<?php echo ($id_img); ?>&like=true"><img class="img_like" src=" <?php echo $like ?>" /></a> <?php echo (get_nb_like($pdo, $id_img)); ?></div>
						<div class="img_comment_section">
							<?php
								if ($comments) {
									foreach ($comments as $comment) {
										if (strstr($comment->comment_value, ' ') === false && strstr($comment->comment_value, 9) === false) {
											echo ('<p class="user_comment">'.$comment->username.' :</p>'.'<div style="word-break: break-all;" class="img_comment_content">'.htmlspecialchars($comment->comment_value).'</div>'.PHP_EOL);
										} else {
											echo ('<p class="user_comment">'.$comment->username.' :</p>'.'<div class="img_comment_content">'.htmlspecialchars($comment->comment_value).'</div>'.PHP_EOL);
										}
									}
								} else {
									echo "No comments";
								}
							
							?>
						</div>
						<textarea class="img_comment_entry" name="comment" placeholder="Comment here" maxlength="255"></textarea>
						<button type="submit" name="submit">submit</button>
					</form>
				</span>
				<?php
			} else {
				?>
				<span class="img_gallery">
						<img class="img_gallery_alone" src="<?php echo $_SESSION['lst_img'][$calc]?> " width="300" height="225"/>
						<div display="inline-block"><img class="img_like" src="img/icons/unlike.png" /><?php echo (get_nb_like($pdo, $id_img)); ?></div>
						<div class="img_comment_section">
							<?php
								if ($comments) {
									foreach ($comments as $comment) {
										if (strstr($comment->comment_value, ' ') === false && strstr($comment->comment_value, 9) === false) {
											echo ('<p class="user_comment">'.$comment->username.' :</p>'.'<div style="word-break: break-all;" class="img_comment_content">' . htmlspecialchars($comment->comment_value) . '</div>' . PHP_EOL);
										} else {
											echo ('<p class="user_comment">'.$comment->username.' :</p>'.'<div class="img_comment_content">' . htmlspecialchars($comment->comment_value) . '</div>' . PHP_EOL);
										}
									}
								} else {
									echo 'No comments';
								}
							?>
						</div>
				</span>
				<?php
			}
			$count += 1;
		}
		$pdo = NULL;
	} else {
		failure_msg('Db doesn\'t exists');
	}
} else {
	echo 'Empty Gallery.';
}

?>