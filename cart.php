<?php 
include("includes/top_includes.php");

$step = (isset($_REQUEST['step']))?(int)$_REQUEST['step']:1;
$step = ($step==1 || $step==2)? $step : ((isset($_POST['step']))?$_POST['step'] : 1);

$min_ok = 1;
if($step==2){
	$min_ok = 0;
	if(isset($_SESSION['total_ttc'])){
		if($_SESSION['total_ttc']>=$_SESSION['orderMin'])
		{
			$min_ok = 1;	
		}
	}
}

if(((int)$step>1 && sizeof($_SESSION['cart'])<=0) || $min_ok==0)
	header("Location:commander.php?step=1");
?>
<!doctype html>
<html>
    <head>
	    <?php include("includes/head.php"); ?>
		<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	    <title>Panier - <?php showLang('PAGE_TITLE_COMMON') ?></title>
	</head>
<body>
<div class="page-container container">
    <?php 
    include("includes/header.php"); 
    $step_num = "STEP".$step;
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <ul class="breadcrumb">
                        <li><a href="commander.php">Commander en ligne</a> <span class="divider"></span></li>
						<li class="active"><?php showLang($step_num) ?></li>
                    </ul>
                </div>
            </div>  
        </div>
        <div class="row">
		    <div class="col-md-12">
				<h1><?php showLang($step_num) ?></h1>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12"><?php
		        switch ((int)$step){
		        	case 1: // Etape 1 : Panier complet
						if(isset($_GET['msg']) && $_GET['msg']=='return'): ?>
							<p class="bg-danger"><?php showLang('PAYPAL_RETURN') ?></p><?php
						endif; ?>
			            <div id="panier_full"></div><?php
						break;

					case 2 : // Etape 2 : Si loggé, choix de l'adresse de livraison et de facturation, sinon authentification
							if(sizeof($_SESSION['cart'])>0)
							{
								$conn = new DbConnector();
								$query = "INSERT INTO commandes (id_client, date, total_ht, total_ttc, id_statut) VALUES (".
											"'".(int)$_SESSION['id_client']."', ".
											"'".date('Y-m-d G:i:s')."', ".
											"".(float)$_SESSION['total_ht'].", ".
											"".(float)$_SESSION['total_ttc'].", ".
											"1)";
							
								if ($result = $conn->query($query)){
										$id_commande = mysql_insert_id();
										$total_produits_ttc = 0;
										$_SESSION['id_commande'] = $id_commande;
										foreach($_SESSION['cart'] as $id_produit => $quantite) { 
											if($id_produit!='options')
											{
												// Mezzes
												if(isset($_SESSION['cart']['options'][$id_produit]) && count($_SESSION['cart']['options'][$id_produit])>0)
												{
													foreach($_SESSION['cart']['options'][$id_produit] as $keyM => $valueM)
									  				{
														$quantite = $_SESSION['cart']['options'][$id_produit][$keyM]['quantite'];
														$result = $conn->query("SELECT * FROM plats WHERE id=".$id_produit);
														$row_produit = $conn->fetchArray($result);
														$prix_ligne_ht = $row_produit['prix_ht'] * $quantite;
														$prix_ligne_ttc = $row_produit['prix_ttc'] * $quantite;
														$total_produits_ttc += $prix_ligne_ttc;
														$arrayOptions = $_SESSION['cart']['options'][$id_produit][$keyM];
														$mezze_options = '';
														foreach($arrayOptions as $key=>$value) {
															if($key!=='quantite' && $key!=='supplement_ht' && $key!=='supplement_ttc')
																$mezze_options.=$value."<br />";
														}
														$query = "INSERT INTO ligne_commande (id_commande, id_plat, prix_ht, prix_ttc, quantite, options, total_ht, total_ttc, remise, id_remise, code_remise, valeur_remise, description_remise) VALUES (".
															"".(int)$id_commande.", ".
															"".(int)$id_produit.", ".
															"".(float)$row_produit['prix_ht'].", ".
															"".(float)$row_produit['prix_ttc'].", ".
															"".(int)$quantite.", ".
															"'".isql($mezze_options)."', ".
															"".(float)$prix_ligne_ht.", ".
															"".(float)$prix_ligne_ttc.", ".
															"0,
															NULL,
															NULL,
															NULL,
															NULL".
															")";
														$conn->query($query);
													}
												}
												else
												{
													$result = $conn->query("SELECT * FROM plats WHERE id=".$id_produit);
													$row_produit = $conn->fetchArray($result);
													$prix_ligne_ht = $row_produit['prix_ht'] * $quantite;
													$prix_ligne_ttc = $row_produit['prix_ttc'] * $quantite;
													$total_produits_ttc += $prix_ligne_ttc;
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
													$conn->query($query);
												}
											}
										}
										// Insertion de la remise
										if(isset($_SESSION['remise']))
										{
											$discount_ttc = (float)($total_produits_ttc * ((int)($_SESSION['remise']['valeur'])) * 0.01);
											$discount_ht = $discount_ttc/(1+getDiscountTaxRate());
											$query = "INSERT INTO ligne_commande (id_commande, id_plat, prix_ht, prix_ttc, quantite, total_ht, total_ttc, remise, id_remise, code_remise, valeur_remise, description_remise) VALUES (".
														"".(int)$id_commande.", ".
														"".(int)$_SESSION['remise']['id'].", ".
														"".(float)$discount_ht.", ".
														"".(float)$discount_ttc.", ".
														"1, ".
														"".(float)$discount_ht.", ".
														"".(float)$discount_ttc.", ".
														"1, ".
														"".(int)$_SESSION['remise']['id'].", ".
														"'".isql($_SESSION['remise']['code'])."', ".
														"".(int)$_SESSION['remise']['valeur'].", ".
														"'".isql($_SESSION['remise']['description'])."'".
														")";
											$conn->query($query);
											if($_SESSION['remise']['quantite_initiale']>0){
												$query = "UPDATE remises SET quantite_restante= quantite_restante-1 WHERE id=".(int)($_SESSION['remise']['id']);
												$conn->query($query);
											}
										}
								}
							} 
							?>
			
                            <form id="choix_adresse" action="cart.php" method="post">
                            	<div class="row">
		    						<div class="col-md-12">
                                		<input type="hidden" name="step" value="3" />
                                		<input type="hidden" name="choix_heure" id="choix_heure" value="0" />
                                        <div class="row">
		    								<div class="col-md-12 col-checkbox">      	
                                            	<label for="cgv" class="checkbox"><input type="checkbox" name="cgv" id="cgv" <?php if(isset($_SESSION['cgv']) && $_SESSION['cgv']==1): ?> checked="checked" <?php endif; ?> /><?php showLang('TERMS_AND_CONDITIONS_OK') ?> (<a href="" id="lire_cgv"><?php showLang('READ') ?></a>)</label>
                                            	<label for="meme_adresse" class="checkbox"><input type="checkbox" name="meme_adresse" id="meme_adresse" <? if(!(isset($_SESSION['adresse_livraison'])) || $_SESSION['adresse_livraison']==$_SESSION['adresse_facturation']): ?> checked="checked" <?php endif; ?> /><?php showLang('SAME_ADDRESS') ?></label>
                                        	</div>
                                        </div>
                                        <div class="row top15">
		    								<div class="col-md-6">
	                                            <?php
	                                            $query_address = "SELECT * FROM adresses WHERE id_client=".$_SESSION['id_client']. " AND active=1 ORDER BY titre_adresse";
	                                            $result_address = $connector->query($query_address); ?>
	                                            <select name="adresse_livraison" id="adresse_livraison"><?php
	                                            while($row_address = $connector->fetchArray($result_address)){
	                                                $sel="";
	                                                if(isset($_SESSION['adresse_livraison'])){
	                                                    if($_SESSION['adresse_livraison']==$row_address['id'])
	                                                        $sel = 'selected="selected"';
	                                                }
	                                                else if($row_address['defaut']==1) 
	                                                        $sel = 'selected="selected"'; ?>
	                                                <option value="<?php echo $row_address['id'] ?>" <?php echo $sel; ?>><?php echo osql($row_address['titre_adresse']) ?></option><?php
	                                            } ?>
	                                            </select>
	                                        </div>
	                                        <div class="col-md-6"><?php
	                                        	$result_address = $connector->query($query_address); ?>
	                                            <select name="adresse_facturation" id="adresse_facturation"><?php
	                                            while($row_address = $connector->fetchArray($result_address)){
	                                                $sel="";
	                                                if(isset($_SESSION['adresse_facturation'])){
	                                                    if($_SESSION['adresse_facturation']==$row_address['id'])
	                                                        $sel = 'selected="selected"';
	                                                }
	                                                else if($row_address['defaut']==1) 
	                                                        $sel = 'selected="selected"'; ?>
	                                                <option value="<?php echo $row_address['id'] ?>" <?php echo $sel; ?>><?php echo osql($row_address['titre_adresse']) ?></option><?php
	                                            } ?>
	                                            </select>
	                                        </div>
                                        </div>
                                        <div class="row top15">
		    								<div class="col-md-6">
	                                            <h3 class="colored"><?php showLang('SHIPPING_ADDRESS') ?></h3>
                                            	<div id="adresse_livraison_details"></div>
	                                        </div>
	                                        <div class="col-md-6">
	                                            <h3 class="colored"><?php showLang('BILLING_ADDRESS') ?></h3>
                                            	<div id="adresse_facturation_details"></div>
	                                        </div>
                                        </div>
                                        <div class="row top15">
		    								<div class="col-md-12">
                                        		<a class="btn" href="mon_compte.php?action=ajouter_adresse&back=cart.php&step=2"><?php showLang('ADD_ADDRESS') ?></a>
                                        	</div>
                                        </div>
                                        <div class="row top15">
		    								<div class="col-md-12">
	                                            <h3><?php showLang('CUTLERY') ?></h3>
	                                            <select name="couverts" id="couverts"><?php
													for($j=0;$j<11;$j++){
														if($j==0){ ?>
															<option value="0"><?php showLang('NO_CUTLERY') ?></option><?php
														}
														else{
															$sel=""; 
															if(isset($_SESSION['couverts'])){
																if($_SESSION['couverts']==$j)
																	$sel = 'selected="selected"';
															}
															?>
															<option value="<?php echo $j ?>" <?php echo $sel; ?>><?php showLang('FOR') ?> <?php echo $j ?></option><?php
														}
													} ?>
	                                            </select>
	                                        </div>
                                        </div>
                                        <div class="row top15">
                                        	<div class="col-md-12">
		                                        <div class="order-notes">
													<p><?php showLang('ORDER_MSG') ?></p>
													<textarea rows="3" cols="10" name="message" class="form-control"></textarea>
												</div>
											</div>
										</div>
										<div class="row top20">
										    <div class="col-md-6 col-xs-6 text-left">
												<p><a href="commander.php?step=1" class="btn btn-primary"><?php showLang('PREVIOUS_STEP') ?></a></p>
											</div>
										    <div class="col-md-6 col-xs-6 text-right">
												<p><input type="submit" id="choix_adresse_suivant" class="btn btn-primary" value="<?php showLang('NEXT_STEP') ?>" /></p>
											</div>
										</div>
                                    </div>
                                </div>
                            </form>
                            
							<?php
							break;

					case 3 : // Etape 3 : Choix du moyen de paiement
						if(isset($_POST['adresse_livraison'])){
							$_SESSION['cgv'] = 1;
							$_SESSION['couverts'] = $_POST['couverts'];
							$_SESSION['adresse_livraison'] = $_POST['adresse_livraison'];
							$_SESSION['adresse_facturation'] = $_POST['adresse_facturation'];
							$_SESSION['message'] = isset($_POST['message'])?$_POST['message']:'';
							$query = "UPDATE commandes SET adresse_livraison=".(int)$_SESSION['adresse_livraison'].", adresse_facturation=".(int)$_SESSION['adresse_facturation'].", date_livraison='".dateFRtoEN($_SESSION['deliveryDate'])."', creneau_livraison='".isql($_SESSION['deliveryTime'])."', couverts=".(int)$_SESSION['couverts'].", message='".isql($_SESSION['message'])."' WHERE id=".(int)$_SESSION['id_commande'];
							$conn = new DbConnector();
							$conn->query($query);
						}

						
						include('sogenactif/sample/call_request.php');
												
						?>

						<form id="choix_paiement" action="cart.php" method="post">
                                <input type="hidden" name="step" value="4" />
                                <input type="hidden" id="moyen_paiement" name="moyen_paiement" value="" />
						</form><?php
						if(sizeof($_SESSION['cart'])>0): ?>
							<div class="row"><?php
								$result_payment = $connector->query("SELECT * FROM order_methods WHERE active=1 AND id!=2");
								while($row_payment=$connector->fetchArray($result_payment)){ ?>
									<div class="col-md-4 payment_block">
										<a class="btn" href="" rel="<?php echo $row_payment['id'] ?>">
                                        	<p class="payment_text"><?php echo str_replace('_', ' ', $row_payment['name']) ?></p>
											<p class="payment_image"><img  src="<?php echo $sitedir.$row_payment['image'] ?>" alt="<?php echo $row_payment['name'] ?>" title="<?php echo str_replace('_', ' ', $row_payment['name']) ?>" align="absmiddle" /></p>
										</a>
									</div><?php
								} ?>
	      					</div>
	      					<div class="row top20">
							    <div class="col-md-6 text-left">
									<p><a href="cart.php?step=2" class="btn btn-primary"><?php showLang('PREVIOUS_STEP') ?></a></p>
								</div>
							</div>
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form">
								<input type="hidden" name="cmd" value="_xclick" />
								<input type="hidden" name="business" value="contact@mabento.fr">
								<input type="hidden" name="item_name" value="Commande <?php echo $_SESSION['id_commande'] ?>">
								<input type="hidden" name="item_number" value="<?php echo $_SESSION['id_client'] ?>" />
								<input type="hidden" name="amount" value="<?php echo $_SESSION['total_ttc'] ?>" />
								<input type="hidden" name="shipping" value="0.00" />
								<input type="hidden" name="custom" value="<?php echo $_SESSION['id_commande'] ?>" />
								<!--<input name="tax" type="hidden" value="<?php echo number_format(($_SESSION['total_ttc'] - $_SESSION['total_ht']), 2, '.', '') ?>" />-->
								<input type="hidden" name="lc" value="FR" />
								<input type="hidden" name="currency_code" value="EUR">
								<input type="hidden" name="return" value="<?php echo $sitedir ?>confirmation.php" />
								<input type="hidden" name="cancel_return" value="<?php echo $sitedir ?>annulation.php" />
								<input type="hidden" name="notify_url" value="<?php echo $sitedir ?>ipnpaypal.php" />
								<input name="no_note" type="hidden" value="1" />
								
								<?php
								$result = $connector->query("SELECT * FROM adresses WHERE id=".$_SESSION['adresse_livraison']);
								$row_address = $connector->fetchArray($result);
								?>
								<input type="hidden" name="charset" value="utf-8">
								<input type="hidden" name="first_name" value="<?php echo $row_address['prenom'] ?>" />
								<input type="hidden" name="last_name" value="<?php echo $row_address['nom'] ?>" />
								<input type="hidden" name="address1" value="<?php echo $row_address['adresse1'] ?>" />
								<input type="hidden" name="address2" value="<?php echo $row_address['adresse2'] ?>" />
								<input type="hidden" name="city" value="<?php echo $row_address['ville'] ?>" />
								<input type="hidden" name="zip" value="<?php echo $row_address['cp'] ?>" />
								<input type="hidden" name="country" value="FR" />
								<input type="hidden" name="night_phone_a" value="33" />
								<input type="hidden" name="night_phone_b" value="<?php echo substr($row_address['telephone'],1) ?>" />
								<input type="hidden" name="night_phone_c" value="" />
								<input type="hidden" name=" address_override" value="1" />
							</form><?php
	      				endif;


						break;
					case 4 : // Etape 4 : Demande de Confirmation
						if(isset($_POST['moyen_paiement'])){
							$_SESSION['moyen_paiement'] = $_POST['moyen_paiement'];
						} ?>
						<form id="confirmer_commande" action="cart.php" method="post">
							<input type="hidden" name="step" value="5" />
                           	<div class="row">
					            <div class="col-md-12">
					            	<p><?php showLang('PAYMENT_METHOD_SELECTION') ?> <?php echo getPaymentMethod($_SESSION['moyen_paiement']) ?></p>
	                                <p><?php showLang('ORDER_AMOUNT') ?> <?php echo showPriceCurrency($_SESSION['total_ttc']) ?></p>
	                                <p><?php showLang('CONFIRMATION_PLEASE') ?></p>
					            </div>
					        </div>
                            <div class="row top20">
							    <div class="col-md-12 text-right">
									<input type="submit" name="confirmation" class="btn btn-primary" value="<?php showLang('CONFIRM') ?>" />
								</div>
							</div>
						</form><?php
						break;
					case 5 : // Etape 5 : Message de confirmation
						if(isset($_POST['confirmation'])){
							$query = "UPDATE commandes SET id_statut=2, id_moyen_paiement=".(int)$_SESSION['moyen_paiement']." WHERE id=".(int)$_SESSION['id_commande'];
							$conn = new DbConnector();
							$conn->query($query);
							email_resto($_SESSION['id_commande'], $_SESSION['adresse_livraison']);
							empty_cart();
						} ?>
                         
						<div class="row">
				            <div class="col-md-12">
				            	<p><?php showLang('ORDER_WAITING_CONFIRMATION') ?></p>
                                <p><?php showLang('ORDERS_HISTORY_MSG') ?> :<br /><a href="mon_compte.php"><?php showLang('ORDERS_HISTORY') ?></a></p>
				            </div>
				        </div><?php
                        break;
				} ?>
			</div>
		</div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
<script language="javascript" type="text/javascript" src="js/cart.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/transition.js"></script>
<script type="text/javascript" src="js/collapse.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
<script>
var step='<?php echo $step ?>';
</script>
</body>
</html>
