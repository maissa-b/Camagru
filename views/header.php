<div class="header_bar gradient_color">
	<span class="home_button">
		<a href="http://localhost:8080/index.php?page=homepage&npage=1"><b>CAMAGRU</b></a>
	</span>
	<span>
			<?php if(isset($_SESSION['user_log']) && !empty($_SESSION['user_log'])): ?>
				<span class="header_buttons"><a href="http://localhost:8080/index.php?page=photobooth&npage=1">Photobooth</a></span>
				<span class="header_buttons"><a href="http://localhost:8080/index.php?page=my_gallery&npage=1">My gallery</a></span>
				<span class="header_buttons"><a href="http://localhost:8080/index.php?page=new_password">Reset my password</a></span>
				<span class="header_buttons"><a href="http://localhost:8080/index.php?page=logout">Log-out here</a></span>
				</script>
			<?php else : ?>
				<span class="header_buttons"><a href="http://localhost:8080/index.php?page=login">Log-in here</a></span>
				<span class="header_buttons"><a href="http://localhost:8080/index.php?page=register">Register here</a></span>
			<?php endif; ?>
			<span class="header_buttons"><a href="http://localhost:8080/index.php?page=gallery&npage=1">Gallery</a></span>
	</span>
</div>