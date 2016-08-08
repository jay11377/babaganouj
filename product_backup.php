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
						<a title="Dress" rel="useZoom: 'zoom1', smallImage: '<?php echo $row['bigphoto1'] ?>'" class="cloud-zoom-gallery" href="<?php echo $row['bigphoto1'] ?>"><img alt="<?php echo osql($row['name'])?>" title="<?php echo osql($row['name'])?>" src="<?php echo $row['bigphoto1'] ?>"></a><?php
						if($row['photo2']): ?>
							<a title="Dress" rel="useZoom: 'zoom1', smallImage: '<?php echo $row['bigphoto2'] ?>'" class="cloud-zoom-gallery" href="<?php echo $row['bigphoto2'] ?>"><img alt="<?php echo osql($row['name'])?>" title="<?php echo osql($row['name'])?>" src="<?php echo $row['bigphoto2'] ?>"></a><?php
						endif;
						if($row['photo3']): ?>
							<a title="Dress" rel="useZoom: 'zoom1', smallImage: '<?php echo $row['bigphoto3'] ?>'" class="cloud-zoom-gallery" href="<?php echo $row['bigphoto3'] ?>"><img alt="<?php echo osql($row['name'])?>" title="<?php echo osql($row['name'])?>" src="<?php echo $row['bigphoto3'] ?>"></a><?php
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
						<!-- <span class="strike">$150.00</span>  --><strong><?php echo showPriceCurrency($row['prix_ttc']) ?></strong>
					</div><?php
					    if($row['menu']) :
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
								$query_options = "SELECT O.id_item, O.quantity, P.name, P.photo1
												    FROM options_items O, plats P
												   WHERE O.id_item = P.id
												   	 AND O.active = 1
													 AND P.active = 1
												     AND id_option = ".$row_mezze['id_option']."
											    ORDER BY O.order_position"; 
								$result_options = $connector->query($query_options); ?>
								<div class="control-group">
									<label class="control-label"><?php echo $row_mezze['name'] ?></label>
						            <div class="controls">
										<select><?php
											while($row_options = $connector->fetchArray($result_options)){ ?>
												<option><?php echo $row_options['name'] ?></option><?php
											} ?>
										</select>
									</div>
								</div><?php
							}




							
							
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
								$query_options = "SELECT O.id_item, O.quantity, P.name, P.photo1
												    FROM options_items O, plats P
												   WHERE O.id_item = P.id
												   	 AND O.active = 1
													 AND P.active = 1
												     AND id_option = ".$row_mezze['id_option']."
											    ORDER BY O.order_position"; 
								$result_options = $connector->query($query_options); ?>
								<div class="row">
								    <div class="col-md-12 slideshow">
										<div class="flexslider" id="test">
										  <?php echo $row_mezze['name'] ?>
										  <ul class="slides"><?php
												while($row_options = $connector->fetchArray($result_options)){ ?>
													<li><img src="<?php echo $row_options['photo1'] ?>" alt="<?php echo $row_options['name'] ?>" /></li><?php
												} ?>
										   </ul>
										</div>

									</div>
								</div>
								
								<div class="pagination_wrapper">
                                    <ul class="pagination"><?php
										$result_options = $connector->query($query_options);
										while($row_options = $connector->fetchArray($result_options)){ ?>
											<li><a href="#"><?php echo osql($row_options['name']) ?></a></li><?php
										} ?>
                                    </ul>
                                </div>
								
                                <?php
								$i++;
							}
							





							/*
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
								$query_options = "SELECT O.id_item, O.quantity, P.name, P.photo1
												    FROM options_items O, plats P
												   WHERE O.id_item = P.id
												   	 AND O.active = 1
													 AND P.active = 1
												     AND id_option = ".$row_mezze['id_option']."
											    ORDER BY O.order_position"; 
								$result_options = $connector->query($query_options); ?>
								<fieldset class="mezze_choix_fs <?php echo $class ?>">
                                    <legend><?php echo $row_mezze['name'] ?></legend>
                                    <div class="mezze_choix_slider">
                                        <div class="slides_nav_container">
                                                <div id="slides">
                                                    <div class="slides_container"><?php
                                                        $result_options = $connector->query($query_options);
														while($row_options = $connector->fetchArray($result_options)){ ?>
															<img src="<?php echo $row_options['photo1'] ?>" width="265" height="199" alt="<?php echo $row_options['name'] ?>"><?php
														} ?>
                                                    </div>
                                                    <a href="#" class="prev"><img src="images/precedente.png" width="15" height="31" alt="<?php showLang('PREVIOUS') ?>"></a>
                                                    <a href="#" class="next"><img src="images/suivante.png" width="15" height="31" alt="<?php showLang('NEXT') ?>"></a>
                                                    <div class="pagination_wrapper">
                                                        <ul class="pagination"><?php
															$result_options = $connector->query($query_options);
															while($row_options = $connector->fetchArray($result_options)){ ?>
																<li><a href="#"><?php echo osql($row_options['name']) ?></a></li><?php
															} ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </fieldset><?php
								$i++;
							}
							*/





						endif ?>
					<div class="line"></div>
					<div class="quantite" data-quantite="1">
	                  <i class="fa fa-minus-square fa-3x"></i>
	                  <div class="inline-block quantite_numero">1</div>                        
	                  <i class="fa fa-plus-square fa-3x"></i>
	                  <i class="fa fa-shopping-cart fa-2x fa-lg importantButton" data-id="5"></i>
	                </div>
	                <!--
					<form class="form-inline">
                        <label>Qty:</label> <input type="text" placeholder="1" class="col-md-1">
						<button class="btn btn-primary" type="button"><?php showLang('ADD_TO_CART') ?></button>
                    </form>
                	-->
					<div class="tabs">
					<ul class="nav nav-tabs" id="myTab">
						<li class="active"><a href="#home">Description</a></li>
						<!--
						<li><a href="#profile">Specification</a></li>
						<li><a href="#messages">Reviews</a></li>
						-->
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="home">
							<?php echo $row['description'] ?>
						</div>
						<!--
						<div class="tab-pane" id="profile">
							<table class="table specs">
									<tr>
										<th>Color</th>
										<th>Size</th>
										<th>Weight</th>
									</tr>
									<tr>
										<td>Blue</td>
										<td>XS</td>
										<td>1.00</td>
									</tr>
									<tr>
										<th>Composition</th>
										<th>Sleeve</th>
										<th>Care</th>
									</tr>
									<tr>
										<td>100% Cotton</td>
										<td> Long Sleeve</td>
										<td>IRON AT 110ºC MAX</td>
									</tr>								
					        </table>
						</div>
						<div class="tab-pane" id="messages">
						    <p>There are no reviews yet, would you like to <a href="#review_btn .btn-default">submit yours?</a></p>
							<h3>Be the first to review “Blue Dress” </h3>
							<form>
								<fieldset>
									<label>Name<span class="required">*</span></label>
									<input type="text" placeholder="Name">
									<label>Email<span class="required">*</span></label>
									<input type="text" placeholder="Email">		
									<label class="rating">Rating</label>
	                                <img alt="rating" src="image/stars-5.png">								
								</fieldset>
							</form>
							<label>Your Review<span class="required">*</span></label>
							<textarea rows="3"></textarea>
							<p id="review_btn .btn-default">
								<button class="btn .btn-default" type="button">Submit Review</button>
							</p>
						</div>
						-->
					</div>
					</div>
			</div>
		</div>
	</div>		
	<?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
<script type="text/javascript" src="js/slides.jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/cloud-zoom.1.0.3.js"></script>
<script>
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
	showTitle:false};
		
jQuery(document).ready(function() 
{
    $('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
    });

    $('.pagination li a').click(function(e) {
	    e.preventDefault();
		$('#test').flexslider(4);
		return false;
	});


});
</script>
</body>
</html>
