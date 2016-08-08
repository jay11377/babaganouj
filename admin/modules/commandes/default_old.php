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
                 <div id="table_stats_global">
                    <table cellpadding="0" cellspacing="2" border="0">
                        <tr>
                        	<th width="40"></th>
                            <th><?php showLang('TOTAL_DELIVERED_ORDERS') ?></th>
                            <th><?php showLang('NUMBER_OF_DELIVERED_ORDERS') ?></th>
                            <th><?php showLang('AVERAGE_CART') ?></th>
                        </tr>
                        <tr>
                        	<td><img src="images/internet.png" /></td>
                            <td id="total_delivered_orders_internet" class="stats_data"></td>
                            <td id="number_of_delivered_orders_internet" class="stats_data"></td>
                            <td id="average_cart_internet" class="stats_data"></td>
                        </tr>
                        <tr>
                        	<td><img src="images/phone.png" /></td>
                            <td id="total_delivered_orders_telephone" class="stats_data"></td>
                            <td id="number_of_delivered_orders_telephone" class="stats_data"></td>
                            <td id="average_cart_telephone" class="stats_data"></td>
                        </tr>
                         <tr>
                        	<td><img src="images/cellphone.png" /></td>
                            <td id="total_delivered_orders_cellphone" class="stats_data"></td>
                            <td id="number_of_delivered_orders_cellphone" class="stats_data"></td>
                            <td id="average_cart_cellphone" class="stats_data"></td>
                        </tr>
                        <tr>
                        	<td><?php showLang('TOTAL') ?></td>
                            <td id="total_delivered_orders" class="stats_data"></td>
                            <td id="number_of_delivered_orders" class="stats_data"></td>
                            <td id="average_cart" class="stats_data"></td>
                        </tr>
                    </table>
                 </div>
                 <div id="show_table_details"><a href="#"><?php showLang('DETAILS') ?></a></div>
                <div id="table_details"></div>
         </div>
         <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('ORDER_ID') ?></td>
                    <td width="50">&nbsp;</td>
                    <td><?php showLang('LAST_NAME') ?></td>
                    <td><?php showLang('ADDRESS') ?></td>
                    <td><?php showLang('DELIVERY_DAY_TIME') ?></td>
                    <td><?php showLang('STATUS') ?></td>
                    <td><?php showLang('TOTAL_WITH_TAX') ?></td>
                    <td><?php showLang('MAP') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody id="orders_list_body"></tbody>
            <tfoot>
            	<tr>
                	<td colspan="9">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
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