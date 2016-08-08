<script>
$(document).ready(function(){
	$("#prenom").keyup(function() {
		$("#prenom_adresse").val($("#prenom").val());
	});
	$("#nom").keyup(function() {
		$("#nom_adresse").val($("#nom").val());
	});
	
	
	$("input[name='cp']").live('focus', function() {
		if($(this).val().length==5){
			active_find_cp=0;
		}
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
	
	
	
});
</script>
<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            
            <fieldset class="sub_fs">
                <legend><?php showLang('PERSONAL_INFO') ?></legend>
                <div class="required field">
                    <label for="prenom"><?php showLang('FIRST_NAME') ?></label>
                    <input type="text" value="<?php echo osql($prenom) ?>" id="prenom" name="prenom">
                </div>
                <div class="required field">
                    <label for="nom"><?php showLang('LAST_NAME') ?></label>
                    <input type="text" value="<?php echo osql($nom) ?>" id="nom" name="nom">
                </div>
            </fieldset>
            <br />
            <fieldset class="sub_fs">
                <legend><?php showLang('ADDRESS') ?></legend>
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
                    <label for="nom"><?php showLang('LAST_NAME') ?></label>
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
            </fieldset>
              
            <div class="buttons">
                <p>
                  <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                  <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                </p>
            </div>
        </fieldset>
    </form>
</div>