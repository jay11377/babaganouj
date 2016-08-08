<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            <div class="required  field ">
            	<label for="question"><?php showLang('QUESTION') ?></label>
                <input type="text" name="question" id ="question" value="<?php echo osql($question);?>" class="long" /> 
            </div>
            <div class="required field">
            	<label for="reponse" class="left"><?php showLang('ANSWER') ?></label>
                <div class="left"><textarea class="ckeditor" id="reponse" name="reponse"><?php echo $reponse ?></textarea></div> 	
            </div>
            <div style="clear:both; height:20px;"></div>
            <div class="buttons">
                <p>
                  <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                  <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                </p>
            </div>
        </fieldset>
    </form>
</div>