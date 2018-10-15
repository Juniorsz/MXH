<?php 
   session_start();
   include("../Model/model.php");
   include("../cookie.php");
   $data = new Model; 
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<title>FSocial Login</title>

	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<link rel="stylesheet" href="../Public/css/core.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/css/uikit.min.css" />

	<!-- Main Font -->
	<script src="js/webfontloader.min.js"></script>
	<script>
		WebFont.load({
			google: {
				families: ['Roboto:300,400,500,700:latin']
			}
		});
	</script>
	<script>
	if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>


	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="http://theme.crumina.net/html-olympus/Bootstrap/dist/css/bootstrap-reboot.css">
	<link rel="stylesheet" type="text/css" href="http://theme.crumina.net/html-olympus/Bootstrap/dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="http://theme.crumina.net/html-olympus/Bootstrap/dist/css/bootstrap-grid.css">

	<!-- Main Styles CSS -->
	<link rel="stylesheet" type="text/css" href="http://theme.crumina.net/html-olympus/css/main.min.css">
	<link rel="stylesheet" type="text/css" href="http://theme.crumina.net/html-olympus/css/fonts.min.css">


</head>

<body>



<div class="main-header main-landing" style="margin-bottom:0px;">

	<div class="content-bg-wrap bg-landing"></div>
	<div class="container">
		<div class="row">
			<div class="col col-lg-6 m-auto col-md-12 col-sm-12 col-12">
				<div class="main-header-content">

					<a href="#" class="logo" style="margin-bottom:40px;">
						<div class="img-wrap">
							<img src="http://theme.crumina.net/html-olympus/img/logo-landing.png" alt="Olympus">
						</div>
						<div class="title-block">
							<h3 class="logo-title">OLYMUS VIỆT NAM</h3>
							<div class="sub-title">MẠNG XÃ HỘI CHO NGƯỜI VIỆT</div>
						</div>
					</a>
					<?php $data->login(); ?>
				</div>
			</div>
		</div>
	</div>

	<img class="img-bottom" src="http://theme.crumina.net/html-olympus/img/group-bottom.png" alt="friends">
	<img class="img-rocket" src="http://theme.crumina.net/html-olympus/img/rocket.png" alt="rocket">
</div>
</body>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/js/uikit.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/js/uikit-icons.min.js"></script>
 <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</html>