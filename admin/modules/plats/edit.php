<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_DISH') ?></h3>
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
		$result2 = $connector->query("SELECT * FROM categories WHERE menu=0 ORDER BY name"); /* Get the list of categories for the select option form */
		$result3 = $connector->query("SELECT * FROM tva ORDER BY value"); /* Get the list of tax rates */
		
		$name=(isset($_POST['name']))?$_POST['name']:$row['name'];
		$ingredient_principal=(isset($_POST['ingredient_principal']))?$_POST['ingredient_principal']:$row['ingredient_principal'];
		$description=(isset($_POST['description']))?$_POST['description']:$row['description'];
		$vegetarien=(isset($_POST['vegetarien']))?1:$row['vegetarien'];
		$removephoto2=(isset($_POST['removephoto2']))?1:0;
		$removephoto3=(isset($_POST['removephoto3']))?1:0;
		$epice= (isset($_POST['epice']))?1:$row['epice'];
		$id_tva = (isset($_POST['id_tva']))?$_POST['id_tva']:$row['id_tva'];
		if(isset($_POST['prix_ht']))
		{
			$prix_ht = $_POST['prix_ht'];
		}
		else
		{
			$prix_ht = $row['prix_ht'];
			// Remove the last 4 digits if they are 0
			for($i=0;$i<4;$i++)
			{
				if(substr($prix_ht, -1)==0)
					$prix_ht = substr($prix_ht, 0, -1);
			}
		}
		$prix_ttc=(isset($_POST['prix_ttc']))?$_POST['prix_ttc']:$row['prix_ttc'];
		$id_categorie=(isset($_POST['id_categorie']))?$_POST['id_categorie']:$row['id_categorie'];

		// Check whether a form has been submitted. If so, carry on
		if( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($name,getLang('NO_NAME_GIVEN'));
			$validator->validateGeneral($prix_ttc,getLang('PRICE_FORMAT_INCORRECT'));
			$vegetarien=(isset($_POST['vegetarien']))?1:0;
			$epice=(isset($_POST['epice']))?1:0;
			//Image
			if( !($validator->foundErrors()) && isset($_FILES['photo1']) && $_FILES['photo1']['name'] != '' ){
				$imagepath = $validator->validateUploadImage('photo1',getLang('NO_IMAGE_GIVEN'),$module,0,0);
				// Thumnail
				if($imagepath!=false){
					/* For the web */
					$big = new Thumbnail('../'.$imagepath);
					$big->resizeForCrop(800,600);
					$big->cropFromCenterSize(800,600);
					$big_path=$big->getThumbPath($imagepath, 'big');
					$big->save($big_path,80); // save the thumbnail
					$big_dbpath=$big->getThumbPathDB($big_path); // set up the path for the database
					
					$full = new Thumbnail('../'.$imagepath);
					$full->resizeForCrop(440,330);
					$full->cropFromCenterSize(440,330);
					$full_path=$full->getThumbPath($imagepath, 'full');
					$full->save($full_path,100); // save the thumbnail
					$full_dbpath=$full->getThumbPathDB($full_path); // set up the path for the database
					
					$thumb = new Thumbnail('../'.$imagepath);
					$thumb->resizeForCrop(261,193);
					$thumb->cropFromCenterSize(261,193);
					$thumb_path=$thumb->getThumbPath($imagepath, 'thumb1');
					$thumb->save($thumb_path,100); // save the thumbnail
					$thumb_dbpath=$thumb->getThumbPathDB($thumb_path); // set up the path for the database
					
					$thumb_details = new Thumbnail('../'.$imagepath);
					$thumb_details->resizeForCrop(84,63);
					$thumb_details->cropFromCenterSize(84,63);
					$thumb_details_path=$thumb_details->getThumbPath($imagepath, 'thumbdetails');
					$thumb_details->save($thumb_details_path,80); // save the thumbnail
					$thumb_details_dbpath=$thumb_details->getThumbPathDB($thumb_details_path); // set up the path for the database
					
					/* For mobile devices */
					$full_mobile = new Thumbnail('../'.$imagepath);
					$full_mobile->resizeForCrop(290,218);
					$full_mobile->cropFromCenterSize(290,218);
					$full_mobile_path=$full_mobile->getThumbPath($imagepath, 'full_mobile1');
					$full_mobile->save($full_mobile_path,80); // save the thumbnail
					$full_mobile_dbpath=$full_mobile->getThumbPathDB($full_mobile_path); // set up the path for the database
				}
			}
			if(!($validator->foundErrors()) && isset($_FILES['photo2']) && $_FILES['photo2']['name'] != '' ){
							
				//photo2
				$image2path = $validator->validateUploadImage('photo2',getLang('NO_IMAGE_GIVEN'),$module);
				
				// Thumnail
				if($image2path!=false){
					
					/* For the web */
					$big2 = new Thumbnail('../'.$image2path);
					$big2->resizeForCrop(800,600);
					$big2->cropFromCenterSize(800,600);
					$big2_path=$big2->getThumbPath($image2path, 'big2');
					$big2->save($big2_path,80); // save the thumbnail
					$big2_dbpath=$big2->getThumbPathDB($big2_path); // set up the path for the database
					
					$full2 = new Thumbnail('../'.$image2path);
					$full2->resizeForCrop(440,330);
					$full2->cropFromCenterSize(440,330);
					$full2_path=$full2->getThumbPath($image2path, 'full2');
					$full2->save($full2_path,100); // save the thumbnail
					$full2_dbpath=$full2->getThumbPathDB($full2_path); // set up the path for the database
					
					$thumb2 = new Thumbnail('../'.$image2path);
					$thumb2->resizeForCrop(261,196);
					$thumb2->cropFromCenterSize(261,196);
					$thumb2_path=$thumb2->getThumbPath($image2path, 'thumb2');
					$thumb2->save($thumb2_path,80); // save the thumbnail
					$thumb2_dbpath=$thumb2->getThumbPathDB($thumb2_path); // set up the path for the database
					
					$thumb2_details = new Thumbnail('../'.$image2path);
					$thumb2_details->resizeForCrop(84,63);
					$thumb2_details->cropFromCenterSize(84,63);
					$thumb2_details_path=$thumb2_details->getThumbPath($image2path, 'thumbdetails2');
					$thumb2_details->save($thumb2_details_path,80); // save the thumbnail
					$thumb2_details_dbpath=$thumb2_details->getThumbPathDB($thumb2_details_path); // set up the path for the database
					
					/* For Mobile Devices */
					$full_mobile2 = new Thumbnail('../'.$image2path);
					$full_mobile2->resizeForCrop(290,218);
					$full_mobile2->cropFromCenterSize(290,218);
					$full_mobile2_path=$full_mobile2->getThumbPath($image2path, 'full_mobile2');
					$full_mobile2->save($full_mobile2_path,80); // save the thumbnail
					$full_mobile2_dbpath=$full_mobile2->getThumbPathDB($full_mobile2_path); // set up the path for the database
	
				}
			}
			
			if(!($validator->foundErrors()) && isset($_FILES['photo3']) && $_FILES['photo3']['name'] != '' ){
							
				//photo3
				$image3path = $validator->validateUploadImage('photo3',getLang('NO_IMAGE_GIVEN'),$module);
				
				// Thumnail
				if($image3path!=false){
					
					/* For the web */
					$big3 = new Thumbnail('../'.$image3path);
					$big3->resizeForCrop(800,600);
					$big3->cropFromCenterSize(800,600);
					$big3_path=$big3->getThumbPath($image3path, 'big3');
					$big3->save($big3_path,80); // save the thumbnail
					$big3_dbpath=$big3->getThumbPathDB($big3_path); // set up the path for the database
					
					$full3 = new Thumbnail('../'.$image3path);
					$full3->resizeForCrop(440,330);
					$full3->cropFromCenterSize(440,330);
					$full3_path=$full3->getThumbPath($image3path, 'full3');
					$full3->save($full3_path,100); // save the thumbnail
					$full3_dbpath=$full3->getThumbPathDB($full3_path); // set up the path for the database
					
					$thumb3 = new Thumbnail('../'.$image3path);
					$thumb3->resizeForCrop(261,196);
					$thumb3->cropFromCenterSize(261,196);
					$thumb3_path=$thumb3->getThumbPath($image3path, 'thumb3');
					$thumb3->save($thumb3_path,80); // save the thumbnail
					$thumb3_dbpath=$thumb3->getThumbPathDB($thumb3_path); // set up the path for the database
					
					$thumb3_details = new Thumbnail('../'.$image3path);
					$thumb3_details->resizeForCrop(84,63);
					$thumb3_details->cropFromCenterSize(84,63);
					$thumb3_details_path=$thumb3_details->getThumbPath($image3path, 'thumbdetails3');
					$thumb3_details->save($thumb3_details_path,80); // save the thumbnail
					$thumb3_details_dbpath=$thumb3_details->getThumbPathDB($thumb3_details_path); // set up the path for the database
					
					/* For mobile devices */
					$full_mobile3 = new Thumbnail('../'.$image3path);
					$full_mobile3->resizeForCrop(290,218);
					$full_mobile3->cropFromCenterSize(290,218);
					$full_mobile3_path=$full_mobile3->getThumbPath($image3path, 'full_mobile3');
					$full_mobile3->save($full_mobile3_path,80); // save the thumbnail
					$full_mobile3_dbpath=$full_mobile3->getThumbPathDB($full_mobile3_path); // set up the path for the database
	
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
							 ingredient_principal='".isql($ingredient_principal)."',
							 description='".isql($description)."',
							 vegetarien=".$vegetarien.",
							 epice=".$epice.",
							 id_tva=".$id_tva.",
							 prix_ht='".isql($prix_ht)."',
							 prix_ttc='".isql($prix_ttc)."',
							 id_categorie =".isql($id_categorie)." ";
				if( isset($_FILES['photo1']) && $_FILES['photo1']['name'] != '' ){
					$query.=",bigphoto1='".$big_dbpath."',".
							"photo1='".$full_dbpath."',".
							"thumbnail1='".$thumb_dbpath."',".
							"photo_mobile1='".$full_mobile_dbpath."',".
							"thumbnail_details1='".$thumb_details_dbpath."',".
							"thumbnail_mobile1='".$thumb_details_dbpath."'";
				}
				if( isset($_FILES['photo2']) && $_FILES['photo2']['name'] != '' ){
					$query.=",bigphoto2='".$big2_dbpath."',".
							"photo2='".$full2_dbpath."',".
							"thumbnail2='".$thumb2_dbpath."',".
							"photo_mobile2='".$full_mobile2_dbpath."',".
							"thumbnail_details2='".$thumb2_details_dbpath."',".
							"thumbnail_mobile2='".$thumb2_details_dbpath."'";
				}
				elseif($removephoto2==1){
					$query.=",bigphoto2='',".
							"photo2='',".
							"thumbnail2='',".
							"photo_mobile2='',".
							"thumbnail_details2='',".
							"thumbnail_mobile2=''";
				}
				if( isset($_FILES['photo3']) && $_FILES['photo3']['name'] != '' ){
					$query.=",bigphoto3='".$big3_dbpath."',".
							"photo3='".$full3_dbpath."',".
							"thumbnail3='".$thumb3_dbpath."',".
							"photo_mobile3='".$full_mobile3_dbpath."',".
							"thumbnail_details3='".$thumb3_details_dbpath."',".
							"thumbnail_mobile3='".$thumb3_details_dbpath."'";
				}
				elseif($removephoto3==1){
					$query.=",bigphoto3='',".
							"photo3='',".
							"thumbnail3='',".
							"photo_mobile3='',".
							"thumbnail_details3='',".
							"thumbnail_mobile3=''";
				}
				$query .= "WHERE id =". $id;
				

				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('DISH_UPDATED').'</p></div>';
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
