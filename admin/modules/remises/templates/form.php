<script>
$(function() {
	if ($("#contraintes_date").is(":checked")){
		$("#block_date_debut").show();
		$("#block_date_fin").show();
	}
	else{
		$("#block_date_debut").hide();
		$("#block_date_fin").hide();
	}		
	$("#contraintes_date").click(function(){
		if ($(this).is(":checked")){
			$("#block_date_debut").show();
			$("#block_date_fin").show();
		}
		else{
			$("#block_date_debut").hide();
			$("#block_date_fin").hide();
		}		
	});
	$('#date_debut').DatePicker({
		eventName:'focus',
		format:'d/m/Y',
		date: $('#date_debut').val(),
		current: $('#date_debut').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#date_debut').attr("disabled","disabled");
		},
		onChange: function(formated, dates){
			$('#date_debut').removeAttr("disabled");
			$('#date_debut').val(formated);
			$('#date_debut').DatePickerHide();
		},
		onHide: function(){
			$('#date_debut').removeAttr("disabled");
		}
	});
	$('#date_fin').DatePicker({
		eventName:'focus',
		format:'d/m/Y',
		date: $('#date_fin').val(),
		current: $('#date_fin').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#date_fin').attr("disabled","disabled");
		},
		onChange: function(formated, dates){
			$('#date_fin').removeAttr("disabled");
			$('#date_fin').val(formated);
			$('#date_fin').DatePickerHide();
		},
		onHide: function(){
			$('#date_fin').removeAttr("disabled");
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
            <div class="required  field ">
            	<label for="code"><?php showLang('VOUCHER_CODE') ?></label>
                <input type="text" name="code" id ="code" value="<?php echo osql($code);?>" /> 
            	<div class="help"><?php showLang('VOUCHER_CODE_HELP') ?></div>
            </div>
            <div class="required  field ">
            	<label for="valeur"><?php showLang('VALUE') ?></label>
                <input type="text" name="valeur" id ="valeur" class="short" value="<?php echo (int)($valeur);?>" /> %
            </div>
            <div class="required  field ">
            	<label for="description"><?php showLang('DESCRIPTION') ?></label>
                <input type="text" name="description" id ="description" value="<?php echo osql($description);?>" > 
            	<div class="help"><?php showLang('VOUCHER_DESCRIPTION_HELP') ?></div>
            </div><?php
            if($_GET['action']=='add') : ?>
                <div class="required  field ">
                    <label for="quantite_initiale"><?php showLang('QUANTITY') ?></label>
                    <input type="text" name="quantite_initiale" id ="quantite_initiale" class="short" value="<?php echo (int)($quantite_initiale);?>" >
                    <div class="help"><?php showLang('AVAILABLE_QUANTITY_HELP') ?></div>
                </div><?php
            endif ?>
            <div class="required field">
            	<label for="panier_minimum"><?php showLang('MINIMUM_ORDER') ?></label>
                <input type="text" name="panier_minimum" id="panier_minimum" class="short" value="<?php echo (int)($panier_minimum);?>" > <?php showLang('CURRENCY') ?>
            	<div class="help"><?php showLang('MINIMUM_ORDER_HELP') ?></div>
            </div>
             <div class="field">
            	<label for="nb_utilisation"><?php showLang('VOUCHER_SINGLE_USE') ?></label>
                <input type="checkbox" id="nb_utilisation" name="nb_utilisation" <?php if($nb_utilisation==1) echo 'checked="checked"' ?> />
                <div class="help"><?php showLang('VOUCHER_SINGLE_USE_HELP') ?></div>
            </div>
            <div class="field">
            	<label for="contraintes_date"><?php showLang('DATE') ?></label>
                <input type="checkbox" id="contraintes_date" name="contraintes_date" <?php if($contraintes_date==1) echo 'checked="checked"' ?> />
                <div class="help"><?php showLang('DATE_RANGE_CONSTRAINT_HELP') ?></div>
            </div>
            <div class="required field" id="block_date_debut" style="display:none">
            	<label for="date_debut"><?php showLang('DATE_START') ?></label>
            	<input type="text" value="<?php echo $date_debut ?>" id="date_debut" name="date_debut" class="date">
            </div>
            <div class="required field" id="block_date_fin" style="display:none">
            	<label for="date_fin"><?php showLang('DATE_END') ?></label>
            	<input type="text" value="<?php echo $date_fin ?>" id="date_fin" name="date_fin" class="date">
            </div>
            <div class="field">
            	<label for="afficher_panier"><?php showLang('DISPLAY_ON_CART') ?></label>
                <input type="checkbox" id="afficher_panier" name="afficher_panier" <?php if($afficher_panier==1) echo 'checked="checked"' ?> />
                <div class="help"><?php showLang('DISPLAY_ON_CART_HELP') ?></div>
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