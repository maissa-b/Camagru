<span class="img_gallery">
	<img class="img_gallery_alone" src="<?php echo $_SESSION['preview_img']; ?>" width="300" height="225"/>
	<div display="inline-block"><img class="img_like" src="img/icons/unlike.png" /><?php echo $_SESSION['preview_img_nb_likes']; ?></div>
	<div class="img_comment_section">
	<?php
		if ($_SESSION['preview_img_comments']) {
			foreach ($_SESSION['preview_img_comments'] as $comment)
			{
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