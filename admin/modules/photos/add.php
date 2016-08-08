<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_PHOTO') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		$titre = (isset($_POST[ 'titre' ]))?$_POST[ 'titre' ]: ''; 
		$commentaire = (isset($_POST[ 'commentaire' ]))?$_POST[ 'commentaire' ]: ''; 
			
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
		
		// Validate the entries
			$validator = new Validator();
			//$validator->validateGeneral($titre,getLang('NO_TITLE_GIVEN'));
			//$validator->validateGeneral($commentaire,getLang('NO_COMMENT_GIVEN'));
			//Image
			$imagepath = $validator->validateUploadImage('image',getLang('NO_IMAGE_GIVEN'),$module);
			
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
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				// Calculate the position of the new item
				$result_pos = $connector->query("SELECT MAX(order_position) AS position FROM $module");
				$row_pos = $connector->fetchArray($result_pos);
				if(is_null($row_pos['position']))
					$position=1;
				else
					$position = $row_pos['position']+1;
				
				// Insertion
				$query = "INSERT INTO $module (titre, commentaire, photo,thumbnail, order_position, active) VALUES (".
				"'".isql($titre)."', ".
				"'".$commentaire."', ".
				"'".$full_dbpath."', ".
				"'".$thumbdbpath."', ".
				"'".$position."',".
				"1)";
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('PHOTO_ADDED').'</p></div>';
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
