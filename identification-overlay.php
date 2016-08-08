<?php include("includes/top_includes.php"); ?>
<div class="row">
    <div class="col-md-12">
    	<form class="loginbox form-horizontal" name="login" id="login" action="" method="post">
            <input type="hidden" name="overlay" id="overlay" value="1">
            <?php showLang('AUTHENTIFICATION_ERROR_OVERLAY') ?><br /><br />
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
