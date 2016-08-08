<?php
/* Script de Instant Payment Notification (IPN) ou Notification Instantanee de Paiement par Paypal */
/* Envoie un e-mail au vendeur quand Paypal a recu un paiement. Si la transaction est OK, Paypal se connecte a ce script et envoie des donnees, puis le script envoie un e-mail recapitulatif au vendeur.*/
/* Ajoutez l'URL de ce script lors de la creation d'un bouton Paypal ou dans les preferences de son compte Paypal a: Préférences de Notification instantanée de paiement. */

include("includes/top_includes.php");

// Modification du statut en base
$query = "UPDATE commandes SET id_statut=2, id_moyen_paiement=".(int)$_SESSION['moyen_paiement']." WHERE id=".(int)$_SESSION['id_commande'];
$conn = new DbConnector();
$conn->query($query);
email_resto($_SESSION['id_commande'], $_SESSION['adresse_livraison']);
empty_cart();
		
?> 