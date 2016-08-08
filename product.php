<?php 
include("includes/top_includes.php"); 
$id=$_GET['id'];
$query="SELECT * FROM plats WHERE id=".(int)$id;
$result = $connector->query($query);
$row = $connector->fetchArray($result);
?>
<!doctype html>
<html>
    <head>
	    <?php include("includes/head.php"); ?>
	    <title><?php echo osql($row['name']) ?> - <?php showLang('PAGE_TITLE_COMMON') ?></title>
		<link href="css/slider_menu.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<div class="page-container container">
    <?php include("includes/header.php"); ?>
    <div class="container">
    	<ul class="breadcrumb">
		    <li><a href="commander.php">Commander en ligne</a> <span class="divider"></span></li>
			<li class="active"><?php echo osql($row['name']) ?></li>
	    </ul>

		<div class="row product-info">
		    <div class="col-md-6">					
				<div class="image"><a class="cloud-zoom" rel="adjustX: 0, adjustY:0" id='zoom1' href="<?php echo $row['bigphoto1'] ?>" title="<?php echo osql($row['name'])?>"><img src="<?php echo $row['bigphoto1'] ?>" title="<?php echo osql($row['name'])?>" alt="<?php echo osql($row['name'])?>" id="image" /></a></div><?php
				if($row['photo2'] || $row['photo3']): ?>
					<div class="image-additional">
						<a title="<?php echo osql($row['name'])?>" rel="useZoom: 'zoom1', smallImage: '<?php echo $row['bigphoto1'] ?>'" class="cloud-zoom-gallery" href="<?php echo $row['bigphoto1'] ?>"><img alt="<?php echo osql($row['name'])?>" title="<?php echo osql($row['name'])?>" src="<?php echo $row['bigphoto1'] ?>"></a><?php
						if($row['photo2']): ?>
							<a title="<?php echo osql($row['name'])?>" rel="useZoom: 'zoom1', smallImage: '<?php echo $row['bigphoto2'] ?>'" class="cloud-zoom-gallery" href="<?php echo $row['bigphoto2'] ?>"><img alt="<?php echo osql($row['name'])?>" title="<?php echo osql($row['name'])?>" src="<?php echo $row['bigphoto2'] ?>"></a><?php
						endif;
						if($row['photo3']): ?>
							<a title="<?php echo osql($row['name'])?>" rel="useZoom: 'zoom1', smallImage: '<?php echo $row['bigphoto3'] ?>'" class="cloud-zoom-gallery" href="<?php echo $row['bigphoto3'] ?>"><img alt="<?php echo osql($row['name'])?>" title="<?php echo osql($row['name'])?>" src="<?php echo $row['bigphoto3'] ?>"></a><?php
						endif; ?>
					</div><?php
				endif; ?>
  			</div>
		    <div class="col-md-6">
				<h1><?php echo osql($row['name']) ?></h1>
				    <div class="line"></div>
						<ul>
							<li><span>Disponibilité : </span>En stock</li>
						</ul>
					<div class="price">
						<strong><?php echo showPriceCurrency($row['prix_ttc']) ?></strong>
					</div>
					<?php
				    if($row['menu']) : ?>
				    	<form class="form-horizontal form-choix-menu"><?php
				    	$query_mezze="SELECT M.id_option, O.name 
									    FROM mezzes M, options O 
									   WHERE M.id_option = O.id
										 AND active = 1
										 AND M.id_plat=$id
									ORDER BY order_position";
						$result_mezze = $connector->query($query_mezze);
						$i=0;
						while($row_mezze = $connector->fetchArray($result_mezze))
						{ 
							$class = ($i%2==0) ? 'mezze_choix_fs_left' : ''; 
							$query_options = "SELECT O.id_item, O.quantity, O.prix_ht, O.prix_ttc, P.name, P.photo1
											    FROM options_items O, plats P
											   WHERE O.id_item = P.id
											   	 AND O.active = 1
												 AND P.active = 1
											     AND id_option = ".$row_mezze['id_option']."
										    ORDER BY O.order_position"; 
							$result_options = $connector->query($query_options); ?>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $row_mezze['name'] ?></label>
							    <div class="col-sm-10">	
								    <select class="form-control select-image"><?php
										while($row_options = $connector->fetchArray($result_options)){ ?>
											<option data-supplement_ht="<?php echo $row_options['prix_ht'] ?>" data-supplement_ttc="<?php echo $row_options['prix_ttc'] ?>"><?php echo $row_options['name'].($row_options['prix_ttc']>0 ? " + ".showPriceCurrency($row_options['prix_ttc']) : "") ?></option><?php
										} ?>
									</select>
									<!-- <i class="fa fa-picture-o fa-2x"></i> -->
							    </div>
							</div><?php
						} ?>
						</form><?php
					endif ?>
					<div class="line"></div>
					<div class="quantite" data-quantite="1">
	                  <i class="fa fa-minus-square fa-3x"></i>
	                  <div class="inline-block quantite_numero">1</div>                        
	                  <i class="fa fa-plus-square fa-3x"></i>
	                  <i class="fa fa-shopping-cart fa-2x fa-lg importantButton <?php echo ($row['menu'] ? 'bouton_ajouter_menu' : 'bouton_ajouter'); ?>" data-id="<?php echo $row['id'] ?>"></i>
	                </div>
					<div class="tabs">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active"><a href="#home">Description</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="home">
								<?php echo $row['description'] ?>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
<script language="javascript" type="text/javascript" src="js/cloud-zoom.1.0.3.js"></script>
<script>

$(document).on('change','select.select-image',function(e){
	price = <?php echo $row['prix_ttc'] ?>;
	price += parseFloat($(this).find(":selected").data('supplement_ttc'));
	$('.price strong').html(showPriceCurrency(price));
	for(i=0;i<3;i++) {
	    $('.price').fadeTo('slow', 0.5).fadeTo('slow', 1.0);
	}
});

$.fn.CloudZoom.defaults = {
	zoomWidth:"auto",
	zoomHeight:"auto",
	position:"inside",
	adjustX:0,
	adjustY:0,
	adjustY:"",
	tintOpacity:0.5,
	lensOpacity:0.5,
	titleOpacity:0.5,
	smoothMove:3,
	showTitle:false
};
</script>
</body>
</html>
