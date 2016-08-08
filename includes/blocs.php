<?php 
$query="SELECT name, photo FROM commander_ligne WHERE active=1";
$result = $connector->query($query);
$row_order_online = $connector->fetchArray($result);


$query="SELECT id_plat, name, photo FROM plat_jour WHERE active=1";
$result = $connector->query($query);
$row_special = $connector->fetchArray($result);

$query_category_special = "SELECT id_categorie FROM plats WHERE id=".$row_special['id_plat'];
$result_category_special = $connector->query($query_category_special);	 
$row_category_special = $connector->fetchArray($result_category_special);
if($row_category_special['id_categorie']==1)
	$link_special = getURLChoixMezze($row_special['id_plat']);
else
	$link_special = getURLPlat($row_special['id_plat']);
?>
<div class="row blocs_index">
	<div class="col-md-4">
		<img src="<?php echo $row_order_online['photo'] ?>" title="livraison" />
	</div>
	<div class="col-md-4">
		<div class="relative zones_livraison">
			<img src="image/carte_neuilly_courbevoie_puteaux.png" title="carte Neuilly, courbevoie, puteaux" />
			<div class="bloc_label">Zones de livraison</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="relative">
			<a href="<?php echo $link_special ?>"><img src="<?php echo $row_special['photo'] ?>" title="<?php echo $row_special['name'] ?>" alt="<?php echo $row_special['name'] ?>" /></a>
			<div class="bloc_label"><a href="<?php echo $link_special ?>"><?php echo $row_special['name'] ?></a></div>
		</div>
	</div>
</div>