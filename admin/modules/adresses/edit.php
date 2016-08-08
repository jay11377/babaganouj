<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_ADDRESS') ?></h3>
    </div>
    <div class="container"><?php
		if(isset($_GET['id'])){
			$id=$_GET['id'];
		}
		else if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		
		if(isset($_GET['id_client'])){
			$id_client=$_GET['id_client'];
		}
		else if(isset($_POST['id_client'])){
			$id_client=$_POST['id_client'];
		}
		
		$connector = new DbConnector();
		$query="SELECT * FROM $module WHERE id=".(int)$id;
		$result=$connector->query($query);
		$row=$connector->fetchArray($result);
		
		$titre_adresse = (isset($_POST[ 'titre_adresse' ]))?$_POST[ 'titre_adresse' ]: $row['titre_adresse'];
		$societe = (isset($_POST[ 'societe' ]))?$_POST[ 'societe' ]: $row['societe'];
		$prenom_adresse = (isset($_POST[ 'prenom_adresse' ]))?$_POST[ 'prenom_adresse' ]: $row['prenom'];
		$nom_adresse = (isset($_POST[ 'nom_adresse' ]))?$_POST[ 'nom_adresse' ]: $row['nom'];
		$adresse1 = (isset($_POST[ 'adresse1' ]))?$_POST[ 'adresse1' ]: $row['adresse1'];
		$adresse2 = (isset($_POST[ 'adresse2' ]))?$_POST[ 'adresse2' ]: $row['adresse2'];
		$cp = (isset($_POST[ 'cp' ]))?$_POST[ 'cp' ]: $row['cp'];
		$ville = (isset($_POST[ 'ville' ]))?$_POST[ 'ville' ]: $row['ville'];
		$telephone = (isset($_POST[ 'telephone' ]))?$_POST[ 'telephone' ]: $row['telephone'];
		$code_entree = (isset($_POST[ 'code_entree' ]))?$_POST[ 'code_entree' ]: $row['code_entree'];
		$interphone = (isset($_POST[ 'interphone' ]))?$_POST[ 'interphone' ]: $row['interphone'];
		$service = (isset($_POST[ 'service' ]))?$_POST[ 'service' ]: $row['service'];
		$escalier = (isset($_POST[ 'escalier' ]))?$_POST[ 'escalier' ]: $row['escalier'];
		$etage = (isset($_POST[ 'etage' ]))?$_POST[ 'etage' ]: $row['etage'];
		$numero_appartement = (isset($_POST[ 'numero_appartement' ]))?$_POST[ 'numero_appartement' ]: $row['numero_appartement'];
		$remarque = (isset($_POST[ 'remarque' ]))?$_POST[ 'remarque' ]: $row['remarque'];
		$defaut = ($row['defaut']==1)?1: 0;
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($prenom_adresse,getLang('NO_FIRST_NAME_ADDRESS_GIVEN'));
			$validator->validateGeneral($nom_adresse,getLang('NO_LAST_NAME_ADDRESS_GIVEN'));
			$validator->validateGeneral($adresse1,getLang('NO_ADDRESS_GIVEN'));
			$validator->validateMultiplePostCode($cp,getLang('POSTCODE_LENGTH'));
			$validator->validateGeneral($ville,getLang('NO_CITY_GIVEN'));
			$validator->validateGeneral($telephone,getLang('NO_TELEPHONE_GIVEN'));
			$validator->validateGeneral($titre_adresse,getLang('NO_ADDRESS_TITLE_GIVEN'));
			
			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				// Update
				$query = "UPDATE adresses SET active=0 WHERE id=".(int)$id;
				if ($connector->query($query)){
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
						"".$defaut.
						")";
						if ($result = $connector->query($query)){
							$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('ADDRESS_UPDATED').'</p></div>';
							header( 'Location: moduleinterface.php?module=clients&action=edit&id='.$id_client ) ;
						}
						else{ ?>
							<script>
								 $('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR')?></p></div>');
								 $('#msgbox').show();
							</script><?php
						}
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
