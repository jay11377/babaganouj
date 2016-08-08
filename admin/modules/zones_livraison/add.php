<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_ZONE') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		$name = (isset($_POST[ 'name' ]))?$_POST[ 'name' ]: '';
		$minimum = (isset($_POST[ 'minimum' ]))?$_POST[ 'minimum' ]: ''; 
		$duree_livraison = (isset($_POST[ 'duree_livraison' ]))?$_POST[ 'duree_livraison' ]: ''; 
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
		
		// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($name,getLang('NO_NAME_GIVEN'));
			if($validator->validateGeneral($minimum,getLang('NO_MINIMUM_GIVEN')))
				$validator->validateNumber($minimum,getLang('MINIMUM_IS_NUMBER'));
			if($validator->validateGeneral($duree_livraison,getLang('NO_DURATION_GIVEN')))
				$validator->validateNumber($duree_livraison,getLang('DURATION_IS_NUMBER'));
			
			
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				
				// Insertion
				$query = "INSERT INTO $module (name,minimum, duree_livraison) VALUES (".
				"'".isql($name)."', ".
				"'".isql($minimum)."', ".
				"'".isql($duree_livraison)."' 
				)";
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('ZONE_ADDED').'</p></div>';
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
