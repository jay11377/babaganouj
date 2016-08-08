<?php
include("includes/top_includes.php");
$conn = new DbConnector();

$query = "SELECT * FROM adresses WHERE id_client=".$_SESSION['id_client']. " AND active=1 ORDER BY titre_adresse";
$result = $conn->query($query);
$i=1;
?>
<div class="row">
<?php
while($row = $conn->fetchArray($result))
{ 
	?>
    <div class="address_info col-md-4">
        <h3><?php if($row['defaut']==1) : ?><i class="fa fa-star"></i>&nbsp;&nbsp<?php endif; echo osql($row['titre_adresse']); ?></h3>
        <p><?php
            if($row['societe']!='')
                echo osql($row['societe'])."<br />"; ?>
             <?php echo osql($row['prenom']) ?> <?php echo osql($row['nom']) ?><br />
             <?php echo osql($row['adresse1']) ?><br /><?php
             if($row['adresse2']!='')
                echo osql($row['adresse2'])."<br />"; ?>
             <?php echo $row['cp'] ?> <?php echo osql($row['ville']) ?><br />
             <?php echo $row['telephone'] ?><br />----------------------------<br /><?php
			 if($row['code_entree']!='')
                echo getLang('ENTRY_CODE').' : '.osql($row['code_entree'])."<br />";
			 if($row['interphone']!='')
                echo getLang('INTERCOM').' : '.osql($row['interphone'])."<br />";
			 if($row['service']!='')
                echo getLang('SERVICE').' : '.osql($row['service'])."<br />";
			 if($row['escalier']!='')
                echo getLang('STAIRCASE').' : '.osql($row['escalier'])."<br />";
			 if($row['etage']!='')
                echo getLang('FLOOR').' : '.osql($row['etage'])."<br />";
			 if($row['numero_appartement']!='')
                echo getLang('APARTMENT_NUMBER').' : '.osql($row['numero_appartement'])."<br />";
			 if($row['remarque']!='')
                echo getLang('COMMENT').' : '.osql($row['remarque'])."<br />";
			?>
        </p>
        <p class="update_adress_block">
            <a href="" class="update_address"><?php showLang('UPDATE') ?></a><?php
            if($conn->getNumRows($result)>1) : ?>
            	<br /><a href="" class="delete_address"><?php showLang('DELETE') ?></a><?php
			endif;
			if($row['defaut']==0) : ?>
            	<br /><a href="" class="default_address" rel="<?php echo $row['id'] ?>-<?php echo $_SESSION['id_client'] ?>"><?php showLang('MAKE_DEFAULT_ADDRESS') ?></a><?php
			endif; ?>
        </p>
    </div><?php
} ?>
</div><?php

$result = $conn->query($query);
while($row = $conn->fetchArray($result))
{ ?>
<div class="row address_form">
    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="post" name="form" action="">
            <input type="hidden" class="delete_msg" value="<?php showLang("DELETE_ADDRESS_CONFIRM") ?>" />
            <input type="hidden" class="id_adresse" name="id_adresse" value="<?php echo $row['id'] ?>" />
            <div class="required">* <?php showLang('REQUIRED_FIELD') ?></div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="titre_adresse"><?php showLang('ADDRESS_TITLE') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control titre_adresse" value="<?php echo osql($row['titre_adresse']) ?>" name="titre_adresse">
                </div>            
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="societe"><?php showLang('COMPANY') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control societe societe" value="<?php echo osql($row['societe']) ?>" name="societe">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="prenom_adresse"><?php showLang('FIRST_NAME') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control prenom_adresse" value="<?php echo osql($row['prenom']) ?>" name="prenom_adresse">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="nom"><?php showLang('LAST_NAME') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control nom_adresse" value="<?php echo osql($row['nom']) ?>" name="nom_adresse">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="adresse1"><?php showLang('ADDRESS') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control adresse1" value="<?php echo osql($row['adresse1']) ?>" name="adresse1">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="adresse2"><?php showLang('ADDRESS2') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control adresse2" value="<?php echo osql($row['adresse2']) ?>" name="adresse2">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="cp"><?php showLang('POSTCODE') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control cp" value="<?php echo $row['cp'] ?>" name="cp">
                </div>
            </div>
            <div class="form-group hidden">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-9">
                    <p class="bg-danger"><?php showLang('CP_WARNING') ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="ville"><?php showLang('CITY') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control ville" value="<?php echo osql($row['ville']) ?>" name="ville">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="telephone"><?php showLang('PHONE') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control telephone" value="<?php echo osql($row['telephone']) ?>" name="telephone">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="code_entree"><?php showLang('ENTRY_CODE') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control code_entree" value="<?php echo osql($row['code_entree']) ?>" name="code_entree">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="interphone"><?php showLang('INTERCOM') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control interphone" value="<?php echo osql($row['interphone']) ?>" name="interphone">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="service"><?php showLang('SERVICE') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control service" value="<?php echo osql($row['service']) ?>" name="service">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="escalier"><?php showLang('STAIRCASE') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control escalier" value="<?php echo osql($row['escalier']) ?>" name="escalier">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="etage"><?php showLang('FLOOR') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control etage" value="<?php echo osql($row['etage']) ?>" name="etage">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="numero_appartement"><?php showLang('APARTMENT_NUMBER') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control numero_appartement" value="<?php echo osql($row['numero_appartement']) ?>" name="numero_appartement">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="remarque"><?php showLang('COMMENT') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control remarque" value="<?php echo osql($row['remarque']) ?>" name="remarque">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <input type="hidden" class="defaut" name="defaut" value="<?php echo $row['defaut'] ?>" />
                    <button class="btn btn-primary cancel_update_address" type="submit" value="<?php showLang('CANCEL') ?>"><?php showLang('CANCEL') ?></button>
                    <button class="btn btn-primary" type="submit" name="submit" value="<?php showLang('UPDATE') ?>"><?php showLang('UPDATE') ?></button>
                </div>
            </div>
        </form>       
    </div>
</div>

<?php
} ?>

<div class="row address_form_empty">
    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="post" name="form" action="">
            <input type="hidden" class="delete_msg" value="<?php showLang("DELETE_ADDRESS_CONFIRM") ?>" />
            <input type="hidden" class="id_adresse" name="id_adresse" value="<?php echo $row['id'] ?>" />
            <div class="required">* <?php showLang('REQUIRED_FIELD') ?></div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="titre_adresse"><?php showLang('ADDRESS_TITLE') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control titre_adresse" value="<?php echo osql($row['titre_adresse']) ?>" name="titre_adresse">
                </div>            
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="societe"><?php showLang('COMPANY') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control societe societe" value="<?php echo osql($row['societe']) ?>" name="societe">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="prenom_adresse"><?php showLang('FIRST_NAME') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control prenom_adresse" value="<?php echo osql($row['prenom']) ?>" name="prenom_adresse">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="nom"><?php showLang('LAST_NAME') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control nom_adresse" value="<?php echo osql($row['nom']) ?>" name="nom_adresse">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="adresse1"><?php showLang('ADDRESS') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control adresse1" value="<?php echo osql($row['adresse1']) ?>" name="adresse1">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="adresse2"><?php showLang('ADDRESS2') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control adresse2" value="<?php echo osql($row['adresse2']) ?>" name="adresse2">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="cp"><?php showLang('POSTCODE') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control cp" value="<?php echo $row['cp'] ?>" name="cp">
                </div>
            </div>
            <div class="form-group hidden">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-9">
                    <p class="bg-danger"><?php showLang('CP_WARNING') ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="ville"><?php showLang('CITY') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control ville" value="<?php echo osql($row['ville']) ?>" name="ville">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="telephone"><?php showLang('PHONE') ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control telephone" value="<?php echo osql($row['telephone']) ?>" name="telephone">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="code_entree"><?php showLang('ENTRY_CODE') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control code_entree" value="<?php echo osql($row['code_entree']) ?>" name="code_entree">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="interphone"><?php showLang('INTERCOM') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control interphone" value="<?php echo osql($row['interphone']) ?>" name="interphone">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="service"><?php showLang('SERVICE') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control service" value="<?php echo osql($row['service']) ?>" name="service">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="escalier"><?php showLang('STAIRCASE') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control escalier" value="<?php echo osql($row['escalier']) ?>" name="escalier">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="etage"><?php showLang('FLOOR') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control etage" value="<?php echo osql($row['etage']) ?>" name="etage">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="numero_appartement"><?php showLang('APARTMENT_NUMBER') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control numero_appartement" value="<?php echo osql($row['numero_appartement']) ?>" name="numero_appartement">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="remarque"><?php showLang('COMMENT') ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control remarque" value="<?php echo osql($row['remarque']) ?>" name="remarque">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <input type="hidden" name="back" value="<?php echo $_POST['backlink'] ?>" />
                    <input type="hidden" class="defaut" name="defaut" value="0" />
                    <input type="hidden" name="step" value="<?php echo $_POST['step'] ?>" />
                    <button class="btn btn-primary" id="cancel_add_address" type="submit" value="<?php showLang('CANCEL') ?>"><?php showLang('CANCEL') ?></button>
                    <button class="btn btn-primary" type="submit" name="submit" value="<?php showLang('SUBMIT') ?>"><?php showLang('SUBMIT') ?></button>
                </div>
            </div>
        </form>       
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <button class="btn btn-primary" id="add_new_address" type="submit" name="add_address" value="<?php showLang('ADD_ADDRESS') ?>"><?php showLang('ADD_ADDRESS') ?></button>
    </div>
</div>