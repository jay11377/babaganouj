<?php
include("includes/top_includes.php");
require_once('admin/includes/Validator.php');

$action = $_POST['action'];

if($action=='ajouter_remise')
{
	$code_remise = $_POST['code_remise'];
	$conn_remise = new DbConnector();
	$query_remise = "SELECT * FROM remises WHERE code='".osql($code_remise)."'";
	$result_remise = $conn_remise->query($query_remise);
	
	if($conn_remise->getNumRows($result_remise)>0)
	{
		$row_remise = $conn_remise->fetchArray($result_remise);
		$validator = new Validator();
		if($row_remise['active']==0)
			$code_remise_erreur = getLang('VOUCHER_CODE_NO_LONGER_AVAILABLE');
		else if($row_remise['quantite_initiale']>0 && $row_remise['quantite_restante']<=0)
			$code_remise_erreur = getLang('VOUCHER_CODE_NO_LONGER_AVAILABLE');
		else if((float)($row_remise['panier_minimum'])>$_SESSION['total_ttc'])
			$code_remise_erreur = getLang('VOUCHER_CODE_MINIMUM_IS')." ".$row_remise['panier_minimum'].getLang('CURRENCY');
		else if($row_remise['nb_utilisation']>0 && getNbUtilisationVoucher((int)$row_remise['id'], (int)$_SESSION['id_client'])>0)
			$code_remise_erreur = getLang('VOUCHER_CODE_ALREADY_USED');
		else if ($row_remise['contraintes_date']==1 && (!($validator->validateCompareDates($row_remise['date_debut'], date("Y-m-d"))) || !($validator->validateCompareDates(date("Y-m-d"), $row_remise['date_fin']))))
			$code_remise_erreur = getLang('VOUCHER_CODE_DATE_ISSUE');
		else if(!(isset($_SESSION['id_client'])))
			$code_remise_erreur = getLang('VOUCHER_LOGGED_IN_ISSUE');
		else
		{
			if(!isset($_SESSION['remise']))
				$_SESSION['remise'] = array();
			$_SESSION['remise']['id'] = (int)($row_remise['id']);
			$_SESSION['remise']['code'] = osql($row_remise['code']);
			$_SESSION['remise']['quantite_initiale'] = (int)($row_remise['quantite_initiale']);
			$_SESSION['remise']['valeur'] = (int)($row_remise['valeur']);
			$_SESSION['remise']['description'] = osql($row_remise['description']);
			$_SESSION['remise']['panier_minimum'] = (int)($row_remise['panier_minimum']);
		}
	}
	else
	{
		$code_remise_erreur = getLang('VOUCHER_CODE_INCORRECT');
	}
}
else if($action=='retirer_remise')
{
	unset($_SESSION['remise']);
}
else
{
		$quantite = $_POST['quantite'];
		
		if($action=='retirer_mezze' || $action=='ajouter_mezze' || $action=='soustraire_mezze')
		{
			$array_produit = explode('-', $_POST['id_produit']);
			$id_produit = $array_produit[0];
			$index_produit = $array_produit[1];
		}
		else
			$id_produit = $_POST['id_produit'];
		
		if($id_produit && produit_existe($id_produit)) {
			switch($action) { //decide what to do
				case "ajouter":
					$_SESSION['cart'][$id_produit]+=$quantite;
				break;
				
				case "ajouter_mezze":
					$_SESSION['cart']['options'][$id_produit][$index_produit]['quantite']+=$quantite;
				break;
				
				case "soustraire":
					$_SESSION['cart'][$id_produit]--;
					if($_SESSION['cart'][$id_produit] == 0) 
						unset($_SESSION['cart'][$id_produit]);
				break;
				
				case "soustraire_mezze":
					$_SESSION['cart']['options'][$id_produit][$index_produit]['quantite']--;
					if($_SESSION['cart']['options'][$id_produit][$index_produit]['quantite']==0)
					{
						unset($_SESSION['cart'][$id_produit][$index_produit]);
						unset($_SESSION['cart']['options'][$id_produit][$index_produit]);
						if(count($_SESSION['cart']['options'][$id_produit])==0)
						{
							unset($_SESSION['cart'][$id_produit]);
							unset($_SESSION['cart']['options'][$id_produit]);
							if(count($_SESSION['cart']['options'])==0)
								unset($_SESSION['cart']['options']);
						}
					}
				break;
				
				case "retirer":
					unset($_SESSION['cart'][$id_produit]);
				break;
				
				case "retirer_mezze":
					unset($_SESSION['cart'][$id_produit][$index_produit]);
					unset($_SESSION['cart']['options'][$id_produit][$index_produit]);
					if(count($_SESSION['cart']['options'][$id_produit])==0)
					{
						unset($_SESSION['cart'][$id_produit]);
						unset($_SESSION['cart']['options'][$id_produit]);
						if(count($_SESSION['cart']['options'])==0)
							unset($_SESSION['cart']['options']);
					}
				break;
				case "empty":
					unset($_SESSION['cart']); //unset the whole cart, i.e. empty the cart.
				break;
		
			}  
		}
}
if($_SESSION['cart']) : //if the cart isn't empty
	$reponse['html'].='
	<form name="panier" action="cart.php" method="post">
    <div class="row">
		    <div class="col-md-12 cart-info">
			        <input type="hidden" value="2" name="step">
			        <input type="hidden" value="'.((isset($_SESSION['id_client'])) ? 1 : 0 ).'" name="logged_in" id="logged_in">
			        <table class="table">
			        	<thead>
			        	<tr>
			            	<th>'.getLang('PHOTO').'</th>
			                <th class="right_txt">'.getLang('UNIT_PRICE').'</th>
			                <th class="centered">'.getLang('QUANTITY').'</th>
			                <th class="left_txt">'.getLang('DESCRIPTION').'</th>
			                <th class="right_txt">'.getLang('TOTAL').'</th>
			                <th class="centered">'.getLang('DELETE').'</th>
			            </tr>
			            </thead>
			            <tbody>';
          	$total_ht = 0;
		  	$total_ttc = 0;


		  $nb_produits = 0;
		  foreach($_SESSION['cart'] as $cart_key =>$cart_val)
		  {
			if($cart_key!='options'){
				if(count($_SESSION['cart']['options'][$cart_key])>0)
				{
					foreach($_SESSION['cart']['options'][$cart_key] as $menu)
					{
						$nb_produits+=$menu['quantite'];
					}
				}
				else
					$nb_produits += $cart_val;
			}
		  }
		  $conn = new DbConnector();
		  foreach($_SESSION['cart'] as $id_produit => $quantite) { 
                if($id_produit!='options')
				{
						$result = $conn->query("SELECT * FROM plats WHERE id=".$id_produit);
						$row_produit = $conn->fetchArray($result);
						// Mezzes
						if(isset($_SESSION['cart']['options'][$id_produit]) && count($_SESSION['cart']['options'][$id_produit])>0)
						{
							 if($row_produit)
							 {
								  foreach($_SESSION['cart']['options'][$id_produit] as $keyM => $valueM)
								  {
									  $quantite = $_SESSION['cart']['options'][$id_produit][$keyM]['quantite'];
									  $supplement_ht = $_SESSION['cart']['options'][$id_produit][$keyM]['supplement_ht'];
							  	  	  $supplement_ttc = $_SESSION['cart']['options'][$id_produit][$keyM]['supplement_ttc'];
								  	  $prix_ligne_ht = ($row_produit['prix_ht'] + $supplement_ht) * $quantite;
								  	  $prix_unitaire_ttc = $row_produit['prix_ttc']+ $supplement_ttc;
								  	  $prix_ligne_ttc = $prix_unitaire_ttc * $quantite;
									  $total_ht += $prix_ligne_ht; // cout total ht
									  $total_ttc += $prix_ligne_ttc; // cout total ttc
									  $reponse['html'].='
									  <tr>
										<td class="panier_photo"><img src="'.osql($row_produit['thumbnail1']).'" /></td>
										<td class="panier_prix">'.showPriceCurrency($prix_unitaire_ttc).'</td>
										<td class="panier_quantite_bouton quantite bouton_mezze" data-quantite="'.$quantite.'" data-id="'.(int)$row_produit['id'].'-'.$keyM.'">
												<i class="fa fa-minus-square fa-3x"></i>
							                  	<div class="inline-block quantite_numero">'.$quantite.'</div>                        
							                  	<i class="fa fa-plus-square fa-3x"></i>
						                  	</div>
										</td>
										<td class="panier_produit">'. 
											osql($row_produit['name']);
											$arrayOptions = $_SESSION['cart']['options'][$id_produit][$keyM];
											$reponse['html'].='<div class="mezze_options_cart">';
											foreach($arrayOptions as $key=>$value) {
												if($key!=='quantite' && $key!=='supplement_ht' && $key!=='supplement_ttc')
													$reponse['html'].='<span class="mezze_option_cart">'.osql($value).'</span><br />';
											}
											$reponse['html'].='</div>
										</td>
										<td class="panier_prix">'.showPriceCurrency($prix_ligne_ttc).'</td>
										<td class="panier_retirer"><i class="fa fa-remove fa-2x bouton_retirer bouton_mezze" data-id="'.(int)$row_produit['id'].'-'.$keyM.'"></td>
									  </tr>';
								  }
							 }
					}
					else
					{
							if($row_produit)
							{
								  $prix_ligne_ht = $row_produit['prix_ht'] * $quantite;
								  $prix_ligne_ttc = $row_produit['prix_ttc'] * $quantite;
								  $total_ht += $prix_ligne_ht; // cout total ht
								  $total_ttc += $prix_ligne_ttc; // cout total ttc
								  $reponse['html'].='
								  <tr>
									<td class="panier_photo"><img src="'.osql($row_produit['thumbnail1']).'" /></td>
									<td class="panier_prix">'.showPriceCurrency($row_produit['prix_ttc']).'</td>
									<td class="panier_quantite_bouton quantite" data-quantite="'.$quantite.'" data-id="'.(int)$row_produit['id'].'">
										<i class="fa fa-minus-square fa-3x"></i>
					                  	<div class="inline-block quantite_numero">'.$quantite.'</div>                        
					                  	<i class="fa fa-plus-square fa-3x"></i>
									</td>
									<td class="panier_produit">'.osql($row_produit['name']).'</td>
									<td class="panier_prix">'.showPriceCurrency($prix_ligne_ttc).'</td>
									<td class="panier_retirer"><i class="fa fa-remove fa-2x bouton_retirer" data-id="'.(int)$row_produit['id'].'"></i></td>
								  </tr>';
							}
					}
				}
          }
		
		  if(isset($_SESSION['remise']))
		  { 
				if(isset($_SESSION['remise']['panier_minimum']) && $total_ttc>=$_SESSION['remise']['panier_minimum']) // On vérifie que le changement de quantité n'est pas provoque la secente en dessous du minimum de panier pour la promo
				{
					 $discount = (float)($total_ttc * ((int)($_SESSION['remise']['valeur'])) * 0.01);
					 $reponse['html'].='
					  <tr>
						<td class="panier_photo">&nbsp;</td>
						<td class="panier_produit">&nbsp;</td>
						<td class="panier_quantite_bouton">&nbsp;</td>
						<td class="panier_produit">'.$_SESSION['remise']['description'].'</td>
						<td class="panier_prix">- '.showPriceCurrency($discount).'</td>
						<td class="panier_retirer"><i class="fa fa-remove fa-2x bouton_retirer_remise"></i></td>
					  </tr>';
					  $total_ht -= $discount/(1+getDiscountTaxRate()); // cout total ht
					  $total_ttc -= $discount; // cout total ttc
				}
		  }
		  
		  $livraison = 0;
		  if($total_ttc>0 && $total_ttc<deliveryFree)
		  {
		  	$livraison = deliveryPrice;
		  	$total_produits = $total_ttc;
		  	$total_ttc += deliveryPrice;
		  	$total_ht += deliveryPrice/1.1;
		  }

		  $_SESSION['total_ht'] = $total_ht;
		  $_SESSION['total_ttc'] = $total_ttc;
		  $reponse['total'] = showPriceCurrency($total_ttc);
		  $reponse['nb_products'] = $nb_produits;
		  $reponse['html'].='
          <tr class="noborder">
            <td colspan="3">&nbsp;</td>
            <td colspan="3" class="total_separateur"><hr /><br /><hr /></td>
          </tr>';
          if($livraison>0)
          {
          	$reponse['html'].='
	          <tr class="noborder">
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td class="panier_total">Total produits</td>
	            <td class="panier_prix_total">'.showPriceCurrency($total_produits).'</td>
	            <td>&nbsp;</td>
	          </tr>
	          <tr class="noborder">
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td class="panier_total">Livraison</td>
	            <td class="panier_prix_total">'.showPriceCurrency($livraison).'</td>
	            <td>&nbsp;</td>
	          </tr>';	
          }
      	  $reponse['html'].='
          <tr class="noborder">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="panier_total">'.getLang('TOTAL_WITH_TAXES').'</td>
            <td class="panier_prix_total">'.showPriceCurrency($total_ttc).'</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="noborder">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="panier_total">dont TVA</td>
            <td class="panier_prix_total">'.showPriceCurrency($total_ttc - $total_ht).'</td>
            <td>&nbsp;</td>
          </tr>
          </tbody>
        </table>
	</div>
	</div>
	<div class="row">';
		$query_remises = "SELECT * FROM remises
						   WHERE active=1
							 AND afficher_panier=1
							 AND ( (contraintes_date=0) OR (contraintes_date=1 AND date_debut<=CURDATE() AND date_fin>=CURDATE()) )";
		$result_remises = $conn->query($query_remises);
		$reponse['html'].='
		<div class="col-md-5"'.((isset($_SESSION['remise'])) ? ' style="display:none"' : '').'>';
			if($conn->getNumRows($result_remises)>0)
			{ 
                $reponse['html'].='
                <div class="promo_block_left">
                    <div class="titre_code_promo">'.getLang('VOUCHER_CODES').'</div>';
                    while($row_remise = $conn->fetchArray($result_remises)){
                        $reponse['html'].='<a href="" class="lien_code_remise">'.osql($row_remise['code']).'</a><span style="padding-left:20px">'.osql($row_remise['description']).'</span></br>';
                    }
                $reponse['html'].='
                </div>';
			}
		$reponse['html'].='
		</div>
		<div class="col-md-7 text-right">';
			if(isset($code_remise_erreur)) :
				$reponse['html'].='<div class="code_promo_erreur required">'.$code_remise_erreur.'</div>';
			endif;
			$reponse['html'].='
			<input name="code_remise" id="code_remise" type="text" class="voucher form-control" value="'.getLang('ENTER_VOUCHER_CODE_SHORT').'" />
			<input type="submit" id="submit_remise" class="btn" value="'.getLang('APPLY').'" />
		</div>
	</div>
	<div class="row top20">
	    <div class="col-md-12">
			<div class="cart-totals">
				<p><input type="submit" id="commander_button" class="btn btn-primary" value="'.getLang('NEXT_STEP').'" /></p>
			</div>
		</div>
	</div>
	</form>';
else:
	$reponse['html'].='<div class="panier_vide">'.getLang('CART_EMPTY').'</div>';
	$reponse['nb_products'] = 0;
	$reponse['total'] = showPriceCurrency(0);
endif;

header('Content-Type: application/json');
echo json_encode($reponse);

?>