<script>
<!--
function change_choice(){
	for (var i=0; i < document.form.type_lien.length; i++)
	{
	   if (document.form.type_lien[i].checked)
		  {
		  var rad_val = document.form.type_lien[i].value;
		  }
	}
	document.getElementById('categorie_block').style.display = 'none';
	document.getElementById('plat_block').style.display = 'none';
	switch(rad_val){
		case 'categorie': document.getElementById('categorie_block').style.display = ''; break;
		case 'plat': document.getElementById('plat_block').style.display = ''; break;
	}
}
-->
</script>
<?php
$result = $connector->query("SELECT id,name FROM categories WHERE active=1 ORDER BY name"); /* Get the list of categories */
$result2 = $connector->query("SELECT id,name FROM plats WHERE active=1 ORDER BY name"); /* Get the list of dishes */
?>
<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
        <fieldset>
            <div class="hidden">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
            </div>
            <div class="field ">
            	<label for="commentaire"><?php showLang('COMMENT') ?></label>
                <input type="text" name="commentaire" id ="commentaire" class="long" value="<?php echo osql($commentaire);?>" > 
            </div>
             <label></label>
             <?php if($_GET['action']=='edit') echo '<img src="../'.$row['thumbnail'].'">' ?>
             
             <div class="required field">
                <label for="image"><?php showLang('IMAGE') ?></label>
                <span class="upload">
                    <input type="text" value="" readonly="readonly" name="fakeimage" class="fake">
                    <span class="wrapper">
                        <span class="button"><?php showLang('UPLOAD') ?></span>
                        <input type="file" onchange="this.form.fakeimage.value = this.value;" class="real" value="" name="image" id="image">
                    </span><br /><br />
                </span>
                <div class="help"><?php showLang('SIZE_910_422'); ?><br /><br /><?php showLang('IMG_SIZE_2M'); ?></div>
            </div>
            <div class="required field">
                <label for="image"><?php showLang('LINK') ?></label>
                <input type="radio" name="type_lien" value="aucun" onclick="change_choice()" <?php if($type_lien=='aucun' || $type_lien=='') echo 'checked="checked"'; ?> /><span class="radiolabel"><?php showLang('NONE') ?></span>
                <input type="radio" name="type_lien" value="categorie" onclick="change_choice()" <?php if($type_lien=='categorie') echo 'checked="checked"'; ?> /><span class="radiolabel"><?php showLang('CATEGORY') ?></span>
                <input type="radio" name="type_lien" value="plat" onclick="change_choice()" <?php if($type_lien=='plat') echo 'checked="checked"'; ?> /><span class="radiolabel"><?php showLang('DISH') ?></span>
                <div class="help"><?php showLang('SLIDER_LINK_HELP'); ?></div>
            </div>
            <div class="field" id="categorie_block" style="display:none"> 
                <label for="id_categorie">&nbsp;</label>
                <select id="id_categorie" name="id_categorie"><?php
                	while ($row = $connector->fetchArray($result)){ ?>
                    	<option value="<?php echo $row['id'] ?>" <?php if($type_lien=="categorie" && $id_lien == $row['id']) echo 'selected = "selected"'?> ><?php echo $row['name'] ?></option><?php 
					} ?>
           		</select>
                <div class="help"></div>
            </div>
            <div class="field" id="plat_block" style="display:none"> 
                <label for="id_plat">&nbsp;</label>
                <select id="id_plat" name="id_plat"><?php
                    while ($row2 = $connector->fetchArray($result2)){ ?>
                    	<option value="<?php echo $row2['id'] ?>" <?php if($type_lien=="plat" && $id_lien == $row2['id']) echo 'selected = "selected"'?> ><?php echo $row2['name'] ?></option><?php 
					} ?>
           		</select>
                <div class="help"></div>
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
<script>
change_choice();
</script>