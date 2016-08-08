<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_OPTION_DISH') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		/*grad the dishes list to deiplay it in the form select option */
		$result_plats = $connector->query("SELECT * FROM plats ORDER BY name");
		
		$quantity = (isset($_POST['quantity']))?$_POST['quantity']:'';
		$prix_ht = (isset($_POST['prix_ht']))?$_POST['prix_ht']:'';
		$prix_ttc = (isset($_POST['prix_ttc']))?$_POST['prix_ttc']:''; 
		$id_item = (isset($_POST['id_item']))?$_POST['id_item']:'';
		$id_option = (isset($_REQUEST['id_option']))?$_REQUEST['id_option']:'';
		
		
			
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
		
		// Validate the entries
			$validator = new Validator();
			
			$validator->validateGeneral($quantity,getLang('NO_QUANTITY_GIVEN'));
			$validator->validatePrice($prix_ttc,getLang('PRICE_FORMAT_INCORRECT'));			
			
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				
				echo 'id option ='.$id_option;
				// Calculate the position of the new item
				$result_pos = $connector->query("SELECT MAX(order_position) AS position FROM options_items where id_option = ".$id_option);
				$row_pos = $connector->fetchArray($result_pos);
				if(is_null($row_pos['position']))
					$position=1;
				else
					$position = $row_pos['position']+1;
				
				// Insertion
				$query = "INSERT INTO options_items(id_option, id_item, quantity, prix_ht, prix_ttc, order_position, active) VALUES (".
				"'".isql($id_option)."', ".
				"'".$id_item."', ".
				"'".$quantity."', ".
				"'".$prix_ht."', ".
				"'".$prix_ttc."', ".
				"'".$position."',
				1)";
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('OPTION_DISH_ADDED').'</p></div>';
					header( 'Location: moduleinterface.php?module='.$module.'&action=default_option_items&id_option='.$id_option.'' ) ;
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
			header( 'Location: moduleinterface.php?module='.$module.'&action=default_option_items&id_option='.$id_option.'' ) ;
		}
		
		// Display the form
		require_once("templates/form_item.php"); ?>
    </div>
</div>
