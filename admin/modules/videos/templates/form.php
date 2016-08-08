<script>
$(document).ready(function(){
	$("#video_id").blur(function(){
		$("#video_images").html('<img src="http://img.youtube.com/vi/' + $(this).val()  + '/0.jpg" width="120" height="90" rel="0"><img src="http://img.youtube.com/vi/' + $(this).val()  + '/1.jpg" rel="1"><img src="http://img.youtube.com/vi/' + $(this).val()  + '/2.jpg" rel="2"><img src="http://img.youtube.com/vi/' + $(this).val()  + '/3.jpg" rel="3">');		
	});
	$("#video_images img").live('click',function(){
		$("#video_images img").removeClass('on');
		$(this).addClass('on');
		$("#video_image").val($(this).attr('rel'));		
	});
})
</script>
<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                <input type="hidden" name="video_image" id="video_image" value="<?php echo $video_image; ?>" />
            </div>
            
            <div class="required  field ">
            	<label for="video_id"><?php showLang('VIDEO_ID') ?></label>
                <input type="text" name="video_id" id ="video_id" value="<?php echo osql($video_id);?>" > 
            </div>
            
            <div class="required  field ">
            	<label class="left"><?php showLang('VIDEO_IMAGES') ?></label>
                 <div class="left" id="video_images"></div>
            </div>
            <div style="clear:both; height:20px;"></div>
            
            <div class="required  field ">
            	<label for="title"><?php showLang('TITLE') ?></label>
                <input type="text" name="title" id ="title" value="<?php echo osql($title);?>" > 
            </div>
			
            <div class="field ">
            	<label for="short_description"><?php showLang('SHORT_DESCRIPTION') ?></label>
                <input type="text" name="short_description" id ="short_description" value="<?php echo osql($short_description);?>" > 
            </div>
            
            <div class="field">
            	<label for="long_description" class="left"><?php showLang('LONG_DESCRIPTION') ?></label>
                <div class="left"><textarea class="ckeditor" id="long_description" name="long_description"><?php echo $long_description ?></textarea></div>
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