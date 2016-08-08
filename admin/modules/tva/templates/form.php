<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            <div class="required  field ">
            	<label for="name"><?php showLang('NAME') ?></label>
                <input type="text" name="name" id ="name" value="<?php echo osql($name);?>" > 
            </div>
            <div class="required  field ">
            	<label><?php showLang('TAX_RATE') ?></label>
                <input type="text" id="tax_rate" name="tax_rate" value="<?php echo osql($tax_rate);?>" />
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