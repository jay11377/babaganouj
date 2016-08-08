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
            <div class="field">
            	<label for="menu"><?php showLang('CATEGORY_TYPE') ?></label>
                <select name="menu" id="menu">
                    <option value="0"><?php showLang('DISHES') ?></option>
                    <option value="1" <?php if($menu==1) echo'selected="selected"';?>><?php showLang('MEZZES'); ?></option>
                </select>
                <div class="help"><?php showLang('CATEGORY_TYPE_EXPLAINED'); ?></div> 	
            </div>
            <div class="field">
            	<label for="description"><?php showLang('DESCRIPTION') ?></label>
                <input class="ckeditor" type="text" name="description" id="description" value="<?php echo osql($description);?>" >
                <div class="help"><?php showLang('NOT_MANDATORY'); ?></div> 	
            </div>
             
             <label></label>
             <?php if($_GET['action']=='edit' && isset($row['thumbnail']) && $row['thumbnail']!='') echo '<img src="../'.$row['thumbnail'].'">' ?>
             
             <div class="field ">
                <label for="image"><?php showLang('IMAGE') ?></label>
                <span class="upload">
                    <input type="text" value="" readonly="readonly" name="fakeimage" class="fake">
                    <span class="wrapper">
                        <span class="button"><?php showLang('UPLOAD') ?></span>
                        <input type="file" onchange="this.form.fakeimage.value = this.value;" class="real" value="" name="image" id="image">
                    </span><br /><br />
                </span>
                <div class="help"><?php showLang('IMG_SIZE_2M_MOBILE'); ?></div>
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