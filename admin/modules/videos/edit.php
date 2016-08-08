<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_VIDEO') ?></h3>
    </div>
    <div class="container"><?php	
		if(isset($_GET['id'])){
			$id=$_GET['id'];
		}
		else if(isset($_POST['id'])){
			$id=$_POST['id'];
		}

		$connector = new DbConnector();
		$query="SELECT * FROM $module WHERE id=".$id;
		$result=$connector->query($query);
		$row=$connector->fetchArray($result);
		
		$video_id = (isset($_POST[ 'video_id' ]))?$_POST[ 'video_id' ]: $row['video_id'];
		$video_image = (isset($_POST[ 'video_image' ]))?$_POST[ 'video_image' ]: '';
		$title = (isset($_POST[ 'title' ]))?$_POST[ 'title' ]: $row['title']; 
		$short_description = (isset($_POST[ 'short_description' ]))?$_POST[ 'short_description' ]: $row['short_description'];
		$long_description = (isset($_POST[ 'long_description' ]))?$_POST[ 'long_description' ]: $row['long_description'];
		
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
				// Update
				$query = "UPDATE $module 
						     SET video_id='".isql($video_id)."',
							     video_thumb='".$thumbdbpath."',
								 video_image='".$fulldbpath."',
								 title='".isql($title)."',
								 short_description='".isql($short_description)."',
								 long_description='".isql($long_description)."' ";
				$query .= "WHERE id =". (int)$id;
				

				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('VIDEO_UPDATED').'</p></div>';
					header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
				}else{ ?>
					<script>
                         $('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR')?></p></div>');
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
