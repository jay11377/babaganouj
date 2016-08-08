<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_CATEGORY') ?></h3>
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
		
		$name=(isset($_POST['name']))?$_POST['name']:$row['name'];
		$description=(isset($_POST['description']))?$_POST['description']:$row['description'];
		$menu = (isset($_POST[ 'menu' ]))?$_POST[ 'menu' ]: $row['menu']; 

		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($name,getLang('NO_NAME_GIVEN'));
			//Image
			if( isset($_FILES['image']) && $_FILES['image']['name'] != '' ){
				$imagepath = $validator->validateUploadImage('image',getLang('NO_IMAGE_GIVEN'),$module,0,0);
				// Thumnail
				if(!($validator->foundErrors()) && $imagepath!=false){
					$thumb = new Thumbnail('../'.$imagepath);
					$thumb->resizeForCrop(130,83);
					$thumb->cropFromCenterSize(130,83);
					$thumbpath=$thumb->getThumbPath($imagepath, 'thumb');
					$thumb->save($thumbpath,80); // save the thumbnail
					$thumbdbpath=$thumb->getThumbPathDB($thumbpath); // set up the path for the database
					
					/*Generating the tumbnail & full image for mobile devices*/
					$full_mobile = new Thumbnail('../'.$imagepath);
					$full_mobile->resizeForCrop(290,258);
					$full_mobile->cropFromCenterSize(290,258);
					$full_mobile_path=$full_mobile->getThumbPath($imagepath, 'full_mobile');
					$full_mobile->save($full_mobile_path,80); // save the thumbnail
					$full_mobile_dbpath=$full_mobile->getThumbPathDB($full_mobile_path); // set up the path for the database
					
					
					$thumb_mobile = new Thumbnail('../'.$imagepath);
					$thumb_mobile->resizeForCrop(79,70);
					$thumb_mobile->cropFromCenterSize(79,70);
					$thumb_mobile_path=$thumb_mobile->getThumbPath($imagepath, 'thumb_mobile');
					$thumb_mobile->save($thumb_mobile_path,80); // save the thumbnail
					$thumb_mobile_dbpath=$thumb_mobile->getThumbPathDB($thumb_mobile_path); // set up the path for the database
				}
			}

			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				// Update
				$query = "UPDATE $module 
						     SET name='".isql($name)."',
							 description='".isql($description)."',
							 menu=".(int)$menu." ";
				if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
					$query.=",photo='".$imagepath."',".
							"thumbnail='".$thumbdbpath."',".
							"photo_mobile='".$full_mobile_dbpath."',".
							"thumbnail_mobile='".$thumb_mobile_dbpath."'";
				}
				$query .= "WHERE id =". $id;
				

				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('CATEGORY_UPDATED').'</p></div>';
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
