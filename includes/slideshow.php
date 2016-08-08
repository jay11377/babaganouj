<?php
$query="SELECT * FROM slider WHERE active=1 ORDER BY order_position";
$result = $connector->query($query);
?>

<div class="row rowslider">
    <div class="col-md-12">
		<div class="flexslider">
		    <ul class="slides"><?php
				while($row = $connector->fetchArray($result)){ ?>
					<li><?php
						if($row['type_lien']!='aucun'):
							if($row['type_lien']=="categorie")
								$link = getURLCategorie($row['id_lien']);
							else
							{
								$query_plat="SELECT id_categorie FROM plats WHERE id=".(int)$row['id_lien'];
								$result_plat = $connector->query($query_plat);	 
								$row_plat = $connector->fetchArray($result_plat);
								if($row_plat['id_categorie']==1)
									$link = getURLChoixMezze($row['id_lien']);
								else
									$link = getURLPlat($row['id_lien']);
							} ?>
							<a href="<?php echo $link; ?>"><?php
						endif; ?>
						<img src="<?php echo $row['photo']?>" alt="<?php echo $row['commentaire']?>" /><?php
						if($row['type_lien']!='aucun')
							echo "</a>"; ?>
					</li><?php
				} ?>
		    </ul>
		</div>
	</div>
</div>