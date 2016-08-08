<div id="header">
  <div class="center">
    <div id="logo">
    	<a href="/" ><img src="img/admin/header/logo.png"/></a>
    	<!-- <a class="button small" href="moduleinterface.php?module=nouvelle_commande&action=default"><?php showLang('NEW_ORDER') ?></a> -->
    </div>
    <div id="userbox">
      <div id="userbox_inner">
        <h3><?php echo $_SESSION["name"]; ?></h3>
        <a class="button small" href="https://accounts.google.com/ServiceLogin?service=analytics&passive=true&nui=1&continue=https://www.google.com/analytics/settings/&followup=https://www.google.com/analytics/settings/" target="_blank"><?php showLang('STATISTICS') ?></a>
        <a class="button small" href="<?php echo $sitedir ?>" target="_blank"><?php showLang('VIEW_SITE') ?></a> 
        <a class="button small" href="moduleinterface.php?module=myaccount&action=default"><?php showLang('MY_PROFILE') ?></a>
        <a class="button small" href="logout.php"><?php showLang('LOGOUT') ?></a>
      </div>
    </div>
  </div>
</div>  
<div id="navcontainer">
    <div id="nav">
        <div id="tabs" class="center">
          <ul class="tabs"><?php
              $links = array (  array('commandes',getLang('ORDERS')),
							  	array('',getLang('CUSTOMERS'), '#subtabs-customers'),
								array('',getLang('HOMEPAGE2'), '#subtabs-homepage'),
								array('',getLang('MAP'),'#subtabs-carte'),
			  					array('photos',getLang('PHOTOS')),
								array('videos',getLang('VIDEOS')),
								array('',getLang('DELIVERY_ZONES'), '#subtabs-zones'),
								// array('faq',getLang('FAQ')),
								array('',getLang('SHOP'), '#subtabs-shop')
								// array('',getLang('LOYALTY'), '#subtabs-loyalty'),/*Modifié par Grégory*/
								// array('stats',getLang('STATS'))
                              );
				 foreach ($links as $row) {	
                 	if($row[0]=='')
                        echo '	<li><a href="'.$row[2].'">'.$row[1].'</a></li>'."\n";
					else
						echo '	<li><a class="tablink" href="moduleinterface.php?module='.$row[0].'&action=default">'.$row[1].'</a></li>'."\n";
                } ?>
          </ul>
        </div>
	</div>
  
    <div id="subnav">
        <div id="subtabs-carte" class="center">
            <ul class="subtabs">
                <li><a href="moduleinterface.php?module=plats&action=default"><?php showLang('DISHES') ?></a></li>
                <li><a href="moduleinterface.php?module=categories&action=default"><?php showLang('CATEGORIES') ?></a></li>
                <li><a href="moduleinterface.php?module=mezzes_options&action=default"><?php showLang('MEZZES_OPTIONS') ?></a></li>
                <li><a href="moduleinterface.php?module=mezzes&action=default"><?php showLang('MEZZES') ?></a></li>
                <li><a href="moduleinterface.php?module=categorie_defaut&action=default"><?php showLang('DEFAULT_CATEGORY') ?></a></li>
            </ul>
        </div>
        
        <div id="subtabs-customers" class="center">
            <ul class="subtabs">
                <!-- <li><a href="moduleinterface.php?module=nouvelle_commande&action=default"><?php showLang('NEW_ORDER') ?></a></li> -->
                <li><a href="moduleinterface.php?module=clients&action=default"><?php showLang('CUSTOMERS_LIST') ?></a></li>
                <li><a href="moduleinterface.php?module=clients&action=newsletter"><?php showLang('NEWSLETTER_LIST') ?></a></li>
                <!-- <li><a href="moduleinterface.php?module=clients&action=phone"><?php showLang('PHONE_LIST') ?></a></li> -->
            </ul>
        </div>
        
        <div id="subtabs-homepage" class="center">
            <ul class="subtabs">
                <li><a href="moduleinterface.php?module=plat_jour&action=default"><?php showLang('DISH_DAY') ?></a></li>
                <li><a href="moduleinterface.php?module=commander_ligne&action=default"><?php showLang('FREE_DELIVERY') ?></a></li>
                <li><a href="moduleinterface.php?module=slider&action=default"><?php showLang('SLIDER') ?></a></li>
                <!-- <li><a href="moduleinterface.php?module=traiteur&action=default"><?php showLang('CATERER') ?></a></li> -->
            </ul>
        </div>
          
        <div id="subtabs-zones" class="center">
            <ul class="subtabs">
                <li><a href="moduleinterface.php?module=zones_livraison&action=default"><?php showLang('MANAGE_ZONES') ?></a></li>
                <li><a href="moduleinterface.php?module=villes&action=default"><?php showLang('MANAGE_CITIES') ?></a></li>
            </ul>
        </div>
        
        <div id="subtabs-shop" class="center">
            <ul class="subtabs">
                <li><a href="moduleinterface.php?module=general&action=default"><?php showLang('BUSINESS_HOURS_PAYMENT_METHODS') ?></a></li>
                <li><a href="moduleinterface.php?module=remises&action=default"><?php showLang('VOUCHERS') ?></a></li>
                <li><a href="moduleinterface.php?module=tva&action=default"><?php showLang('TAXES') ?></a></li>
                <li><a href="moduleinterface.php?module=email&action=default"><?php showLang('EMAIL_ADDRESS') ?></a></li>
                <li><a href="moduleinterface.php?module=retards&action=default"><?php showLang('LATE_ORDERS') ?></a></li>
            </ul>
        </div>  
        
        <!--Modifié par Grégory-->
        
        <div id="subtabs-loyalty" class="center">
            <ul class="subtabs">
                <li><a href="moduleinterface.php?module=fidelite&action=default"><?php showLang('LOYALTY_PROGRAM') ?></a></li>
                <li><a href="moduleinterface.php?module=bonclient&action=default"><?php showLang('GOOD_CUSTOMER') ?></a></li>
                <li><a href="moduleinterface.php?module=mauvaisclient&action=default"><?php showLang('BAD_CUSTOMER') ?></a></li>
            </ul>
        </div>  
        
        <!--Fin modification-->
    </div>
</div>