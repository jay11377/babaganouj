<?php 
include("includes/top_includes.php");
?>
<div id="right_wrapper">
    <div id="right_inner">
        <div id="livraison">
            <fieldset id="livraison_fs">
                <legend><?php showLang('DELIVERY') ?></legend>
            </fieldset>
            <a id="zones_livraison" href=""><?php showLang('CHECK_WHERE_WE_DELIVER') ?></a>
            <div class="souhait"><?php showLang('YOU_WISH') ?> :</div>
            <div class="livraison_info"><?php showLang('GET_DELIVERED_ON') ?> <span class="blanc" id="livraison_date"><?php echo isset($_SESSION['deliveryDate']) ? $_SESSION['deliveryDate'] : ""  ?></span></div>
            <div class="livraison_info"><?php showLang('HOUR') ?> : <span class="blanc" id="livraison_heure"><?php echo isset($_SESSION['deliveryTime']) ? $_SESSION['deliveryTime'] : "" ?></span></div>
            <div class="livraison_info"><?php showLang('CITY') ?> : <span class="blanc" id="livraison_ville"><?php echo isset($_SESSION['deliveryCity']) ? $_SESSION['deliveryCity'] : "" ?></span></div>
            <div class="livraison_info"><?php showLang('MINIMUM_DELIVERY') ?> : <span class="blanc" id="livraison_min"><?php echo isset($_SESSION['orderMin']) ? $_SESSION['orderMin']." ".getLang('CURRENCY') : "" ?></span></div>
            <div class="livraison_modif"><input type="button" class="bouton" value="Modifier" /></div>
        </div>
        <div id="panier">
            <fieldset id="panier_fs">
                <legend><?php showLang('MY_SHOPPING_BAG') ?></legend>
            </fieldset>
            <div id="produits_panier"></div>
        </div>
    </div>
</div>