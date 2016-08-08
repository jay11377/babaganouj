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
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=default"><?php showLang('BACK_TO_ORDERS_LIST') ?></a>
    </div>
    <div class="container"><?php	
		if(isset($_GET['id'])){
			$id=$_GET['id'];
		}
		else if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		$statut_commande = (isset($_POST['statut_commande']))?$_POST['statut_commande']:'';
		$delivery_h = (isset($_POST['delivery_h']))?$_POST['delivery_h']:'';
		$delivery_m = (isset($_POST['delivery_m']))?$_POST['delivery_m']:'';
		
		$connector = new DbConnector();
		
		$query = "SELECT C.id AS id_commande, C.date, C.total_ht, C.total_ttc, C.couverts, C.message, C.date_livraison, C.creneau_livraison, C.heure_livraison, CL.email, S.id as statut_id, S.statut, O.name as moyen_paiement, A.societe, A.prenom, A.nom, A.adresse1, A.adresse2, A.cp, A.ville, A.telephone, A.code_entree, A.interphone, A.service, A.escalier, A.etage, A.numero_appartement, A.remarque
					FROM commandes C
			   LEFT JOIN clients CL ON C.id_client=CL.id
			   LEFT JOIN statut_commande S ON C.id_statut=S.id
			   LEFT JOIN order_methods O ON C.id_moyen_paiement=O.id
			   LEFT JOIN adresses A ON C.adresse_livraison=A.id
				   WHERE C.id=".$id;
		$result = $connector->query($query);
		$row=$connector->fetchArray($result);
		$result_statut = $connector->query("SELECT * FROM statut_commande WHERE id!=1 ORDER BY id");
		// Check whether a form has been submitted. If so, carry on
		if( isset($_POST['email_client']) && $_POST['email_client']==getLang('SEND_EMAIL')){
			// Send email
			$delivery_time = $delivery_h."h".$delivery_m;
			if(email_client($id, $delivery_time, $row['date_livraison'])){
				$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('EMAIL_SUCCESS').'</p></div>';
				header( 'Location: moduleinterface.php?module='.$module.'&action=edit&id='.$id );
			}
			else{ ?>
				 <script>
					 $('#msgbox').html("<div class=\"msg msg-error\"><p><?php showLang('EMAIL_FAILURE') ?></p></div>");
					 $('#msgbox').show();
				 </script><?php
			}
		}
		else if( isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Update
			$query = "UPDATE $module 
						 SET id_statut=".$statut_commande."
						 WHERE id =". $id;
			if ($result = $connector->query($query)){
            	$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('ORDER_UPDATED').'</p></div>';
				header( 'Location: moduleinterface.php?module='.$module.'&action=edit&id='.$id );
			}else{ ?>
				<script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR')?></p></div>');
					 $('#msgbox').show();
				</script><?php
			}
		}
		
		// On verifie si la livraison doit avoir lieu aujourd'hui
		if($row['date_livraison']!=date('Y-m-d'))
			$decale = 1;
		else
			$decale = 0;
		// Display the form
		require_once("templates/form.php"); ?>
    </div>
</div>
