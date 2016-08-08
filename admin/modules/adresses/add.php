<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('ADD_CUSTOMER') ?></h3>
    </div>
    <div class="container"><?php	
		$connector = new DbConnector();
		if(isset($_GET['id_client'])){
			$id_client=$_GET['id_client'];
		}
		else if(isset($_POST['id_client'])){
			$id_client=$_POST['id_client'];
		}
		$query = "SELECT nom, prenom FROM clients WHERE id=".(int)$id_client;
		$result = $connector->query($query);
		$row = $connector->fetchArray($result);
		$prenom = $row['prenom'];
		$nom = $row['nom'];
		
		$titre_adresse = (isset($_POST[ 'titre_adresse' ]))?$_POST[ 'titre_adresse' ]: getLang('ADDRESS_TITLE_DEFAULT');
		$societe = (isset($_POST[ 'societe' ]))?$_POST[ 'societe' ]: '';
		$prenom_adresse = (isset($_POST[ 'prenom_adresse' ]))?$_POST[ 'prenom_adresse' ]: $prenom;
		$nom_adresse = (isset($_POST[ 'nom_adresse' ]))?$_POST[ 'nom_adresse' ]: $nom;
		$adresse1 = (isset($_POST[ 'adresse1' ]))?$_POST[ 'adresse1' ]: '';
		$adresse2 = (isset($_POST[ 'adresse2' ]))?$_POST[ 'adresse2' ]: '';
		$cp = (isset($_POST[ 'cp' ]))?$_POST[ 'cp' ]: '';
		$ville = (isset($_POST[ 'ville' ]))?$_POST[ 'ville' ]: '';
		$telephone = (isset($_POST[ 'telephone' ]))?$_POST[ 'telephone' ]: '';
		$code_entree = (isset($_POST[ 'code_entree' ]))?$_POST[ 'code_entree' ]: '';
		$interphone = (isset($_POST[ 'interphone' ]))?$_POST[ 'interphone' ]: '';
		$service = (isset($_POST[ 'service' ]))?$_POST[ 'service' ]: '';
		$escalier = (isset($_POST[ 'escalier' ]))?$_POST[ 'escalier' ]: '';
		$etage = (isset($_POST[ 'etage' ]))?$_POST[ 'etage' ]: '';
		$numero_appartement = (isset($_POST[ 'numero_appartement' ]))?$_POST[ 'numero_appartement' ]: '';
		$remarque = (isset($_POST[ 'remarque' ]))?$_POST[ 'remarque' ]: '';
		
		// Check whether a form has been submitted. If so, carry on
		if ( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT') ){
			
			// Valider l'adresse
			$validator = new Validator();
			$validator->validateGeneral($prenom_adresse,getLang('NO_FIRST_NAME_ADDRESS_GIVEN'));
			$validator->validateGeneral($nom_adresse,getLang('NO_LAST_NAME_ADDRESS_GIVEN'));
			$validator->validateGeneral($adresse1,getLang('NO_ADDRESS_GIVEN'));
			$validator->validateMultiplePostCode($cp,getLang('POSTCODE_LENGTH'));
			$validator->validateGeneral($ville,getLang('NO_CITY_GIVEN'));
			$validator->validateGeneral($telephone,getLang('NO_TELEPHONE_GIVEN'));
			$validator->validateGeneral($titre_adresse,getLang('NO_ADDRESS_TITLE_GIVEN'));
			
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html("<div class=\"msg msg-error\"><p><?php echo $validator->listErrors('<br>'); ?></p></div>");
					 $('#msgbox').show();
                 </script><?php
			}
			else{	
					$query = "INSERT INTO adresses (id_client, societe, prenom, nom, adresse1, adresse2, cp, ville, telephone, code_entree, interphone, service, escalier, etage, numero_appartement, remarque, titre_adresse, active, defaut) VALUES (".
					$id_client.", ".
					"'".isql($societe)."', ".
					"'".isql($prenom_adresse)."', ".
					"'".isql($nom_adresse)."', ".
					"'".isql($adresse1)."', ".
					"'".isql($adresse2)."', ".
					"'".isql($cp)."', ".
					"'".isql(getCloserCityName($ville))."', ".
					"'".isql($telephone)."', ".
					"'".isql($code_entree)."', ".
					"'".isql($interphone)."', ".
					"'".isql($service)."', ".
					"'".isql($escalier)."', ".
					"'".isql($etage)."', ".
					"'".isql($numero_appartement)."', ".
					"'".isql($remarque)."', ".
					"'".isql($titre_adresse)."', ".
					"1, ".
					"0".
					")";
					
					if ($result = $connector->query($query)){
						$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('ADDRESS_ADDED').'</p></div>';
						header( 'Location: moduleinterface.php?module=clients&action=edit&id='.$id_client ) ;
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
