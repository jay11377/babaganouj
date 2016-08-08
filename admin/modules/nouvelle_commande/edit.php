<?php
// Display a message
if(isset($_SESSION['pagemsg']))
{
	echo '<div class="msgbox">';
	echo $_SESSION['pagemsg'];
	echo '</div>';
	unset($_SESSION['pagemsg']);
}
?>
<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_ORDER') ?></h3>
    </div>
    <div class="container"><?php
		$id_commande=$_GET['id'];
		$connector = new DbConnector();
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			header( 'Location: moduleinterface.php?module=commander&action=default' ) ;
		}
		
		// If Cancel has been clicked
		else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
			header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
		}
		
		// Infos commande
		$query = "SELECT id, id_client, adresse_livraison, adresse_facturation, id_moyen_paiement, couverts, message, date_livraison, creneau_livraison, heure_livraison
					FROM commandes 
				   WHERE id=".(int)$id_commande;
		
		$result = $connector->query($query);
		$commande=$connector->fetchArray($result);
		
		// Produits commande
		$query = "SELECT id_plat, quantite, options
					FROM ligne_commande 
				   WHERE id_commande=".(int)$id_commande."
				ORDER BY id";
		$produits = $connector->query($query);
		
		// Display the form
        require_once("templates/form.php"); ?>
    </div>
</div>
<script type="text/javascript">
$(function() {
	// Afficher le bloc de modification de l'adresse
	$('#modifier_adresse, #ajouter_adresse').show();
	
	// Id client
	id_client = "<?php echo $commande['id_client'] ?>";
	$('#id_client').val(id_client);
	
	// Prévoir les autres adresses du client
	$.post(
		"ajax_request.php", 
		{id_client : id_client, request : 'adressesList'}, 
		function(data){
			$('#adresses_block').html(data.msg);
		},
		"json"
 	);
	
	// Adresse de livraison
	id_adresse_livraison = "<?php echo $commande['adresse_livraison'] ?>";
	$.post(
		"ajax_request.php", 
		{id_adresse : id_adresse_livraison, request : 'adresseDetails'}, 
		function(data){
			$('#adresse_livraison').html(data.msg);
			$('#id_adresse_livraison').val(id_adresse_livraison);
		},
		"json"
	);
	
	// Adresse de facturation
	id_adresse_facturation = "<?php echo $commande['adresse_facturation'] ?>";
	$.post(
		"ajax_request.php", 
		{id_adresse : id_adresse_facturation, request : 'adresseDetails'}, 
		function(data){
			$('#adresse_facturation').html(data.msg);
			$('#id_adresse_facturation').val(id_adresse_facturation);
		},
		"json"
	);
	
	<?php
	while($produit = $connector->fetchArray($produits)){ 
		?>
		// Construction du panier
		var nb_items = cart.length;
		var produit = new Object();
		produit.quantite = <?php echo $produit['quantite'] ?>;
		produit.id_produit = <?php echo $produit['id_plat'] ?>;
		<?php
		if(isset($produit['options']) && $produit['options']!='') // Si c'est un menu
		{  
			?>
			produit.menuOptions = Array(); 
			i=0; 
			<?php
			$menuOptions = explode("<br />",$produit['options']);
			foreach($menuOptions as $menuOption)
			{ 
				if($menuOption!="")
				{ ?>
					produit.menuOptions[i] = '<?php echo html_entity_decode($menuOption) ?>';
					i=i+1;
				<?php
				}
			}
		} ?>
		cart[nb_items] = produit;
		
		$.post(
			"ajax_request.php", 
			{cart : cart, request : 'updateCart'},
			function(data){
				$('#panier').html(data.msg);
			},
			"json"
		);
	
	<?php
	} ?>
	
})
</script>
