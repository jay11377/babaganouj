<?php include("includes/top_includes.php"); ?>
<!doctype html>
<html>
    <head>
	    <?php include("includes/head.php"); ?>
	    <title><?php showLang('PAGE_TITLE_CGV') ?></title>
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
                        <li class="active"><?php showLang('PAGE_TITLE_CGV') ?></li>
                    </ul>
                </div>
            </div>  
        </div>        
        <div class="row">
            <div class="col-md-12">
                <h1><?php showLang('PAGE_TITLE_CGV') ?></h1>
            </div>
        </div>
        <?php include("includes/cgv.php"); ?>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
</body>
</html>
