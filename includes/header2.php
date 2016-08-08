<div class="container header">
	<div class="row">
	    <div class="col-md-4">
		    <div class="logo">
			    <a href="index.php"><img src="image/logo3.png" alt="ma bento" /></a>
			</div>
		</div>
		<div class="col-md-3 text-center" id="account_links"><?php
			if(isset($_SESSION['id_client'])) : ?> 
				<?php showLang('WELCOME') ?> <?php echo $_SESSION['nom']?><br />
	            <a href="mon_compte" class="link-logged"><i class="fa fa-user icon-left-first fa-3x"></i></a>
	            <a href="#" id="deconnexion" class="link-logged"> <i class="fa fa-power-off icon-left fa-3x"></i></a><?php
			else : ?>
				<a href="creer_compte.php"><i class="fa fa-pencil"></i> Créer un compte</a> <a href="identification.php"><i class="fa fa-lock"></i> S'identifier</a><?php
			endif; ?>
		</div>
		<div class="col-md-5">
		    <div id="search">
                <input type="text" placeholder="Rechercher" name="filter_name">
                <div class="button-search"></div>
            </div>
		    <div class="cart dropdown">
				<img alt="cart empty" src="image/shopping_basket.png"> 
				<a href="cart.html" class="dropdown-toggle" data-toggle="dropdown"><span id="nb_items"></span> articles - <span id="total_cart"></span></a>
				<div class="cart-info dropdown-menu" id="produits_panier">
				</div> 									
			</div>
		</div>
	</div>
</div>




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