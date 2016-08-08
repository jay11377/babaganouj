<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_PHOTO') ?></h3>
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
		
		$titre=(isset($_POST['titre']))?$_POST['titre']:$row['titre'];
		$commentaire=(isset($_POST['commentaire']))?$_POST['commentaire']:$row['commentaire'];

		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			//$validator->validateGeneral($titre,getLang('NO_TITLE_GIVEN'));
			//$validator->validateGeneral($commentaire,getLang('NO_COMMENT_GIVEN'));
			//Image
			if( !($validator->foundErrors())&&( isset($_FILES['image']) && $_FILES['image']['name'] != '') ){
				$imagepath = $validator->validateUploadImage('image',getLang('NO_IMAGE_GIVEN'),$module,0,0);
				// Thumnail
				if(!($validator->foundErrors()) && $imagepath!=false){
					$full = new Thumbnail('../'.$imagepath);
					$full->resizeForCrop(900,675);
					$full->cropFromCenterSize(900,675);
					$full_path=$full->getThumbPath($imagepath, 'full');
					$full->save($full_path,80);
					$full_dbpath=$full->getThumbPathDB($full_path); // set up the path for the database
					
					$thumb = new Thumbnail('../'.$imagepath);
					$thumb->resizeForCrop(200,150);
					$thumb->cropFromCenterSize(200,150);
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
						     SET titre='".isql($titre)."',
							     commentaire='".$commentaire."'";
				if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
					$query.=",photo='".$full_path."',".
							"thumbnail='".$thumbdbpath."'";
				}
				$query .= "where id =". $id;
				

				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('PHOTO_UPDATED').'</p></div>';
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
