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
                        <li class="active">Annulation</li>
                    </ul>
                </div>
            </div>  
        </div>        
        <div class="row">
            <div class="col-md-12">
                <h1>Annulation</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            	<p>Il y a eu un problème avec le paiement par carte, veuillez essayer de renouveler l'opération. Merci.</p>
                <p><?php showLang('ORDERS_HISTORY_MSG') ?> :<br /><a href="mon_compte.php"><?php showLang('ORDERS_HISTORY') ?></a></p>
            </div>
        </div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
</body>
</html>
