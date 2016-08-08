<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_ORDER_ONLINE_PICTURE') ?></h3>
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
		$id_plat=(isset($_POST['id_plat']))?$_POST['id_plat']:$row['id_plat'];

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
					$full = new Thumbnail('../'.$imagepath);
					$full->resizeForCrop(350,170);
					$full->cropFromCenterSize(350,170);
					$full_path=$full->getThumbPath($imagepath, 'full');
					$full->save($full_path,80);
					$full_dbpath=$full->getThumbPathDB($full_path); // set up the path for the database
					
					$thumb = new Thumbnail('../'.$imagepath);
					$thumb->resizeForCrop(130,63);
					$thumb->cropFromCenterSize(130,63);
					$thumbpath=$thumb->getThumbPath($imagepath, 'thumb');
					$thumb->save($thumbpath,80); // save the thumbnail
					$thumbdbpath=$thumb->getThumbPathDB($thumbpath); // set up the path for the database

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
						     SET name='".isql($name)."'";
				if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
					$query.=",photo='".$full_dbpath."',
							 thumbnail='".$thumbdbpath."'";
				}
				$query .= "WHERE id =". intval($id);
				

				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('DISH_DAY_UPDATED').'</p></div>';
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
