<?php
include("includes/top_includes.php");
require_once('admin/includes/Validator.php');

$action = isset($_POST['action']) ? $_POST['action'] : "";


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
else{
	$quantite = isset($_POST['quantite']) ? $_POST['quantite'] : "";

	if($action=='retirer_mezze' || $action=='ajouter_mezze' || $action=='soustraire_mezze')
	{
		$array_produit = explode('-', $_POST['id_produit']);
		$id_produit = $array_produit[0];
		$index_produit = $array_produit[1];
	}
	else
		$id_produit = isset($_POST['id_produit']) ? $_POST['id_produit'] : "";
	$categorie = isset($_POST['categorie'])?$_POST['categorie']:-1;
	
	if($id_produit && produit_existe($id_produit)) {
		if(!(isset($_SESSION['cart'])))
			$_SESSION['cart']=array();
		switch($action) { //decide what to do
			case "ajouter":
				if(!(isset($_SESSION['cart'][(int)$id_produit])))
					$_SESSION['cart'][(int)$id_produit]=0;	
				$_SESSION['cart'][(int)$id_produit]+=$quantite;
				if($categorie!=-1){
					$arrayOptions = $_POST['arrayOptions'];
					$supplement_ht = $_POST['supplement_ht'];
					$supplement_ttc = $_POST['supplement_ttc'];
					foreach($arrayOptions as $key=>$value){
						$arrayOptions[$key]=stripslashes($value);
					}
					$index = getMezzeIndex($id_produit, $arrayOptions);
					$_SESSION['cart']['options'][$id_produit][$index]['quantite']+=$quantite;
					$_SESSION['cart']['options'][$id_produit][$index]['supplement_ht'] = $supplement_ht;
					$_SESSION['cart']['options'][$id_produit][$index]['supplement_ttc'] = $supplement_ttc;
					if($_SESSION['cart']['options'][$id_produit][$index]['quantite']<2)
					{
						foreach($arrayOptions as $key=>$value) {
							$_SESSION['cart']['options'][$id_produit][$index][] = $value;
						}
					}
				}
			break;
			case "ajouter_mezze":
				$_SESSION['cart']['options'][$id_produit][$index_produit]['quantite']+=$quantite;
			break;
			case "soustraire":
				$_SESSION['cart'][$id_produit]--;
				if($_SESSION['cart'][$id_produit] == 0){ 
					unset($_SESSION['cart'][$id_produit]);
					unset($_SESSION['cart']['options'][$id_produit]);
				}
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

if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0) : //if the cart isn't empty
          $reponse['html'].='<table class="table">';
          $i=1;
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
                if($id_produit!='options'):
					 $result = $conn->query("SELECT * FROM plats WHERE id=".$id_produit);
					 $row_produit = $conn->fetchArray($result);
					 // Menus
					 if(isset($_SESSION['cart']['options'][$id_produit]) && count($_SESSION['cart']['options'][$id_produit])>0)
					 {
					  	if($row_produit):
                              foreach($_SESSION['cart']['options'][$id_produit] as $keyM => $valueM)
							  	{ 
							  	  $quantite = $_SESSION['cart']['options'][$id_produit][$keyM]['quantite'];
							  	  $supplement_ht = $_SESSION['cart']['options'][$id_produit][$keyM]['supplement_ht'];
							  	  $supplement_ttc = $_SESSION['cart']['options'][$id_produit][$keyM]['supplement_ttc'];
								  $prix_ligne_ht = ($row_produit['prix_ht'] + $supplement_ht) * $quantite;
								  $prix_ligne_ttc = ($row_produit['prix_ttc']+ $supplement_ttc) * $quantite;
								  $total_ht += $prix_ligne_ht; // cout total ht
								  $total_ttc += $prix_ligne_ttc; // cout total ttc
							  	  $reponse['html'].='
                                  	<tr>
	                                    <td class="image"><img alt="'.osql($row_produit['name']).'" src="'.$row_produit['thumbnail1'].'"></td>
										<td class="name">'.
											osql($row_produit['name']);
											$arrayOptions = $_SESSION['cart']['options'][$id_produit][$keyM];
	                                        $reponse['html'].='<div class="mezze_options_cart">';
	                                        foreach($arrayOptions as $key=>$value) {
	                                            if($key!=='quantite' && $key!=='supplement_ht' && $key!=='supplement_ttc')
	                                                $reponse['html'].='<span class="mezze_option_cart">'.osql($value).'</span><br />';
	                                        }
	                                        $reponse['html'].='</div>
										</td>
										<td class="quantity">x&nbsp;'.$quantite.'</td>
										<td class="total">'.showPriceCurrency($prix_ligne_ttc).'</td>
										<td class="remove"><i class="fa fa-remove bouton_retirer bouton_retirer bouton_mezze" data-id="'.(int)$row_produit['id'].'-'.$keyM.'"></i></td>
									</tr>';								  
							  	}
						endif;	
					}
					else
					{
					  	if($row_produit):
							  $prix_ligne_ht = (float)$row_produit['prix_ht'] * (int)$quantite;
							  $prix_ligne_ttc = (float)$row_produit['prix_ttc'] * (int)$quantite;
							  $total_ht += $prix_ligne_ht; // cout total ht
							  $total_ttc += $prix_ligne_ttc; // cout total ttc
							  $reponse['html'].='
							  <tr>
								<td class="image"><img alt="'.osql($row_produit['name']).'" src="'.$row_produit['thumbnail1'].'"></td>
								<td class="name">'.osql($row_produit['name']).'</td>
								<td class="quantity">x&nbsp;'.$quantite.'</td>
								<td class="total">'.showPriceCurrency($prix_ligne_ttc).'</td>
								<td class="remove"><i class="fa fa-remove bouton_retirer" data-id="'.(int)$row_produit['id'].'"></i></td>
							  </tr>';
					    endif;
					}
				endif;
          }
		  if(isset($_SESSION['remise']))
		  { 
		  	 if(isset($_SESSION['remise']['panier_minimum']) && $total_ttc>=$_SESSION['remise']['panier_minimum']) // On vérifie que le changement de quantité n'est pas provoque la secente en dessous du minimum de panier pour la promo
			 {
				 $discount = (float)($total_ttc * ((int)($_SESSION['remise']['valeur'])) * 0.01);
				 $reponse['html'].='
				   <tr>
					<td colspan="3" class="panier_produit">'.$_SESSION['remise']['description'].'</td>
					<td class="panier_prix">- '.showPriceCurrency($discount).'</td>
					<td class="panier_retirer"><i class="fa fa-remove bouton_retirer_remise"></i></td>
				  </tr>';
				  $total_ht -= $discount/(1+getDiscountTaxRate()); // cout total ht
				  $total_ttc -= $discount; // cout total ttc
			 }
		  }
		  $reponse['html'].='</table>';

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
			<div class="cart-total">
			  <table>
				 <tbody>';
					if($livraison>0)
			          {
			          	$reponse['html'].='
				          <tr>
				            <td>Total produits : </td>
				            <td>'.showPriceCurrency($total_produits).'</td>
				          </tr>
				          <tr>
				            <td class="txt-right">Livraison : </td>
				            <td class="txt-right">'.showPriceCurrency($livraison).'</td>
				          </tr>';	
			          }
      	  			$reponse['html'].='
	                <tr>
					  <td><b>Total : </b></td>
					  <td>'.showPriceCurrency($total_ttc).'</td>
					</tr>
	                <tr>
					  <td><b>dont TVA : </b></td>
					  <td>'.showPriceCurrency($total_ttc - $total_ht).'</td>
					</tr>
				</tbody>
			  </table>
	          <div class="checkout"><a href="cart.php">Commander</a></div>
			</div>';

          /*
          <tr>
            <td colspan="6">
                <div id="block_remise" class="field block_remise_right" <?php if(isset($_SESSION['remise'])) : ?>style="display:none"<?php endif; ?>>
                        <div style="position:relative">
                            <div style="padding-right:100px;"><?php
								$code_remise_value=getLang('ENTER_VOUCHER_CODE_SHORT');
								if(isset($code_remise_erreur)) :
									echo '<div class="code_promo_erreur orange">'.$code_remise_erreur."</div>";
								endif; ?>
								<input name="code_remise" id="code_remise" type="text" class="voucher_short" value="<?php echo $code_remise_value ?>" />
                            </div>
                            <div style="position:absolute; bottom:0; right:0">
                                <input type="submit" id="submit_remise" class="bouton3" value="<?php showLang('APPLY') ?>" />
                            </div>
                        </div>
                </div>
            </td>
          </tr>
          <tr>
            <td colspan="6" class="panier_valider"><input type="submit" id="commander_button" class="bouton2" value="<?php showLang('SUBMIT') ?>" /></td>
          </tr>
          <tr>
            <td colspan="6" class="panier_paiement"><img src="images/moyens_paiement.gif" alt="moyens_de_paiement" /></td>
          </tr>
      	  */
        
else:
	$reponse['html'].='<div class="panier_vide">'.getLang('CART_EMPTY').'</div>';
	$reponse['nb_products'] = 0;
	$reponse['total'] = showPriceCurrency(0);
endif; 
  
header('Content-Type: application/json');
echo json_encode($reponse);

//echo print_r($_SESSION['cart']);
?>