<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_VOUCHER') ?></h3>
    </div>
    <div class="container"><?php
		if(isset($_GET['id'])){
			$id=$_GET['id'];
		}
		else if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		
		$connector = new DbConnector();
		$query="SELECT * FROM $module WHERE id=".(int)$id;
		$result=$connector->query($query);
		$row=$connector->fetchArray($result);
		
		$name=(isset($_POST['name']))?$_POST['name']:$row['name'];
		$minimum=(isset($_POST['minimum']))?$_POST['minimum']:$row['minimum'];
		$duree_livraison=(isset($_POST['duree_livraison']))?$_POST['duree_livraison']:$row['duree_livraison'];
		
		$code = (isset($_POST[ 'code' ]))?$_POST[ 'code' ]: $row['code'];
		$valeur = (isset($_POST[ 'valeur' ]))?$_POST[ 'valeur' ]: $row['valeur'];
		$description = (isset($_POST[ 'description' ]))?$_POST[ 'description' ]: $row['description'];
		$panier_minimum = (isset($_POST[ 'panier_minimum' ]))?$_POST[ 'panier_minimum' ]: $row['panier_minimum'];
		$nb_utilisation = (isset($_POST[ 'nb_utilisation' ]))?1:(int)$row['nb_utilisation'];
		$contraintes_date = (isset($_POST[ 'contraintes_date' ]))?1:(int)$row['contraintes_date'];
		$date_debut = (isset($_POST[ 'date_debut' ]))?$_POST[ 'date_debut' ]: dateENtoFR($row['date_debut']);
		$date_fin = (isset($_POST[ 'date_fin' ]))?$_POST[ 'date_fin' ]: dateENtoFR($row['date_fin']);
		$date_debut_sql = (isset($_POST[ 'date_debut' ]))?dateFRtoEN($_POST[ 'date_debut' ]): $row['date_debut'];
		$date_fin_sql = (isset($_POST[ 'date_fin' ]))?dateFRtoEN($_POST[ 'date_fin' ]): $row['date_fin'];
		$afficher_panier = (isset($_POST[ 'afficher_panier' ]))?1:$row['afficher_panier'];
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			$nb_utilisation = (isset($_POST[ 'nb_utilisation' ]))?1:0;
			$contraintes_date = (isset($_POST[ 'contraintes_date' ]))?1:0;
			$afficher_panier = (isset($_POST[ 'afficher_panier' ]))?1:0;
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($code,getLang('NO_CODE_GIVEN'));
			if($validator->validateGeneral($valeur,getLang('NO_VALUE_GIVEN')))
				$validator->validateNumberOver0($valeur,getLang('VALUE_IS_NUMBER'));
			$validator->validateGeneral($description,getLang('NO_DESCRIPTION_GIVEN'));
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
				// Update
				$query = "UPDATE $module 
						     SET code='".isql($code)."',
							 valeur =".(int)($valeur).",
							 description='".isql($description)."',
							 panier_minimum =".(int)($panier_minimum).",
							 nb_utilisation =".(int)($nb_utilisation).",
							 contraintes_date =".(int)($contraintes_date).",
							 date_debut='".isql($date_debut_sql)."',
							 date_fin='".isql($date_fin_sql)."',
							 afficher_panier =".(int)($afficher_panier);
				$query .= " WHERE id =".intval($id);
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('VOUCHER_UPDATED').'</p></div>';
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
