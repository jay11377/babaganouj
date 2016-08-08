<?php
include("includes/top_includes.php");
$conn = new DbConnector();

$query = "SELECT * FROM clients WHERE id=".$_SESSION['id_client'];
$result = $conn->query($query);
$row = $conn->fetchArray($result);
?>
<div class="row">
    <div class="col-md-12">
    	<form class="form-horizontal" id="personal_info_form" role="form" method="post" name="form" action="">
			<input type="hidden" id="id_client" name="id_client" value="<?php echo $_SESSION["id_client"] ?>" />
		    <div class="required">* <?php showLang('REQUIRED_FIELD') ?></div>
		    <div class="form-group">
				<label class="col-md-3 control-label" for="prenom"><?php showLang('FIRST_NAME') ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?php echo osql($row['prenom']) ?>" id="prenom" name="prenom">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="nom"><?php showLang('LAST_NAME') ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?php echo osql($row['nom']) ?>" id="nom" name="nom">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="email"><?php showLang('EMAIL') ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?php echo osql($row['email']) ?>" id="email" name="email">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="password"><?php showLang('CURRENT_PASSWORD') ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="password" class="form-control" value="" id="current_password" name="current_password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="password"><?php showLang('NEW_PASSWORD') ?></label>
				<div class="col-md-9">
					<input type="password" class="form-control" value="" id="new_password" name="new_password">
				</div>
			</div>
		    <div class="form-group">
		        <label class="col-md-3 control-label" for="password"><?php showLang('NEW_PASSWORD_CONFIRMATION') ?></label>
		        <div class="col-md-9">
		        	<input type="password" class="form-control" value="" id="new_password_confirmation" name="new_password_confirmation">
		        </div>
		    </div>
			<div class="form-group">
                <label class="col-md-3 control-label" for="newsletter"><?php showLang('NEWSLETTER_SUBSCRIBE') ?></label>
                <div class="col-md-9">
                    <input type="checkbox" style="margin-top:10px" name="newsletter" <?php if($newsletter==1): ?> checked="checked" <?php endif; ?> />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary" type="submit" name="submit" value="<?php showLang('SUBMIT') ?>"><?php showLang('SUBMIT') ?></button>
                </div>
            </div>
		</form>
	</div>
</div>