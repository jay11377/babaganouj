<script type="text/javascript">
	function HTMLentitiesdecode (texte) {

	//texte = texte.replace(/#/g,'&#35;'); // 160 A0
	//texte = texte.replace(/\n/g,'&#92;n'); // 160 A0
	//texte = texte.replace(/\r/g,'&#92;r'); // 160 A0
	
	texte = texte.replace(/&amp;/g,'&'); // 38 26
	texte = texte.replace(/&quot;/g,'"'); // 34 22
	texte = texte.replace(/&lt;/g,'<'); // 60 3C
	texte = texte.replace(/&gt;/g,'>'); // 62 3E
	
	texte = texte.replace(/&cent;/g,'\242');
	texte = texte.replace(/&pound;/g,'\243');
	texte = texte.replace(/&euro;/g,'\€');
	texte = texte.replace(/&yen;/g,'\245');
	texte = texte.replace(/&deg;/g,'\260');
	//texte = texte.replace(/\274/g,'&frac14;');
	texte = texte.replace(/&OElig;/g,'\274');
	//texte = texte.replace(/\275/g,'&frac12;');
	texte = texte.replace(/&oelig;/g,'\275');
	//texte = texte.replace(/\276/g,'&frac34;');
	texte = texte.replace(/&Yuml;/g,'\276');
	texte = texte.replace(/&iexcl;/g,'\241');
	texte = texte.replace(/&laquo;/g,'\253');
	texte = texte.replace(/&raquo;/g,'\273');
	texte = texte.replace(/&iquest;/g,'\277');
	texte = texte.replace(/&Agrave;/g,'\300');
	texte = texte.replace(/&Aacute;/g,'\301');
	texte = texte.replace(/&Acirc;/g,'\302');
	texte = texte.replace(/&Atilde;/g,'\303');
	texte = texte.replace(/&Auml;/g,'\304');
	texte = texte.replace(/&Aring;/g,'\305');
	texte = texte.replace(/&AElig;/g,'\306');
	texte = texte.replace(/&Ccedil;/g,'\307');
	texte = texte.replace(/&Egrave;/g,'\310');
	texte = texte.replace(/&Eacute;/g,'\311');
	texte = texte.replace(/&Ecirc;/g,'\312');
	texte = texte.replace(/&Euml;/g,'\313');
	texte = texte.replace(/&Igrave;/g,'\314');
	texte = texte.replace(/&Iacute;/g,'\315');
	texte = texte.replace(/&Icirc;/g,'\316');
	texte = texte.replace(/&Iuml;/g,'\317');
	texte = texte.replace(/&ETH;/g,'\320');
	texte = texte.replace(/&Ntilde;/g,'\321');
	texte = texte.replace(/&Ograve;/g,'\322');
	texte = texte.replace(/&Oacute;/g,'\323');
	texte = texte.replace(/&Ocirc;/g,'\324');
	texte = texte.replace(/&Otilde;/g,'\325');
	texte = texte.replace(/&Ouml;/g,'\326');
	texte = texte.replace(/&Oslash;/g,'\330');
	texte = texte.replace(/&Ugrave;/g,'\331');
	texte = texte.replace(/&Uacute;/g,'\332');
	texte = texte.replace(/&Ucirc;/g,'\333');
	texte = texte.replace(/&Uuml;/g,'\334');
	texte = texte.replace(/&Yacute;/g,'\335');
	texte = texte.replace(/&THORN;/g,'\336');
	texte = texte.replace(/&szlig;/g,'\337');
	texte = texte.replace(/&agrave;/g,'\340');
	texte = texte.replace(/&aacute;/g,'\341');
	texte = texte.replace(/&acirc;/g,'\342');
	texte = texte.replace(/&atilde;/g,'\343');
	texte = texte.replace(/&auml;/g,'\344');
	texte = texte.replace(/&aring;/g,'\345');
	texte = texte.replace(/&aelig;/g,'\346');
	texte = texte.replace(/&ccedil;/g,'\347');
	texte = texte.replace(/&egrave;/g,'\350');
	texte = texte.replace(/&eacute;/g,'\351');
	texte = texte.replace(/&ecirc;/g,'\352');
	texte = texte.replace(/&euml;/g,'\353');
	texte = texte.replace(/&igrave;/g,'\354');
	texte = texte.replace(/&iacute;/g,'\355');
	texte = texte.replace(/&icirc;/g,'\356');
	texte = texte.replace(/&iuml;/g,'\357');
	texte = texte.replace(/&eth;/g,'\360');
	texte = texte.replace(/&ntilde;/g,'\361');
	texte = texte.replace(/&ograve;/g,'\362');
	texte = texte.replace(/&oacute;/g,'\363');
	texte = texte.replace(/&ocirc;/g,'\364');
	texte = texte.replace(/&otilde;/g,'\365');
	texte = texte.replace(/&ouml;/g,'\366');
	texte = texte.replace(/&oslash;/g,'\370');
	texte = texte.replace(/&ugrave;/g,'\371');
	texte = texte.replace(/&uacute;/g,'\372');
	texte = texte.replace(/&ucirc;/g,'\373');
	texte = texte.replace(/&uuml;/g,'\374');
	texte = texte.replace(/&yacute;/g,'\375');
	texte = texte.replace(/&thorn;/g,'\376');
	texte = texte.replace(/&yuml;/g,'\377');
	return texte;
	}
	
	function getURLParameter(name) {
		return decodeURI(
			(RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
		);
	}
	
	/* function autocomplete */
	var cart = Array();
	$(function() {
			
			id_client = getURLParameter('client');
			id_adresse = getURLParameter('address');
			if(id_client!="null" && id_adresse!="null") 
			{
				$('#id_client').val(id_adresse);
				$('#changer_adresse, #ajouter_adresse, #recommander').show();
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
			
			$('.td_qte input.tiny').live('focus', function(e){
				$(this).val('');
			});
			$('.td_qte input.tiny').live('blur', function(e){
				if(isNaN($(this).val()) || $(this).val()=='')
					$(this).val(1);
			});
			$('.td_qte input.tiny').live('keydown', function(e){
				if (e.which==9)
				{
					e.preventDefault();
					$(this).parent().parent().next('tr').find('.td_qte input').focus();
					return false;
				}
				if (e.which==13)
				{
					e.preventDefault();
					$(this).parent().next("td").find('.addToCart img').click();
					$(this).parent().parent().next('tr').find('.td_qte input').focus();
					return false;
				}
			});
			
			$('#changer_adresse').live('click', function(e){
				e.preventDefault();
				$('#ajouter_adresse_form').hide();
				$('#adresses_block').toggle();
				return false;
			});
			$('#ajouter_adresse').live('click', function(e){
				e.preventDefault();
				$('#adresse_a_modifier').val(0);
				$('#adresses_block').hide();
				$('#ajouter_adresse_form').toggle();
				$('#ajouter_adresse_form .sub_fs legend').html("<?php showLang('ADD_ADDRESS') ?>");
				var id_client = $('#id_client').val();
				$.post(
					"ajax_request.php", 
					{id_client : id_client, request : 'remplirNomPrenom'}, 
					function(data){
						$('#prenom_adresse').val(data.prenom);
						$('#nom_adresse').val(data.nom);
						$('#telephone').val(data.telephone);
					},
					"json"
			    );
				return false;
			});
			$('.modifier_adresse').live('click', function(e){
				e.preventDefault();
				$('#adresses_block').hide();
				$('#ajouter_adresse_form').toggle();
				$('#ajouter_adresse_form .sub_fs legend').html("<?php showLang('EDIT_ADDRESS') ?>");
				var id_adresse = parseInt($(this).attr('rel'));
				$('#adresse_a_modifier').val(id_adresse);
				$.post(
					"ajax_request.php", 
					{id_adresse : id_adresse, request : 'remplirTout'}, 
					function(data){
						$('#titre_adresse').val(data.titre_adresse);
						$('#societe').val(data.societe);
						$('#prenom_adresse').val(data.prenom);
						$('#nom_adresse').val(data.nom);
						$('#adresse1').val(data.adresse1);
						$('#adresse2').val(data.adresse2);
						$('#cp').val(data.cp);
						$('#ville').val(data.ville);
						$('#telephone').val(data.telephone);
						$('#code_entree').val(data.code_entree);
						$('#interphone').val(data.interphone);
						$('#service').val(data.service);
						$('#escalier').val(data.escalier);
						$('#etage').val(data.etage);
						$('#numero_appartement').val(data.numero_appartement);
						$('#remarque').val(data.remarque);
						$('#titre_adresse').val(data.titre_adresse);
					},
					"json"
			    );
				return false;
			});
			var xhr;
			$('#client').autocomplete({
					source: function(request, response) {
							if(typeof(xhr)!='undefined')
								xhr.abort();
							xhr = $.post(
									"ajax_request.php", 
									{clientStartsWith : request.term, request : 'customerList'}, 
									function(data){
										response($.map(data, function(item) {
											return {
												label: item.info,
												value: request.term,
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
						  
						  $('#changer_adresse, #ajouter_adresse, #recommander').show();
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
			
			$("#recommander").live('click',function(e){
				e.preventDefault();
				var id_client = $('#id_client').val();
				if($.browser.msie)
					$("#bgOverlay").show();
				else
					$("#bgOverlay").fadeIn(300);	
				 $.post(
						"ajax_request.php", 
						{id_client : id_client, request : 'ordersList', limit : 10}, 
						function(data){
							$("#ordersOverlay").html(data.msg);
							var pos=$(window).scrollTop() + 'px';
							 $("#ordersOverlay").css({'top':pos});
							 $("#ordersOverlay").show();
							 $("#ordersOverlay .ordersWrapper").show();
							},
							"json"
				  );
				return false;
			});
			
			$("input[name='cp']").live('keyup', function() {
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
										  if(typeof(postcode_error)=='undefined')
										  {
											 $my_cp.parent().next(".field").next(".field").find("input[name='ville']").val(data.msg);
											 $my_cp.parent().next(".field").hide();
											 $my_cp.parent().next(".field").next(".field").next(".field").find("input[name='telephone']").focus();
										  }
										  else{
											  $my_cp.parent().next(".field").find("input[name='ville']").val(data.msg);
											  $my_cp.parent().next(".field").next(".field").find("input[name='email']").focus();
										  }
									  }
									  else
									  {
										  if(typeof(postcode_error)=='undefined')
											$my_cp.parent().next(".field").show();
									  }
								},
								"json"
							);
					}
				}	
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
			
			$(".meme_commande").live("click", function(e){
				e.preventDefault();
				var id_commande = $(this).attr("name");
				$.post(
						"ajax_request.php", 
						{id_commande : id_commande, request : 'recommander'},
						function(data){
							$('#panier').html(data.msg);
							
                            // Construction du panier en js
							cart.length=0;
							$('#panier tr td.panier_photo').each(function(){
								var nb_items = cart.length;
								var produit = new Object();
								
								produit.id_produit = $(this).find(".id_produit").val();
								produit.quantite = $(this).find(".quantite").val();
								if($(this).find(".options").val()!="")
								{
									produit.menuOptions = Array();  
									var menuOptions = $(this).find(".options").val() 
									var menuOptions = menuOptions.split('<br />');
									for(var i=0;i<menuOptions.length;i++){
										if(menuOptions[i]!='')
											produit.menuOptions[i] = HTMLentitiesdecode(menuOptions[i]);
									}
								}
								cart[nb_items] = produit;
							});
							
							$("#ordersOverlay .ordersWrapper").slideUp(400,function(){
								$("#ordersOverlay").hide();
								if($.browser.msie)
									$("#bgOverlay").hide();
								else
									$("#bgOverlay").fadeOut({duration: 600, easing:'easeOutCubic'});									   	
							});
						},
						"json"
			    );
				return false;
			});
			
			$('#categories a.category').click(function(e){
				e.preventDefault();
				var id_categorie = parseInt($(this).attr("rel"));
				$("table.table_cat").hide();
				$("table.#table_cat_" + id_categorie).show();
			});
			
			$('a.addToCart').live('click', function(e){
				e.preventDefault();
				var id_plat = parseInt($(this).attr("rel"));
				var qte = $(this).parent().parent().find(".td_qte").find(".tiny").val();
				
				var found=false;
				cart.forEach(function(entry) {
					if(entry.id_produit==parseInt(id_plat) && found==false)
					{
						entry.quantite = entry.quantite + parseInt(qte);  
						found = true;
					}
				});
				if(found==false)
				{
					var nb_items = cart.length;
					var produit = new Object();
					produit.quantite = parseInt(qte);
					produit.id_produit = parseInt(id_plat);
					cart[nb_items] = produit;
				}
				
				$.post(
						"ajax_request.php", 
						{cart : cart, request : 'updateCart'},
						function(data){
							$('#panier').html(data.msg);
							$('html,body').animate({scrollTop: $("#categories_et_plats").offset().top - 230},'fast');
						},
						"json"
			    );
				
				return false;
			});
			
			$('a.addToCartMezze').live('click', function(e){
				e.preventDefault();
				var id_plat = parseInt($(this).attr("rel"));
				
				var menuOptions = Array();
				i=0;
				$(this).parent().find("select").each(function(index) {
					menuOptions[i] = $(this).val();
					i=i+1;
				});
				
				var found=false;
				cart.forEach(function(entry) {
					if(entry.id_produit==parseInt(id_plat) && found==false)
					{
						var sameOptions=true;
						entry.menuOptions.forEach(function(option, indexOption) {
							if(option != menuOptions[indexOption])
							{
								sameOptions=false;
							}
						});
						if(sameOptions==true)
						{
							entry.quantite = entry.quantite + 1;
							found = true;
						}
					}
				});
				if(found==false)
				{
					var nb_items = cart.length;
					var menu = new Object();
					menu.quantite = 1;
					menu.id_produit = parseInt(id_plat);
					menu.menuOptions = Array(); 
					i=0;
					$(this).parent().find("select").each(function(index) {
						menu.menuOptions[i] = $(this).val();
						i=i+1;
					});
					cart[nb_items] = menu;
				}
				
				$.post(
						"ajax_request.php", 
						{cart : cart, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
				return false;
			});
			
		
			$(".bouton_retirer").live('click',function(e){
				e.preventDefault();
				
				var indexProduit = $(this).attr("rel");
				cart.splice(indexProduit, 1);
				$.post(
						"ajax_request.php", 
						{cart : cart, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
			});
			
			$(".panier_plus").live('click', function(e){
				var num = parseInt($(this).parent().parent().find(".panier_quantite").html(),10);
				num++;
				if(num<10)
					num = "0"+num;
				$(this).parent().parent().find(".panier_quantite").html(num);
				
				var indexProduit = $(this).attr("rel");
				cart[indexProduit].quantite++;
				$.post(
						"ajax_request.php", 
						{cart : cart, request : 'updateCart'}, 
						function(data){
							$('#panier').html(data.msg);
						},
						"json"
			    );
			});
			
			$(".panier_moins").live('click', function(e){
				var num = parseInt($(this).parent().parent().find(".panier_quantite").html(),10);
				if(num>1)
					num--;
				if(num<10)
					num = "0"+num;
				$(this).parent().parent().find(".panier_quantite").html(num);
				
				var indexProduit = $(this).attr("rel");
				cart[indexProduit].quantite--;
				if(cart[indexProduit].quantite==0)
					cart.splice(indexProduit, 1);
				
				$.post(
						"ajax_request.php", 
						{cart : cart, request : 'updateCart'}, 
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
				
				//Validation de la commande
				if( $('#ajouter_adresse_form').is(':hidden') ) {
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
					edit_commande = $("#edit_commande").val();
					id_commande = $("#id_commande").val();
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
								no_minimum : no_minimum,
								edit_commande : edit_commande,
								id_commande : id_commande, 
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
				}
				
				// Validation de la nouvelle adresse
				else
				{
					var form        = $('form#new_order');
					var formData     = form.serialize() + '&request=ajouterAdresse';
					$.post(
						"ajax_request.php", 
						formData, 
						function(data){
							if(data.errors!='')
							{
								$('#msgbox').html(data.errors);
								$('#msgbox').show();
								$('html,body').animate({scrollTop: $("#msgbox").offset().top - 20},'fast');
							}
							else
							{
								$('#msgbox').hide();
								$('#ajouter_adresse_form').hide();
								var id_adresse = data.id_adresse;
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
						},
						"json"
			    	);
				}
				
				return false;
				
			});
			
	});	
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
                <input type="hidden" name="adresse_a_modifier" id="adresse_a_modifier" value="0" />
                <input type="hidden" name="edit_commande" id="edit_commande" value="<?php echo isset($commande) ? 1 : 0 ?>" />
                <input type="hidden" name="id_commande" id="id_commande" value="<?php echo isset($commande) ? $id_commande : 0 ?>" />
            </div>
            
            <div class="required field">
                <label for="client"><?php showLang('CLIENT_NAME') ?></label>
                <input type="text" value="" id="client" name="client">
                <a class="button small alt" href="moduleinterface.php?module=clients&action=add&back=nouvelle_commande"><?php showLang('ADD_CUSTOMER') ?></a>
            </div>
            <a href="#" id="recommander"><?php showLang('REORDER') ?></a><a href="#" id="changer_adresse"><?php showLang('CHANGE_ADDRESS') ?></a><a href="#" id="ajouter_adresse"><?php showLang('ADD_ADDRESS') ?></a>
            <div id="adresses_block">
            </div>
            <div id="ajouter_adresse_form">
                <fieldset class="sub_fs">
                    <legend><?php showLang('ADD_ADDRESS') ?></legend>
                    <div class="required field">
                        <label for="titre_adresse"><?php showLang('ADDRESS_TITLE') ?></label>
                        <input type="text" value="<?php echo osql($titre_adresse) ?>" id="titre_adresse" name="titre_adresse">
                    </div>
                    <div class="field">
                        <label for="societe"><?php showLang('COMPANY') ?></label>
                        <input type="text" value="<?php echo osql($societe) ?>" id="societe" name="societe">
                    </div>
                    <div class="required field">
                        <label for="prenom_adresse"><?php showLang('FIRST_NAME') ?></label>
                        <input type="text" value="<?php echo osql($prenom_adresse) ?>" id="prenom_adresse" name="prenom_adresse">
                    </div>
                    <div class="required field">
                        <label for="nom_adresse"><?php showLang('LAST_NAME') ?></label>
                        <input type="text" value="<?php echo osql($nom_adresse) ?>" id="nom_adresse" name="nom_adresse">
                    </div>
                    <div class="required field">
                        <label for="adresse1"><?php showLang('ADDRESS') ?></label>
                        <input type="text" value="<?php echo osql($adresse1) ?>" id="adresse1" name="adresse1">
                    </div>
                    <div class="field">
                        <label for="adresse2"><?php showLang('ADDRESS2') ?></label>
                        <input type="text" value="<?php echo osql($adresse2) ?>" id="adresse2" name="adresse2">
                    </div>
                    <div class="required field">
                        <label for="cp"><?php showLang('POSTCODE') ?></label>
                        <input type="text" value="<?php echo osql($cp) ?>" id="cp" name="cp" class="short">
                    </div>
                    <div class="field" style="display:none">
                        <label></label>
                        <div class="msgbox2">
                            <div class="msg msg-warn"><p><?php showLang('CP_WARNING') ?></p></div>
                        </div>
                    </div>
                    <div class="required field">
                        <label for="ville"><?php showLang('CITY') ?></label>
                        <input type="text" value="<?php echo osql($ville) ?>" id="ville" name="ville">
                    </div>
                    <div class="required field">
                        <label for="telephone"><?php showLang('PHONE') ?></label>
                        <input type="text" value="<?php echo osql($telephone) ?>" id="telephone" name="telephone" class="medium">
                    </div>
                    <div class="field">
                        <label for="code_entree"><?php showLang('ENTRY_CODE') ?></label>
                        <input type="text" value="<?php echo osql($code_entree) ?>" id="code_entree" name="code_entree" class="medium">
                    </div>
                    <div class="field">
                        <label for="interphone"><?php showLang('INTERCOM') ?></label>
                        <input type="text" value="<?php echo osql($interphone) ?>" id="interphone" name="interphone">
                    </div>
                    <div class="field">
                        <label for="service"><?php showLang('SERVICE') ?></label>
                        <input type="text" value="<?php echo osql($service) ?>" id="service" name="service">
                    </div>
                    <div class="field">
                        <label for="escalier"><?php showLang('STAIRCASE') ?></label>
                        <input type="text" value="<?php echo osql($escalier) ?>" id="escalier" name="escalier" class="medium">
                    </div>
                    <div class="field">
                        <label for="etage"><?php showLang('FLOOR') ?></label>
                        <input type="text" value="<?php echo osql($etage) ?>" id="etage" name="etage" class="short">
                    </div>
                    <div class="field">
                        <label for="numero_appartement"><?php showLang('APARTMENT_NUMBER') ?></label>
                        <input type="text" value="<?php echo osql($numero_appartement) ?>" id="numero_appartement" name="numero_appartement" class="short">
                    </div>
                    <div class="field">
                        <label for="remarque"><?php showLang('COMMENT') ?></label>
                        <input type="text" value="<?php echo osql($remarque) ?>" id="remarque" name="remarque">
                    </div>
                    <div class="buttons">
                        <p>
                          <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                          <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                        </p>
                    </div>
                </fieldset>  
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
                    $table_cats = '';
					while($row_cat = $connector->fetchArray($result_cat)){ 
						$table_cats .= getDishes($row_cat['id']); ?>
                        <a href="" class="category" rel="<?php echo $row_cat['id'] ?>"><?php echo osql($row_cat['name']) ?></a><br /><?php 
                    } 
					?>
                </div>
                <div id="plats"><?php echo $table_cats ?></div>
                <br class="clear">
            </div>
            <br class="clear">
            <div class="required field">
            	<label for="moyen_paiement"><?php showLang('PAYMENT_METHOD') ?></label>
            	<select name="moyen_paiement" id="moyen_paiement"><?php
            		$result_payment = $connector->query("SELECT * FROM order_methods WHERE active=1");
					while($row_payment=$connector->fetchArray($result_payment)){ 
						if($row_payment['name']!='Paypal'){ ?>
							<option value="<?php echo $row_payment['id'] ?>"<?php if(isset($commande) && $commande['id_moyen_paiement'] == $row_payment['id']) echo ' selected="selected"';?>><?php echo str_replace('_', ' ', $row_payment['name']) ?></option><?php
						}
					} ?>
                </select>
            </div>
            <div class="required field">
            	<label for="couverts"><?php showLang('CUTLERY') ?></label>
                <select name="couverts" id="couverts"><?php
					for($j=0;$j<11;$j++){
						if($j==0){ ?>
							<option value="0"<?php if(isset($commande) && $commande['couverts'] == $j) echo ' selected="selected"';?>><?php showLang('NO_CUTLERY') ?></option><?php
						}
						else{ ?>
							<option value="<?php echo $j ?>"<?php if(isset($commande) && $commande['couverts'] == $j) echo ' selected="selected"';?>><?php showLang('FOR') ?> <?php echo $j ?></option><?php
						}
					} ?>
				</select>
            </div>
            <div class="required field">
            	<label for="message"><?php showLang('MESSAGE') ?></label>
            	<textarea name="message" id="message"><?php if(isset($commande) && $commande['message'] != '') echo $commande['message']; ?></textarea>
            </div>
            <div class="required field">
            	<label for="date"><?php showLang('DELIVERY_DATE') ?></label>
            	<input type="text" value="<?php echo isset($commande) ? dateENtoFR($commande['date_livraison']) : date("d/m/Y") ?>" id="date" name="date" class="date">
            </div>
            <div class="required field">
            	<label for="creneau_livraison"><?php showLang('DELIVERY_ASKED_TIME') ?></label>
                <select name="creneau_livraison" id="creneau_livraison">
					<option value="<?php showLang('ASAP') ?>"<?php if(isset($commande) && $commande['creneau_livraison'] == getRawLang('ASAP')) echo ' selected="selected"';?>><?php showLang('ASAP') ?></option>
                    <option value="1"<?php if(isset($commande) && $commande['creneau_livraison'] != getRawLang('ASAP')) echo ' selected="selected"';?>><?php showLang('SAME_AS_DELIVERY_TIME') ?></option>
				</select>
            </div>
            <div class="required field">
            	<label for="heure"><?php showLang('DELIVERY_TIME') ?></label>
            	<input type="text" value="<?php echo isset($commande) ? $commande['heure_livraison'] : '' ?>" id="heure" name="heure" class="short">
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