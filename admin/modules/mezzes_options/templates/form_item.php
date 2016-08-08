<?php
    $result = $connector->query("SELECT * FROM tva WHERE id=1");
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
})
</script>
<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                <input type="hidden" name="id_option" value="<?php echo $id_option; ?>" />
            </div>
           
             <div class="required  field">
            	<label for="id_item"><?php showLang('OPTION_DISH') ?></label>
                <select name="id_item" id="id_item">
                    <?php while ($row = $connector->fetchArray($result_plats)){ ?>
                        <option value="<?php echo $row["id"]?>" > <?php echo $row["name"]?></option>
                    <?php }?>
                </select>
            </div>
            
            <div class="required  field ">
            	<label for="quantity"><?php showLang('QUANTITY') ?></label>
                <select name="quantity">
                	<?php
						for($i=1;$i<11;$i++)
						{ ?>
							<option value="<?php echo $i ?>" <?php if($quantity==$i) echo ' selected="selected"' ?>><?php echo $i; ?></option><?php
						}
					?>
                </select>
            </div>

            <div class="required field ">
                <label for="prix_ht"><?php showLang('EXTRA_NO_TAX') ?></label>
                <input type="text" name="prix_ht" id ="prix_ht" class="short" value="0"> <?php showLang('CURRENCY') ?>
                <div class="help"><?php showLang('PRICE_FORMAT'); ?></div> 
            </div>
            
            <div class="required field ">
                <label for="prix_ttc"><?php showLang('EXTRA_WITH_TAX') ?></label>
                <input type="text" name="prix_ttc" id ="prix_ttc" class="short" value="0"> <?php showLang('CURRENCY') ?>
                <div class="help"><?php showLang('PRICE_FORMAT'); ?></div> 
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