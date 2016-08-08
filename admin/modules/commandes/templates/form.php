<div class="inset container" id="order_details">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            <div class="required field">
            	<label><?php showLang('ORDER_ID') ?></label>
                <p class="info">
                	<?php echo osql($row['id_commande']); ?><br />
					<?php showLang('ON_THE') ?><br /><span class="black11bold"><?php echo dateLongENtoFR($row['date']) ?></span>
                </p>
            </div>
			<div class="required field">
            	<label><?php showLang('DELIVERY_DAY_TIME') ?></label>
                <p class="info"><span class="black11bold"><?php echo dateENtoFR($row['date_livraison']) ?></span><br><?php showLang('DELIVERY_ASKED_TIME') ?> : <span class="black11bold"><?php echo $row['creneau_livraison'] ?></span></p>
            </div>
			<div class="required field">
            	<label><?php showLang('DELIVERY_REAL_TIME') ?></label>
                <p class="info"><?php echo ($row['heure_livraison'] && $row['heure_livraison']!='')?$row['heure_livraison'] : "-" ?></p>
            </div><?php
			if($row['statut_id']==2 && $row['email']!=''): 
				if($row['heure_livraison']!=''){
					$hours = getTimeHours($row['heure_livraison']);
					$minutes = getTimeMinutes($row['heure_livraison']);
				}
				else if($row['creneau_livraison']==getRawLang('ASAP')){
					// avoir l'heure de livraison par rapport à l'heure courante
					$delivery_time_city = getMinutes(osql($row['ville']));
					$hours = date('H', strtotime("+".$delivery_time_city." minutes"));
					$minutes = date('i', strtotime("+".$delivery_time_city." minutes"));
					$modulo = $minutes%5;
					if($modulo>0)
						$minutes = $minutes - $modulo + 5;
					if($minutes==60){
						$minutes=0;
						$hours++;
					}
				}
				else{
					$hours = getTimeHours($row['creneau_livraison']);
					$minutes = getTimeMinutes($row['creneau_livraison']);
				}
				
				?>
                <div class="required field">
                    <label class="important"><?php showLang('DELIVERY_REAL_TIME') ?></label>
                    <select name="delivery_h" id="delivery_h" style="width:60px"><?php 
						for($k=0; $k<=23;$k++){ ?>
                        	<option value="<?php echo $k ?>" <?php if($k == (int)$hours) echo 'selected="selected"' ?>><?php echo $k ?></option><?php 
						} ?>
                    </select>
                    <select name="delivery_m" id="delivery_m" style="width:60px"><?php 
						for($k=0; $k<=55;$k+=5){ ?>
                            <option value="<?php echo $k ?>" <?php if($k == (int)$minutes) echo 'selected="selected"' ?>><?php echo ($k<10)?"0".$k : $k ?></option><?php 
						} ?>
                    </select>
                    <input name="email_client" type="submit" value="<?php showLang('SEND_EMAIL') ?>" class="button"><?php
					if($decale) : ?>
                    	<div class="help important"><?php showLang('DELIVERY_NOT_TODAY') ?></div><?php
					endif; 
					?>
                </div><?php
			endif; ?>
            <div class="required field">
            	<label><?php showLang('STATUS') ?></label>
                <select name="statut_commande"><?php 
					while($row_statut = $connector->fetchArray($result_statut)){ ?>
                        <option value="<?php echo $row_statut['id']?>" <?php if($row_statut["id"] == $row['statut_id']) echo 'selected="selected"'?> >
                            <?php echo $row_statut['statut'] ?>
                        </option><?php 
					} ?>
                </select>
                <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
            </div>
            <div class="required field">
            	<label><?php showLang('SHIPPING_INFO') ?></label>
                <p class="info">
                	<?php echo osql($row['prenom']) ?> <?php echo osql($row['nom']) ?><br />
					<?php echo osql($row['telephone']) ?><br />
                    <a href="mailto:<?php echo osql($row['email']) ?>"><?php echo osql($row['email']) ?></a><br /><br />
                    <?php if ($row['societe'] && $row['societe']!='') echo osql($row['societe'])."<br />" ?>
                    <?php echo osql($row['adresse1']) ?><br />
                    <?php if ($row['adresse2'] && $row['adresse2']!='') echo osql($row['adresse2'])."<br />" ?>
                    <?php echo osql($row['cp']) ?> <?php echo osql($row['ville']) ?><br />
                    <?php echo $row['telephone'] ?><br />----------------------------<br /><?php
					 if($row['code_entree']!='')
						echo getLang('ENTRY_CODE').' : '.osql($row['code_entree'])."<br />";
					 if($row['interphone']!='')
						echo getLang('INTERCOM').' : '.osql($row['interphone'])."<br />";
					 if($row['service']!='')
						echo getLang('SERVICE').' : '.osql($row['service'])."<br />";
					 if($row['escalier']!='')
						echo getLang('STAIRCASE').' : '.osql($row['escalier'])."<br />";
					 if($row['etage']!='')
						echo getLang('FLOOR').' : '.osql($row['etage'])."<br />";
					 if($row['numero_appartement']!='')
						echo getLang('APARTMENT_NUMBER').' : '.osql($row['numero_appartement'])."<br />";
					 if($row['remarque']!='')
						echo getLang('COMMENT').' : '.osql($row['remarque'])."<br />"; ?>
                </p>
            </div>
            <div class="required field">
            	<label><?php showLang('TOTAL_WITH_TAX') ?></label>
                <p class="info"><?php echo $row['total_ttc'] ?> <?php showLang('CURRENCY') ?></p>
            </div>
            <div class="required field">
            	<label><?php showLang('PAYMENT_METHOD') ?></label>
                <p class="info"><?php echo $row['moyen_paiement'] ?></p>
            </div>
            <div class="required field">
            	<label><?php showLang('CUTLERY') ?></label>
                <p class="info"><?php echo $row['couverts']; ?></p>
            </div>
            <div class="required field">
            	<label><?php showLang('COMMENT') ?></label>
                <p class="info"><?php if($row['message'] && $row['message']!='') echo nl2br(osql($row['message'])) ?></p>
            </div>
            <div class="required field">
            	<label><?php showLang('MAP') ?></label>
                <p class="info"><a href="http://maps.google.fr/maps?f=d&source=s_d&saddr=186 avenue charles de gaulle+92200+NEUILLY SUR SEINE+France&daddr=<?php echo urlencode(osql($row['adresse1'])) ?>+<?php echo $row['cp'] ?>+<?php echo urlencode(osql($row['ville'])) ?>+France" target="_blank"><img src="images/map.png" /></a></p>
            </div>
            
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <td><?php showLang('PHOTO') ?></td>
                    <td><?php showLang('DISH') ?></td>
                    <td><?php showLang('QUANTITY') ?></td>
                    <td><?php showLang('UNIT_PRICE') ?></td>
                    <td><?php showLang('TOTAL_PRICE') ?></td>
                </thead>
                <tbody><?php
                    $query = "SELECT LC.*, P.name, P.thumbnail1 
                                FROM ligne_commande LC
                           LEFT JOIN plats P ON (LC.id_plat=P.id)
                               WHERE id_commande=".$id."
							     AND remise=0
                            ORDER BY id";
                    $result_details = $connector->query($query);
                    while($row_details = $connector->fetchArray($result_details))
                    { ?>
                        <tr>
                            <td><img src="../<?php echo $row_details['thumbnail1'] ?>" /></td>
                            <td><?php echo $row_details['name'] ?><div class="mezze_options"><?php echo $row_details['options'] ?></div></td>
                            <td><?php echo $row_details['quantite'] ?></td>
                            <td><?php echo showPriceCurrency($row_details['prix_ttc']) ?></td>
                            <td><?php echo showPriceCurrency($row_details['total_ttc']) ?></td>
                        </tr><?php
                    } 
					// Remises
					$query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$id." AND remise=1";
					$result_vouchers = $connector->query($query_vouchers);
					while($row_voucher = $connector->fetchArray($result_vouchers))
					{ ?>
						<tr>
							<td></td>
							<td><?php echo osql($row_voucher['description_remise']) ?></td>
							<td></td>
							<td></td>
							<td><?php echo showPriceCurrency($row_voucher['total_ttc']) ?></td>
						</tr><?php
					} 
					?> 
                </tbody>
                <tbody>
                	<tr>
                    	<td colspan="4" align="right"><strong><?php showLang('TOTAL_NO_TAX') ?></strong></td>
                        <td><?php echo showPriceCurrency($row['total_ht']) ?></td>
                    </tr>
                    <tr>
                    	<td colspan="4" align="right"><strong><?php showLang('TOTAL_WITH_TAX') ?></strong></td>
                        <td><?php echo showPriceCurrency($row['total_ttc']) ?></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </form>
</div>