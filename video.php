<?php include("includes/top_includes.php"); ?>
<!doctype html>
<html>
    <head>
	    <?php include("includes/head.php"); ?>
	    <title>Vidéo de présentation - <?php showLang('PAGE_TITLE_COMMON') ?></title>
	</head>
<body>
<div class="page-container container">
    <?php include("includes/header.php"); ?>
    <div class="container">
		<div class="page_heading">
			<h1>Vidéo de présentation Ma Bento</h1>				
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<video width="720" height="480" controls="controls">
				  <source src="videos/MaBentoHD.mp4" type="video/mp4" />
				</video>
			</div>
		</div>
		<div class="row top30">
			<div class="col-md-12 text-center">
			    <p><a href="video-special.php"><img src="../old/mp3/images/video_spe_icon.jpg" width="250" height="112"></a></p>
			    <p><a href="video-japanexpo.php"><img src="../old/mp3/images/video_japan_expo.jpg" width="250" height="112" alt="Japan Expo"></a></p>
			</div>
		</div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
</body>
</html>
