<?php 
include("includes/top_includes.php");
require_once('admin/includes/Validator.php');

$nom = (isset($_POST[ 'nom' ]))?$_POST[ 'nom' ]: '';
$email = (isset($_POST[ 'email' ]))?$_POST[ 'email' ]: '';
$telephone = (isset($_POST[ 'telephone' ]))?$_POST[ 'telephone' ]: '';
$objet = (isset($_POST[ 'objet' ]))?$_POST[ 'objet' ]: '';
$message = (isset($_POST[ 'message' ]))?$_POST[ 'message' ]: '';

if ( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT') ){
	// Valider les données perso
	$validator = new Validator();
	$validator->validateGeneral($nom,getLang('NO_LAST_NAME_GIVEN'));
	$validator->validateEmail($email,getLang('EMAIL_INCORRECT'));
	$validator->validateGeneral($objet,getLang('NO_SUBJECT_GIVEN'));
	$validator->validateGeneral($message,getLang('NO_MESSAGE_GIVEN'));
	
	if(!$validator->foundErrors()){
			// Envoi du mail
			$mail_Subject = "Demande d'information à Baba Ghannouj";
			$mail_Body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" bgcolor=\"".bgEmail."\">";
			$mail_Body .= "<tr>";
			$mail_Body .= "<td align=\"center\" valign=\"top\" width=\"600\">";
			$mail_Body .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"600\">";
			$mail_Body .= "<tr><td bgcolor=\"".bgEmailLogo."\"><br />&nbsp;<img src=\"".$sitedir."image/logoemail.png\" /><br /><br /></td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">Nom</td><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">".osql($nom)."</td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">Email</td><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\"><a style=\"color:".textEmail."\" href=\"mailto:".osql($email)."\">".osql($email)."</a></td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">T&eacute;l&eacute;phone</td><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">".osql($telephone)."</td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">Objet</td><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">".osql($objet)."</td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\" valign=\"top\">Message</td><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:18px;\">".osql($message)."</td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "<tr><td>&nbsp;</td></tr>";
			$mail_Body .= "</table>";
			$mail_Body .= "</td>";
			$mail_Body .= "</tr>";
			$mail_Body .= "</table>";
			$mail_From = osql($email);
			$mail_To = siteEmail;
			//$mail_To = getEmail();
			if(sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat)){
				$_SESSION['pagemsg'] = getLang('CATERER_INFO_SENT');
				header( 'Location: message.php' ) ;
			}
	}
}

?>

<!doctype html>
<html>
    <head>
	    <?php include("includes/head.php"); ?>
	    <title>Contact - <?php showLang('PAGE_TITLE_COMMON') ?></title>
	</head>
<body>
<div class="page-container container">
    <?php include("includes/header.php"); ?>
    <div class="container">
		<div class="page_heading">
			<h1>Contactez-nous</h1>				
		</div>
		<div class="row">
		    <div class="col-md-12">
			    <div id="map">
                    <p>Activez le Javascript !</p>
                </div>
			</div>
		</div>		
		<div class="row">
			<div class="col-md-6">
				<div class="contact_form">
					<form class="form-horizontal" role="form" method="post" name="form" action="">
						<fieldset class="form-group">
							<p>Une question ?</p> 
							N'hésitez pas à nous contacter, nous nous ferons un plaisir de vous répondre dans les plus brefs délais.<?php
							if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT') && $validator->foundErrors()){ ?>
		                        <p class="bg-danger"><?php echo $validator->listErrors('<br>'); ?></p><?php
		                    } 
		                    else{ ?>
		                    	<br /><br /><?php
		                    }?>
							<label>Nom<span class="required">*</span></label>
							<input type="text" name="nom" placeholder="Nom" class="form-control">
							<label>Email<span class="required">*</span></label>
							<input type="text" name="email" placeholder="Email" class="form-control">
							<label>Téléphone</label>
							<input type="text" name="telephone" placeholder="Téléphone" class="form-control">
							<label>Objet<span class="required">*</span></label>
							<select name="objet" class="form-control">
								<option>Réservation</option>
								<option>Livraisons</option>
                                <option>Privatisation</option>
                                <option>Fournisseurs</option>
                                <option>Autres</option>
							</select>						
						</fieldset>
						<div class="form-group">
							<label>Message / Question<span class="required">*</span></label>
							<textarea rows="5" name="message" class="form-control"></textarea>
						</div>
						<p class="form-group">
							<button class="btn btn-primary" name="submit" type="submit" value="<?php showLang('SUBMIT') ?>">Envoyer</button>
						</p>
					</form>
				</div>
			</div>				
			<div class="col-md-6">
				<div class="location">
					<address>
					  <strong>Ma Bento</strong><br>
					  35 Cours Michelet<br>
					  92800 Puteaux<br>
					  <abbr title="Phone">Téléphone :</abbr> (+33) 01 49 07 49 07
					</address>

					<address>
					  <strong>Mail</strong><br>
					  <a href="mailto:#">babaghannoujparis@gmail.com</a>
					</address>
				</div>
			</div>	
		</div>			
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=true"></script>
<script language="javascript" type="text/javascript" src="js/jquery.ui.map.full.min.js"></script>
<script>
jQuery(window).load(function() 
{
	$('#map').gmap().bind('init', function(ev, map) 
	{
		$('#map').gmap('addMarker', {'position': '48.8880915,2.2470315', 'bounds': true}).click(function() 
		{
			$('#map').gmap('openInfoWindow', 
			{
				'content': 
				'<p>35 Cours Michelet</p><p>92800 Puteaux</p>'
			}, this);
		});
		$('#map').gmap('option', 'zoom', 15);
	});
});
</script>
</body>
</html>
