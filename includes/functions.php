<?php
if(strpos($_SERVER['SCRIPT_NAME'], 'sogenactif')===FALSE)
	require_once("admin/includes/functions.php");
else
	require_once("functions.php");
if(strpos($_SERVER['SCRIPT_NAME'], '/sogenactif')===FALSE)
	require_once('fr.php');
else
	require_once("fr.php");

function getURLChoixMezze($id){
	return "mezze_choix.php?id=".(int)$id;
	/*
	$connector_plat = new DbConnector();
	$query_plat = "SELECT name FROM plats WHERE id=".(int)$id;
	$result_plat = $connector_plat->query($query_plat);
	$row_plat = $connector_plat->fetchArray($result_plat);
	return $id."__".str2url($row_plat['name']);
	*/
}

function getURLPlat($id){
	return "product.php?id=".(int)$id;
	/*
	$connector_plat = new DbConnector();
	$query_plat = "SELECT name FROM plats WHERE id=".(int)$id;
	$result_plat = $connector_plat->query($query_plat);
	$row_plat = $connector_plat->fetchArray($result_plat);
	return $id."_".str2url($row_plat['name']);
	*/
}

function getURLCategorie($id){
	return "commander.php";
	/*
	$connector_cat = new DbConnector();
	$query_cat = "SELECT name, menu FROM categories WHERE id=".(int)$id;
	$result_cat = $connector_cat->query($query_cat);
	$row_cat = $connector_cat->fetchArray($result_cat);
	if($row_cat['menu']==1) // Mezze
		return $id."--".str2url($row_cat['name']);
	else
		return $id."-".str2url($row_cat['name']);
	*/
}

function getURLVille($id){
	$connector_ville = new DbConnector();
	$query_ville = "SELECT name FROM villes WHERE id=".(int)$id;
	$result_ville = $connector_ville->query($query_ville);
	$row_ville = $connector_ville->fetchArray($result_ville);
	return "livraison-libanais-".str2url($row_ville['name'])."-92_v".$id;
}
function getURLVilleParis($id){
	return "livraison-libanais_p".$id;
}

/**
 * Return a friendly url made from the provided string
 * If the mbstring library is available, the output is the same as the js function of the same name
 *
 * @param string $str
 * @return string
 */
function str2url($str)
{
	if (function_exists('mb_strtolower'))
		$str = mb_strtolower($str, 'utf-8');

	$str = trim($str);
	$str = preg_replace('/[\x{0105}\x{0104}\x{00E0}\x{00E1}\x{00E2}\x{00E3}\x{00E4}\x{00E5}]/u','a', $str);
	$str = preg_replace('/[\x{00E7}\x{010D}\x{0107}\x{0106}]/u','c', $str);
	$str = preg_replace('/[\x{010F}]/u','d', $str);
	$str = preg_replace('/[\x{00E8}\x{00E9}\x{00EA}\x{00EB}\x{011B}\x{0119}\x{0118}]/u','e', $str);
	$str = preg_replace('/[\x{00EC}\x{00ED}\x{00EE}\x{00EF}]/u','i', $str);
	$str = preg_replace('/[\x{0142}\x{0141}\x{013E}\x{013A}]/u','l', $str);
	$str = preg_replace('/[\x{00F1}\x{0148}]/u','n', $str);
	$str = preg_replace('/[\x{00F2}\x{00F3}\x{00F4}\x{00F5}\x{00F6}\x{00F8}\x{00D3}]/u','o', $str);
	$str = preg_replace('/[\x{0159}\x{0155}]/u','r', $str);
	$str = preg_replace('/[\x{015B}\x{015A}\x{0161}]/u','s', $str);
	$str = preg_replace('/[\x{00DF}]/u','ss', $str);
	$str = preg_replace('/[\x{0165}]/u','t', $str);
	$str = preg_replace('/[\x{00F9}\x{00FA}\x{00FB}\x{00FC}\x{016F}]/u','u', $str);
	$str = preg_replace('/[\x{00FD}\x{00FF}]/u','y', $str);
	$str = preg_replace('/[\x{017C}\x{017A}\x{017B}\x{0179}\x{017E}]/u','z', $str);
	$str = preg_replace('/[\x{00E6}]/u','ae', $str);
	$str = preg_replace('/[\x{0153}]/u','oe', $str);

	// Remove all non-whitelist chars.
	$str = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]-]/','', $str);
	$str = preg_replace('/[\s\'\:\/\[\]-]+/',' ', $str);
	$str = preg_replace('/[ ]/','-', $str);
	$str = preg_replace('/[\/]/','-', $str);

	// If it was not possible to lowercase the string with mb_strtolower, we do it after the transformations.
	// This way we lose fewer special chars.
	$str = strtolower($str);

	return $str;
}

function getCurrentFile(){
	return basename($_SERVER['PHP_SELF']);
}
function produit_existe($id_produit) {
    $connector_produit = new DbConnector();
	$result_produit = $connector_produit->query("SELECT * FROM plats WHERE id=".$id_produit);
	return $connector_produit->getNumRows($result_produit)>0;
	$connector_produit->close();
}
function getZones(){
	$connector_zones = new DbConnector();
	$query_zones="SELECT name FROM zones_livraison ORDER BY name LIMIT 0,3";
	$result_zones = $connector_zones->query($query_zones);	 
	while($row_zones = $connector_zones->fetchArray($result_zones))
		$zones[]=$row_zones['name'];
	return $zones;
}

function getOpenTime($jour){
	$conn = new DbConnector();
	$query="SELECT * FROM horaires H LEFT JOIN jours J ON H.id = J.id_horaire WHERE id =".$jour;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	if($row['ouvert_midi']==1){
		if(stillOpenToday()){
			if($row['ouverture_midi_h']>=date('G'))
				return $row['ouverture_midi_h']."h".setMinute($row['ouverture_midi_min']);
			else if($row['ouvert_soir']==1)
				return $row['ouverture_soir_h']."h".setMinute($row['ouverture_soir_min']);		
		}
		else
			return $row['ouverture_midi_h']."h".setMinute($row['ouverture_midi_min']);
	}
	else if($row['ouvert_soir']==1)
		return $row['ouverture_soir_h']."h".setMinute($row['ouverture_soir_min']);
}

function stillOpenToday(){
	$conn = new DbConnector();
	$query="SELECT * FROM horaires H LEFT JOIN jours J ON H.id = J.id_horaire WHERE id =".date('N');
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	$open_today=false;
	if(!($row['ouvert_midi']==0 && $row['ouvert_soir']==0))
	{
			$open_today=true;
			$now_h = date('H');
			$now_m = date('i');
			$ouverture_midi_h = $row['ouverture_midi_h'];
			$ouverture_midi_m = $row['ouverture_midi_min'];
			$fermeture_midi_h = $row['fermeture_midi_h'];
			$fermeture_midi_m = $row['fermeture_midi_min'];
			$ouverture_soir_h = $row['ouverture_soir_h'];
			$ouverture_soir_m = $row['ouverture_soir_min'];
			$fermeture_soir_h = $row['fermeture_soir_h'];
			$fermeture_soir_m = $row['fermeture_soir_min'];
			
			if($row['ouvert_midi']==1)
			{
				if($row['ouvert_soir']==0
								 &&
					($now_h > $fermeture_midi_h
				   	|| ($now_h==$fermeture_midi_h && $now_m > $fermeture_midi_m))
				   )
					$open_today=false;	
			}
			
			if($row['ouvert_soir']==1)
			{
				if($now_h > $fermeture_soir_h 
				   || ($now_h==$fermeture_soir_h && $now_m > $fermeture_soir_m))	
						$open_today=false;
			}
			
	}
	return $open_today;
}

function isOpenedNow(){
	$conn = new DbConnector();
	$query="SELECT * FROM horaires H LEFT JOIN jours J ON H.id = J.id_horaire WHERE id =".date('N');
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	$open_now=false;
	if(!($row['ouvert_midi']==0 && $row['ouvert_soir']==0))
	{
			$now_h = date('H');
			$now_m = date('i');
			
			$ouverture_midi_h = $row['ouverture_midi_h'];
			$ouverture_midi_m = $row['ouverture_midi_min'];
			$fermeture_midi_h = $row['fermeture_midi_h'];
			$fermeture_midi_m = $row['fermeture_midi_min'];
			$ouverture_soir_h = $row['ouverture_soir_h'];
			$ouverture_soir_m = $row['ouverture_soir_min'];
			$fermeture_soir_h = $row['fermeture_soir_h'];
			$fermeture_soir_m = $row['fermeture_soir_min'];
			
			if($row['ouvert_midi']==1)
			{
				if($now_h > $ouverture_midi_h && $now_h < $fermeture_midi_h ||
				   $now_h == $ouverture_midi_h && $now_m <= $fermeture_midi_m ||
				   $now_h == $fermeture_midi_h && $now_m <= $fermeture_midi_m)
					$open_now=true;
			}
			if($row['ouvert_soir']==1 && $open_now==false)
			{
				if($now_h > $ouverture_soir_h && $now_h < $fermeture_soir_h ||
				   $now_h == $ouverture_soir_h && $now_m <= $fermeture_soir_m ||
				   $now_h == $fermeture_soir_h && $now_m <= $fermeture_soir_m)
					$open_now=true;
			}
	}
	return $open_now;
}

function isOpenedNowOneZero(){
	if(isOpenedNow())
		return 1;
	else
		return 0;
}

function willBeOpenIn($min){
	$conn = new DbConnector();
	$query="SELECT * FROM horaires H LEFT JOIN jours J ON H.id = J.id_horaire WHERE id =".date('N');
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	$open_now=false;
	if(!($row['ouvert_midi']==0 && $row['ouvert_soir']==0))
	{
			$delivery_h = date('H', strtotime("+".$min." minutes"));
			$delivery_m = date('i', strtotime("+".$min." minutes"));
			
			$ouverture_midi_h = $row['ouverture_midi_h'];
			$ouverture_midi_m = $row['ouverture_midi_min'];
			$fermeture_midi_h = $row['fermeture_midi_h'];
			$fermeture_midi_m = $row['fermeture_midi_min'];
			$ouverture_soir_h = $row['ouverture_soir_h'];
			$ouverture_soir_m = $row['ouverture_soir_min'];
			$fermeture_soir_h = $row['fermeture_soir_h'];
			$fermeture_soir_m = $row['fermeture_soir_min'];
			
			if($row['ouvert_midi']==1)
			{
				if($delivery_h > $ouverture_midi_h && $delivery_h < $fermeture_midi_h ||
				   $delivery_h == $ouverture_midi_h && $delivery_m <= $fermeture_midi_m ||
				   $delivery_h == $fermeture_midi_h && $delivery_m <= $fermeture_midi_m)
					$open_now=true;
			}
			if($row['ouvert_soir']==1 && $open_now==false)
			{
				if($delivery_h > $ouverture_soir_h && $delivery_h < $fermeture_soir_h ||
				   $delivery_h == $ouverture_soir_h && $delivery_m <= $fermeture_soir_m ||
				   $delivery_h == $fermeture_soir_h && $delivery_m <= $fermeture_soir_m)
					$open_now=true;
			}
	}
	return $open_now;
}

function getPaymentMethod($id){
	$conn = new DbConnector();
	$query="SELECT name FROM order_methods WHERE id =".(int)$id;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	return str_replace('_',' ',$row['name']);
}

function getCategoryId($id_plat){
	$conn = new DbConnector();
	$query="SELECT id_categorie FROM plats WHERE id =".(int)$id_plat;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	return $row['id_categorie'];
}

function getDefaultCategoryId(){
	$conn = new DbConnector();
	$query="SELECT value FROM settings WHERE name='categorie_defaut'";
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	return $row['value'];
}

function getNbUtilisationVoucher($id_remise, $id_client){
	$conn = new DbConnector();
	$query="SELECT * 
			  FROM commandes C
	     LEFT JOIN ligne_commande LC ON (C.id=LC.id_commande) 
		     WHERE C.id_client = ".(int)$id_client." ". 
			  "AND C.id_statut>1
			   AND C.id_statut<5 
			   AND LC.id_remise = ".(int)$id_remise;
	$result = $conn->query($query);
	return $conn->getNumRows($result);
}

function setMinute($min){
	return ((int)$min==0)?"00":$min;
}

function empty_cart(){
	unset($_SESSION['cart']);
	unset($_SESSION['id_commande']);
	unset($_SESSION['message']);
	unset($_SESSION['deliveryTime']);
	unset($_SESSION['moyen_paiement']);
	unset($_SESSION['cgv']);
	unset($_SESSION['couverts']);
	unset($_SESSION['remise']);
	$_SESSION['total_ht'] = 0;
	$_SESSION['total_ttc'] = 0;
}

/*
function deconnecter(){
	unset($_SESSION['id_commande']);
	unset($_SESSION['message']);
	unset($_SESSION['id_client']);
	unset($_SESSION['email']);
	unset($_SESSION['nom']);
	unset($_SESSION['password']);
	unset($_SESSION['cgv']);
	unset($_SESSION['couverts']);
}
*/

function getMezzeIndex($id_produit, $array){
	if(isset($_SESSION['cart']['options'][$id_produit]) && count($_SESSION['cart']['options'][$id_produit])>0)
	{
		$found = 0;
		$arrayMezze = $_SESSION['cart']['options'][$id_produit];
		foreach($arrayMezze as $key=>$value)
		{
			$temp_array = $value;
			unset($temp_array['quantite']);
			$diff=0;
			foreach($temp_array as $k=>$v)
			{
				if($temp_array[$k]!=$array[$k])
					$diff++;
			}
			if($diff==0)
				return $key;
			
			/*
			if(count(array_diff($temp_array, $temp_mezze))==0){ // à ne pas utiliser car ===
				return $key;
			}
			*/
		}
		return count($_SESSION['cart']['options'][$id_produit]);
	}
	else
		return 0;
}

function getEmail(){
	$conn = new DbConnector();
	$result = $conn->query("SELECT email FROM email WHERE id=1");
	$row = $conn->fetchArray($result);
	return $row['email'];
}

function email_resto($id_commande){
	global $sitedir;
	$conn = new DbConnector();
	$mail_From = siteEmail;
	$mail_To = getEmail();
	$query = "SELECT LC.prix_ht, LC.prix_ttc, LC.quantite, LC.total_ht, LC.total_ttc, LC.options, P.name, P.thumbnail1
						FROM ligne_commande LC
				   LEFT JOIN plats P on (LC.id_plat=P.id)
				   WHERE LC.id_commande=".$id_commande." ".
				    "AND remise=0";
			$result = $conn->query($query);
			$items = "<table border=\"0\" cellpadding=\"10\">";
			$items .= "<tr>";
			$items .= "<td><strong>Photo</strong></td>";
			$items .= "<td><strong>Plat</strong></td>";
			$items .= "<td align=\"right\"><strong>Prix unitaire</strong></td>";
			$items .= "<td><strong>Quantit&eacute;</strong></td>";
			$items .= "<td align=\"right\"><strong>Total</strong></td>";
			$items .= "</tr>";
			// Produits
			while($row = $conn->fetchArray($result)){
				$items .= "<tr>";
				$items .= "<td><img src=\"" . $sitedir .$row['thumbnail1']. "\"></td>";
				$items .= "<td>" . osql($row['name']); 
				if(!is_null($row['options'])): 
					$items .= "<br /><i style=\"color:#555\">".$row['options']."</i>";
				endif;
				$items .= "</td>";
				$items .= "<td align=\"right\">" . showPrice($row['prix_ttc'])." &euro;</td>";
				$items .= "<td>" . $row['quantite'] . "</td>";
				$items .= "<td align=\"right\">" . showPrice($row['total_ttc'])." &euro;</td>";
				$items .= "</tr>";
			}
			// Remises
			$query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$id_commande." AND remise=1";
			$result_vouchers = $conn->query($query_vouchers);
			while($row_voucher = $conn->fetchArray($result_vouchers))
			{
				$items .= "<tr>";
				$items .= "<td></td>";
				$items .= "<td>" . osql($row_voucher['description_remise']) . "</td>";
				$items .= "<td align=\"right\">" . $row_voucher['prix_ttc']." &euro;</td>";
				$items .= "<td>" . $row_voucher['quantite'] . "</td>";
				$items .= "<td align=\"right\">" . $row_voucher['total_ttc']." &euro;</td>";
				$items .= "</tr>";
			}
			// Total
			$query = "SELECT total_ht, total_ttc, date_livraison, creneau_livraison, couverts, message, email, adresse_livraison
						FROM commandes C
				   LEFT JOIN clients CL ON (C.id_client=CL.id) 
				   WHERE C.id=".$id_commande;
			$result = $conn->query($query);
			$row = $conn->fetchArray($result);
			$items .= "<tr>";
			$items .= "<td colspan=\"4\" align=\"right\"><b>Total HT</b></td>";
			$items .= "<td align=\"right\"><b>" . showPrice($row['total_ht'])." &euro;</b></td>";
			$items .= "</tr>";
			$items .= "<tr>";
			$items .= "<td colspan=\"4\" align=\"right\"><b>Total TTC</b></td>";
			$items .= "<td align=\"right\"><b>".showPrice($row['total_ttc'])." &euro;</b></td>";
			$items .= "</tr>";
			$items .= "</table>";
		
			$result_address = $conn->query("SELECT * FROM adresses WHERE id=".$row['adresse_livraison']);
			$row_address = $conn->fetchArray($result_address);
			// Envoi du mail
			$mail_Subject = "Nouvelle commande Ma Bento";
			$mail_Body = "Une commande vient d'&ecirc;tre pass&eacute;e sur le site.";
			$mail_Body .= "<br />";
			$mail_Body .= "<br />====================================================";
			$mail_Body .= "<br />" . "Numero de commande: " . $id_commande;
			$mail_Body .= "<br />";
			$mail_Body .= "<br />" . "<strong>Adresse de livraison:</strong>";
			$mail_Body .= "<br />" . osql($row_address['societe']);
			$mail_Body .= "<br />" . osql($row_address['prenom']) . " " .osql($row_address['nom']);
			$mail_Body .= "<br />" . osql($row_address['adresse1']);
			if($row_address['adresse2']!='')
			$mail_Body .= "<br />" . osql($row_address['adresse2']);
			$mail_Body .= "<br />" . osql($row_address['cp']) . " " .osql($row_address['ville']);
			$mail_Body .= "<br />----------------------------<br />";
			if($row_address['code_entree']!='')
				$mail_Body .= getLang('ENTRY_CODE').' : '.osql($row_address['code_entree'])."<br />";
			if($row_address['interphone']!='')
				$mail_Body .= getLang('INTERCOM').' : '.osql($row_address['interphone'])."<br />";
			if($row_address['service']!='')
				$mail_Body .= getLang('SERVICE').' : '.osql($row_address['service'])."<br />";
			if($row_address['escalier']!='')
				$mail_Body .= getLang('STAIRCASE').' : '.osql($row_address['escalier'])."<br />";
			if($row_address['etage']!='')
				$mail_Body .= getLang('FLOOR').' : '.osql($row_address['etage'])."<br />";
			if($row_address['numero_appartement']!='')
				$mail_Body .= getLang('APARTMENT_NUMBER').' : '.osql($row_address['numero_appartement'])."<br />";
			if($row_address['remarque']!='')
				$mail_Body .= getLang('COMMENT').' : '.osql($row_address['remarque'])."<br />";
			$mail_Body .= "<br />";
			if($row['couverts']!='')
				$mail_Body .= "<br />" . "Couverts: " . $row['couverts'];
			if($row['message']!='')
				$mail_Body .= "<br />" . "Message du client: " . osql($row['message']);
			$mail_Body .= "<br />" . "Date de livraison: " . dateENtoFR($row['date_livraison']);
			$mail_Body .= "<br />" . "Heure de livraison demand&eacute;e : " . $row['creneau_livraison'];
			$mail_Body .= "<br />" . "Telephone: " . osql($row_address['telephone']);
			$mail_Body .= "<br />" . "Adresse e-mail: " . osql($row['email']);
			$mail_Body .= "<br />====================================================";
			
			$mail_Body .= "<br />";
			$mail_Body .= "<br />" . $items;
			$mail_Body .= "<br />";
			//mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
			sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat);
}

function email_registration_confirmation($id_client){
	global $sitedir;
	
	$conn = new DbConnector();
	$query = "SELECT prenom, nom, email FROM clients WHERE id=".$id_client;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	// Envoi du mail
	$mail_Subject = "Confirmation de votre inscription";
	$mail_Body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" bgcolor=\"".bgEmail."\">";
	$mail_Body .= "<tr>";
    $mail_Body .= "<td align=\"center\" valign=\"top\" width=\"600\">";
	$mail_Body .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"600\">";
	$mail_Body .= "<tr><td bgcolor=\"".bgEmailLogo."\"><br />&nbsp;<img src=\"".$sitedir."image/logoemail.png\" /><br /><br /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Bonjour ".osql($row['prenom']) . " " .osql($row['nom']).",</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Nous avons le plaisir de vous annoncer que votre compte a bien &eacute;t&eacute; enregistr&eacute;.</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Votre identifiant est : ".$row['email']."</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Vous pouvez d&egrave;s &agrave; pr&eacute;sent vous connecter &agrave; votre compte client afin de pouvoir passer votre 1&egrave;re commande et d&eacute;guster nos plats japonais.</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Si vous rencontrez un probl&egrave;me pour acc&eacute;der &agrave; votre compte, rendez vous sur <a style=\"color:".textEmail."\" href=\"mailto:".siteEmail."\">".siteEmail."</a> ou contacter le service client au ".phoneEmail.".</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Cordialement</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">L'&eacute;quipe ".siteName." </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td><img src=\"".$sitedir."image/logoemail.png\" /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Suivez notre actualit&eacute; sur </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Facebook : <a style=\"color:".textEmail."\" href=\"https://www.facebook.com/pages/MaBento/254840191217166?ref=hl\">".siteName."</a></td></tr>";
	// $mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Twitter : <a style=\"color:".textEmail."\" href=\"\">'.siteName.'</a></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "</table>";
	$mail_Body .= "</td>";
	$mail_Body .= "</tr>";
	$mail_Body .= "</table>";
	
	$mail_From = siteEmail; 
	$mail_To = osql($row['email']);
	if(sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat)){
		return true;
	}
	return false;
}

function email_code($id_client, $code){
	global $sitedir;
	
	$conn = new DbConnector();
	$query = "SELECT prenom, nom, email FROM clients WHERE id=".$id_client;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	// Envoi du mail
	$mail_Subject = "Changement de votre mot de passe";
	$mail_Body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" bgcolor=\"".bgEmail."\">";
	$mail_Body .= "<tr>";
    $mail_Body .= "<td align=\"center\" valign=\"top\" width=\"600\">";
	$mail_Body .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"600\">";
	$mail_Body .= "<tr><td bgcolor=\"".bgEmailLogo."\"><br />&nbsp;<img src=\"".$sitedir."image/logoemail.png\" /><br /><br /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Bonjour ".osql($row['prenom']) . " " .osql($row['nom']).",</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Voici votre code de v&eacute;rification : ".$code."</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Si vous rencontrez un probl&egrave;me pour acc&eacute;der &agrave; votre compte, rendez vous sur <a style=\"color:".textEmail."\" href=\"mailto:".siteEmail."\">".siteEmail."</a> ou contacter le service client au ".phoneEmail.".</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Cordialement</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">L'&eacute;quipe ".siteName." </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td><img src=\"".$sitedir."image/logoemail.png\" /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Suivez notre actualit&eacute; sur </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Facebook : <a style=\"color:".textEmail."\" href=\"https://www.facebook.com/pages/MaBento/254840191217166?ref=hl\">".siteName."</a></td></tr>";
	// $mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Twitter : <a style=\"color:".textEmail."\" href=\"\">'.siteName.'</a></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "</table>";
	$mail_Body .= "</td>";
	$mail_Body .= "</tr>";
	$mail_Body .= "</table>";
	
	$mail_From = siteEmail; 
	$mail_To = osql($row['email']);
	if(sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat)){
		return true;
	}
	return false;
}

?>