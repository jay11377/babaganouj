<div class="inset container">
    <form method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data">
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
                <legend><?php showLang('ADDRESSES') ?></legend>
				<div class="add_address"><a href="moduleinterface.php?module=adresses&action=add&id_client=<?php echo $row['id'] ?>"><?php showLang('ADD_ADDRESS') ?></a></div><?php
                $query = "SELECT * FROM adresses WHERE id_client=".(int)$id. " AND active=1 ORDER BY titre_adresse";
                $result_addresses = $connector->query($query);
                $i=1;
				while($row_addresses = $connector->fetchArray($result_addresses))
                {
					?>
                    <div class="address_info <?php if($i%3==1) echo "first"; ?>">
                        <h3><?php if($row_addresses['defaut']==1) : ?><img src="images/defaut.png" align="baseline" alt="<?php showLang('DEFAULT_ADDRESS') ?>" title="<?php showLang('DEFAULT_ADDRESS') ?>" />&nbsp;&nbsp<?php endif; echo osql($row_addresses['titre_adresse']); ?></h3>
                        <p><?php
                            if($row_addresses['societe']!='')
                                echo osql($row_addresses['societe'])."<br />"; ?>
                             <?php echo osql($row_addresses['prenom']) ?> <?php echo osql($row_addresses['nom']) ?><br />
                             <?php echo osql($row_addresses['adresse1']) ?><br /><?php
                             if($row_addresses['adresse2']!='')
                                echo osql($row_addresses['adresse2'])."<br />"; ?>
                             <?php echo $row_addresses['cp'] ?> <?php echo osql($row_addresses['ville']) ?><br />
                             <?php echo $row_addresses['telephone'] ?><br />----------------------------<br /><?php
                             if($row_addresses['code_entree']!='')
                                echo getLang('ENTRY_CODE').' : '.osql($row_addresses['code_entree'])."<br />";
                             if($row_addresses['interphone']!='')
                                echo getLang('INTERCOM').' : '.osql($row_addresses['interphone'])."<br />";
                             if($row_addresses['service']!='')
                                echo getLang('SERVICE').' : '.osql($row_addresses['service'])."<br />";
                             if($row_addresses['escalier']!='')
                                echo getLang('STAIRCASE').' : '.osql($row_addresses['escalier'])."<br />";
                             if($row_addresses['etage']!='')
                                echo getLang('FLOOR').' : '.osql($row_addresses['etage'])."<br />";
                             if($row_addresses['numero_appartement']!='')
                                echo getLang('APARTMENT_NUMBER').' : '.osql($row_addresses['numero_appartement'])."<br />";
                             if($row_addresses['remarque']!='')
                                echo getLang('COMMENT').' : '.osql($row_addresses['remarque'])."<br />";
                            ?>
                        </p>
                        <p class="update_adress_block">
                            <a href="moduleinterface.php?module=adresses&action=edit&id=<?php echo $row_addresses['id']; ?>&id_client=<?php echo $row['id'] ?>"><?php showLang('UPDATE') ?></a><?php
                            if($connector->getNumRows($result_addresses)>1) : ?>
                                <br /><a href="moduleinterface.php?module=adresses&action=delete&id=<?php echo $row_addresses['id']; ?>&id_client=<?php echo $row['id'] ?>" onclick="return confirm('<?php showLang('DELETE_MSG') ?>');"><?php showLang('DELETE') ?></a><?php
                            endif;
                            if($row_addresses['defaut']==0) : ?>
                                <br /><a href="moduleinterface.php?module=adresses&action=make_default&id=<?php echo $row_addresses['id']; ?>&id_client=<?php echo $row['id'] ?>"><?php showLang('MAKE_DEFAULT_ADDRESS') ?></a><?php
                            endif; ?>
                        </p>
                    </div><?php
					if($i%3==0) echo '<br class="clear">';
					$i++;
                }
                ?>
            </fieldset>
              
            <div class="buttons">
                <p>
                  <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                  <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                </p>
            </div>
    </form>
</div>