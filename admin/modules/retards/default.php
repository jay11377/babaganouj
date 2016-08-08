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
         <div class="margin10"><?php showLang('DATE_RANGE_LATE') ?></div>
         <div class="margin10 left"><p id="date"></p></div>
         <div class="margin10 left">
         		<div class="status_color left late_0">&nbsp;</div>
                <div class="left status_color_text "><?php showLang('LATE_0') ?></div>
				<div class="clear" style="height:5px"></div>
				<div class="status_color left late_10">&nbsp;</div>
                <div class="left status_color_text "><?php showLang('LATE_10') ?></div>
				<div class="clear" style="height:5px"></div>
                <div class="status_color left late_20">&nbsp;</div>
                <div class="left status_color_text "><?php showLang('LATE_20') ?></div>
				<div class="clear" style="height:5px"></div>
                <div class="status_color left late_30">&nbsp;</div>
                <div class="left status_color_text "><?php showLang('LATE_30') ?></div>
				<div class="clear" style="height:5px"></div>
                <div class="status_color left late_40">&nbsp;</div>
                <div class="left status_color_text "><?php showLang('LATE_40') ?></div>
				<div class="clear" style="height:5px"></div>
         </div>
         <div class="clear"></div>
         <div style="padding:15px; color:#F00; font-weight:bold; font-size:16px;">
         	<?php showLang('LATE_TOTAL') ?> : <span id="total_retard"></span>
         </div>
         <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('ORDER_ID') ?></td>
                    <td width="50">&nbsp;</td>
                    <td><?php showLang('LAST_NAME') ?></td>
                    <td><?php showLang('ADDRESS') ?></td>
                    <td><?php showLang('DELIVERY_DAY_TIME') ?></td>
                    <td><?php showLang('LATE') ?></td>
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
	
	$.post(
		"ajax_update_late.php", 
		{date_debut : today, date_fin : today}, 
		function(data){
			  $("#orders_list_body").html(data);
		}
	);
	
	$.post(
		"ajax_request.php", 
		{date_debut : today, date_fin : today, request : 'total_retard'}, 
		function(data){
			// Total des retards
			$("#total_retard").html(data.total_retard);
		},
		"json"
	);
	
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
				"ajax_update_late.php", 
				{date_debut : formated[0], date_fin : formated[1]}, 
				function(data){
					  $("#orders_list_body").html(data);
				}
			);
			$.post(
				"ajax_request.php", 
				{date_debut : formated[0], date_fin : formated[1], request : 'total_retard'}, 
				function(data){
					// Total des retards
					$("#total_retard").html(data.total_retard);
				},
				"json"
			);
		}
	});
});
</script>