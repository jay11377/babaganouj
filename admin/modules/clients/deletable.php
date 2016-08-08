<?php
// Create an instance of DbConnector
$connector = new DbConnector();



$now = date('Y-m-d G:i:s');
$nbdays = 90;

$query = '
SELECT a.id, a.nom, a.prenom, a.email, a.newsletter, COUNT(c.id) AS nb_commandes, DATEDIFF(\''.$now.'\',c.date) as nbdays 
FROM clients a 
LEFT JOIN commandes c ON a.id=c.id_client 
WHERE c.id = (SELECT MAX(id) FROM commandes WHERE id_statut=4 AND id_client=a.id)
  AND DATEDIFF(\''.$now.'\',c.date)>'.$nbdays.'
GROUP BY a.id 
ORDER BY nom'
$result = $connector->query($query);
$pagetitle=getLang('CUSTOMERS')." (".$connector->getNumRows($result).")";


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
    
     <div class="required field">
        <label for="client"><?php showLang('CLIENT_NAME') ?></label>
        <input type="text" value="" id="client" name="client">
    </div>
    <div class="container">
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$.post(
			"ajax_request.php", 
			{clientStartsWith : '', request : 'clientsTable'}, 
			function(data){
				$('.container').html(data.msg);
			},
			"json"
	  );
	$("#client").live('keyup', function() {
		 $.post(
				"ajax_request.php", 
				{clientStartsWith : $(this).val(), request : 'clientsTable'}, 
				function(data){
					$('.container').html(data.msg);
				},
				"json"
		  );
	});
	
	$(".clientHistory").live('click',function(e){
		e.preventDefault();
		var id_client = $(this).attr("rel")
		if($.browser.msie)
			$("#bgOverlay").show();
		else
			$("#bgOverlay").fadeIn(300);	
		 $.post(
				"ajax_request.php", 
				{id_client : id_client, request : 'ordersList'}, 
				function(data){
					//$('.container').html(data.msg);
					$("#ordersOverlay").html(data.msg);
					var pos=$(window).scrollTop() + 'px';
					 $("#ordersOverlay").css({'top':pos});
					 $("#ordersOverlay").show();
					 $("#ordersOverlay .ordersWrapper").show();
					 //$("#ordersOverlay .ordersWrapper").slideDown({duration: 1000, easing:'easeOutCubic'});
					},
					"json"
		  );
		return false;
	});
	
	$("#ordersOverlay .closeOrders a").live('click',function(e){
		//mapCitiesInfoDeployed=false;
		e.preventDefault();
		$("#ordersOverlay .ordersWrapper").slideUp(400,function(){
			$("#ordersOverlay").hide();
			if($.browser.msie)
				$("#bgOverlay").hide();
			else
				$("#bgOverlay").fadeOut({duration: 600, easing:'easeOutCubic'});									   	
		});
		return false;
	});
	
	$(".details_commande").live("click", function(e){
		e.preventDefault();
		$tr = $(this).parent().parent().next(".commande_details_tr");
		var id_commande_clicked = $(this).parent().next(".reorder").find(".meme_commande").attr("name");  
		var id_commande_deployed = $(".commande_details_tr:visible").prev().find(".meme_commande").attr("name");
		// Si on clique sur le détails qui est déjà déployé
		if(id_commande_clicked==id_commande_deployed){
			$(".commande_details_tr:visible div").slideUp(500, function(){
				$(this).hide();
				$(this).parent().parent().hide();
			});
		}
		else{
			if($(".commande_details_tr:visible div").length){
				$(".commande_details_tr:visible div").slideUp(300, function(){
					$(this).hide();
					$(this).parent().parent().hide();
					$tr.show();
					$tr.find("div").slideDown(600);
				});
			}
			else{
				$tr.show();
				$tr.find("div").slideDown(700);
			}
		}
		return false;
	});
	
});
</script>