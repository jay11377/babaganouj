<?php
$pagetitle=ucfirst($module);
$date_value = date("d/m/Y");

// Display a message
if(isset($_SESSION['pagemsg']))
{
	echo '<div class="msgbox">';
	echo $_SESSION['pagemsg'];
	echo '</div>';
	unset($_SESSION['pagemsg']);
}
?>

<div class="box">
	<div class="header">
    	<h3><?php echo $pagetitle; ?></h3>
    </div>
    <div class="container" id="orders_list">
         <div class="margin10"><?php showLang('DATE_RANGE') ?></div>
         <div class="margin10 left"><p id="date"></p></div>
         <div class="margin10 left">
         	<?php
            	$connector = new DbConnector();
				$query = "SELECT * FROM statut_commande WHERE id>1 ORDER BY id";
				$result = $connector->query($query);
				while ($row = $connector->fetchArray($result)){  
					$class = "status".$row['id']; ?>
                    <div class="status_color left <?php echo $class ?>">&nbsp;</div>
                    <div class="left status_color_text "><?php echo osql($row['statut']) ?></div>
					<div class="clear" style="height:5px"></div><?php
				}
				?>
				<div class="status_color left status_future">&nbsp;</div>
                <div class="left status_color_text "><?php showLang('FUTURE_ORDER') ?></div>
				<div class="clear" style="height:5px"></div>
         </div>
         <div class="clear"></div>
         
         <div id="stats">
         	<div id="date_interval"></div>
         </div>
         
         <div id="stats_table">
             <!--Modifié par Grégory-->
             <h3><?php showLang('BEST_ORDER')?></h3>
             <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th><?php showLang('NUMBER_SHORT');?></th>
                        <th><?php showLang('DISH');?></th>
                        <th><?php showLang('NUMBER_OF_DISHES_ORDERED');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $debut="2014-03-01";
                    $fin="2014-03-31";
                    $query="SELECT plats.name, SUM( quantite ) AS nbCommandes
    FROM commandes, ligne_commande, plats
    WHERE commandes.id = ligne_commande.id_commande
    AND ligne_commande.id_plat = plats.id
    AND id_statut =4
    AND date_livraison BETWEEN '".$debut."' AND '".$fin."'
    GROUP BY id_plat
    ORDER BY nbCommandes DESC
    LIMIT 0 , 20";
                    $result=$connector->query($query);
                    $i=1;
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t".'<tr style="background:'.(($i%2) ? '#eee' : '#f9f9f9').'">
                        <td width="20">'.$i.'</td>
                        <td>'.osql($row['name']).'</td>
                        <td>'.$row['nbCommandes'].'</td>
                    </tr>';
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="3"><?php showLang('BEST_CUSTOMER_ORDER')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th></th>
                        <th><?php showLang('NAMES');?></th>
                        <th><?php showLang('NUMBER_OF_DELIVERED_ORDERS');?></th>
                    </tr>
                    <?php 
                    $debut="2014-03-01";
                    $fin="2014-03-31";
                    $query="SELECT CONCAT( nom, \" \", prenom ) AS nomPrenom, COUNT( * ) AS nbCommandes
    FROM commandes, clients
    WHERE commandes.id_client = clients.id
    AND id_statut =4
    AND date_livraison BETWEEN '".$debut."' AND '".$fin."'
    GROUP BY id_client
    ORDER BY nbCommandes DESC
    LIMIT 0 , 20";
                    $result=$connector->query($query);
                    $i=1;
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t<tr>
                        <td width=\"20\">".$i."</td>
                        <td>".$row['nomPrenom']."</td>
                        <td>".$row['nbCommandes']."</td>
                    </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="3"><?php showLang('BEST_CUSTOMER_PRICE')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th></th>
                        <th><?php showLang('NAMES');?></th>
                        <th><?php showLang('TOTAL_PRICE');?></th>
                    </tr>
                    <?php 
                    $debut="2014-03-01";
                    $fin="2014-03-31";
                    $query="SELECT CONCAT( nom, \" \", prenom ) AS nomPrenom,  SUM(total_ttc) AS totalCommandes
    FROM commandes, clients
    WHERE commandes.id_client = clients.id
    AND id_statut =4
    AND date_livraison BETWEEN '".$debut."' AND '".$fin."'
    GROUP BY id_client
    ORDER BY totalCommandes DESC
    LIMIT 0 , 20";
                    $result=$connector->query($query);
                    $i=1;
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t<tr>
                        <td width=\"20\">".$i."</td>
                        <td>".$row['nomPrenom']."</td>
                        <td>".$row['totalCommandes']."</td>
                    </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="3"><?php showLang('BEST_CITY_ORDER')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th></th>
                        <th><?php showLang('CITY');?></th>
                        <th><?php showLang('NUMBER_OF_DELIVERED_ORDERS');?></th>
                    </tr>
                    <?php 
                    $debut="2014-03-01";
                    $fin="2014-03-31";
                    $query="SELECT ville, count( * ) AS nb_commandes
    FROM commandes, adresses
    WHERE commandes.adresse_livraison = adresses.id
    AND date_livraison BETWEEN '".$debut."' AND '".$fin."'
    AND id_statut=4
    GROUP BY ville
    ORDER BY nb_commandes DESC
    LIMIT 0 , 20";
                    $result=$connector->query($query);
                    $i=1;
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t<tr>
                        <td width=\"20\">".$i."</td>
                        <td>".$row['ville']."</td>
                        <td>".$row['nb_commandes']."</td>
                    </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            
             <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="3"><?php showLang('BEST_CITY_PRICE')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th></th>
                        <th><?php showLang('CITY');?></th>
                        <th><?php showLang('TOTAL_PRICE');?></th>
                    </tr>
                    <?php 
                    $debut="2014-03-01";
                    $fin="2014-03-31";
                    $query="SELECT ville, sum( total_ttc ) AS total
    FROM commandes, adresses
    WHERE commandes.adresse_livraison = adresses.id
    AND date_livraison BETWEEN '".$debut."' AND '".$fin."'
    GROUP BY ville
    ORDER BY total DESC
    LIMIT 0 , 20";
                    $result=$connector->query($query);
                    $i=1;
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t<tr>
                        <td width=\"20\">".$i."</td>
                        <td>".$row['ville']."</td>
                        <td>".$row['total']."</td>
                    </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="2"><?php showLang('SALES_BY_MONTH')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?php showLang('MONTH');?></th>
                        <th><?php showLang('INCOME');?></th>
                    </tr>
                    <?php 
                    $debut="2013-01-01";
                    $fin="2013-12-31";
                    $query="SELECT sum( total_ttc ) AS total , DATE_FORMAT(date_livraison, '%m/%Y') AS mois
    FROM commandes
    WHERE id_statut =4
    AND date_livraison >= '".$debut."'
    AND date_livraison <= '".$fin."'
    GROUP BY MONTH( date_livraison )
    ORDER BY date_livraison";
                    $result=$connector->query($query);
                    $CAparMois=array();
                    $maxTotal=0;
                    $minTotal=3000000;
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t<tr>
                        <td>".$row['mois']."</td>
                        <td>".$row['total']."</td>
                    </tr>";
                        $CAparMois[$row['mois']]=$row['total'];
                        if($row['total']>$maxTotal){
                            $maxTotal=$row['total'];
                        }
                        if($row['total']<$minTotal){
                            $minTotal=$row['total'];
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
                   
            
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="2"><?php showLang('SALES_BY_WEEK')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?php showLang('WEEK');?></th>
                        <th><?php showLang('INCOME');?></th>
                    </tr>
                    <?php 
                    $debut="2013-05-01";
                    $fin="2014-04-30";
                    $query="SELECT sum(total_ttc) AS total, date_livraison, DATE_FORMAT((subdate(date_livraison, interval (dayofweek(date_livraison)-2)day)),'%d/%m/%y') AS lundi
    FROM commandes
    WHERE id_statut =4
    AND date_livraison >= '".$debut."'
    AND date_livraison <= '".$fin."'
    GROUP BY WEEK( date_livraison, 7 )
    ORDER BY date_livraison";
                    $result=$connector->query($query);
                    while($row=$connector->fetchArray($result)){
                        echo "\n\t\t\t\t<tr>
                        <td>".$row['lundi']."</td>
                        <td>".$row['total']."</td>
                    </tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!--Fin modification-->
	</div>
</div>


<script>
$(document).ready(function(){
	var today = '<?php echo date("Y-m-d") ?>';
	
	$("#show_table_details a").click(function(e){
		e.preventDefault();
		$("#table_details").toggle();
		return false;
	});
	
	$.post(
		"ajax_update_orders.php", 
		{date_debut : today, date_fin : today}, 
		function(data){
			  $("#orders_list_body").html(data);
		}
	);
	
	$.post(
		"ajax_request.php", 
		{date_debut : today, date_fin : today, request : 'paymentStats'}, 
		function(data){
			// internet
			$("#total_delivered_orders_internet").html(data.total_delivered_orders_internet);
			$("#number_of_delivered_orders_internet").html(data.number_of_delivered_orders_internet);
			$("#average_cart_internet").html(data.average_cart_internet);
			// telephone
			$("#total_delivered_orders_telephone").html(data.total_delivered_orders_telephone);
			$("#number_of_delivered_orders_telephone").html(data.number_of_delivered_orders_telephone);
			$("#average_cart_telephone").html(data.average_cart_telephone);
			// portable
			$("#total_delivered_orders_cellphone").html(data.total_delivered_orders_cellphone);
			$("#number_of_delivered_orders_cellphone").html(data.number_of_delivered_orders_cellphone);
			$("#average_cart_cellphone").html(data.average_cart_cellphone);
			// total
			$("#total_delivered_orders").html(data.total_delivered_orders);
			$("#number_of_delivered_orders").html(data.number_of_delivered_orders);
			$("#average_cart").html(data.average_cart);
			
			$("#table_details").html(data.table_details);
			$("#date_interval").html(data.date_interval);
			if(parseInt(data.number_of_delivered_orders) > 0)
				$("#show_table_details").show();
			else
			{
				$("#show_table_details").hide();
				$("#table_details").hide();
			}
		},
		"json"
	);
	
	// Recharger la liste des commandes toutes les 30 secondes
	$("body").everyTime(30000, "updateOrders", function() {
		$.post(
			"ajax_update_orders.php", 
			{date_debut : $('#date').DatePickerGetDate(true)[0], date_fin : $('#date').DatePickerGetDate(true)[1]}, 
			function(data){
				  $("#orders_list_body").html(data);
			}
		);
	});
	
	// Changer le statut de commande
	$('.statut_select').live("click",function(){
		var id_statut = $(this).parent().find("select").val();
		var id_commande = $(this).attr("name");
		$.post(
				"ajax_request.php", 
				{id_statut : id_statut, id_commande : id_commande, request : 'updateStatus'}, 
				function(data){
					if(data.msg!="")
						alert(data.msg);
					else{
						$.post(
							"ajax_update_orders.php", 
							{date_debut : $('#date').DatePickerGetDate(true)[0], date_fin : $('#date').DatePickerGetDate(true)[1]}, 
							function(data){
								  $("#orders_list_body").html(data);
							}
						);
						$.post(
							"ajax_request.php", 
							{date_debut : today, date_fin : today, request : 'paymentStats'}, 
							function(data){
								// internet
								$("#total_delivered_orders_internet").html(data.total_delivered_orders_internet);
								$("#number_of_delivered_orders_internet").html(data.number_of_delivered_orders_internet);
								$("#average_cart_internet").html(data.average_cart_internet);
								// telephone
								$("#total_delivered_orders_telephone").html(data.total_delivered_orders_telephone);
								$("#number_of_delivered_orders_telephone").html(data.number_of_delivered_orders_telephone);
								$("#average_cart_telephone").html(data.average_cart_telephone);
								// portable
								$("#total_delivered_orders_cellphone").html(data.total_delivered_orders_cellphone);
								$("#number_of_delivered_orders_cellphone").html(data.number_of_delivered_orders_cellphone);
								$("#average_cart_cellphone").html(data.average_cart_cellphone);
								// total
								$("#total_delivered_orders").html(data.total_delivered_orders);
								$("#number_of_delivered_orders").html(data.number_of_delivered_orders);
								$("#average_cart").html(data.average_cart);
								
								$("#table_details").html(data.table_details);
								$("#date_interval").html(data.date_interval);
								if(parseInt(data.number_of_delivered_orders) > 0)
									$("#show_table_details").show();
								else
								{
									$("#show_table_details").hide();
									$("#table_details").hide();
								}
							},
							"json"
						);
					}
				},
				"json"
			);
	});
	
	// Changer le statut de commande
	
	$('.time_field').live('keyup', function(e) {
	  if(e.keyCode == 13) {
			var heure_livraison = $(this).val();
			var id_commande = $(this).attr("title");
			
			$.post(
					"ajax_request.php", 
					{heure_livraison : heure_livraison, id_commande : id_commande, request : 'updateTime'}, 
					function(data){
						if(data.msg!="")
							alert(data.msg);
						else{
							$.post(
								"ajax_update_orders.php", 
								{date_debut : $('#date').DatePickerGetDate(true)[0], date_fin : $('#date').DatePickerGetDate(true)[1]}, 
								function(data){
									  $("#orders_list_body").html(data);
								}
							);
							$.post(
								"ajax_request.php", 
								{date_debut : today, date_fin : today, request : 'paymentStats'}, 
								function(data){
									// internet
									$("#total_delivered_orders_internet").html(data.total_delivered_orders_internet);
									$("#number_of_delivered_orders_internet").html(data.number_of_delivered_orders_internet);
									$("#average_cart_internet").html(data.average_cart_internet);
									// telephone
									$("#total_delivered_orders_telephone").html(data.total_delivered_orders_telephone);
									$("#number_of_delivered_orders_telephone").html(data.number_of_delivered_orders_telephone);
									$("#average_cart_telephone").html(data.average_cart_telephone);
									// portable
									$("#total_delivered_orders_cellphone").html(data.total_delivered_orders_cellphone);
									$("#number_of_delivered_orders_cellphone").html(data.number_of_delivered_orders_cellphone);
									$("#average_cart_cellphone").html(data.average_cart_cellphone);
									// total
									$("#total_delivered_orders").html(data.total_delivered_orders);
									$("#number_of_delivered_orders").html(data.number_of_delivered_orders);
									$("#average_cart").html(data.average_cart);
									
									$("#table_details").html(data.table_details);
									$("#date_interval").html(data.date_interval);
									if(parseInt(data.number_of_delivered_orders) > 0)
										$("#show_table_details").show();
									else
									{
										$("#show_table_details").hide();
										$("#table_details").hide();
									}
								},
								"json"
							);
						}
					},
					"json"
				);
      }
	});
	
	/*
	$('.time_button').live("click",function(){
		var heure_livraison = $(this).parent().find('.small_input_text').val();
		var id_commande = $(this).attr("name");

		$.post(
				"ajax_request.php", 
				{heure_livraison : heure_livraison, id_commande : id_commande, request : 'updateTime'}, 
				function(data){
					if(data.msg!="")
						alert(data.msg);
					else{
						$.post(
							"ajax_update_orders.php", 
							{date_debut : $('#date').DatePickerGetDate(true)[0], date_fin : $('#date').DatePickerGetDate(true)[1]}, 
							function(data){
								  $("#orders_list_body").html(data);
							}
						);
						$.post(
							"ajax_request.php", 
							{date_debut : today, date_fin : today, request : 'paymentStats'}, 
							function(data){
								// internet
								$("#total_delivered_orders_internet").html(data.total_delivered_orders_internet);
								$("#number_of_delivered_orders_internet").html(data.number_of_delivered_orders_internet);
								$("#average_cart_internet").html(data.average_cart_internet);
								// telephone
								$("#total_delivered_orders_telephone").html(data.total_delivered_orders_telephone);
								$("#number_of_delivered_orders_telephone").html(data.number_of_delivered_orders_telephone);
								$("#average_cart_telephone").html(data.average_cart_telephone);
								// portable
								$("#total_delivered_orders_cellphone").html(data.total_delivered_orders_cellphone);
								$("#number_of_delivered_orders_cellphone").html(data.number_of_delivered_orders_cellphone);
								$("#average_cart_cellphone").html(data.average_cart_cellphone);
								// total
								$("#total_delivered_orders").html(data.total_delivered_orders);
								$("#number_of_delivered_orders").html(data.number_of_delivered_orders);
								$("#average_cart").html(data.average_cart);
								
								$("#table_details").html(data.table_details);
								$("#date_interval").html(data.date_interval);
								if(parseInt(data.number_of_delivered_orders) > 0)
									$("#show_table_details").show();
								else
								{
									$("#show_table_details").hide();
									$("#table_details").hide();
								}
							},
							"json"
						);
					}
				},
				"json"
			);
	});
	*/
	
	$('#date').DatePicker({
		flat: true,
		//format:'d/m/Y',
		date: [today,today],
		current: today,
		calendars: 2,
		mode: 'range',
		starts: 1,
		onChange: function(formated, dates){
			$.post(
				"ajax_update_orders.php", 
				{date_debut : formated[0], date_fin : formated[1]}, 
				function(data){
					  $("#orders_list_body").html(data);
				}
			);
			$.post(
				"ajax_request.php", 
				{date_debut : formated[0], date_fin : formated[1], request : 'paymentStats'}, 
				function(data){
					// internet
					$("#total_delivered_orders_internet").html(data.total_delivered_orders_internet);
					$("#number_of_delivered_orders_internet").html(data.number_of_delivered_orders_internet);
					$("#average_cart_internet").html(data.average_cart_internet);
					// telephone
					$("#total_delivered_orders_telephone").html(data.total_delivered_orders_telephone);
					$("#number_of_delivered_orders_telephone").html(data.number_of_delivered_orders_telephone);
					$("#average_cart_telephone").html(data.average_cart_telephone);
					// portable
					$("#total_delivered_orders_cellphone").html(data.total_delivered_orders_cellphone);
					$("#number_of_delivered_orders_cellphone").html(data.number_of_delivered_orders_cellphone);
					$("#average_cart_cellphone").html(data.average_cart_cellphone);
					// total
					$("#total_delivered_orders").html(data.total_delivered_orders);
					$("#number_of_delivered_orders").html(data.number_of_delivered_orders);
					$("#average_cart").html(data.average_cart);
					
					$("#table_details").html(data.table_details);
					$("#date_interval").html(data.date_interval);
					if(parseInt(data.number_of_delivered_orders) > 0)
						$("#show_table_details").show();
					else
					{
						$("#show_table_details").hide();
						$("#table_details").hide();
					}
				},
				"json"
			);
		}
	});
});
</script>