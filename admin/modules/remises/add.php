<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_VOUCHER') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		$code = (isset($_POST[ 'code' ]))?$_POST[ 'code' ]: '';
		$valeur = (isset($_POST[ 'valeur' ]))?$_POST[ 'valeur' ]: '';
		$description = (isset($_POST[ 'description' ]))?$_POST[ 'description' ]: '';
		$quantite_initiale = (isset($_POST[ 'quantite_initiale' ]))?$_POST[ 'quantite_initiale' ]: 0;
		$quantite_restante = (isset($_POST[ 'quantite_initiale' ]))?$_POST[ 'quantite_initiale' ]: '';
		$panier_minimum = (isset($_POST[ 'panier_minimum' ]))?$_POST[ 'panier_minimum' ]: '';
		$nb_utilisation = 1;
		$contraintes_date = (isset($_POST[ 'contraintes_date' ]))?1:0;
		$date_debut = (isset($_POST[ 'date_debut' ]))?$_POST[ 'date_debut' ]: date("d/m/Y");
		$date_fin = (isset($_POST[ 'date_fin' ]))?$_POST[ 'date_fin' ]: date("d/m/Y");
		$date_debut_sql = (isset($_POST[ 'date_debut' ]))?dateFRtoEN($_POST[ 'date_debut' ]): date("d/m/Y");
		$date_fin_sql = (isset($_POST[ 'date_fin' ]))?dateFRtoEN($_POST[ 'date_fin' ]): date("d/m/Y");
		$afficher_panier = (isset($_POST[ 'afficher_panier' ]))?1:0;
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			$nb_utilisation = (isset($_POST[ 'nb_utilisation' ]))?1:0;
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($code,getLang('NO_CODE_GIVEN'));
			$validator->validateVoucher($code,getLang('VOUCHER_CODE_EXISTING'));
			if($validator->validateGeneral($valeur,getLang('NO_VALUE_GIVEN')))
				$validator->validateNumberOver0($valeur,getLang('VALUE_IS_NUMBER'));
			$validator->validateGeneral($description,getLang('NO_DESCRIPTION_GIVEN'));
			if($validator->validateGeneral($quantite_initiale,getLang('NO_QUANTITY_GIVEN')))
				$validator->validateNumber($quantite_initiale,getLang('DURATION_IS_NUMBER'));
			if($validator->validateGeneral($panier_minimum,getLang('NO_MINIMUM_ORDER_GIVEN')))
				$validator->validateNumber($panier_minimum,getLang('MINIMUM_ORDER_IS_NUMBER'));
			$validator->validateCompareDates($date_debut_sql, $date_fin_sql, getLang('DATE_START_AFTER_DATE_END'));
			
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				
				// Insertion
				$query = "INSERT INTO $module (code, valeur, description, quantite_initiale, quantite_restante, panier_minimum, nb_utilisation, contraintes_date, date_debut, date_fin, afficher_panier, active) VALUES (".
				"'".isql($code)."', ".
				"".(int)$valeur.", ".
				"'".isql($description)."', ".
				"".(int)$quantite_initiale.", ".
				"".(int)$quantite_restante.", ".
				"".(int)$panier_minimum.", ".
				"".(int)$nb_utilisation.", ".
				"".(int)$contraintes_date.", ".
				"'".isql($date_debut_sql)."', ".
				"'".isql($date_fin_sql)."', ".
				"".(int)$afficher_panier.", ".
				"1
				)";
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('VOUCHER_ADDED').'</p></div>';
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
