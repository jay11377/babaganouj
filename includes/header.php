<div class="container header">
	<div class="row topbg startbg">
	    <div class="col-md-4">
		    <div class="logo">
			    <a href="commander.php"><img src="image/logo2.png" alt="ma bento" /></a>
			</div>
		</div>
		<div class="col-md-3" id="account_links"><?php
			if(isset($_SESSION['id_client'])) : ?> 
				<?php showLang('WELCOME') ?> <?php echo $_SESSION['nom']?><br />
	            <a href="mon_compte" class="link-logged"><i class="fa fa-user icon-left-first fa-3x"></i></a>
	            <a href="#" id="deconnexion" class="link-logged"> <i class="fa fa-power-off icon-left fa-3x"></i></a><?php
			else : ?>
				<a href="creer_compte.php"><i class="fa fa-pencil"></i> Créer un compte</a> <a href="identification.php"><i class="fa fa-lock"></i> S'identifier</a><?php
			endif; ?>
            <div class="top_info">
                Restaurant libanais, Traiteur<br />
                Livraison à Domicile<br />
                Vente à Emporter<br />
            </div>
		</div>
		<div class="col-md-5">
		    <div id="search">
                <input type="text" placeholder="Rechercher" name="filter_name">
                <div class="button-search"></div>
            </div>
		    <div class="cart dropdown">
				<img alt="cart empty" src="image/shopping_basket2.png"> 
				<a href="cart.html" class="dropdown-toggle" data-toggle="dropdown"><span id="nb_items">7</span> articles - <span id="total_cart">25,10 €</span></a>
				<div class="cart-info dropdown-menu" id="produits_panier">
				</div> 									
			</div>
            <div class="top_info top_info_right">
                134 Avenue Aristide Briand<br />
                92160 Antony<br />
                <a href="contact.php" style="display:block; margin:3px 0"><i class="fa fa-exchange"></i> Contact</a>
            </div>
		</div>
	</div>
    <div class="row topbg endbg">
        <div class="col-md-12">
            <!-- Menu > 768 -->
            <div id="" class="main-mobile-menu hidden-xs fixNavigation">
                <div class="main-mobile-menu-wrap">
                    <div class="wrap-title" data-toggle="collapse" data-parent="#collapseMenu" href="#collapseOne">
                        <h4>Commander en ligne</h4>
                        <span class="open-mobile"></span>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <ul class="menu-mobile">
                            <li><a href="category.php?id=14">Salades</a></li>
                            <li><a href="category.php?id=7">Hors d'oeuvre froids</a></li>
                            <li><a href="category.php?id=1">Mezzés</a></li>
                            <li><a href="category.php?id=11">Boissons</a></li>
                            <li><a href="category.php?id=6">Bouchées Salées</a></li>
                            <li><a href="category.php?id=9">Hors d'oeuvres chauds</a></li>
                            <li><a href="category.php?id=3">Sandwichs chauds</a></li>
                            <li><a href="category.php?id=12">Vins & bières</a></li>
                            <li><a href="category.php?id=4">Assiettes variées</a></li>
                            <li><a href="category.php?id=15">Grillades & viandes</a></li>
                            <li><a href="category.php?id=10">Desserts</a></li>
                            <li><a href="category.php?id=1">Formules</a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <!-- Menu < 768 -->
            
            <div id="collapseMenuMobile" class="main-mobile-menu visible-xs fixNavifation">
                <div class="main-mobile-menu-wrap">
                    <div class="wrap-title" data-toggle="collapse" data-parent="#collapseMenuMobile" href="#collapseMobile">
                        <h4>Carte</h4>
                        <span class="open-mobile"></span>
                    </div>
                    <div id="collapseMobile" class="panel-collapse collapse">
                        <ul class="menu-mobile">
                                                    <li>
                                    <a href="category.php?id=5">Accompagnements</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/6">Bouch&eacute;es Sal&eacute;s </a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/3">Sandwiches</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/14">Salades</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/7">Hors-d&#039;oeuvre Froids</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/9">Hors-d&#039;oeuvre Chauds</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/15">Grillades</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/4">Plats Gourmands</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/1">Mezze &amp; Formule</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/10">Desserts</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/11">Boissons Fraiches &amp; Bi&egrave;re</a>                        </li>  
                                                    <li>
                                    <a href="http://laravel.jprojet.fr/category/12">Vins &amp; Arak du Liban </a>                        </li>  
                             
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





<!--
<div class="container menu">
	<div class="row">
	    <div class="col-md-12">
			
          <div class="navbar">
 
			  <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				  <span class="sr-only">Toggle navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
			  </div>

             
             <div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-left">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="concept.php">Concept</a></li>
                    <li><a href="photos.php">Galerie photo</a></li>
                    <li><a href="video.php">Vidéos</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li style="background:#d94848;"><a href="commander.php" style="color:#fff">Commander en ligne</a></li>
				</ul>
				</div>
				</div>
            </div>
	</div>
</div>
-->