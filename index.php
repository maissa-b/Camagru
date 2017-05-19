<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>

<!DOCTYPE html>
	<html>
		<head>
			<title>Camagru</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, user-scalable=no">
			<link rel="stylesheet" href="css/app.css">
			<link rel="stylesheet" media="all and (max-width: 680px)" href="css/app_handheld.css">
			<link rel="stylesheet" media="all and (max-height: 950px)" href="css/app_handheld.css">
			<link rel="stylesheet" media="all and (max-width: 319px)" href="css/app_no_display.css">
			<link rel="stylesheet" media="all and (max-height: 479px)" href="css/app_no_display.css">
		</head>
		<body>
			<div class="page2">
				<p>Window too small, min resolution : 320x480</p>
			</div>
			<div class="page">
				<?php
					include_once('functions/general_functions.php');
					include('views/header.php');

					?> <div class="container"><?php
					if (isset($_GET['page']) && !empty($_GET['page'])) {
						switch ($_GET['page']) {
							case $_GET['page'] = 'register':		include_once('controlers/register_c.php');			break;
							case $_GET['page'] = 'login':			include_once('controlers/login_c.php');				break;
							case $_GET['page'] = 'photobooth':		include_once('controlers/photobooth_c.php');		break;
							case $_GET['page'] = 'gallery':			include_once('controlers/gallery_c.php');			break;
							case $_GET['page'] = 'my_gallery':		include_once('controlers/my_gallery_c.php');		break;
							case $_GET['page'] = 'preview_img':		include_once('controlers/preview_img_c.php');		break;
							case $_GET['page'] = 'reset_password':	include_once('controlers/reset_password_c.php');	break;
							case $_GET['page'] = 'new_password':	include_once('controlers/new_password_c.php');		break;
							case $_GET['page'] = 'confirm_account':	include_once('controlers/confirm_account_c.php');	break;
							case $_GET['page'] = 'confirm_reset':	include_once('controlers/confirm_reset_c.php');		break;
							case $_GET['page'] = 'no_webcam':		include_once('controlers/no_webcam_c.php');			break;
							case $_GET['page'] = 'logout':			include_once('controlers/logout_c.php');			break;
							case $_GET['page'] = 'homepage': 		include_once('controlers/homepage_c.php');			break;
							
							default: ft_location('page', 'homepage&npage=1'); break;
						}
					} else {
						ft_location('page', 'homepage&npage=1');
					}
					echo '</div>';

					include('views/footer.php');
				?>
			</div>
		</body>
	</html>