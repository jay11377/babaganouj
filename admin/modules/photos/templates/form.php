<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            
            <div class="field ">
            	<label for="titre"><?php showLang('TITLE') ?></label>
                <input type="text" name="titre" id ="titre" value="<?php echo osql($titre);?>" > 
            </div>
            
            <div class="field ">
            	<label for="commentaire" class="left"><?php showLang('COMMENT') ?></label>
                <div class="left"><textarea class="ckeditor" id="commentaire" name="commentaire"><?php echo $commentaire ?></textarea></div>
            </div>
            <div style="clear:both; height:20px;"></div>
             
             <label></label>
             <?php if($_GET['action']=='edit') echo '<img src="'.$sitedir.$row['thumbnail'].'">' ?>
             
             <div class="required  field ">
                <label for="image"><?php showLang('IMAGE') ?></label>
                <span class="upload">
                    <input type="text" value="" readonly="readonly" name="fakeimage" class="fake">
                    <span class="wrapper">
                        <span class="button"><?php showLang('UPLOAD') ?></span>
                        <input type="file" onchange="this.form.fakeimage.value = this.value;" class="real" value="" name="image" id="image">
                    </span><br /><br />
                </span>
                <div class="help"><?php showLang('SIZE_4_3'); ?><br /><br /><?php showLang('IMG_SIZE_2M'); ?></div>
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