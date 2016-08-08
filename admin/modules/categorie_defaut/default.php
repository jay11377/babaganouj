<div class="msgbox" id="msgbox" style="display:none"></div>
<?php
// Create an instance of DbConnector
$connector = new DbConnector();

// Display a message
if(isset($_SESSION['pagemsg']))
{
	echo '<div class="msgbox">';
	echo $_SESSION['pagemsg'];
	echo '</div>';
	unset($_SESSION['pagemsg']);
}

if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
	$categorie = $_POST['id_categorie'];
	$query = "UPDATE settings SET value = '".$categorie."' WHERE name = 'categorie_defaut'";
	if (!$connector->query($query) ){?>
		<script>
            $('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR') ?></p></div>');
            $('#msgbox').show();
        </script><?php
	}
	else{
		$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('DEFAULT_CATEGORY_UPDATED').'</p></div>';
		header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
	}
}
?>


<div class="box">
	<div class="header">
    	<h3><?php showLang('DEFAULT_CATEGORY_CHOICE') ?></h3>
    </div>
    <div class="container">
    	<div class="inset container">
            <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
                <fieldset>		
                    <div class="hidden">
                        <input type="hidden" name="module" value="<?php echo $module; ?>" />
                        <input type="hidden" name="action" value="<?php echo $action; ?>" />
                    </div>
                    <div class="required field ">
                        <label for="id_categorie"><?php showLang('CATEGORY') ?></label>
                        <select name="id_categorie" id="id_categorie"><?php 
                            $result = $connector->query("SELECT value FROM settings WHERE name = 'categorie_defaut'");
                            $row = $connector->fetchArray($result);
                            $current_value = $row['value'];
                            $result = $connector->query("SELECT * FROM categories ORDER BY name");
                            while($row = $connector->fetchArray($result)){ ?>
                                <option value="<?php echo $row['id']?>" <?php if($row['id'] == $current_value) echo'selected="selected"';?> ><?php echo $row['name']?></option><?php 
                            } ?>
                        </select>
                        <div class="help"><?php showLang('DEFAULT_CATEGORY_HELP'); ?></div>
                    </div>
                    <div class="buttons">
                        <p>
                          <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                        </p>
                    </div>
               </fieldset>
          </form>
       </div>
	</div>
</div>