<?php
require_once('includes/fr.php');
require_once('includes/config.php');
require_once('includes/Sentry.php');
require_once('includes/functions.php');
require_once('includes/DbConnector.php');
require_once('includes/Validator.php');

$request = $_POST['request'];
$connector = new DbConnector();

switch($request){
	// Total des retard sur la période
	case 'total_retard' :
		$date_debut = $_POST['date_debut'];
		$date_fin = $_POST['date_fin'];
		
		$query = "SELECT C.creneau_livraison, C.heure_livraison
					FROM commandes C
				   WHERE C.id_statut=4
					 AND C.creneau_livraison LIKE '%h%' 
					 AND C.par_telephone=0
					 AND C.date_livraison>='".$date_debut."' AND C.date_livraison<='".$date_fin."'
		   			 AND date_livraison>='".$date_debut."' AND date_livraison<='".$date_fin."' 
			    ORDER BY C.id DESC";
				
		$result = $connector->query($query);
		$total_retard = 0;
		while($row = $connector->fetchArray($result))
		{
			// Calcul du retard
			$creneau_livraison = str_replace("h",":",$row['creneau_livraison']).":00";
			$heure_livraison = str_replace("h",":",$row['heure_livraison']).":00";
			$h1=strtotime($heure_livraison);
			$h2=strtotime($creneau_livraison);
			$retard = date('i',$h1-$h2);
			$total_retard += $retard;
		}
		$heure = (int)($total_retard/60);
		$mn = $total_retard%60;
		if($heure>0)
			$reponse['total_retard'] = $heure."h".$mn;
		else
			$reponse['total_retard'] = $total_retard. "mn";
		break;
	// Ville à partir du code postal
	case 'getCity' :
		$cp = $_POST['cp'];
		$result_city = getCityFromCp($cp);
		if($connector->getNumRows($result_city)==0)
			$reponse['statut'] = 0;
		else{
			$reponse['statut'] = 1;
			$row_city = $connector->fetchArray($result_city);
			$reponse['msg'] = getCorrectCityName($row_city['name']);	
		}
		break;
	
	// Tableau des clients dont le nom ou le prénom commane par
	case 'clientsTable' :
		$client = $_POST['clientStartsWith'];
		$query = 'SELECT a.id, a.nom, a.prenom, a.email, a.newsletter, COUNT(c.id) AS nb_commandes 
				FROM clients a 
		   LEFT JOIN commandes c ON a.id=c.id_client 
		       WHERE c.id_statut=4 
			     AND (
				         prenom LIKE "%' . isql($client) . '%"
						 OR  nom  LIKE "%' . isql($client) . '%"
						 OR  CONCAT(prenom, " ", nom) LIKE "%' . isql($client) . '%"
					 )  
		    GROUP BY a.id 
		    ORDER BY nom';
		$result = $connector->query($query);
		$reponse['msg'] = '<table cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td>'.getLang('NAME').'</td>
								<td>'.getLang('EMAIL_ADDRESS').'</td>
								<td>'.getLang('TOTAL_ORDERS').'</td>
								<td>'.getLang('NEWSLETTER_SUBSCRIBTION').'</td>
								<td>'.getLang('OPTIONS').'</td>
							</tr>
						</thead>
						<tbody>';
		$i=1;
		while ($row_client = $connector->fetchArray($result)){
			$class=($i%2==0)?'even':'odd';
			if($i==1)
				$class.=" first";
			$img = ($row_client['newsletter']==1)? "images/chomp/led-ico/accept.png" : "images/chomp/led-ico/cross_octagon.png";
			$reponse['msg'] .= '
			<tr class="'.$class.'">
				<td valign="top">
					<a class="clientHistory" rel="'.$row_client['id'].'" href="moduleinterface.php?module=clients&action=edit&id='.$row_client['id'].'" title="'.getLang('EDIT').'">'.strtoupper(osql($row_client['nom']))." ".ucfirst(strtolower(osql($row_client['prenom']))).'</a>
				</td>
				<td>'.osql($row_client['email']).'</td>
				<td>'.$row_client['nb_commandes'].'</td>
				<td><img src="'.$img.'" /></td>
				<td>
					<a class="image" href="moduleinterface.php?module=clients&action=edit&id='.$row_client['id'].'" title="'.getLang('EDIT').'"><img src="images/chomp/led-ico/pencil.png" alt="'.getLang('EDIT').'" /></a>
				</td>
			</tr>';
			$i++;
		 }
         $reponse['msg'] .= '       
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="5">&nbsp;</td>
                </tr>
            </tfoot>
        </table>';
		break;
	
	// Liste des clients dont le nom ou le prénom commane par
	case 'customerList' :
		$client = $_POST['clientStartsWith'];
		$query = 'SELECT c.id, c.nom, c.prenom, c.email, a.id as id_adresse, a.societe, a.nom AS nom_adresse, a.prenom AS prenom_adresse, a.adresse1, a.cp, a.ville, a.telephone
				    FROM clients c
			   LEFT JOIN adresses a ON (a.id_client = c.id)
				   WHERE CONCAT(c.prenom, " ", c.nom, " ", a.societe, " ",a.prenom, " ", a.nom, " ", a.telephone) LIKE "%' . isql($client) . '%"
				   ';
	
		$result = $connector->query($query);
		$reponse=array();
		while ($row_client = $connector->fetchArray($result)){ 
			$row_array=array();
			$row_array['info'] = $row_client['telephone']." ".((isset($row_client['societe']) && $row_client['societe']!="") ? $row_client['societe']." " : "").$row_client['prenom']." ".$row_client['nom']." (".$row_client['id']." - ".(($row_client['email']!='') ? $row_client['email'] : "").") ".$row_client['adresse1']." ".$row_client['cp']." ".$row_client['ville']; 
            $row_array['id_client'] = $row_client['id'];
			$row_array['id_adresse'] = $row_client['id_adresse'];
			array_push($reponse,$row_array);
		}
		break;
	
	// Liste des commandes d'un client
	case 'ordersList' :
		$query = "SELECT * FROM commandes WHERE id_client=".(int)$_POST['id_client']." AND id_statut>1 ORDER BY id DESC";
		if(isset($_POST['limit']))
			$query.=" LIMIT ".(int)$_POST['limit'];
		$result = $connector->query($query);    
		$query_client = "SELECT prenom, nom FROM clients WHERE id=".(int)$_POST['id_client'];
		$result_client = $connector->query($query_client);
		$row_client = $connector->fetchArray($result_client);
		if($connector->getNumRows($result)==0):
			$reponse['msg'] = '
			 <div class="ordersWrapper">
				<div class="ordersWrapper2">
					<div class="closeOrders"><a href="#"><img src="images/fermer.png" alt="'.getLang('CLOSE').'"></a></div>
					<div class="ordersInner">
					   <fieldset id="orders_fs">
						  <legend>'.strtoupper(osql($row_client['nom']))." ".ucfirst(strtolower(osql($row_client['prenom']))).'</legend>
					   </fieldset>
					   <div class="no_orders">'.getLang('NO_ORDERS').'</div>
					</div>
				</div>
			</div>';
		else :
			$query_stats = "SELECT SUM(total_ttc) AS total_delivered_orders, COUNT(*) AS number_of_delivered_orders    
							  FROM commandes
		   		             WHERE id_statut=4
							   AND id_client=".(int)$_POST['id_client'];
			$result_stats = $connector->query($query_stats);
			$row_stats = $connector->fetchArray($result_stats);
			$average_cart = $row_stats['total_delivered_orders']/$row_stats['number_of_delivered_orders'];
			$reponse['msg'] = '
			 <div class="ordersWrapper">
				<div class="ordersWrapper2">
					<div class="closeOrders"><a href="#"><img src="images/fermer.png" alt="'.getLang('CLOSE').'"></a></div>
					<div class="ordersInner">
					   <fieldset id="orders_fs">
						  <legend>'.strtoupper(osql($row_client['nom']))." ".ucfirst(strtolower(osql($row_client['prenom']))).'</legend>
					   </fieldset>
					   <table cellpadding="0" cellspacing="2" border="0" id="table_stats">
								<tr>
									<td>'.getLang('TOTAL_DELIVERED_ORDERS').'</td>
									<td>'.showPriceCurrency($row_stats['total_delivered_orders']).'</td>
								</tr>
								<tr>
									<td>'.getLang('NUMBER_OF_DELIVERED_ORDERS').'</td>
									<td>'.$row_stats['number_of_delivered_orders'].'</td>
								</tr>
								<tr>
									<td>'.getLang('AVERAGE_CART').'</td>
									<td>'.showPriceCurrency($average_cart).'</td>
								</tr>
					   </table>
					   <table>
							<thead>
								<tr>
									<th>'.getLang('ORDER_ID').'</th>
									<th>'.getLang('DATE').'</th>
									<th>'.getLang('TOTAL').'</th>
									<th>'.getLang('PAYMENT').'</th>
									<th>'.getLang('STATUS').'</th>
									<th>'.getLang('DETAILS').'</th>
									<th>'.getLang('REORDER').'</th>
								</tr>
							</thead>
							<tbody>';
			$i=1;
			while($row = $connector->fetchArray($result))
				{
					$class=($i%2==0)?'td_even':'';
					$reponse['msg'] .= '
					<tr class="'.$class.'">
						<td>'.$row['id'].'</td>
						<td>'.displayDate($row['date']).'</td>
						<td>'.showPriceCurrency($row['total_ttc']).'</td>
						<td>'.getPaymentMethodName($row['id_moyen_paiement']).'</td>
						<td>'.getStatus($row['id_statut']).'</td>
						<td><a href="" class="details_commande">'.getLang('DETAILS').'</a></td>
						<td class="reorder"><a href="" class="meme_commande" name="'.$row['id'].'"><img src="images/recommander.png" /></a></td>
					</tr>
					<tr class="commande_details_tr">
						<td colspan="7">
							<div>
								<table class="commande_details">
									<thead>
										<th>'.getLang('PHOTO').'</th>
										<th>'.getLang('DISH').'</th>
										<th>'.getLang('QUANTITY').'</th>
										<th>'.getLang('UNIT_PRICE').'</th>
										<th>'.getLang('TOTAL_PRICE').'</th>
									</thead>
									<tbody>';
									
										// Produits 
										$query_details = "SELECT LC.*, P.name, P.thumbnail1 
													FROM ligne_commande LC
											   LEFT JOIN plats P ON (LC.id_plat=P.id)
												   WHERE id_commande=".$row['id']."
												     AND remise = 0
												ORDER BY id";
										$result_details = $connector->query($query_details);
										while($row_details = $connector->fetchArray($result_details))
										{
											$reponse['msg'] .= '
											<tr>
												<td><img src="../'.$row_details['thumbnail1'].'" /></td>
												<td>'.
													$row_details['name'];
													if(!is_null($row_details['options'])):
														$reponse['msg'] .= '<div class="mezze_options_history">'.$row_details['options'].'</div>';
													endif;
												$reponse['msg'] .= '
												</td>
												<td>'.$row_details['quantite'].'</td>
												<td>'.showPriceCurrency($row_details['prix_ttc']).'</td>
												<td>'.showPriceCurrency($row_details['total_ttc']).'</td>
											</tr>';
										}
										// Remises
										$query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$row['id']." AND remise=1";
										$result_vouchers = $connector->query($query_vouchers);
										while($row_voucher = $connector->fetchArray($result_vouchers))
										{
											$reponse['msg'] .= '
                                            <tr>
												<td></td>
												<td>'.osql($row_voucher['description_remise']).'</td>
												<td></td>
												<td></td>
												<td>'.showPriceCurrency($row_voucher['total_ttc']).'</td>
											</tr>';
										} 
										
									$reponse['msg'] .= '
									</tbody>
								</table>
							 </div>
						</td>
					</tr>';
					$i++;
				 }
			$reponse['msg'] .= '
							</tbody>
			  			</table>
			  </div></div></div>';
		endif;
		break;
	// Liste des produits dont le nom commence par
	case 'dishList' :
		$dish = $_POST['dishStartsWith'];
		$query = 'SELECT id, name, prix_ttc
				    FROM plats
				   WHERE name LIKE "%' . isql($dish) . '%"';
		$result = $connector->query($query);
		$reponse=array();
		while ($row_plat = $connector->fetchArray($result)){ 
			$row_array=array();
			$row_array['plat_prix_id'] = $row_plat['name']." - ".showPriceCurrencyAutocomplete($row_plat['prix_ttc'])." (".$row_plat['id'].")"; 
            $row_array['id_plat'] = $row_plat['id'];
			array_push($reponse,$row_array);
		}
		break;
	
	// Liste des adresses d'un client
	case 'adressesList' :
		$id_client = $_POST['id_client'];
		$query = 'SELECT id,defaut,titre_adresse
				    FROM adresses
				   WHERE active=1
				   	 AND id_client='.(int)$id_client; 
		$result = $connector->query($query);
		$reponse['msg']='';
		$i=1;
		while ($row_adresse = $connector->fetchArray($result)){ 
			$class="address_info";
			if($i%3==1) $class.=" first";
			$reponse['msg'].='<div class="'.$class.'">';
			$reponse['msg'].='<h3>';
			if($row_adresse['defaut']==1)
				$reponse['msg'].='<img src="images/defaut.png" align="baseline" />&nbsp;&nbsp';
			$reponse['msg'].=osql($row_adresse['titre_adresse']);
			$reponse['msg'].='</h3>';
			$reponse['msg'].='<p>';
			$reponse['msg'].=displayAddress($row_adresse['id']);
			$reponse['msg'].='</p>';
			$reponse['msg'].='<p class="update_adress_block">';
			$reponse['msg'].='<a href="" rel="'.$row_adresse['id'].'" class="set_shipping_address">'.getLang('SET_AS_SHIPPING_ADDRESS').'</a><br />';
			$reponse['msg'].='<a href="" rel="'.$row_adresse['id'].'" class="set_billing_address">'.getLang('SET_AS_BILLING_ADDRESS').'</a>';
			$reponse['msg'].='</p>';
			$reponse['msg'].='</div>';
			if($i%3==0) $reponse['msg'].='<br class="clear">';
			$i++;
		}
		break;
		
	// Préremplir le nom et le prénom de la nouvelle adresse
	case 'remplirNomPrenom' :
		$id_client = $_POST['id_client'];
		$query = 'SELECT prenom, nom FROM clients WHERE id='.(int)$id_client; 
		$result = $connector->query($query);
		$row_client = $connector->fetchArray($result);
		$reponse['nom']=$row_client['nom'];
		$reponse['prenom']=$row_client['prenom'];
		
		// Détection du téléphone portable 
		$reponse['telephone']='';
		$query = 'SELECT telephone FROM adresses WHERE id = (SELECT MAX(id) FROM adresses WHERE telephone LIKE "06%" AND id_client='.(int)$id_client.')'; 
		$result = $connector->query($query);
		if($connector->getNumRows($result)>0)
		{
			$row = $connector->fetchArray($result);
			$reponse['telephone'] = $row['telephone'];
		}
		break;
	
	// Ajouter adresse
	case 'ajouterAdresse' :
		$id_client = $_POST['id_client'];
		
		$postValues = array();
		foreach ( $_POST as $name => $value ) {
			$postValues[$name] = trim( $value );
		}
		extract( $postValues );
		
		$reponse['errors'] = '';
		$reponse['insert'] = '';
		
		// Valider l'adresse
		$validator = new Validator();
		
		
		$validator->validateGeneral($prenom_adresse,getLang('NO_FIRST_NAME_ADDRESS_GIVEN'));
		$validator->validateGeneral($nom_adresse,getLang('NO_LAST_NAME_ADDRESS_GIVEN'));
		$validator->validateGeneral($adresse1,getLang('NO_ADDRESS_GIVEN'));
		$validator->validateMultiplePostCode($cp,getLang('POSTCODE_LENGTH'));
		$validator->validateGeneral($ville,getLang('NO_CITY_GIVEN'));
		$validator->validateGeneral($telephone,getLang('NO_TELEPHONE_GIVEN'));
		$validator->validateGeneral($titre_adresse,getLang('NO_ADDRESS_TITLE_GIVEN'));
		
		if ( $validator->foundErrors() )
		{
			$reponse['errors'] = '<div class="msg msg-error"><p>'.$validator->listErrors('<br>').'</p></div>'; 
		}
		else
		{
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
			"1".
			")";
			
			if ($connector->query($query))
			{
				$reponse['id_adresse'] = mysql_insert_id();
				$reponse['insert'] = 1;	
			}
			else
				$reponse['errors'] = '<div class="msg msg-error"><p>'.getLang('DB_ERROR').'</p></div>';
		}
		
		
		break;
	
	// Adresse complète
	case 'adresseDetails' :
		$id_adresse = $_POST['id_adresse'];
		$reponse['msg']=displayAddress($id_adresse);
		break;
	
	// Plats d'une catégorie donnée
	case 'dishesList' :
		$id_categorie = $_POST['id_categorie'];
		$reponse['msg']=getDishes($id_categorie);
		break;
		
	// Mise à jour du panier
	case 'updateCart' :
		$cart = isset($_POST['cart']) ? $_POST['cart'] : NULL;
		$reponse['msg'] ='
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th>'.getLang('PHOTO').'</th>
				<th>'.getLang('UNIT_PRICE').'</th>
				<th>'.getLang('QUANTITY').'</th>
				<th>'.getLang('DESCRIPTION').'</th>
				<th class="right_txt">'.getLang('TOTAL').'</th>
				<th class="right_txt">'.getLang('DELETE').'</th>
			</tr>';
		  $i=1;
		  $total_ht = 0;
		  $total_ttc = 0;
		  $conn = new DbConnector(); 
		  if(sizeof($cart)>0)
		  {
			  foreach($cart as $product)
			  {
					$id_produit = $product['id_produit'];
				  	$quantite = $product['quantite'];
					if(isset($quantite) && $quantite!='undefined')
					{
							$result = $conn->query("SELECT * FROM plats WHERE id=".(int)$id_produit);
							$row_produit = $conn->fetchArray($result);
							if($row_produit)
							{
								  $prix_ligne_ht = $row_produit['prix_ht'] * $quantite;
								  $prix_ligne_ttc = $row_produit['prix_ttc'] * $quantite;
								  $quantite_string = ($quantite<10)? "0".$quantite : $quantite;
								  $total_ht += $prix_ligne_ht; // cout total ht
								  $total_ttc += $prix_ligne_ttc; // cout total ttc
								  $reponse['msg'] .=' 
								  <tr '.(($i%2)?'class="alternate_row"' : '').'>
									<td class="panier_photo"><img src="../'.osql($row_produit['thumbnail1']).'" /></td>
									<td class="panier_produit">'.showPriceCurrency($row_produit['prix_ttc']).'</td>
									<td class="panier_quantite_bouton">
										<table>
											<tr>
												<td class="panier_bouton"><img src="../images/moins_noir.gif" alt="moins" class="panier_moins" rel="'.($i-1).'" /></td>
												<td class="panier_quantite">'.$quantite_string.'</td>
												<td class="panier_bouton"><img src="../images/plus_noir.gif" alt="plus" class="panier_plus" rel="'.($i-1).'" /></td>
											</tr>
										</table>
									</td>
									<td class="panier_produit">'. 
										osql($row_produit['name']);
										if(isset($product['menuOptions'])) // SI c'est un menu
										{
											$reponse['msg'] .= '
											<div class="mezze_options_cart">';
											foreach($product['menuOptions'] as $option)
													$reponse['msg'] .='<span class="mezze_option_cart">'.osql($option).'</span><br />';
											$reponse['msg'] .= '</div>';
										}
									$reponse['msg'] .= '
									</td>
									<td class="panier_prix">'.showPriceCurrency($prix_ligne_ttc).'</td>
									<td class="panier_retirer"><img src="../images/retirer.gif" alt="retirer" class="bouton_retirer" rel="'.($i-1).'" /></td>
								  </tr>';
								  $i++;
							}						
					}
					
			  }
		  }
		  $reponse['msg'] .=' 
		  <tr class="panier_bottom">
			<td colspan="6" class="total_separateur"><hr /><br /><hr /></td>
		  </tr>
		  <tr class="panier_bottom">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="panier_total">'.getLang('TOTAL').'</td>
			<td class="panier_prix_total">'.showPriceCurrency($total_ttc).'</td>
			<td>&nbsp;</td>
		  </tr>
		</table>';
		$reponse['msg'] .='
		<input type="hidden" id="total_ht" value="'.$total_ht.'">
		<input type="hidden" id="total_ttc" value="'.$total_ttc.'">';
		break;
	
	// Re-commander
	case 'recommander' :
		$id_commande = $_POST['id_commande'];
		$query = "SELECT * FROM ligne_commande WHERE id_commande=".(int)$id_commande." AND remise=0 ORDER BY id";
		$result = $connector->query($query);
		
		$reponse['msg'] ='
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th>'.getLang('PHOTO').'</th>
				<th>'.getLang('UNIT_PRICE').'</th>
				<th>'.getLang('QUANTITY').'</th>
				<th>'.getLang('DESCRIPTION').'</th>
				<th class="right_txt">'.getLang('TOTAL').'</th>
				<th class="right_txt">'.getLang('DELETE').'</th>
			</tr>';
		$i=1;
		$total_ht = 0;
		$total_ttc = 0;
		  
		while($row = $connector->fetchArray($result))
		{
			$id_produit = $row['id_plat'];
			$quantite = $row['quantite'];
			
			if(isset($quantite) && $quantite!='undefined')
			{
					$result2 = $connector->query("SELECT * FROM plats WHERE id=".(int)$id_produit);
					$row_produit = $connector->fetchArray($result2);
					if($row_produit)
					{	
						  $prix_ligne_ht = $row_produit['prix_ht'] * $quantite;
						  $prix_ligne_ttc = $row_produit['prix_ttc'] * $quantite;
						  $quantite_string = ($quantite<10)? "0".$quantite : $quantite;
						  $total_ht += $prix_ligne_ht; // cout total ht
						  $total_ttc += $prix_ligne_ttc; // cout total ttc
						  
						  $reponse['msg'] .=' 
						  <tr '.(($i%2)?'class="alternate_row"' : '').'>
							<td class="panier_photo">
								<img src="../'.osql($row_produit['thumbnail1']).'" />
								<input type="hidden" class="id_produit" value="'.$row['id_plat'].'">
								<input type="hidden" class="quantite" value="'.$row['quantite'].'">
								<input type="hidden" class="options" value="'.osql($row['options']).'">
							</td>
							<td class="panier_produit">'.showPriceCurrency($row_produit['prix_ttc']).'</td>
							<td class="panier_quantite_bouton">
								<table>
									<tr>
										<td class="panier_bouton"><img src="../images/moins_noir.gif" alt="moins" class="panier_moins" rel="'.($i-1).'" /></td>
										<td class="panier_quantite">'.$quantite_string.'</td>
										<td class="panier_bouton"><img src="../images/plus_noir.gif" alt="plus" class="panier_plus" rel="'.($i-1).'" /></td>
									</tr>
								</table>
							</td>
							<td class="panier_produit">'. 
								osql($row_produit['name']);
								if(isset($row['options']) && $row['options']!='') // Si c'est un menu
								{
									$menuOptions = explode("<br />",$row['options']);
									$reponse['msg'] .= '
									<div class="mezze_options_cart">';
									foreach($menuOptions as $menuOption)
									{	 
										if($menuOption!="")
											$reponse['msg'] .='<span class="mezze_option_cart">'.html_entity_decode($menuOption).'</span><br />';
									}
									$reponse['msg'] .= '</div>';
								}
							$reponse['msg'] .= '
							</td>
							<td class="panier_prix">'.showPriceCurrency($prix_ligne_ttc).'</td>
							<td class="panier_retirer"><img src="../images/retirer.gif" alt="retirer" class="bouton_retirer" rel="'.($i-1).'" /></td>
						  </tr>';
						  $i++;
					}
			}
			
		}
		
		  $reponse['msg'] .=' 
		  <tr class="panier_bottom">
			<td colspan="6" class="total_separateur"><hr /><br /><hr /></td>
		  </tr>
		  <tr class="panier_bottom">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="panier_total">'.getLang('TOTAL').'</td>
			<td class="panier_prix_total">'.showPriceCurrency($total_ttc).'</td>
			<td>&nbsp;</td>
		  </tr>
		</table>';
		$reponse['msg'] .='
		<input type="hidden" id="total_ht" value="'.$total_ht.'">
		<input type="hidden" id="total_ttc" value="'.$total_ttc.'">';
		break;
	
	// Mise à jour du statut 
	case 'updateStatus' :
	
		$id_statut = $_POST['id_statut'];
		$id_commande = $_POST['id_commande'];
		if($result = $connector->query("UPDATE commandes SET id_statut=".$id_statut." WHERE id =". $id_commande))
			$reponse['msg']='';
			
			/*Modifié par Grégory*/
			
			/*
			$result= $connector->query("SELECT `value` FROM `settings` WHERE id = '5'");
			$row_statut=$connector->fetchArray($result);
			if($row_statut['value']==$id_statut){ //Si la condition d'obtention des points est remplie
					
					//On vérifie d'abord que les points sont en attente de validation
					$query="SELECT `id_fidelite`, `points`, `id_fidelite_state` FROM `fidelite` WHERE `id_commande`=".$id_commande;
					$result=$connector->query($query);
					$row=$connector->fetchArray($result);
					if(intval($row["id_fidelite_state"])==1){
						//Ensuite on peut valider les points si besoin
						$query='UPDATE `fidelite`
						SET `id_fidelite_state`=2,`date_maj`="'.date('Y-m-d G:i:s').'" 
						WHERE `id_commande`='.$id_commande;
						$connector->query($query);
						
						$query='INSERT INTO `fidelite_historique`(`id_fidelite`, `id_fidelite_state`, `points`, `date_ajout`) VALUES (
						'.$row["id_fidelite"].',
						2,
						'.$row["points"].',
						"'.date('Y-m-d G:i:s').'")'; 
						$connector->query($query);
					}	
			}
			*/
		
			/*Fin modification*/
		else
			$reponse['msg']=getRawLang('DB_ERROR_2');
		break;
		
	// Mise à jour de l'heure de livraison 
	case 'updateTime' :
		$heure_livraison = $_POST['heure_livraison'];
		$heure_livraison = str_replace('H', 'h', $heure_livraison);
		$id_commande = $_POST['id_commande'];
		
		$validator = new Validator();
		$validator->validateTime($heure_livraison,getRawLang('NO_DELIVERY_TIME_SELECTED'));
		$reponse['msg'] = '';
		
		if ( $validator->foundErrors() ){
			$reponse['msg'] = $validator->errors;
		}
		else{
			$result_commande = $connector->query("SELECT date_livraison FROM commandes WHERE id=".$id_commande);
			$row_commande = $connector->fetchArray($result_commande);
			email_client($id_commande, $heure_livraison, $row_commande['date_livraison']);
		}
		break;
		
	// Ajout d'une commande
	case 'newOrder' :
		  $id_client = $_POST['id_client'];
		  $total_ht = $_POST['total_ht'];
		  $total_ttc = $_POST['total_ttc'];
		  $id_moyen_paiement = $_POST['id_moyen_paiement'];
		  $adresse_livraison = $_POST['adresse_livraison'];
		  $adresse_facturation = $_POST['adresse_facturation'];
		  $couverts = $_POST['couverts'];
		  $message = $_POST['message'];
		  $date_livraison = dateFRtoEN($_POST['date_livraison']);
		  $heure_livraison = isset($_POST['heure_livraison']) ? $_POST['heure_livraison'] : '';
		  $heure_livraison = str_replace('H', 'h', $heure_livraison);
		  $creneau_livraison = $_POST['creneau_livraison'];
		  $creneau_livraison = ((int)$creneau_livraison==1) ? $heure_livraison : $creneau_livraison;
		  $no_minimum = $_POST['no_minimum'];
		  $edit_commande = $_POST['edit_commande'];
		  $id_commande = $_POST['id_commande'];
		  
		  $cart = $_POST['cart'];
		  
		  $validator = new Validator();
		  $validator->validateNumber($id_client,getRawLang('NO_CLIENT_SELECTED'));
		  $validator->validateNumber($adresse_livraison,getRawLang('NO_DELIVERY_ADDRESS_SELECTED'));
		  $validator->validateNumber($adresse_facturation,getRawLang('NO_INVOICE_ADDRESS_SELECTED'));
		  $validator->validateGeneral($date_livraison,getRawLang('NO_DELIVERY_DATE_SELECTED'));
		  if($heure_livraison!='')
		  	$validator->validateTime($heure_livraison,getRawLang('NO_DELIVERY_TIME_SELECTED'));
		  if($total_ttc==0)
		  	$validator->addError(getRawLang('EMPTY_CART'));
		  
		  $reponse['confirm']='';
		  if ( !($validator->foundErrors()) && ((int)$no_minimum)==0 ){
		  	if($total_ttc<getOrderMin(getCp((int)$adresse_livraison)))
		  		$reponse['confirm'] = getRawLang('MIN_ERROR')."\n".getRawLang('ORDER_ANYWAY');
		  }
		  $reponse['error']='';
		  if ( $validator->foundErrors() ){
			  $reponse['error'] = $validator->errors[0];
		  }
		  else
		  {
				  if($reponse['confirm']=='')
				  {
						  if($edit_commande)
						  {
							  $query = 'UPDATE commandes SET 
							  	id_client = '.(int)$id_client.', 
								date = "'.date('Y-m-d G:i:s').'",  
								total_ht = '.(float)$total_ht.', 
								total_ttc = '.(float)$total_ttc.', 
								id_moyen_paiement = '.(int)$id_moyen_paiement.', 
								adresse_livraison = '.(int)$adresse_livraison.', 
								adresse_facturation = '.(int)$adresse_facturation.', 
								couverts = '.(int)$couverts.', 
								message = "'.isql($message).'", 
								date_livraison = "'.isql($date_livraison).'", 
								creneau_livraison = "'.isql($creneau_livraison).'", 
								heure_livraison = "'.isql($heure_livraison).'"
								WHERE id = '.(int)$id_commande;
								
								if($connector->query($query))
								{
									$query = 'DELETE FROM ligne_commande WHERE id_commande = '.(int)$id_commande;
								}
						  }
						  else
						  {
							  $query = "INSERT INTO commandes (id_client, date, total_ht, total_ttc, id_statut, id_moyen_paiement, adresse_livraison, adresse_facturation, couverts, message, date_livraison, creneau_livraison, heure_livraison, par_telephone) VALUES (".
								"'".(int)$id_client."', ".
								"'".date('Y-m-d G:i:s')."', ".
								"".(float)$total_ht.", ".
								"".(float)$total_ttc.", ".
								"3,".
								"".(int)$id_moyen_paiement.", ".
								"".(int)$adresse_livraison.", ".
								"".(int)$adresse_facturation.", ".
								"".(int)$couverts.", ".
								"'".isql($message)."', ".
								"'".isql($date_livraison)."', ".
								"'".isql($creneau_livraison)."', ".
								"'".isql($heure_livraison)."', ".
								"1)";
						  }
						  
						  if($connector->query($query)){
								  if(!$edit_commande)	
								  	$id_commande = mysql_insert_id();
								  foreach($cart as $product)
								  {
									    $id_produit = $product['id_produit'];
				  						$quantite = $product['quantite'];
										if(isset($quantite) && $quantite!='undefined')
										{
												$result = $connector->query("SELECT * FROM plats WHERE id=".$id_produit);
												$row_produit = $connector->fetchArray($result);
												if($row_produit)
												{
														$prix_ligne_ht = $row_produit['prix_ht'] * $quantite;
														$prix_ligne_ttc = $row_produit['prix_ttc'] * $quantite;
														
														if(isset($product['menuOptions'])) // Si c'est un menu
														{
															  $menu_options = ''; 
															  foreach($product['menuOptions'] as $option)
															  	 $menu_options .= osql($option).'<br />';
															
															  $query = "INSERT INTO ligne_commande (id_commande, id_plat, prix_ht, prix_ttc, quantite, options, total_ht, total_ttc, remise, id_remise, code_remise, valeur_remise, description_remise) VALUES (".
																"".(int)$id_commande.", ".
																"".(int)$id_produit.", ".
																"".(float)$row_produit['prix_ht'].", ".
																"".(float)$row_produit['prix_ttc'].", ".
																"".(int)$quantite.", ".
																"'".isql($menu_options)."', ".
																"".(float)$prix_ligne_ht.", ".
																"".(float)$prix_ligne_ttc.", ".
																"0,
																NULL,
																NULL,
																NULL,
																NULL".
																")";
															  $connector->query($query);	
														}
														else // Plat
														{
																  $query = "INSERT INTO ligne_commande (id_commande, id_plat, prix_ht, prix_ttc, quantite, total_ht, total_ttc, remise, id_remise, code_remise, valeur_remise, description_remise) VALUES (".
																				"".(int)$id_commande.", ".
																				"".(int)$id_produit.", ".
																				"".(float)$row_produit['prix_ht'].", ".
																				"".(float)$row_produit['prix_ttc'].", ".
																				"".(int)$quantite.", ".
																				"".(float)$prix_ligne_ht.", ".
																				"".(float)$prix_ligne_ttc.", ".
																				"0,
																				NULL,
																				NULL,
																				NULL,
																				NULL".
																				")";
																 $connector->query($query);
														}
												} // End row_produit
										} // End if(quantite)
										
										
							  }// End foreach
							  // Messge ok
							  $reponse['msg'] = 'moduleinterface.php?module=commandes&action=default';
						  } // End if($connector->query($query)){
						  else
						  {
							  // Message pb
							  $reponse['msg']='';
						  }
				  } // End if($reponse['confirm']=='')
				  else
					$reponse['msg'] = 'moduleinterface.php?module=commandes&action=default';
		  } // End else
		  
		break;
	
	// Stats de paiement
	case 'paymentStats' :
		$date_debut = $_POST['date_debut'];
		$date_fin = $_POST['date_fin'];
		
		$query = "SELECT SUM(total_ttc) AS total_delivered_orders, COUNT(*) AS number_of_delivered_orders    
					FROM commandes
		   		   WHERE id_statut=4
		   			 AND date_livraison>='".$date_debut."' AND date_livraison<='".$date_fin."' ";
		$result = $connector->query($query);
		$table_details = "";
		$row = $connector->fetchArray($result);
		$number_of_delivered_orders = $row['number_of_delivered_orders'];
		if($number_of_delivered_orders>0){
			// Stats (internet, telephone, total)
			// Internet
			$query_internet = "SELECT SUM(total_ttc) AS total_delivered_orders, COUNT(*) AS number_of_delivered_orders    
								FROM commandes
							   WHERE id_statut=4
								 AND par_telephone=0
								 AND date_livraison>='".$date_debut."' AND date_livraison<='".$date_fin."' ";
			$result_internet = $connector->query($query_internet);
			$row_internet = $connector->fetchArray($result_internet);
			$number_of_delivered_orders_internet = $row_internet['number_of_delivered_orders'];
			if($number_of_delivered_orders_internet>0){
				$total_delivered_orders_internet = $row_internet['total_delivered_orders'];
				$average_cart_internet = $row_internet['total_delivered_orders']/$row_internet['number_of_delivered_orders'];
			}
			else{
				$total_delivered_orders_internet = 0;
				$average_cart_internet = 0;
			}
			// Telephone
			$query_telephone = "SELECT SUM(total_ttc) AS total_delivered_orders, COUNT(*) AS number_of_delivered_orders    
								FROM commandes
							   WHERE id_statut=4
								 AND par_telephone=1
								 AND date_livraison>='".$date_debut."' AND date_livraison<='".$date_fin."' ";
			$result_telephone = $connector->query($query_telephone);
			$row_telephone = $connector->fetchArray($result_telephone);
			$number_of_delivered_orders_telephone = $row_telephone['number_of_delivered_orders'];
			if($number_of_delivered_orders_telephone>0){
				$total_delivered_orders_telephone = $row_telephone['total_delivered_orders'];
				$average_cart_telephone = $row_telephone['total_delivered_orders']/$row_telephone['number_of_delivered_orders'];
			}
			else{
				$total_delivered_orders_telephone = 0;
				$average_cart_telephone = 0;
			}
			// Portable
			$query_cellphone = "SELECT SUM(total_ttc) AS total_delivered_orders, COUNT(*) AS number_of_delivered_orders    
								FROM commandes
							   WHERE id_statut=4
								 AND par_telephone=2
								 AND date_livraison>='".$date_debut."' AND date_livraison<='".$date_fin."' ";
			$result_cellphone = $connector->query($query_cellphone);
			$row_cellphone = $connector->fetchArray($result_cellphone);
			$number_of_delivered_orders_cellphone = $row_cellphone['number_of_delivered_orders'];
			if($number_of_delivered_orders_cellphone>0){
				$total_delivered_orders_cellphone = $row_cellphone['total_delivered_orders'];
				$average_cart_cellphone = $row_cellphone['total_delivered_orders']/$row_cellphone['number_of_delivered_orders'];
			}
			else{
				$total_delivered_orders_cellphone = 0;
				$average_cart_cellphone = 0;
			}
			// Total
			$total_delivered_orders = $row['total_delivered_orders'];
			$average_cart = $row['total_delivered_orders']/$row['number_of_delivered_orders'];
			
			// Table details (moyens de paiement)
			$query_details = "SELECT SUM(total_ttc) AS total_delivered_orders, COUNT(*) AS number_of_delivered_orders, id_moyen_paiement    
								FROM commandes
		   		   			   WHERE id_statut=4
		   			 			 AND date_livraison>='".$date_debut."' AND date_livraison<='".$date_fin."'  
							GROUP BY id_moyen_paiement";
			$result_details = $connector->query($query_details);
			$table_details.="<table cellpadding=\"0\" cellspacing=\"2\" border=\"0\">
								<tr>
									<th>".getLang('PAYMENT_METHOD')."</th>
									<th>".getLang('TOTAL_DELIVERED_ORDERS')."</th>
									<th>".getLang('NUMBER_OF_DELIVERED_ORDERS')."</th>
									<th>".getLang('AVERAGE_CART')."</th>
								</tr>";
			while($row_details = $connector->fetchArray($result_details))
			{
				$average_cart_details = $row_details['total_delivered_orders']/$row_details['number_of_delivered_orders'];
				$table_details.="<tr>
									<td>".getPaymentMethodName($row_details['id_moyen_paiement'])."</td>
									<td>".showPriceCurrency($row_details['total_delivered_orders'])."</td>
									<td>".$row_details['number_of_delivered_orders']."</td>
									<td>".showPriceCurrency($average_cart_details)."</td>
								</tr>";
			}
			$table_details.="</table>";
		}
		else{
			// Internet
			$total_delivered_orders_internet = 0;
			$number_of_delivered_orders_internet = 0;
			$average_cart_internet = 0;
			// Telephone
			$total_delivered_orders_telephone = 0;
			$number_of_delivered_orders_telephone = 0;
			$average_cart_telephone = 0;
			// Portable
			$total_delivered_orders_cellphone = 0;
			$number_of_delivered_orders_cellphone = 0;
			$average_cart_telephone = 0;
			// Total
			$total_delivered_orders = 0;
			$number_of_delivered_orders = 0;
			$average_cart = 0;
		}
		// Internet
		$reponse['total_delivered_orders_internet'] = showPriceCurrency($total_delivered_orders_internet);
		$reponse['number_of_delivered_orders_internet'] = $number_of_delivered_orders_internet;
		$average_cart_internet = showPriceCurrency($average_cart_internet);
		$reponse['average_cart_internet'] = $average_cart_internet;
		// Telephone
		$reponse['total_delivered_orders_telephone'] = showPriceCurrency($total_delivered_orders_telephone);
		$reponse['number_of_delivered_orders_telephone'] = $number_of_delivered_orders_telephone;
		$average_cart_telephone = showPriceCurrency($average_cart_telephone);
		$reponse['average_cart_telephone'] = $average_cart_telephone;
		// Portable
		$reponse['total_delivered_orders_cellphone'] = showPriceCurrency($total_delivered_orders_cellphone);
		$reponse['number_of_delivered_orders_cellphone'] = $number_of_delivered_orders_cellphone;
		$average_cart_cellphone = showPriceCurrency($average_cart_cellphone);
		$reponse['average_cart_cellphone'] = $average_cart_cellphone;
		// Total
		$reponse['total_delivered_orders'] = showPriceCurrency($total_delivered_orders);
		$reponse['number_of_delivered_orders'] = $number_of_delivered_orders;
		$average_cart = showPriceCurrency($average_cart);
		$reponse['average_cart'] = $average_cart;
		$reponse['table_details'] = $table_details;
		if($date_debut==$date_fin)
			$reponse['date_interval'] = getLang('ORDERS_MADE').' '.getLang('THE').' '.dateENtoFR($date_debut);
		else	
			$reponse['date_interval'] = getLang('ORDERS_MADE').' '.getLang('BETWEEN_THE').' '.dateENtoFR($date_debut).getLang('AND_THE').' '.dateENtoFR($date_fin);
		break;
	
	/*Modifié par Grégory*/
	case 'updateLoyaltySettings' :
		$query='UPDATE `settings` SET `value`="'.$_POST['value'].'" WHERE `name`="'.$_POST['name'].'"';
		if($connector->query($query)){
			$reponse['result'] = 'OK';
		} else {
			$reponse['result'] = 'Error'.mysqli_error($connector->link);
		}
		
		break;
		
		
		
	case 'goodCustomer' :
		//On récupère les valeurs de config
		$result_conf=$connector->query("SELECT value FROM settings WHERE id='7'");
		$row_conf=$connector->fetchArray($result_conf);
		$seuilBonClient=$row_conf['value'];
	
		
		//On récupère la liste de tous les clients
		$query_listeClient = 'SELECT `id` , `prenom` , `nom` , `email`
FROM `clients`';
		$result_listeClients=$connector->query($query_listeClient);
		$clients = array();
		while($row_client=$connector->fetchArray($result_listeClients)){
			$clients[$row_client['id']]=array();
			$clients[$row_client['id']]['nom']=$row_client['nom'];
			$clients[$row_client['id']]['prenom']=$row_client['prenom'];
			$clients[$row_client['id']]['email']=$row_client['email'];
		}
		
		
		//Pour chaque client on fait la somme des commandes à partir du dernier bon ou depuis le début
		$bonClients=array();
		$i=0;
		foreach ($clients as $id_client => $client){
			$query_bonClients="SELECT SUM( total_ttc ) AS somme
FROM commandes
WHERE id_statut = '4'
AND id_client =".$id_client;

			$result=$connector->query($query_bonClients);
			$row=$connector->fetchArray($result);
			if(!is_null($row['somme']) && $row['somme'] >= $seuilBonClient ){
				$bonClients[$id_client]=$clients[$id_client];
				if($i==0){
					if(emailGoodCustomer($bonClients[$id_client]['nom'],$bonClients[$id_client]['prenom'], "jeremy.thebault@yahoo.com", "G4239")){
						$reponse['mail']='ok';
					}else{
						$reponse['mail']='echec';
					}
					$i++;
					
				}
			}
		}
			
		//$reponse=$bonClients;
		/*
		$query="INSERT INTO `bon_achat`(`id_client`, `code`, `description`, `valeur`, `actif`, `debut_validite`, `fin_validite`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])";*/
		break;
	
	
	case 'badCustomer' :
		//On récupère les valeurs de config
		$result_conf=$connector->query("SELECT value FROM settings WHERE id='10'");
		$row_conf=$connector->fetchArray($result_conf);
		$nbJoursSansCommandes=$row_conf['value'];
		
		$query="SELECT id_client, nom, prenom, email, DATEDIFF(CURDATE(), date_livraison) AS joursDepuisCommande
FROM commandes, clients
WHERE clients.id = commandes.id_client
AND DATEDIFF(CURDATE() ,date_livraison) >=300
GROUP BY id_client";
		$result=$connector->query($query);
		$mauvaisClients=array();
		while($row=$connector->fetchArray($result)){
			$mauvaisClients[$row['id_client']]=array();
			$mauvaisClients[$row['id_client']]['nom']=$row['nom'];
			$mauvaisClients[$row['id_client']]['prenom']=$row['prenom'];
			$mauvaisClients[$row['id_client']]['email']=$row['email'];
			$mauvaisClients[$row['id_client']]['joursDepuisCommande']=$row['joursDepuisCommande'];
		}
		$reponse=$mauvaisClients;
		
		break;
	/*Fin modification*/
}

header('Content-Type: application/json');
echo json_encode($reponse);

?>