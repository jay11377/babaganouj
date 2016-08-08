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
                        <li class="active">S'identifier</li>
                    </ul>
                </div>
            </div>  
        </div>        
        <div class="row">
            <div class="col-md-12">
                <h1>Login</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            	<form class="loginbox form-horizontal" name="login" id="login" action="" method="post">
                    <p>Login</p>
                    <div class="form-group" id="msg_error">
                        <div class="col-md-12">
                            <p class="bg-danger"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="email"><?php showLang('EMAIL') ?><span class="required">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="password"><?php showLang('PASSWORD') ?><span class="required">*</span></label>
                        <div class="col-md-8">
                            <input type="password"  class="form-control" name="password" id="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <!-- <a href="pwdreset_default"><?php showLang('PASSWORD_FORGOTTEN') ?> ?&nbsp;&nbsp;</a> -->
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
</body>
</html>
