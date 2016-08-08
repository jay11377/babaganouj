 <?php
require_once('includes/fr.php');
require_once('includes/config.php');
require_once('includes/Sentry.php');
require_once('includes/DbConnector.php');

$connector = new DbConnector();

$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];
$date_fin_7 = date('Y-m-d', strtotime("-7days"));

$i=1;
$query = "SELECT C.id AS id_commande, C.date, C.total_ttc, C.message, C.date_livraison, C.creneau_livraison, C.heure_livraison, C.id_statut, C.par_telephone, C.id_client, CL.email, S.statut, O.name as moyen_paiement, A.societe, A.prenom, A.nom, A.adresse1, A.adresse2, A.cp, A.ville, A.telephone 
			FROM commandes C
	   LEFT JOIN clients CL ON C.id_client=CL.id
	   LEFT JOIN statut_commande S ON C.id_statut=S.id
	   LEFT JOIN order_methods O ON C.id_moyen_paiement=O.id
	   LEFT JOIN adresses A ON C.adresse_livraison=A.id
		   WHERE C.id_statut>1 ";
if($date_fin_7>$date_fin) // date a plus d'une semaine, on ne montre pas les commandes futures
	$query.= "AND C.date_livraison>='".$date_debut."' AND C.date_livraison<='".$date_fin."' ";
else{
	if($date_debut==$date_fin) // Si une seule date d�lectionn�e � moins d'une semaine, on montre toutes les commandes futures
		$query.= "AND ((C.date_livraison='".$date_debut."') OR (C.date_livraison>'".$date_debut."' AND C.id_statut=2)) ";
	else // Si intervalle de date � moins d'une semaine, on montre tous les commandes livr�e dans l'intervalle de date, mais on masque les commandes futures � ces dates
		$query.= "AND C.date_livraison>='".$date_debut."' AND C.date_livraison<='".$date_fin."' AND C.id_statut>1 ";	
}
$query.="ORDER BY id_commande DESC";

$result = $connector->query($query);
while ($row = $connector->fetchArray($result)){ 
	//$class = ($i%2==0)?"pair":""; 
	$class = "status".$row['id_statut'];
	// Exception pour les commandes programm�es
	if($row['id_statut']<3 && dateNoTime($row['date'])!=$row['date_livraison'])
		$class = "status_future"
?>
    <tr class="<?php echo $class; ?>">
		<td>
            <a href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id_commande']; ?>" title="<?php showLang('EDIT') ?>"><?php echo osql($row['id_commande']); ?></a><br /><br />
			<?php showLang('ON_THE') ?><br /><span class="black11bold"><?php echo dateLongENtoFR($row['date']) ?></span>
		</td>
        <td><?php
			if($row['par_telephone']==1) : ?>
			   <img src="images/phone.png" /><?php
			elseif($row['par_telephone']==0 && hasVoucher($row['id_commande'])) : ?>
            	<img src="images/internet.png" /><img src="images/icone_promo.png" /><?php
			elseif($row['par_telephone']==0) : ?>
            	<img src="images/internet.png" /><?php
			elseif($row['par_telephone']==2 && hasVoucher($row['id_commande'])) : ?>
            	<img src="images/cellphone.png" /><img src="images/icone_promo.png" /><?php
            else : ?>
            	<img src="images/cellphone.png" /><?php
			endif;
            if(!(alreadyClient($row['id_client']))) : ?>
            	<img src="images/new.gif" /><?php
            endif; ?>
        </td>
        <td><?php echo osql($row['prenom']) ?> <?php echo osql($row['nom']) ?><br />
			<?php echo osql($row['telephone']) ?></td>
		<td><?php
			if($row['societe']!='')
                echo osql($row['societe'])."<br />"; ?>
             <?php echo osql($row['adresse1']) ?><br /><?php
             if($row['adresse2']!='')
                echo osql($row['adresse2'])."<br />"; ?>
             <?php echo $row['cp'] ?> <?php echo osql($row['ville']) ?><br /><?php
			 if(isset($row['code_entree']) && $row['code_entree']!='')
                echo getLang('ENTRY_CODE').' : '.osql($row['code_entree'])."<br />";
			 if(isset($row['interphone']) && $row['interphone']!='')
                echo getLang('INTERCOM').' : '.osql($row['interphone'])."<br />";
			 if(isset($row['service']) && $row['service']!='')
                echo getLang('SERVICE').' : '.osql($row['service'])."<br />";
			 if(isset($row['escalier']) && $row['escalier']!='')
                echo getLang('STAIRCASE').' : '.osql($row['escalier'])."<br />";
			 if(isset($row['etage']) && $row['etage']!='')
                echo getLang('FLOOR').' : '.osql($row['etage'])."<br />";
			 if(isset($row['numero_appartement']) && $row['numero_appartement']!='')
                echo getLang('APARTMENT_NUMBER').' : '.osql($row['numero_appartement'])."<br />";
			 if(isset($row['remarque']) && $row['remarque']!='')
                echo getLang('COMMENT').' : '.osql($row['remarque']);
			?>
		</td>
        <td>
			<span class="black11bold"><?php echo dateENtoFR($row['date_livraison']) ?></span><br />
            <?php showLang('DELIVERY_ASKED_TIME'); echo " : ".$row['creneau_livraison'] ?><br />
            <?php 
			showLang('DELIVERY_REAL_TIME_SHORT'); echo " : "; ?><span class="black11bold"><?php echo ($row['heure_livraison'] && $row['heure_livraison']!='')?$row['heure_livraison'] : '<input type="text" class="small_input_text" name="heure_livraison" /> <input type="button" class="small_button time_button" name="'.$row['id_commande'].'" value="ok" />' ?></span></td><?php
			?>
            <?php 
			/*
			showLang('DELIVERY_REAL_TIME_SHORT'); echo " : "; ?><span class="black11bold"><?php echo ($row['heure_livraison'] && $row['heure_livraison']!='')?$row['heure_livraison'] : '<input type="text" class="small_input_text time_field" title="'.$row['id_commande'].'" name="heure_livraison" />' ?></span></td>
			*/
			?>
        <td>
        	<select class="small_select" name="statut_commande"><?php 
				$result_statut = $connector->query("SELECT * FROM statut_commande WHERE id!=1 ORDER BY id");
				while($row_statut = $connector->fetchArray($result_statut)){ ?>
					<option value="<?php echo $row_statut['id']?>" <?php if($row_statut["id"] == $row['id_statut']) echo 'selected="selected"'?> >
						<?php echo $row_statut['statut'] ?>
					</option><?php 
				} ?>
			</select> <input type="button" class="small_button statut_select" name="<?php echo $row['id_commande']; ?>" value="ok" />
        </td>
        <td><?php echo $row['total_ttc'] ?> <?php showLang('CURRENCY') ?><br /><?php echo str_replace('_', ' ', $row['moyen_paiement']) ?></td>
		<td><a href="http://maps.google.fr/maps?f=d&source=s_d&saddr=27 voie des sculpteurs+92800+PUTEAUX+France&daddr=<?php echo urlencode(osql($row['adresse1'])) ?>+<?php echo $row['cp'] ?>+<?php echo urlencode(osql($row['ville'])) ?>+France" target="_blank"><img src="images/map_small.png" /></a></td>
		<td>
            <!--
            <a class="image" href="moduleinterface.php?module=commandes&action=edit&id=<?php echo $row['id_commande']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
            -->
            <a class="image" href="moduleinterface.php?module=nouvelle_commande&action=edit&id=<?php echo $row['id_commande']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
			<a class="image" id="print<?php echo $row['id_commande'] ?>" href="print.php?id=<?php echo $row['id_commande'] ?>" title="<?php showLang('PRINT') ?>" target="_blank"><img src="images/print.png" alt="<?php showLang('PRINT') ?>" /></a>
        </td>
	</tr>
<?php
	$i++;
 } 
?>