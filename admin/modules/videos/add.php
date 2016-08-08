<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_VIDEO') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		$video_id = (isset($_POST[ 'video_id' ]))?$_POST[ 'video_id' ]: '';
		$video_image = (isset($_POST[ 'video_image' ]))?$_POST[ 'video_image' ]: '';
		$title = (isset($_POST[ 'title' ]))?$_POST[ 'title' ]: ''; 
		$short_description = (isset($_POST[ 'short_description' ]))?$_POST[ 'short_description' ]: '';
		$long_description = (isset($_POST[ 'long_description' ]))?$_POST[ 'long_description' ]: '';
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
		
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($video_id,getLang('NO_VIDEO_ID_GIVEN'));
			$validator->validateGeneral($video_image,getLang('NO_IMAGE_GIVEN'));
			$validator->validateGeneral($title,getLang('NO_TITLE_GIVEN'));
			
			// Thumnail
			if(!($validator->foundErrors())){
				$filename = $video_image.".jpg";
				$result_increment = $connector->query("SHOW TABLE STATUS LIKE '".$module."'");
				$row_increment = $connector->fetchArray($result_increment);
				$p = "uploads/".$module."/";
				$filename = $row_increment['Auto_increment'].".jpg";
				$imagepath=$p.$filename;
				// Image
				$sourcepath = "../uploads/videos/image_temp.jpg";
				$full = new Thumbnail($sourcepath);
				$fullpath=$full->getThumbPath($imagepath, 'image');
				$full->save($fullpath,100); // save the thumbnail
				$fulldbpath=$full->getThumbPathDB($fullpath); // set up the path for the database
				// Thumb
				$sourcepath = "../uploads/videos/thumb_temp.jpg";
				$thumb = new Thumbnail($sourcepath);
				$thumb->resizeForCrop(120,90);
				$thumb->cropFromCenterSize(120,90);
				$thumbpath=$thumb->getThumbPath($imagepath, 'thumb');
				$thumb->save($thumbpath,100); // save the thumbnail
				$thumbdbpath=$thumb->getThumbPathDB($thumbpath); // set up the path for the database
			}
			
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html("<div class=\"msg msg-error\"><p><?php echo $validator->listErrors('<br>'); ?></p></div>");
					 $('#msgbox').show();
                 </script><?php
			}else{
				// Order Existing items from 1 to n 
				$result_order=$connector->query("SELECT id, order_position FROM $module ORDER BY order_position");
				$i=1;
				while ($row_order = $connector->fetchArray($result_order)){
					$temp_id=$row_order['id']; 
					$connector->query("UPDATE $module SET order_position=$i WHERE id=$temp_id");
					$i++;
				}
				
				// Insertion
				$query = "INSERT INTO $module (video_id, video_thumb, video_image, title, short_description, long_description, order_position, active) VALUES (".
				"'".isql($video_id)."', ".
				"'".$thumbdbpath."', ".
				"'".$fulldbpath."', ".
				"'".isql($title)."', ".
				"'".isql($short_description)."', ".
				"'".isql($long_description)."', ".
				"0,".
				"1)";
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('VIDEO_ADDED').'</p></div>';
					header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
				}else{ ?>
                
					<script>
						$('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR') ?></p></div>');
                        $('#msgbox').show();
                    </script><?php
				}
			}
		}
		
		// If Cancel has been clicked
		else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
			header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
		}
		
		// Display the form
		require_once("templates/form.php"); ?>
    </div>
</div>
