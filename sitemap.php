<?php include("includes/top_includes.php"); ?>
<!doctype html>
<html>
    <head>
	    <?php include("includes/head.php"); ?>
	    <title><?php showLang('PAGE_TITLE_COMMON') ?></title>
	</head>
<body>
<div class="page-container container">
    <?php include("includes/header.php"); ?>
    <div class="container">
		<div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <ul class="breadcrumb">
                        <li><a href="commander.php">Commander en ligne</a> <span class="divider"></span></li>
                        <li class="active"><?php showLang('SITEMAP') ?></li>
                    </ul>
                </div>
            </div>  
        </div>        
        <div class="row">
            <div class="col-md-12">
                <h1><?php showLang('SITEMAP') ?></h1>
            </div>
        </div>
        <div class="row sitemap">
            <div class="col-md-12">
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="concept.php">Concept</a></li>
                    <li><a href="photos.php">Gallerie photo</a></li>
                    <li><a href="video.php">Vidéos</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="commander.php">Commander en ligne</a></li>
                </ul>
            </div>        
        </div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
</body>
</html>
