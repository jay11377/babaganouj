<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_CITY') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		$result2 = $connector->query("SELECT * FROM zones_livraison"); /*To grab the list of categories for the select option form */
		
		$name = (isset($_POST[ 'name' ]))?$_POST[ 'name' ]: ''; 
		$postcode=(isset($_POST['postcode']))?$_POST['postcode']:'';
		$id_zone = (isset($_POST['id_zone']))?$_POST['id_zone']:'';
		
		
			
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
		
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
				// Calculate the position of the new item
				$result_pos = $connector->query("SELECT MAX(order_position) AS position FROM $module");
				$row_pos = $connector->fetchArray($result_pos);
				if(is_null($row_pos['position']))
					$position=1;
				else
					$position = $row_pos['position']+1;
				
				// Insertion
				$query = "INSERT INTO $module (name, postcode, id_zone, order_position) VALUES (";
				$query.=
				"'".isql($name)."', ".
				"'".isql($postcode)."',".
				"'".isql($id_zone)."',".
				"'".$position."')";
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('CITY_ADDED').'</p></div>';
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
