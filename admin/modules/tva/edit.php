<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_TAX_RATE') ?></h3>
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
		$tax_rate=(isset($_POST['tax_rate']))?$_POST['tax_rate']:$row['value'];

		// Check whether a form has been submitted. If so, carry on
		if( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($name,getLang('NO_NAME_GIVEN'));
			$validator->validatePrice($tax_rate,getLang('TAX_RATE_FORMAT_INCORRECT'));
			
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
							 value =".$tax_rate;
				$query .= " WHERE id =". $id;

				if ($result = $connector->query($query)){
					if($row['value']!=$tax_rate)
					{
						$result = $connector->query("SELECT id, prix_ht, prix_ttc FROM plats WHERE id_tva=".$id);
						
						// Changement du prix HT
						while ($row = $connector->fetchArray($result)){
							$prix_ht = round(($row['prix_ttc'] / (1 + ($tax_rate/100))),6);
							$query = "UPDATE plats SET prix_ht = ".$prix_ht." WHERE id = ".$row['id'];
							$connector->query($query);	
						}
						
						
						// Changement du prix ttc
						/*
						while ($row = $connector->fetchArray($result)){
							$prix_ttc = round(($row['prix_ht'] * (1 + ($tax_rate/100))),2);
							$query = "UPDATE plats SET prix_ttc = ".$prix_ttc." WHERE id = ".$row['id'];
							$connector->query($query);	
						}
						*/
					}
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('TAX_RATE_UPDATED').$query.'</p></div>';
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
