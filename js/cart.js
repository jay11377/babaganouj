function updateBigCart(html, nb_products, total)
{
	$("#panier_full").html(html);
	$('.cart #nb_items').html(nb_products);
	$('.cart #total_cart').html(total);
}

!function ($) {
$(function(){
	if(typeof(step)!="undefined" && parseInt(step)==1)
	{
		$.ajax({
		  url: "updatebigcart.php",
		  type: "POST",
		  success: function(data){
		  	  updateBigCart(data.html, data.nb_products, data.total);
		  }
		});
	}
	if(typeof(step)!="undefined" && parseInt(step)==2)
	{
		if ($("#meme_adresse").is(":checked"))
			$("#adresse_facturation").hide();
		else
			$("#adresse_facturation").show();
		$("#meme_adresse").click(function(){
			if ($(this).is(":checked")){
				$("#adresse_facturation").hide();
				$("#adresse_facturation").val($("#adresse_livraison").val());
				$("#adresse_facturation_details").html($("#adresse_livraison_details").html());
			}
			else
				$("#adresse_facturation").show();
		});
		$.post(
		    "address_info.php", 
			{id_address:$("#adresse_livraison").val(), setTime:1}, 
			function(data){
				  $("#adresse_livraison_details").html(data);
				  if ($("#meme_adresse").is(":checked"))
				  {
				  	$("#adresse_facturation").val($("#adresse_livraison").val());
					$("#adresse_facturation_details").html(data);
				  }
	  		}
		);
		$("#adresse_livraison").change(function(){
			$.post(
				"address_info.php", 
				{id_address:$(this).val(), setTime:1}, 
				function(data){
					  $("#adresse_livraison_details").html(data);
					  if ($("#meme_adresse").is(":checked"))
					  {
						$("#adresse_facturation").val($("#adresse_livraison").val());  
				  	  	$("#adresse_facturation_details").html(data);
					  }
				}
			);
		});
		$("#adresse_facturation").change(function(){
			$.post(
				"address_info.php", 
				{id_address:$(this).val()}, 
				function(data){
					$("#adresse_facturation_details").html(data);
				}
			);
		});
	}

	$(document).on('click','#lire_cgv',function(e){
		e.preventDefault();
		BootstrapDialog.show({
			type: 'type-danger', 
            title: 'Conditions générales de vente',
            message: $('<div></div>').load('includes/cgv.php')
        });
		return false
	});

	$("#commander_button").live("click",function(e){
		if($("#logged_in").val()==0){
			e.preventDefault();
			BootstrapDialog.show({
				type: 'type-danger', 
	            title: 'Identification',
	            message: $('<div></div>').load('identification-overlay.php')
	        });
			return false;
		}
	});
		  
	$(document).on('submit','#choix_adresse',function(e){
		if($('#choix_heure').val()==1)
			return true;
		var return_val = false;
		if ($("#cgv").is(":checked")){
			var adresse_livraison = $(this).find("#adresse_livraison").val(); 
			var adresse_facturation = $(this).find("#adresse_facturation").val();
			$.ajax({
					  url: "ajax_check_address.php",
					  async: false, 
					  type: "POST",
					  data: ({adresse_livraison : adresse_livraison, adresse_facturation : adresse_facturation}),
					  success: function(data){
							 if(data.statut=="0")
							 {
								BootstrapDialog.show({
									type: 'type-danger', 
						            title: 'Erreur',
						            message: data.msg
						        });
							 }
							  else{
									var dialog = BootstrapDialog.show({
										type: 'type-danger', 
							            title: 'Heure de livraison',
							            message: $('<div></div>').load('ajax_overlay_time.php')
							        });
									// return_val = true;
							  }		 
					  },
					  dataType : "json"
				});
		}
		else{
			return_val=false;
			 $.ajax({
				  url: "getLang.php",
				  type: "POST",
				  data: ({string : 'CGV_ERROR'}),
				  success: function(msg){
					BootstrapDialog.show({
						type: 'type-danger', 
			            title: 'Erreur',
			            message: msg
			        });
				  }
			  });
		}
		return return_val
	});

	$("#validateTime").live('click',function(e){
		e.preventDefault();
		$.post(
			    "update_delivery_time.php", 
				{date : $("#date").val(), heure : $("#heure").val()}, 
				function(data){
					$('#choix_heure').val(1);
					$('#choix_adresse').submit();
		  		},
				"json"
		);
		return false;
	});

	$(document).on('click','.payment_block a',function(e){
		e.preventDefault();
		$("#moyen_paiement").val($(this).attr("rel"));
		if($("#moyen_paiement").val()==1)
			$("#paypal_form").submit();
		else	
			$("#choix_paiement").submit();
		return false;
	});


})
}(window.jQuery)
