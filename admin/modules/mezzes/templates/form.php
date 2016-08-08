<?php
	$result = $connector->query("SELECT * FROM tva WHERE id=".$id_tva);
	$row_rate=$connector->fetchArray($result);
	$tax_rate = $row_rate['value']; 
?>
<script>
$(document).ready(function(){
	var taux_tva = "<?php echo $tax_rate ?>";
	taux_tva = parseFloat(taux_tva);
	$("#prix_ht").keyup(function() {
  		if(isNaN($("#prix_ht").val()) || $("#prix_ht").val()=='')
		{
			$("#prix_ttc").val('');
		}
		else
		{
			var prix_ttc =  parseFloat($("#prix_ht").val() * (1 + (taux_tva/100))).toFixed(2).toString();
			$("#prix_ttc").val(prix_ttc);
		}
	});
	$("#prix_ttc").keyup(function() {
  		if(isNaN($("#prix_ttc").val()) || $("#prix_ttc").val()=='')
		{
			$("#prix_ht").val('');
		}
		else
		{
			var prix_ht =  parseFloat($("#prix_ttc").val() / (1 + (taux_tva/100))).toFixed(6).toString();
			// Remove the last 4 digits if they are 0
			for(i=0;i<4;i++)
			{
				if(prix_ht.substr(prix_ht.length - 1)==0)
					prix_ht = prix_ht.substr(0, prix_ht.length - 1)
			}
			$("#prix_ht").val(prix_ht);
		}
	});
	
	$("#id_tva").change(function(){
		taux_tva = $("#id_tva option:selected").attr('title');
		if(isNaN($("#prix_ht").val()) || $("#prix_ht").val()=='')
		{
			$("#prix_ttc").val('');
		}
		else
		{
			var prix_ttc =  parseFloat($("#prix_ht").val() * (1 + (taux_tva/100))).toFixed(2).toString();
			$("#prix_ttc").val(prix_ttc);
		}
	});
	$( "#sortable1, #sortable2" ).sortable({
			connectWith: ".connectedSortable"
	}).disableSelection();
})
</script>
<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data" onsubmit="return setSortedList3()">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                <input type="hidden" id="sortedlist" name="sortedlist" value="" />
            </div>
			<div class="required  field ">
            	<label for="name"><?php showLang('NAME') ?></label>
                <input type="text" name="name" id ="name" value="<?php echo osql($name);?>" > 
            </div>
            <div class="field ">
                <label for="ingredient_principal"><?php showLang('MAIN_INGREDIENT') ?></label>
                <input type="text" name="ingredient_principal" id ="ingredient_principal" value="<?php echo osql($ingredient_principal);?>" > 
            </div>
            <div class="field">
            	<label for="description" class="left"><?php showLang('DESCRIPTION') ?></label>
                <div class="left"><textarea class="ckeditor" id="description" name="description"><?php echo $description ?></textarea></div>
            </div>
            <div style="clear:both; height:20px;"></div>
            
            <div class="field">
            	<label><?php showLang('VEGETARIAN') ?></label>
                <input type="checkbox" id="vegetarien" name="vegetarien" <?php if($vegetarien) echo 'checked="checked"' ?> />
            </div>
            
            <div class="field">
            	<label><?php showLang('SPICY') ?></label>
                <input type="checkbox" id="epice" name="epice" <?php if($epice) echo 'checked="checked"' ?> />
            </div>
            
            <div class="required field ">
            	<label for="id_tva"><?php showLang('TAX_RATE') ?></label>
                <select name="id_tva" id="id_tva">
                    <?php while($row_tva = $connector->fetchArray($result3)){ ?>
                    <option value="<?php echo $row_tva['id']?>" <?php if($row_tva['id'] == $id_tva) echo'selected="selected"';?> title="<?php echo $row_tva['value'] ?>"><?php echo $row_tva['name']?> (<?php echo $row_tva['value']?>%)</option>
                    <?php } ?>
                </select>
            </div>
             
             <label></label>
             <?php if($_GET['action']=='edit') echo '<img src="../'.$row['thumbnail1'].'">' ?>
             
             <div class="required  field ">
                <label for="photo1"><?php echo getLang('IMAGE').' 1'; ?></label>
                <span class="upload">
                    <input type="text" value="" readonly="readonly" name="fakeimage" class="fake">
                    <span class="wrapper">
                        <span class="button"><?php showLang('UPLOAD'); ?></span>
                        <input type="file" onchange="this.form.fakeimage.value = this.value;" class="real" value="" name="photo1" id="photo1">
                    </span><br /><br />
                </span>
                <div class="help"><?php showLang('SIZE_4_3'); ?><br /><br /><?php showLang('IMG_SIZE_2M'); ?></div>
            </div>
            
              <label></label>
              <?php
             if($_GET['action']=='edit' && $row['thumbnail2'] != '' ): ?>
				<img src="../<?php echo $row['thumbnail2'] ?>">&nbsp;&nbsp;
                <input type="checkbox" id="removephoto2" name="removephoto2" /><div class="help"><?php showLang('REMOVE_IMAGE'); ?></div><?php
			  endif; ?>
              <div class="field ">
                <label for="photo2"><?php echo getLang('IMAGE').' 2';  ?></label>
                <span class="upload">
                    <input type="text" value="" readonly="readonly" name="fakeimage2" class="fake2">
                    <span class="wrapper">
                        <span class="button"><?php showLang('UPLOAD') ?></span>
                        <input type="file" onchange="this.form.fakeimage2.value = this.value;" class="real" value="" name="photo2" id="photo2">
                    </span><br /><br />
                </span>
                <div class="help"><?php showLang('SIZE_4_3'); ?><br /><br /><?php showLang('IMG_SIZE_2M'); ?></div>
            </div>
            
            <label></label><?php 
			if($_GET['action']=='edit' && $row['thumbnail3'] != '' ): ?>
				<img src="../<?php echo $row['thumbnail3'] ?>">&nbsp;&nbsp;
                <input type="checkbox" id="removephoto3" name="removephoto3" /><div class="help"><?php showLang('REMOVE_IMAGE'); ?></div><?php
			endif; ?>
            <div class="field ">
                <label for="photo3"><?php echo getLang('IMAGE').' 3';  ?></label>
                <span class="upload">
                    <input type="text" value="" readonly="readonly" name="fakeimage3" class="fake3">
                    <span class="wrapper">
                        <span class="button"><?php showLang('UPLOAD') ?></span>
                        <input type="file" onchange="this.form.fakeimage3.value = this.value;" class="real" value="" name="photo3" id="photo3">
                    </span><br /><br />
                </span>
                <div class="help"><?php showLang('SIZE_4_3'); ?><br /><br /><?php showLang('IMG_SIZE_2M'); ?></div>
            </div>
            
             <div class="required field ">
            	<label for="prix_ht"><?php showLang('PRICE_NO_TAX') ?></label>
                <input type="text" name="prix_ht" id ="prix_ht" class="short" value="<?php echo osql($prix_ht);?>"> <?php showLang('CURRENCY') ?>
                <div class="help"><?php showLang('PRICE_FORMAT'); ?></div> 
            </div>
            
            <div class="required field ">
            	<label for="prix_ttc"><?php showLang('PRICE_WITH_TAX') ?></label>
                <input type="text" name="prix_ttc" id ="prix_ttc" class="short" value="<?php echo osql($prix_ttc);?>"> <?php showLang('CURRENCY') ?>
                <div class="help"><?php showLang('PRICE_FORMAT'); ?></div> 
            </div>
            <div class="required field ">
            	<label for="id_categorie"><?php showLang('CATEGORY') ?></label>
                	<select name="id_categorie" id="id_categorie">
                    	<?php while($row_cat = $connector->fetchArray($result_cat)){ ?>
                    	<option value="<?php echo $row_cat['id']?>" <?php if($row_cat['id'] == $id_categorie) echo'selected="selected"';?> ><?php echo $row_cat['name']?></option>
                        <?php } ?>
                    </select>
            </div>
             <div class="required  field ">
				<label style="float:left"><?php showLang('MEZZES_OPTIONS') ?></label>
                <div style="float:left">
                	<div class="msg msg-info"><p><?php showLang('DRAG_AND_DROP_MEZZE') ?></p></div>
                    <div style="float:left">
                        <ul id="sortable1" class="connectedSortable"><?php
                            if($id=='')
								$result_options = $connector->query('SELECT * from options ORDER BY name');
							else
								$result_options = $connector->query("SELECT * FROM options WHERE id NOT IN(SELECT id_option FROM mezzes WHERE id_plat=".intval($id).") ORDER BY name");
							while($row = $connector->fetchArray($result_options)){ ?>
                                    <li class="ui-state-default" id="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></li><?php
                            } ?>
                        </ul>
                    </div>
                    <div style="float:left; margin:20px 20px 0 0"><img src="images/arrow_right.png" /></div>
                    <div style="float:left">
                        <ul id="sortable2" class="connectedSortable"><?php
                             if($id!=''){
									$result_options = $connector->query("SELECT O.* FROM mezzes M LEFT JOIN options O ON M.id_option=O.id WHERE id_plat=".intval($id)." ORDER BY order_position");
									while($row = $connector->fetchArray($result_options)){ ?>
										<li class="ui-state-default" id="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></li><?php
									} 
							 }  ?>
                        </ul>
                    </div>
                </div>
                <div style="clear:both"></div>
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