<?php
/* Script de Instant Payment Notification (IPN) ou Notification Instantanee de Paiement par Paypal */
/* Envoie un e-mail au vendeur quand Paypal a recu un paiement. Si la transaction est OK, Paypal se connecte a ce script et envoie des donnees, puis le script envoie un e-mail recapitulatif au vendeur.*/
/* Ajoutez l'URL de ce script lors de la creation d'un bouton Paypal ou dans les preferences de son compte Paypal a: Préférences de Notification instantanée de paiement. */

include("includes/top_includes.php");

/* ADRESSE E-MAIL DU VENDEUR */
$emailto = getEmail();
/* ADRESSE E-MAIL DE L'EMETTEUR DE CE MAIL (le FROM), DOIT ETRE UN VRAI COMPTE MAIL. */
$emailfrom = siteEmail;
/* PREFIX AU SUJET DU MAIL POUR FILTRE ANTI-SPAM */
$sujetprefix = "[PAYPAL]";

/* NE RIEN MODIFIER CI-DESSOUS */
// lecture du post de PayPal et ajout de 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = trim(urlencode(stripslashes($value)));
	$req .= "&$key=$value";
}

// reponse a PayPal pour validation
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Host: www.paypal.com:80\r\n";
//$header .= "Host: sandbox.paypal.com:80\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
//$fp = fsockopen ('sandbox.paypal.com', 80, $errno, $errstr, 30);

// variables
$item_name = $_POST['item_name'];
$business = $_POST['business'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$mc_gross = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$receiver_id = $_POST['receiver_id'];
$quantity = $_POST['quantity'];
$num_cart_items = $_POST['num_cart_items'];
$payment_date = $_POST['payment_date'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$payment_type = $_POST['payment_type'];
$payment_status = $_POST['payment_status'];
$payment_gross = $_POST['payment_gross'];
$payment_fee = $_POST['payment_fee'];
$settle_amount = $_POST['settle_amount'];
$memo = $_POST['memo'];
$payer_email = $_POST['payer_email'];
$txn_type = $_POST['txn_type'];
$payer_status = $_POST['payer_status'];
$address_name = $_POST['address_name'];
$address_street = $_POST['address_street'];
$address_city = $_POST['address_city'];
$address_state = $_POST['address_state'];
$address_zip = $_POST['address_zip'];
$address_country = $_POST['address_country'];
$address_status = $_POST['address_status'];
$item_number = $_POST['item_number'];
$tax = $_POST['tax'];
$option_name1 = $_POST['option_name1'];
$option_selection1 = $_POST['option_selection1'];
$option_name2 = $_POST['option_name2'];
$option_selection2 = $_POST['option_selection2'];
$for_auction = $_POST['for_auction'];
$invoice = $_POST['invoice'];
$custom = $_POST['custom'];
$notify_version = $_POST['notify_version'];
$verify_sign = $_POST['verify_sign'];
$payer_business_name = $_POST['payer_business_name'];
$payer_id =$_POST['payer_id'];
$mc_currency = $_POST['mc_currency'];
$mc_fee = $_POST['mc_fee'];
$exchange_rate = $_POST['exchange_rate'];
$settle_currency  = $_POST['settle_currency'];
$parent_txn_id  = $_POST['parent_txn_id'];


if (!$fp) {
// HTTP ERROR
} 
else {
	
	$mail_From = $emailfrom; 
	$mail_To = $emailto;
	
	
	fputs ($fp, $header . $req);
	while (!feof($fp)) {	
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			
			// Modification du statut en base
			$query = "UPDATE commandes SET id_statut=2, id_moyen_paiement=1 WHERE id=".$custom;
			$connector->query($query);
			
			$query = "SELECT LC.prix_ht, LC.prix_ttc, LC.quantite, LC.total_ht, LC.total_ttc, LC.options, P.name, P.thumbnail1
						FROM ligne_commande LC
				   LEFT JOIN plats P on (LC.id_plat=P.id)
				   WHERE LC.id_commande=".$custom." "."
				     AND LC.remise=0";
			$result = $connector->query($query);
			$items = "<table border=\"0\" cellpadding=\"10\">";
			$items .= "<tr>";
			$items .= "<td><strong>Photo</strong></td>";
			$items .= "<td><strong>Plat</strong></td>";
			$items .= "<td align=\"right\"><strong>Prix unitaire</strong></td>";
			$items .= "<td><strong>Quantit&eacute;</strong></td>";
			$items .= "<td align=\"right\"><strong>Total</strong></td>";
			$items .= "</tr>";
			// Produits
			while($row = $connector->fetchArray($result)){
				$items .= "<tr>";
				$items .= "<td><img src=\"" . $sitedir .$row['thumbnail1']. "\"></td>";
				$items .= "<td>" . osql($row['name']); 
				if(!is_null($row['options'])): 
					$items .= "<br /><i style=\"color:#555\">".$row['options']."</i>";
				endif;
				$items .= "</td>";
				$items .= "<td align=\"right\">" . $row['prix_ttc']." &euro;</td>";
				$items .= "<td>" . $row['quantite'] . "</td>";
				$items .= "<td align=\"right\">" . $row['total_ttc']." &euro;</td>";
				$items .= "</tr>";
			}
			// Remises
			$query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$custom." AND remise=1";
			$result_vouchers = $conn->query($query_vouchers);
			while($row_voucher = $conn->fetchArray($result_vouchers))
			{
				$items .= "<tr>";
				$items .= "<td style=\"font: 13px Arial, sans-serif;color: #ffffff;line-height:16px;\">" . osql($row_voucher['description_remise']) . "</td>";
				$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: #ffffff;line-height:16px;\">" . $row_voucher['prix_ttc']." &euro;</td>";
				$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: #ffffff;line-height:16px;\">" . $row_voucher['quantite'] . "</td>";
				$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: #ffffff;line-height:16px;\">" . $row_voucher['total_ttc']." &euro;</td>";
		$items .= "</tr>";
			} 
			$query = "SELECT total_ht, total_ttc, date_livraison, creneau_livraison, message, telephone, adresse_livraison 
						FROM commandes C
				   LEFT JOIN adresses A ON (C.adresse_livraison=A.id)  
				   WHERE C.id=".$custom;
			$result = $connector->query($query);
			$row = $connector->fetchArray($result);
			$items .= "<tr>";
			$items .= "<td colspan=\"4\" align=\"right\"><b>Total HT</b></td>";
			$items .= "<td><b>" . showPrice($row['total_ht'])." &euro;</b></td>";
			$items .= "</tr>";
			$items .= "<tr>";
			$items .= "<td colspan=\"4\" align=\"right\"><b>Total TTC</b></td>";
			$items .= "<td><b>" . $row['total_ttc']." &euro;</b></td>";
			$items .= "</tr>";
			$items .= "</table>";
			
			
			$result_address = $connector->query("SELECT * FROM adresses WHERE id=".$row['adresse_livraison']);
			$row_address = $connector->fetchArray($result_address);	
			// Envoi du mail
			$mail_Subject = $sujetprefix . " Nouvelle commande Grillcroute";
			$mail_Body = "Paypal vient de valider et recevoir un paiement par carte bancaire. <br />Connectez-vous a votre compte Paypal pour connaitre les details de cette transaction . <br />https://www.paypal.com/fr/";
			$mail_Body .= "<br />";
			$mail_Body .= "<br />====================================================";
			$mail_Body .= "<br />" . "<strong>Informations venant de Paypal:</strong>";
			$mail_Body .= "<br />" . "Transaction ID: " .  $txn_id ;
			$mail_Body .= "<br />" . "Date de paiement: " . $payment_date;
			$mail_Body .= "<br />" . "Etat du paiement: " . $payment_status;
			$mail_Body .= "<br />" . "Montant: " . $mc_gross . " " .$mc_currency;
			$mail_Body .= "<br />" . "Frais Paypal: " . $mc_fee . " " .$mc_currency;
			//$mail_Body .= "<br />" . "Montant sur le compte: " . ($mc_gross - $mc_fee) . " " .$mc_currency;
			//$mail_Body .= "\n" . "Nombre d'objets dans le panier: " . $num_cart_items;
			$mail_Body .= "<br />====================================================";
			//$mail_Body .= "\n" . "Facture numero: " . $invoice;
			$mail_Body .= "<br />" . "<strong>Informations venat du site Grillcroute:</strong>";
			$mail_Body .= "<br />" . "Numero de commande: " . $custom;
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
			/*
			$mail_Body .= "<br />" . $payer_business_name;
			$mail_Body .= "<br />" . $first_name . " " .$last_name;
			$mail_Body .= "<br />" . $address_street;
			$mail_Body .= "<br />" . $address_zip . " " .$address_city;
			*/
			//$mail_Body .= "\n" . "Etat et Pays: " . $address_state . " " .$address_country . " " .$address_country_code;
			$mail_Body .= "<br />";
			//$mail_Body .= "<br />" . "Message du client: " . $memo;
			if($row['message']!='')
				$mail_Body .= "<br />" . "Message du client: " . osql($row['message']);
			$mail_Body .= "<br />" . "Date de livraison: " . dateENtoFR($row['date_livraison']);
			$mail_Body .= "<br />" . "Cr&eacute;nau horaire de livraison: Entre" . $row['creneau_livraison'];
			$mail_Body .= "<br />" . "Telephone: " . osql($row['telephone']);
			$mail_Body .= "<br />" . "Adresse e-mail: " . $payer_email;
			//$mail_Body .= "\n" . "Statut Paypal du client: " . $payer_status;
			$mail_Body .= "<br />====================================================";
			
			$mail_Body .= "<br />";
			$mail_Body .= "<br />" . $items;
			$mail_Body .= "<br />";
			/*
			foreach ($_POST as $key => $value){
				$emailtext .= $key . " = " .$value ."\n";
			}
			mail($mail_To, $mail_Subject, $mail_Body . "\n\nVoici les donnees brutes recues par Paypal: \n\n" . $emailtext, $mail_From);
			*/
			//mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
			sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat);
		}
		else if (strcmp ($res, "INVALID") == 0) {
			// Envoi d'un mail si invalide
			$mail_From = $emailfrom;
			$mail_To = $emailto;
			$mail_Subject = $sujetprefix . " Paiement PAYPAL NON VALIDE";
			$mail_Body = "Un client a voulu payer par Paypal mais la transaction n'est pas valide. La commande est annulee. <br />Ce message est envoye pour information, il n'y a rien a faire. \nhttps://www.paypal.com/fr/ <br />Ci-dessous, les donnees brutes envoyees par Paypal.";
			$mail_Body .= "<br />";
			$mail_Body .= "<br />====================================================";
			foreach ($_POST as $key => $value){
				$emailtext .= $key . " = " .$value ."\n";
			}
			//mail($mail_To, $mail_Subject, $mail_Body . "\n\nVoici les donnees brutes recues par Paypal: \n\n" . $emailtext, $mail_From);
			//mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
			sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat);
		}
	}
	fclose ($fp);
}
?> 