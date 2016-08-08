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
            	<div class="help"><?php showLang('ZONE_NAME_HELP') ?></div>
            </div>
            <div class="required field">
            	<label for="minimum"><?php showLang('MINIMUM') ?></label>
                <input type="text" name="minimum" id="minimum" class="short" value="<?php echo osql($minimum);?>" > <?php showLang('CURRENCY') ?>
            	<div class="help"><?php showLang('ZONE_MINIMUM_HELP') ?></div>
            </div>
             <div class="required field">
            	<label for="duree_livraison"><?php showLang('DELIVERY_DURATION') ?></label>
                <input type="text" name="duree_livraison" id="duree_livraison" class="short" value="<?php echo osql($duree_livraison);?>" > <?php showLang('MN') ?> 
            	<div class="help"><?php showLang('ZONE_DURATION_HELP') ?></div>
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