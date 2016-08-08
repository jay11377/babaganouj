<script type="text/javascript">
	/* function autocomplete */
	$(function() {
			$('#modifier_adresse').click(function(e){
				e.preventDefault();
				$('#adresses_block').toggle();
				return false;
			});
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
												id_client: item.id_client,
												id_adresse: item.id_adresse
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
						  
						  $('#modifier_adresse').show();
						  var id_adresse = parseInt(ui.item.id_adresse);
						  $.post(
							"ajax_request.php", 
							{id_adresse : id_adresse, request : 'adresseDetails'}, 
							function(data){
								$('#adresse_livraison').html(data.msg);
								$('#id_adresse_livraison').val(id_adresse);
								$('#adresse_facturation').html(data.msg);
								$('#id_adresse_facturation').val(id_adresse);
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
			
			$('#categories a.category').click(function(e){
				e.preventDefault();
				var id_categorie = parseInt($(this).attr("rel"));
				$.post(
						"ajax_request.php", 
						{id_categorie : id_categorie, request : 'dishesList'}, 
						function(data){
							$('#plats').html(data.msg);
						},
						"json"
			    );
				return false;
			});
			
			var cart = Array();
			var mezze_options = Array();
			var mezze_qty = Array();
			$('a.addToCart').live('click', function(e){
				e.preventDefault();
				var id_plat = parseInt($(this).attr("rel"));
				var qte = $(this).parent().parent().find(".td_qte").find(".tiny").val();
				if(typeof(cart[id_plat])!='undefined')
					cart[id_plat] = parseInt(cart[id_plat]) + parseInt(qte); 
				else
					cart[id_plat] = parseInt(qte);
				$.post(
						"ajax_request.php", 
						{cart : cart, mezze_options : mezze_options, mezze_qty : mezze_qty, request : 'updateCart'},
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
				return false;
			});
			
			$('a.addToCartMezze').live('click', function(e){
				e.preventDefault();
				var id_plat = parseInt($(this).attr("rel"));
				if(typeof(mezze_options[id_plat])=='undefined')
					mezze_options[id_plat]=Array();
				i=0;
				var arrayOptions = Array();
				$(this).parent().find("select").each(function(index) {
					arrayOptions[i] = $(this).val();
					i=i+1;
				});
				var index_mezze = getMezzeIndex(parseInt(id_plat), arrayOptions, mezze_options);
				
				// quantite
				if(typeof(mezze_qty[id_plat])=='undefined')
					mezze_qty[id_plat]=Array();
					
				if(typeof(mezze_qty[id_plat][index_mezze])=='undefined')
				{
					mezze_qty[id_plat][index_mezze]=1;
					mezze_options[id_plat][index_mezze]=Array();
					i=0;
					$(this).parent().find("select").each(function(index) {
						mezze_options[id_plat][index_mezze][i] = $(this).val();
						i=i+1;
					});
				}
				else
				{
					mezze_qty[id_plat][index_mezze]=mezze_qty[id_plat][index_mezze]+1;
				}
				
				if(typeof(cart[id_plat])!='undefined')
					cart[id_plat] = parseInt(cart[id_plat]) + 1; 
				else
					cart[id_plat] = 1;
				$.post(
						"ajax_request.php", 
						{cart : cart, mezze_options : mezze_options, mezze_qty : mezze_qty, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
				return false;
			});
			
			
			if (typeof Array.removeIndex === "undefined") {
				Array.prototype.removeIndex = function (indexToRemove,throwException) {
					if(indexToRemove >= this.length)
					if(throwException)
							throw "Index to remove is out of range";
						 else
							return false;
					/*
					if(this.length-1 != indexToRemove)
						for(var i = 0; i < this.length-1; i++)
							this[i] = this[i >= indexToRemove ? i+1 : i];
					this.pop();
					*/
					this[indexToRemove] = undefined;
					return true;
				};
			}
			
			$(".bouton_retirer").live('click',function(e){
				e.preventDefault();
				var id_produit = $(this).attr("rel");
				if($(this).hasClass("bouton_mezze"))
				{	
					array_produit = id_produit.split('-');
					id_produit = array_produit[0];
					index_produit = array_produit[1];
					mezze_qty[id_produit].removeIndex(index_produit);
					mezze_options[id_produit].removeIndex(index_produit);
					if(mezze_options[id_produit].length==0)
					{
						mezze_options.removeIndex(id_produit);
						mezze_qty.removeIndex(id_produit);
						cart.removeIndex(id_produit);	
					}
				}
				else
				{
					cart.removeIndex(id_produit);	
				}
				$.post(
						"ajax_request.php", 
						{cart : cart, mezze_options : mezze_options, mezze_qty : mezze_qty, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
			});
			
			$(".panier_plus").live('click', function(e){
				var num = parseInt($(this).parent().parent().find(".panier_quantite").html(),10);
				var id_produit = $(this).attr("rel");
				num++;
				if(num<10)
					num = "0"+num;
				$(this).parent().parent().find(".panier_quantite").html(num);
				if($(this).hasClass("bouton_mezze"))
				{
					array_produit = id_produit.split('-');
					id_produit = array_produit[0];
					index_produit = array_produit[1];
					cart[id_produit] = parseInt(cart[id_produit])+1;
					mezze_qty[id_produit][index_produit] = parseInt(mezze_qty[id_produit][index_produit]) + 1;
				}
				else
				{
					cart[id_produit] = parseInt(cart[id_produit])+1;	
				}
				$.post(
						"ajax_request.php", 
						{cart : cart, mezze_options : mezze_options, mezze_qty : mezze_qty, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
			});
			
			$(".panier_moins").live('click', function(e){
				var num = parseInt($(this).parent().parent().find(".panier_quantite").html(),10);
				var id_produit = $(this).attr("rel");
				if(num>1)
					num--;
				if(num<10)
					num = "0"+num;
				$(this).parent().parent().find(".panier_quantite").html(num);
				if($(this).hasClass("bouton_mezze"))
				{
					array_produit = id_produit.split('-');
					id_produit = array_produit[0];
					index_produit = array_produit[1];
					cart[id_produit] = cart[id_produit] - 1;
					mezze_qty[id_produit][index_produit] = mezze_qty[id_produit][index_produit] - 1;
					if(mezze_qty[id_produit][index_produit]==0)
					{
						mezze_qty[id_produit].removeIndex(index_produit);
						mezze_options[id_produit].removeIndex(index_produit);
						if(mezze_options[id_produit].length==0)
						{
							mezze_options.removeIndex(id_produit);
							mezze_qty.removeIndex(id_produit);
							cart.removeIndex(id_produit);	
						}
					}
				}
				else
				{
					cart[id_produit] = cart[id_produit]-1;
					if(cart[id_produit]==0)
						cart.removeIndex(id_produit);	
				}
				$.post(
						"ajax_request.php", 
						{cart : cart, mezze_options : mezze_options, mezze_qty : mezze_qty, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
			});
			
			
			$('#date').DatePicker({
				eventName:'focus',
				format:'d/m/Y',
				date: $('#date').val(),
				current: $('#date').val(),
				starts: 1,
				position: 'right',
				onBeforeShow: function(){
					$('#date').attr("disabled","disabled");
				},
				onChange: function(formated, dates){
					$('#date').removeAttr("disabled");
					$('#date').val(formated);
					$('#date').DatePickerHide();
				},
				onHide: function(){
					$('#date').removeAttr("disabled");
				}
			});
			
			$('#new_order').submit(function(e){
				e.preventDefault();
				id_client = parseInt($("#id_client").val());
				total_ht = parseFloat($("#total_ht").val());
				total_ttc = parseFloat($("#total_ttc").val());
				id_moyen_paiement = parseInt($("#moyen_paiement").val());
				adresse_livraison = parseInt($("#id_adresse_livraison").val());
				adresse_facturation = parseInt($("#id_adresse_facturation").val());
				couverts = parseInt($("#couverts").val());
				message = $("#message").val();
				date_livraison = $("#date").val();
				creneau_livraison = $("#creneau_livraison").val();
				heure_livraison = $("#heure").val();
				no_minimum = $("#no_minimum").val();
				$.post(
						"ajax_request.php", 
						{
							id_client : id_client, 
							total_ht : total_ht, 
							total_ttc : total_ttc, 
							id_moyen_paiement : id_moyen_paiement,  
							adresse_livraison : adresse_livraison,
							adresse_facturation : adresse_facturation,
							couverts : couverts,
							message : message,
							date_livraison : date_livraison,
							creneau_livraison : creneau_livraison,
							heure_livraison : heure_livraison,
							cart : cart, 
							mezze_options : mezze_options, 
							mezze_qty : mezze_qty,
							no_minimum : no_minimum,
							request : 'newOrder'
						}, 
						function(data){
							 if(data.error!='') // Il y a une erreur
							 	alert(data.error);
							 else if(data.confirm!=''){ // Le montant est inférieur au minimum de commande
								if(parseInt(no_minimum)==0)  { // Si on est en attente de confirmation
									if (confirm(data.confirm)){
										$('#no_minimum').val(1);
										$('#new_order').submit();
									}
								}
								else{
									if(data.msg!='') // Si on a confirmé
										document.location.href = data.msg;
									 else
							 			alert("Erreur pendant la creation de la commande");
								}
							 }
							 else if(data.msg!='') // Si la commande a été ajoutée
								document.location.href = data.msg;
							 else
							 	alert("Erreur pendant la creation de la commande");
						},
						"json"
			    );
				return false;
				
			});
			
	});

function getMezzeIndex(id_produit, array, mezze_options){
	if(typeof(mezze_options[id_produit])!='undefined')
	{
		var arrayMezze = mezze_options[id_produit];
		for(var i=0;i<arrayMezze.length;i++)
		{
		   temp_array = arrayMezze[i];
		   //unset($temp_array['quantite']);
		   diff=0;
		   for(var j=0;j<temp_array.length;j++)
		   {
			   if(temp_array[j]!=array[j])
			   		diff++;
		   }
		   if(diff==0)
		      return i;
		}
		return arrayMezze.length;
	}
	else
		return 0;
}
	
</script>

<div class="inset container">
    <form id="new_order" method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                <input type="hidden" name="id_client" id="id_client" value="" />
                <input type="hidden" name="id_adresse_livraison" id="id_adresse_livraison" value="" />
                <input type="hidden" name="id_adresse_facturation" id="id_adresse_facturation" value="" />
                <input type="hidden" name="no_minimum" id="no_minimum" value="0" />
            </div>
            
            <div class="required field">
                <label for="client"><?php showLang('CLIENT_NAME') ?></label>
                <input type="text" value="" id="client" name="client">
                <a class="button small alt" href="moduleinterface.php?module=clients&action=add"><?php showLang('ADD_CUSTOMER') ?></a>
            </div>
            <a href="#" id="modifier_adresse">Modifier adresse</a>
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
            <div id="panier"></div> 
            <br class="clear">
            <div id="categories_et_plats">
                <div id="categories">
                    <?php
                    $query_cat="SELECT id,name FROM categories WHERE active=1 ORDER BY order_position";
                    //$query_cat="SELECT id,name FROM categories WHERE active=1 ORDER BY name";
                    $result_cat = $connector->query($query_cat);
                    while($row_cat = $connector->fetchArray($result_cat)){ ?>
                        <a href="" class="category" rel="<?php echo $row_cat['id'] ?>"><?php echo osql($row_cat['name']) ?></a><br /><?php 
                    }
                    ?>
                </div>
                <div id="plats"></div>
                <br class="clear">
            </div>
            <br class="clear">
            <div class="required field">
            	<label for="moyen_paiement"><?php showLang('PAYMENT_METHOD') ?></label>
            	<select name="moyen_paiement" id="moyen_paiement"><?php
            		$result_payment = $connector->query("SELECT * FROM order_methods WHERE active=1");
					while($row_payment=$connector->fetchArray($result_payment)){ 
						if($row_payment['name']!='Paypal'){ ?>
							<option value="<?php echo $row_payment['id'] ?>"><?php echo str_replace('_', ' ', $row_payment['name']) ?></option><?php
						}
					} ?>
                </select>
            </div>
            <div class="required field">
            	<label for="couverts"><?php showLang('CUTLERY') ?></label>
                <select name="couverts" id="couverts"><?php
					for($j=0;$j<11;$j++){
						if($j==0){ ?>
							<option value="0"><?php showLang('NO_CUTLERY') ?></option><?php
						}
						else{ ?>
							<option value="<?php echo $j ?>" <?php echo $sel; ?>><?php showLang('FOR') ?> <?php echo $j ?></option><?php
						}
					} ?>
				</select>
            </div>
            <div class="required field">
            	<label for="message"><?php showLang('MESSAGE') ?></label>
            	<textarea name="message" id="message"></textarea>
            </div>
            <div class="required field">
            	<label for="date"><?php showLang('DELIVERY_DATE') ?></label>
            	<input type="text" value="<?php echo date("d/m/Y") ?>" id="date" name="date" class="date">
            </div>
            <div class="required field">
            	<label for="creneau_livraison"><?php showLang('DELIVERY_ASKED_TIME') ?></label>
                <select name="creneau_livraison" id="creneau_livraison">
					<option value="<?php showLang('ASAP') ?>"><?php showLang('ASAP') ?></option>
                    <option value="1"><?php showLang('SAME_AS_DELIVERY_TIME') ?></option>
				</select>
            </div>
            <div class="required field">
            	<label for="heure"><?php showLang('DELIVERY_TIME') ?></label>
            	<input type="text" value="" id="heure" name="heure" class="short">
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