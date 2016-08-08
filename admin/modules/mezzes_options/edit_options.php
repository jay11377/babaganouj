<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_OPTION') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		$id = $_REQUEST['id'];
		$query="SELECT * FROM options where id = ".$id; 
		$result = $connector->query($query);
		$row = $connector->fetchArray($result);
		$name = (isset($_POST[ 'name' ]))?$_POST[ 'name' ]: $row['name']; 
		$name_admin = (isset($_POST[ 'name_admin' ]))?$_POST[ 'name_admin' ]: $row['name_admin']; 
		
			
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
		
		// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($name,getLang('NO_NAME_GIVEN'));
			
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				
				// Update
				$query = "UPDATE options SET name = '".isql($name)."', name_admin = '".isql($name_admin)."' WHERE id = ".(int)$id;
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('OPTION_UPDATED').'</p></div>';
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
		require_once("templates/form_options.php"); ?>
    </div>
</div>
