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
            <div class="required field">
            	<label for="postcode"><?php showLang('POSTCODE') ?></label>
                	<input type="text" name="postcode" id="postcode" value="<?php echo osql($postcode);?>" > 
            </div>

             <div class="required  field ">
            	<label for="id_categorie"><?php showLang('ZONES') ?></label>
                	<select name="id_zone" id="id_zone">
                    	<?php while($row = $connector->fetchArray($result2)){ ?>
                    	<option value="<?php echo $row['id']?>" <?php if($row['id'] == $id_zone) echo'selected="selected"';?> ><?php echo $row['name']?></option>
                        <?php } ?>
                    </select>
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