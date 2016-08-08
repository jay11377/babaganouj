<script type="text/javascript">
	/* function autocomplete */
	$(function() {
			$('#client').autocomplete({
					source: function(request, response) {
							$.post(
									"ajax_request.php", 
									{clientStartsWith : request.term, request : 'customerList'}, 
									function(data){
										response($.map(data, function(item) {
											return {
												label: item.nom_prenom_id,
												value: item.nom_prenom_id,
												id_client: item.id_client
											}
										}))
									},
									"json"
							);
					},
					select: function(event, ui) {
						  $('#id_client').val(ui.item.id_client);
						  $.post(
									"ajax_request.php", 
									{id_client : ui.item.id_client, request : 'adressesList'}, 
									function(data){
										$('#adresses_block').html(data.msg);
									},
									"json"
						  );
                	}
			});
			$('.set_shipping_address').live('click', function(e){
				e.preventDefault();
				var id_adresse = parseInt($(this).attr("rel"));
				$.post(
						"ajax_request.php", 
						{id_adresse : id_adresse, request : 'adresseDetails'}, 
						function(data){
							$('#adresse_livraison').html(data.msg);
							$('#id_adresse_livraison').val(id_adresse);
						},
						"json"
			    );
				return false;
			});
			$('.set_billing_address').live('click', function(e){
				e.preventDefault();
				var id_adresse = parseInt($(this).attr("rel"));
				$.post(
						"ajax_request.php", 
						{id_adresse : id_adresse, request : 'adresseDetails'}, 
						function(data){
							$('#adresse_facturation').html(data.msg);
							$('#id_adresse_facturation').val(id_adresse);
						},
						"json"
			    );
				return false;
			});
			
			$('#plat').autocomplete({
					source: function(request, response) {
							$.post(
									"ajax_request.php", 
									{dishStartsWith : request.term, request : 'dishList'}, 
									function(data){
										response($.map(data, function(item) {
											return {
												label: item.plat_prix_id,
												value: item.plat_prix_id,
												id_plat: item.id_plat
											}
										}))
									},
									"json"
							);
					},
					select: function(event, ui) {
						  //$('#id_client').val(ui.item.id_client);
						  /*
						  $.post(
									"ajax_request.php", 
									{id_plat : ui.item.id_plat, array : <?php $panier ?>request : 'addDishToCart'}, 
									function(data){
										$('#adresses_block').html(data.msg);
									},
									"json"
						  );
						  */
                	}
			});
			
	});
	
</script>
<?php
$panier=array();
?>
<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                <input type="hidden" name="id_client" id="id_client" value="" />
                <input type="hidden" name="id_adresse_livraison" id="id_adresse_livraison" value="" />
                <input type="hidden" name="id_adresse_facturation" id="id_adresse_facturation" value="" />
            </div>
            
            <div class="required field">
                <label for="client"><?php showLang('CLIENT_NAME') ?></label>
                <input type="text" value="" id="client" name="client">
            </div>
            <div id="adresses_block">
            </div>
            <br class="clear">
            <br class="clear">
            <fieldset style="float:left; width:394px; margin-right:40px;">
            	<legend><?php showLang('SHIPPING_ADDRESS') ?></legend>
            	<div id="adresse_livraison"></div>
            </fieldset>
            <fieldset style="float:left; width:394px;">
            	<legend><?php showLang('BILLING_ADDRESS') ?></legend>
                <div id="adresse_facturation"></div>
            </fieldset>
            <br class="clear">
            <br class="clear">
            <div class="required field">
                <label for="plat"><?php showLang('DISH') ?></label>
                <input type="text" value="" id="plat" name="plat">
            </div>
            <div id="panier">
            </div> 
            <div class="buttons">
                <p>
                  <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                  <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                </p>
            </div>
        </fieldset>
    </form>
</div>