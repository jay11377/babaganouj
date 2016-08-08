<div class="row" id="menuTabs">
	<div class="col-md-12">
		<ul id="myTab" class="nav nav-tabs">		   
			<?php
			$query_cat="SELECT id,name FROM categories WHERE active=1 ORDER BY order_position";
			$result_cat = $connector->query($query_cat);
			$i=0;
			while($row_cat = $connector->fetchArray($result_cat)){ ?>
		        <li<?php echo ($i==0)?' class="active"':'' ?>><a href="#<?php echo str2url($row_cat['name']) ?>" data-toggle="tab"><?php echo osql($row_cat['name']) ?></a></li><?php 		        
				$i++;
			}
			?>
		</ul>
		<div id="myTabContent" class="tab-content">
			<?php
			$query_cat="SELECT id,name FROM categories WHERE active=1 ORDER BY order_position";
			$result_cat = $connector->query($query_cat);
			$i=0;
			while($row_cat = $connector->fetchArray($result_cat))
			{ ?>
				<div class="tab-pane fade in<?php echo ($i==0)?' active':'' ?>" id="<?php echo str2url($row_cat['name']) ?>">
					<div class="row">
						<?php
						$query="SELECT * FROM plats WHERE id_categorie=".(int)$row_cat['id']." AND active=1 ORDER BY order_position";
						$result = $connector->query($query);
						$i=0;
						while($row = $connector->fetchArray($result)){  ?>
							<div class="col-md-4">
							    <div class="product"><?php
							    	if($row['ingredient_principal'])
							    	{ ?>
										<div class="product_sale<?php echo ($row['vegetarien']==1)?' vegetarian' : ''?>"><?php echo osql($row['ingredient_principal']) ?></div><?php
									} ?>
								    <a href="<?php echo getURLPlat($row['id']) ?>"><img alt="<?php echo osql($row['name']) ?>" src="<?php echo $row['photo1']?>"></a>
									<div class="name"><a href="<?php echo getURLPlat($row['id']) ?>"><?php echo osql($row['name']) ?></a></div>
								    <div class="price"><p><?php echo showPriceCurrency($row['prix_ttc']) ?></p></div><?php
								    if($row['menu']) : ?>
										<div class="quantite" data-quantite="1">
											<a href="<?php echo getURLPlat($row['id']) ?>"><i class="importantButton pickmenu" data-id="<?php echo (int)$row['id'] ?>"><?php showLang('PICK_MENU_OPTIONS') ?></i></a>
										</div><?php
						            else : ?>
										<div class="quantite" data-quantite="1">
						                  <i class="fa fa-minus-square fa-3x"></i>
						                  <div class="inline-block quantite_numero">1</div>                        
						                  <i class="fa fa-plus-square fa-3x"></i>
						                  <i class="fa fa-shopping-cart fa-2x fa-lg importantButton bouton_ajouter" data-id="<?php echo (int)$row['id'] ?>"></i>
						                </div><?php
						            endif; ?>
								</div>
							</div><?php
						} ?>
					</div>
				</div><?php
				$i++;
			}
			?>
		</div>
	</div>	
</div>