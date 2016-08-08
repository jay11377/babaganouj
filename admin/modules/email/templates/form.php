<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            <div class="required  field ">
            	<label for="email"><?php showLang('EMAIL_ADDRESS') ?></label>
                <input type="text" name="email" id ="email" value="<?php echo osql($email);?>" >
                <div class="help"><?php showLang('COMMA_SEPARATION') ?></div> 
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