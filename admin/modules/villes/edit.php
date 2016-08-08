<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_CITY') ?></h3>
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
		$result2 = $connector->query("SELECT * FROM zones_livraison"); /*To grab the list of categories for the select option form */
		
		$name=(isset($_POST['name']))?$_POST['name']:$row['name'];
		$postcode=(isset($_POST['postcode']))?$_POST['postcode']:$row['postcode'];
		$id_zone=(isset($_POST['id_zone']))?$_POST['id_zone']:$row['id_zone'];

		// Check whether a form has been submitted. If so, carry on
		if( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($name,getLang('NO_NAME_GIVEN'));
			$validator->validateMultiplePostCode($postcode,getLang('POSTCODE_LENGTH'));
			
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
							 postcode='".isql($postcode)."',
							 id_zone ='".isql($id_zone)."'";
				$query .= "where id =". $id;

				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('CITY_UPDATED').'</p></div>';
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
