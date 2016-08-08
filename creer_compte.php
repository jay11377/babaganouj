<?php
include("includes/top_includes.php");
require_once('admin/includes/Validator.php');

$prenom = (isset($_POST[ 'prenom' ]))?$_POST[ 'prenom' ]: '';
$nom = (isset($_POST[ 'nom' ]))?$_POST[ 'nom' ]: '';
$email = (isset($_POST[ 'email' ]))?$_POST[ 'email' ]: '';
$password = (isset($_POST[ 'password' ]))?$_POST[ 'password' ]: '';
$password_confirmation = (isset($_POST[ 'password_confirmation' ]))?$_POST[ 'password_confirmation' ]: '';
//$newsletter = (isset($_POST[ 'newsletter' ]))?1:0;
$newsletter = 1;

$titre_adresse = (isset($_POST[ 'titre_adresse' ]))?$_POST[ 'titre_adresse' ]: getLang('ADDRESS_TITLE_DEFAULT');
$societe = (isset($_POST[ 'societe' ]))?$_POST[ 'societe' ]: '';
$prenom_adresse = (isset($_POST[ 'prenom_adresse' ]))?$_POST[ 'prenom_adresse' ]: '';
$nom_adresse = (isset($_POST[ 'nom_adresse' ]))?$_POST[ 'nom_adresse' ]: '';
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



if ( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT') ){
	$newsletter = (isset($_POST[ 'newsletter' ]))?1:0;
	
	// Valider les données perso
	$validator = new Validator();
	$validator->validateGeneral($prenom,getLang('NO_FIRST_NAME_GIVEN'));
	$validator->validateGeneral($nom,getLang('NO_LAST_NAME_GIVEN'));
	$validator->validateEmailAccount($email,getLang('EMAIL_INCORRECT'));
	if($validator->validatePassword($password,getLang('NO_PASSWORD_GIVEN')));
		$validator->compare($password,$password_confirmation,getLang('PASSWORDS_DONT_MATCH'));
	
	// Valider l'adresse
	$validator->validateGeneral($prenom_adresse,getLang('NO_FIRST_NAME_ADDRESS_GIVEN'));
	$validator->validateGeneral($nom_adresse,getLang('NO_LAST_NAME_ADDRESS_GIVEN'));
	$validator->validateGeneral($adresse1,getLang('NO_ADDRESS_GIVEN'));
	$validator->validateMultiplePostCode($cp,getLang('POSTCODE_LENGTH'));
	$validator->validateGeneral($ville,getLang('NO_CITY_GIVEN'));
	$validator->validateGeneral($telephone,getLang('NO_TELEPHONE_GIVEN'));
	$validator->validateGeneral($titre_adresse,getLang('NO_ADDRESS_TITLE_GIVEN'));
	
	if(!$validator->foundErrors()){
		$query = "INSERT INTO clients (prenom, nom, email, password, newsletter) VALUES (".
		"'".isql($prenom)."', ".
		"'".isql($nom)."', ".
		"'".isql($email)."', ".
		"'".setPassword($password)."', ".
		"".$newsletter."".
		")"; 
		
		$connector = new DbConnector();
		if ($connector->query($query)){
			$last_client_id = mysql_insert_id();
			$query = "INSERT INTO adresses (id_client, societe, prenom, nom, adresse1, adresse2, cp, ville, telephone, code_entree, interphone, service, escalier, etage, numero_appartement, remarque, titre_adresse, active, defaut) VALUES (".
			$last_client_id.", ".
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
			"1".
			")";
			
			if ($connector->query($query)){
				if ($theUser->checkLogin($email, $password))
				{ 
					email_registration_confirmation($_SESSION['id_client']);
					if(isset($_POST['back'])){
						if(isset($_SESSION['id_commande'])){
							$query = "UPDATE commandes SET id_client = ".(int)$_SESSION['id_client']. " WHERE id = ".(int)$_SESSION['id_commande']." AND id_client=0";
							$connector->query($query);
						}
						header( 'Location: cart.php?step=2' ) ;
					}
					else{
						$_SESSION['pagemsg'] = getLang('CLIENT_ADDED');
						header( 'Location: message.php' ) ;
					}
				}
			}
			else{
				$_SESSION['pagemsg'] = getLang('DB_ERROR');
				header( 'Location: message.php' ) ;
			}
		}
		else{
			$_SESSION['pagemsg'] = getLang('DB_ERROR');
			header( 'Location: message.php' ) ;
		}
	}
}

?>
<!doctype html>
<html>
    <head>
        <?php include("includes/head.php"); ?>
        <title><?php showLang('PAGE_TITLE_COMMON') ?></title>
    </head>
<body>
<div class="page-container container">
    <?php include("includes/header.php"); ?>
    <div class="container">
       <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <ul class="breadcrumb">
                        <li><a href="commander.php">Commander en ligne</a> <span class="divider"></span></li>
                        <li class="active">Créer un compte</li>
                    </ul>
                </div>
            </div>  
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <h1>Créer un compte</h1>
                <p class="well">Déjà enregistré ? <a href="identification.php">Cliquez-ici pour vous identifier</a></p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="post" name="form" action="" id="creer_compte">
                    <?php
                    if(isset($_GET['back'])): ?>
                        <input type="hidden" name="back" value="<?php echo $_GET['back'] ?>" /><?php
                    endif;
                    ?>
                    <div class="required">* <?php showLang('REQUIRED_FIELD') ?></div><?php
                    if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT') && $validator->foundErrors()){ ?>                        
                        <p class="bg-danger"><?php echo $validator->listErrors('<br>'); ?></p><?php
                    } ?>
                    <h3><?php showLang('PERSONAL_INFO') ?></h3>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="prenom"><?php showLang('FIRST_NAME') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($prenom) ?>" id="prenom" name="prenom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="nom"><?php showLang('LAST_NAME') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($nom) ?>" id="nom" name="nom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="email"><?php showLang('EMAIL') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($email) ?>" id="email_compte" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="password"><?php showLang('PASSWORD') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" value="" id="password" name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="password"><?php showLang('PASSWORD_CONFIRMATION') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" value="" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="newsletter"><?php showLang('NEWSLETTER_SUBSCRIBE') ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" style="margin-top:10px" name="newsletter" <?php if($newsletter==1): ?> checked="checked" <?php endif; ?> />
                        </div>
                    </div>

                    <h3><?php showLang('ADDRESS') ?></h3>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="titre_adresse"><?php showLang('ADDRESS_TITLE') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($titre_adresse) ?>" id="titre_adresse" name="titre_adresse">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="societe"><?php showLang('COMPANY') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($societe) ?>" id="societe" name="societe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="prenom_adresse"><?php showLang('FIRST_NAME') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($prenom_adresse) ?>" id="prenom_adresse" name="prenom_adresse">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="nom"><?php showLang('LAST_NAME') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($nom_adresse) ?>" id="nom_adresse" name="nom_adresse">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="adresse1"><?php showLang('ADDRESS') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($adresse1) ?>" id="adresse1" name="adresse1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="adresse2"><?php showLang('ADDRESS2') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($adresse2) ?>" id="adresse2" name="adresse2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="cp"><?php showLang('POSTCODE') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($cp) ?>" id="cp" name="cp" class="short">
                        </div>
                    </div>
                    <div class="form-group" style="display:none">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <p class="bg-danger"><?php showLang('CP_WARNING') ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="ville"><?php showLang('CITY') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($ville) ?>" id="ville" name="ville">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="telephone"><?php showLang('PHONE') ?> <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($telephone) ?>" id="telephone" name="telephone" class="medium">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="code_entree"><?php showLang('ENTRY_CODE') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($code_entree) ?>" id="code_entree" name="code_entree" class="medium">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="interphone"><?php showLang('INTERCOM') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($interphone) ?>" id="interphone" name="interphone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="service"><?php showLang('SERVICE') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($service) ?>" id="service" name="service">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="escalier"><?php showLang('STAIRCASE') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($escalier) ?>" id="escalier" name="escalier" class="medium">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="etage"><?php showLang('FLOOR') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($etage) ?>" id="etage" name="etage" class="short">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="numero_appartement"><?php showLang('APARTMENT_NUMBER') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($numero_appartement) ?>" id="numero_appartement" name="numero_appartement" class="short">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="remarque"><?php showLang('COMMENT') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo osql($remarque) ?>" id="remarque" name="remarque">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary" type="submit" name="submit" value="<?php showLang('SUBMIT') ?>"><?php showLang('SUBMIT') ?></button>
                        </div>
                    </div>
                </form>                        
                            
            </div>
        </div>                          
    </div>      
    <?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
<script>
$(document).ready(function(){
    $(document).on('blur','#prenom',function(e){
        $("#prenom_adresse").val($("#prenom").val());
    });
    $(document).on('blur','#nom',function(e){
        $("#nom_adresse").val($("#nom").val());
    });
});
</script>
</body>
</html>