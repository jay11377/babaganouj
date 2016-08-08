<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:200,300,400,600,700' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:200,300,400,600,700' rel='stylesheet' type='text/css'/>
<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='js/jquery-1.8.3.min.js'><\/script>")</script>
<script language="javascript" type="text/javascript" src="js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.²-min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
<script language="javascript" type="text/javascript" src="js/nicole-fashion.js"></script>
<link href='css/carousel.css' rel='stylesheet' type='text/css'/>
<script>
jQuery(document).ready(function() 
{
	$('.jcarousel').jcarousel({
		vertical: false,
		wrap: 'circular',
		visible: 5,
		scroll: 3
	});

	$(document).ready( function(){	
		
			$('.flexslider').flexslider({
				animation:"slide",
				easing:"",
				direction:"horizontal",
				startAt:0,
				initDelay:0,
				slideshowSpeed:7000,
				animationSpeed:600,
				prevText:"Previous",
				nextText:"Next",
				pauseText:"Pause",
				playText:"Play",
				pausePlay:false,
				controlNav:true,
				slideshow:false,
				animationLoop:true,
				randomize:false,
				smoothHeight:false,
				useCSS:true,
				pauseOnHover:true,
				pauseOnAction:true,
				touch:true,
				video:false,
				mousewheel:false,
				keyboard:false
		});
	});	
});
</script>