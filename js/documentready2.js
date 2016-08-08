!function ($) {
$(function(){
	
	$(document).on('click','#add_new_address',function(e){
		e.preventDefault();
		$(this).hide();
		$('.bg-danger, .bg-success').remove();
		$(".address_info").hide();
		$(".address_form").hide();
		$(".address_form_empty").show();
		return false;
	});
	
	$(document).on('click','a.update_address',function(e){
		e.preventDefault();
		$('.bg-danger, .bg-success').remove();
		$(".address_info").hide();
		$(".address_form_empty").hide();
		$(".address_form").hide();
		$("#add_new_address").hide();
		var index = $(".address_info").index($(this).parent().parent());
		$(".address_form:eq(" + index + ")").show();
		return false;
	});
	
	$(document).on('click','a.default_address',function(e){
		e.preventDefault();
		var str = $(this).attr("rel");
		var array = str.split("-");
		$.post(
			    "ajax_default_address.php", 
				{id_adresse : array[0], id_client : array[1]}, 
				function(data){
					  if(data.statut=="1")
					  {
						  $("#addresses").html('');
						  $("#addresses").load('ajax_all_addresses.php', function(){
							 $("#addresses").prepend(data.msg);
						  });
					  }
					  else
					  {
						 $('.bg-danger, .bg-success').remove();
						 $("#addresses").prepend(data.msg);
					  }
		  		},
				"json"
		);
		return false;
	});
	
	$(document).on('click','.cancel_update_address',function(e){
		e.preventDefault();
		$(".address_info").show();
		$(".address_form").hide();
		$(".address_form_empty").hide();
		$("#add_new_address").show();
		return false;
	});
	
	$(document).on('submit','#personal_info_form',function(e){
		e.preventDefault();
		var id_client = $(this).find("#id_client").val(); 
		var prenom = $(this).find("#prenom").val();
		var nom = $(this).find("#nom").val();
		var email = $(this).find("#email").val();
		var current_password = $(this).find("#current_password").val();
		var new_password = $(this).find("#new_password").val();
		var new_password_confirmation = $(this).find("#new_password_confirmation").val();
		var newsletter = $(this).find("#newsletter").val();
		$.post(
			    "ajax_update_personal_info.php", 
				{id_client : id_client, prenom : prenom, nom : nom, email : email, current_password : current_password, new_password : new_password, new_password_confirmation : new_password_confirmation, newsletter : newsletter}, 
				function(data){
					  if(data.statut=="1")
					  {
						  $("#personal_info").load('ajax_personal_info.php', function(){
							 $("#personal_info").prepend(data.msg);
						  });
					  }
					  else
					  {
						 $('.bg-danger, .bg-success').remove();
						 $("#personal_info").prepend(data.msg);
					  }
		  		},
				"json"
		);
		return false;
	});
	
	$(document).on('click','a.delete_address',function(e){
		e.preventDefault();
		var index = $(".address_info").index($(this).parent().parent());
		$(".address_form:eq(" + index + ")").show();
		var id_adresse = $(".address_form:eq(" + index + ")").find(".id_adresse").val();
		var delete_msg = $(".address_form:eq(" + index + ")").find(".delete_msg").val();
		if(confirm(delete_msg))
		{
			$.post(
			  		"ajax_delete_address.php",
			  		{id_adresse : id_adresse},
			  		function(data){
						  if(data.statut=="1")
					  	  {
							  $('.bg-danger, .bg-success').remove();
							  $(".address_info").show();
							  $(".address_form").hide();
							  $(".address_form_empty").hide();
							  $("#add_new_address").show();
							  $("#addresses").html('');
							  $("#addresses").load('ajax_all_addresses.php', function(){
								 $("#addresses").prepend(data.msg);
							  });
						  }
						  else
						  {
							  $('.bg-danger, .bg-success').remove();
						 	  $("#addresses").prepend(data.msg);
						  }
					  },
					  "json"
			);
		}
		return false;
	});
	
	$(document).on('click','.meme_commande',function(e){
		e.preventDefault();
		var id_commande = $(this).attr("name");
		$.ajax({
		  url: "ajax_reorder.php",
		  type: "POST",
		  data: ({id_commande : id_commande}),
		  success: function(msg){
			  // aller sur la page de categorie par défaut
			  document.location.href = msg;
		  }
		});
		return false;
	});
	
	$(document).on('submit','.address_form form',function(e){
		e.preventDefault();
		var id_adresse = $(this).find(".id_adresse").val(); 
		var societe = $(this).find(".societe").val();
		var prenom_adresse = $(this).find(".prenom_adresse").val();
		var nom_adresse = $(this).find(".nom_adresse").val();
		var adresse1 = $(this).find(".adresse1").val();
		var adresse2 = $(this).find(".adresse2").val();
		var cp = $(this).find(".cp").val();
		var ville = $(this).find(".ville").val();
		var telephone = $(this).find(".telephone").val();
		var titre_adresse = $(this).find(".titre_adresse").val();
		var code_entree = $(this).find(".code_entree").val();
		var interphone = $(this).find(".interphone").val();
		var service = $(this).find(".service").val();
		var escalier = $(this).find(".escalier").val();
		var etage = $(this).find(".etage").val();
		var numero_appartement = $(this).find(".numero_appartement").val();
		var remarque = $(this).find(".remarque").val();
		var defaut = $(this).find(".defaut").val();
		$.post(
			    "ajax_update_address.php", 
				{id_adresse : id_adresse, societe : societe, prenom_adresse : prenom_adresse, nom_adresse : nom_adresse, adresse1 : adresse1, adresse2 : adresse2, cp : cp, ville : ville, telephone : telephone, titre_adresse : titre_adresse, code_entree : code_entree, interphone : interphone, service : service, escalier : escalier, etage : etage, numero_appartement : numero_appartement, remarque : remarque, defaut : defaut}, 
				function(data){
					  if(data.statut=="1")
					  {
						  $(".address_info").show();
						  $(".address_form").hide();
						  $(".address_form_empty").hide();
						  $("#add_new_address").show();
						  $("#addresses").html('');
						  $("#addresses").load('ajax_all_addresses.php', function(){
							 $("#addresses").prepend(data.msg);
						  });
					  }
					  else
					  {
						 $('.bg-danger, .bg-success').remove();
						 $("#addresses").prepend(data.msg);
					  }
		  		},
				"json"
		);
		return false;
	});
	
	$(document).on('submit','.address_form_empty form',function(e){
		e.preventDefault();
		var societe = $(this).find(".societe").val();
		var prenom_adresse = $(this).find(".prenom_adresse").val();
		var nom_adresse = $(this).find(".nom_adresse").val();
		var adresse1 = $(this).find(".adresse1").val();
		var adresse2 = $(this).find(".adresse2").val();
		var cp = $(this).find(".cp").val();
		var ville = $(this).find(".ville").val();
		var telephone = $(this).find(".telephone").val();
		var titre_adresse = $(this).find(".titre_adresse").val();
		var code_entree = $(this).find(".code_entree").val();
		var interphone = $(this).find(".interphone").val();
		var service = $(this).find(".service").val();
		var escalier = $(this).find(".escalier").val();
		var etage = $(this).find(".etage").val();
		var numero_appartement = $(this).find(".numero_appartement").val();
		var remarque = $(this).find(".remarque").val();
		$.post(
			   	"ajax_add_address.php", 
				{societe : societe, prenom_adresse : prenom_adresse, nom_adresse : nom_adresse, adresse1 : adresse1, adresse2 : adresse2, cp : cp, ville : ville, telephone : telephone, titre_adresse : titre_adresse, code_entree : code_entree, interphone : interphone, service : service, escalier : escalier, etage : etage, numero_appartement : numero_appartement, remarque : remarque}, 
				function(data){
					  if(data.statut=="1")
					  {
						  if($("input[name=back]").val()!='')
						  {
							  document.location.href=$("input[name=back]").val() + "?" + "step=" + $("input[name=step]").val();
						  }
						  else
						  {
							  $(".address_info").show();
							  $(".address_form").hide();
							  $(".address_form_empty").hide();
							  $("#add_new_address").show();
							  $("#addresses").html('');
							  $("#addresses").load('ajax_all_addresses.php', function(){
								 $("#addresses").prepend(data.msg);
							  });
						  }
					  }
					  else
					  {
						 $('.bg-danger, .bg-success').remove();
						 $("#addresses").prepend(data.msg);
					  }
		  		},
				"json"
		);
		return false;
	});

	$(document).on('click','#cancel_add_address, .cancel_update_address',function(e){
		e.preventDefault();
		$(".address_info").show();
		$(".address_form").hide();
		$(".address_form_empty").hide();
		$("#add_new_address").show();
		return false;
	});

	$(document).on('click','.details_commande',function(e){
		e.preventDefault();
		return false;
	});


	
	$(document).on('submit','form#login',function(e){
		e.preventDefault();
		var email = $("#email").val();
		var password = $("#password").val();
		var overlay = $("#overlay").val();

		$.post("login.php", 
			{"email": email, "password": password}, 
			function(data){
			  if(data.statut=="1")
			  {
				 if(overlay==1)
				 	document.location.href = "cart.php?step=2";
				 else
				 	document.location.href = "commander.php";

			  }
			  else
			  {
			      $("#password").val('');
				  $("#msg_error p").html(data.msg);
				  $("#msg_error").show();
			  }
		  	},
			"json"
		);
		return false;
	});
	
	$(document).on('click','#deconnexion',function(e){
		e.preventDefault();
		$.ajax({
		  url: "logout.php",
		  type: "POST",
		  success: function(msg){
		  	location.reload();
		  }
		});
		return false;
	});
	
	$(document).on('click','.zones_livraison',function(e){
		BootstrapDialog.show({
			type: 'type-danger', 
            title: 'Zones de livraison',
            message: $('<div></div>').load('ajax_overlay_map.php')
        });
	});
})
}(window.jQuery)