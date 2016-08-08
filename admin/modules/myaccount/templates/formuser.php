<div class="box">
	<div class="header">
    	<h3><?php showLang('MY_PROFILE') ?></h3>
    </div>
    <div class="container">	
        <div class="inset container">
            <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
                <fieldset>
                    <div class="hidden">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="module" value="<?php echo $module; ?>" />
                        <input type="hidden" name="action" value="<?php echo $action; ?>" />
                    </div>
                    <div class="required  field ">
                      <label for="name"><?php showLang('USER_NAME') ?></label>
                      <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                      <div class="help"></div>
                    </div>
                    <div class=" field ">
                      <label for="name"><?php showLang('PASSWORD') ?></label>
                      <input type="password" name="password" id="password" value="">
                      <div class="help"><?php showLang('PASSWORD_CHANGE') ?></div>
                    </div>
                    <div class=" field ">
                      <label for="name">Confirm Password</label>
                      <input type="password" name="passwordagain" id="passwordagain" value="">
                      <div class="help"><?php showLang('PASSWORD_CHANGE') ?></div>
                    </div>
                    <div class="required  field ">
                      <label for="name"><?php showLang('FIRST_NAME') ?></label>
                      <input type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>">
                      <div class="help"></div>
                    </div>
                    <div class="required  field ">
                      <label for="name"><?php showLang('LAST_NAME') ?></label>
                      <input type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>">
                      <div class="help"></div>
                    </div>
                    <div class="required  field ">
                      <label for="name"><?php showLang('EMAIL_ADDRESS') ?></label>
                      <input type="text" name="email" id="email" value="<?php echo $email; ?>">
                      <div class="help"></div>
                    </div>
                    <div class="buttons">
                        <p>
                          <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                          <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                        </p>
                    </div> 
                </fieldset>
            </form>
        </div>
	</div>
</div>