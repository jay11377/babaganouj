function updateSmallCart(html, nb_products, total)
{
	$("#produits_panier").html(html);
	$('.cart #nb_items').html(nb_products);
	$('.cart #total_cart').html(total);
}
function showPriceCurrency(price){
	price = price.toFixed(2).replace('.',',') + ' €';
	return price;
}


!function ($) {
$(function(){
  
  var active_find_cp=1;	
  var code_remise_default=true;
  var default_remise_value;

  // Fix for dropdowns on mobile devices for bootstrap bug https://github.com/twitter/bootstrap/issues/4550
  $('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { 
	  e.stopPropagation(); 
  });
  $(document).on('click','.dropdown-menu a',function(){
	  document.location = $(this).attr('href');
  });

  $.ajax({
	  url: "updatecart.php",
	  type: "POST",
	  success: function(data){
	  	  updateSmallCart(data.html, data.nb_products, data.total);
	  }
	});

  	$(document).on('click','.bouton_ajouter',function(e){
		var bouton = $(this);
		var quantite = $(this).parent().data('quantite');
		var id_produit = $(this).data('id');
		$.ajax({
		  url: "updatecart.php",
		  type: "POST",
		  data: ({id_produit : id_produit, quantite : quantite, action : 'ajouter'}),
		  success: function(data){
		  	  updateSmallCart(data.html, data.nb_products, data.total);
		  	  bouton.parent().data("quantite",1);
		  	  bouton.parent().find(".quantite_numero").html("1");
		  }
		});
	});

  	$(document).on('click','.bouton_ajouter_menu',function(e){
		arrayOptions = new Array;
		var supplement_ht = 0;
		var supplement_ttc = 0;
		$("select.select-image option:selected").each(function() {
			arrayOptions.push($(this).text());
        	supplement_ht += $(this).data('supplement_ht');
        	supplement_ttc += $(this).data('supplement_ttc');
        });
		var bouton = $(this);
		var quantite = $(this).parent().data('quantite');
		var id_produit = $(this).data('id');
		
		$.ajax({
		  url: "updatecart.php",
		  type: "POST",
		  data: ({id_produit : id_produit, quantite : quantite, supplement_ht : supplement_ht, supplement_ttc : supplement_ttc, categorie : 'mezze', 'arrayOptions[]' : arrayOptions, action : 'ajouter'}),
		  success: function(data){
		  	  updateSmallCart(data.html, data.nb_products, data.total);
		  	  bouton.parent().data("quantite",1);
		  	  bouton.parent().find(".quantite_numero").html("1");
		  }
		});
	});

	$(document).on('click','.bouton_retirer',function(e){
		var id_produit = $(this).data('id');
		if($(this).hasClass("bouton_mezze"))
			action_title = "retirer_mezze";
		else
			action_title = "retirer";
		if(typeof(step)!="undefined" && parseInt(step)==1){
			$.ajax({
			  url: "updatebigcart.php",
			  type: "POST",
			  data: ({id_produit : id_produit, quantite : 0, action : action_title}),
			  success: function(data){
				  updateBigCart(data.html, data.nb_products, data.total);
				  $.ajax({
					  url: "updatecart.php",
					  type: "POST",
					  success: function(data){
						  updateSmallCart(data.html, data.nb_products, data.total);
					  }
					});
			  }
			});
		}
		else{
			$.ajax({
			  url: "updatecart.php",
			  type: "POST",
			  data: ({id_produit : id_produit, quantite : 0, action : action_title}),
			  success: function(data){
				  updateSmallCart(data.html, data.nb_products, data.total);
			  }
			});
		}

	});

	$(document).on('click','.quantite .fa-plus-square',function(e){
		var bouton = $(this);
		var quantite = bouton.parent().data('quantite');
		quantite++;
		bouton.parent().data("quantite",quantite);
		bouton.parent().find(".quantite_numero").html(quantite);
		
		if(bouton.parent().hasClass('panier_quantite_bouton'))
		{
			var id_produit = bouton.parent().data("id");
			if(bouton.parent().hasClass('bouton_mezze'))
				action_title = "ajouter_mezze";
			else
				action_title = "ajouter";
			$.ajax({
			  url: "updatebigcart.php",
			  type: "POST",
			  data: ({id_produit : id_produit, quantite : 1, action : action_title}),
			  success: function(data){
				  updateBigCart(data.html, data.nb_products, data.total);
				  $.ajax({
					  url: "updatecart.php",
					  type: "POST",
					  success: function(data){
						  updateSmallCart(data.html, data.nb_products, data.total);
					  }
					});
			  }
			});
		}

	});

	$(document).on('click','.quantite .fa-minus-square',function(e){
		var bouton = $(this);
		var quantite = $(this).parent().data('quantite');
		if(quantite>1)
			quantite--;
		bouton.parent().data("quantite",quantite);
		bouton.parent().find(".quantite_numero").html(quantite);

		if(bouton.parent().hasClass('panier_quantite_bouton'))
		{
			var id_produit = bouton.parent().data("id");
			if(bouton.parent().hasClass('bouton_mezze'))
				action_title = "soustraire_mezze";
			else
				action_title = "soustraire";
			$.ajax({
			  url: "updatebigcart.php",
			  type: "POST",
			  data: ({id_produit : id_produit, quantite : 0, action : action_title}),
			  success: function(data){
				  updateBigCart(data.html, data.nb_products, data.total);
				  $.ajax({
					  url: "updatecart.php",
					  type: "POST",
					  success: function(data){
						  updateSmallCart(data.html, data.nb_products, data.total);
					  }
					});
			  }
			});
		}
	});

	$(document).on('click','.bouton_retirer_remise',function(e){
		if(typeof(step)!="undefined" && parseInt(step)==1){
			$.ajax({
			  url: "updatebigcart.php",
			  type: "POST",
			  data: ({action : "retirer_remise"}),
			  success: function(data){
				  updateBigCart(data.html, data.nb_products, data.total);
				  $.ajax({
					  url: "updatecart.php",
					  type: "POST",
					  success: function(data){
						  updateSmallCart(data.html, data.nb_products, data.total);
					  }
					});
			  }
			});
		}
		else{
			$.ajax({
			  url: "updatecart.php",
			  type: "POST",
			  data: ({action : "retirer_remise"}),
			  success: function(msg){
				  $("#produits_panier").html(msg);
			  }
			});
		}
		return false;
	});

	$(document).on('focus','#code_remise',function(e){
		if(code_remise_default){
			default_remise_value = $(this).val();
			code_remise_default = false;
			$(this).val('');
		}
  	});
  	$(document).on('blur','#code_remise',function(e){
		if($(this).val() == ''){
			$(this).val(default_remise_value);
			code_remise_default = true;
		}
	});
	$(document).on('click','.lien_code_remise',function(e){
		e.preventDefault();
		$('#code_remise').val($(this).html());
		code_remise_default = false;
		return false;
	});
	$(document).on('click','#submit_remise',function(e){
		e.preventDefault();
		var code_remise = $("#code_remise").val();
		
		if(typeof(step)!="undefined" && parseInt(step)==1){
			$.ajax({
				  url: "updatebigcart.php",
				  type: "POST",
				  data: ({code_remise : code_remise, action : "ajouter_remise"}),
				  success: function(data){
					  $("#panier_full").html(data.html);
					  $('.cart #total_cart').html(data.total);
					  code_remise_default = true;
				  }
			});
		}
		else{
			$.ajax({
				  url: "updatecart.php",
				  type: "POST",
				  data: ({code_remise : code_remise, action : "ajouter_remise"}),
				  success: function(data){
					  updateSmallCart(data.html, data.nb_products, data.total);
					  code_remise_default = true;
				  }
			});
		}
		return false;
	});
	
	$(document).on('keydown','#code_remise',function(e){
		if(e.keyCode == 13) {
		  e.preventDefault();
		  return false;
		}
	});
	$(document).on('focus',"input[name='cp']",function(e){
		if($(this).val().length==5){
			active_find_cp=0;
		}
	});
	$(document).on('keyup',"input[name='cp']",function(e){
		$my_cp = $(this);
		if($(this).val().length<5)
			active_find_cp=1;
		if(active_find_cp==1){
			if($(this).val().length==5){
					$.post(
						"ajax_request.php", 
						{request : 'getCity', cp : $(this).val()}, 
						function(data){
							  if(data.statut=="1")
							  {
							  	 $my_cp.parent().parent().next(".form-group").next(".form-group").find("input[name='ville']").val(data.msg);
								 $my_cp.parent().parent().next(".form-group").hide();
							 	 $my_cp.parent().parent().next(".form-group").next(".form-group").next(".form-group").find("input[name='telephone']").focus();
							  }
							  else
							  {
								$my_cp.parent().parent().next(".form-group").show();
							  }
						},
						"json"
					);
			}
		}	
	});



})
}(window.jQuery)
