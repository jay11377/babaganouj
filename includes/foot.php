<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:200,300,400,600,700' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:200,300,400,600,700' rel='stylesheet' type='text/css'/>
<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='js/jquery-1.8.3.min.js'><\/script>")</script>
<script language="javascript" type="text/javascript" src="js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.flexslider-min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
<script language="javascript" type="text/javascript" src="js/documentready.js"></script>
<script language="javascript" type="text/javascript" src="js/documentready2.js"></script>
<script language="javascript" type="text/javascript" src="js/bootstrap-dialog.js"></script>
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
				slideshowSpeed:4000,
				animationSpeed:600,
				prevText:"Previous",
				nextText:"Next",
				pauseText:"Pause",
				playText:"Play",
				pausePlay:false,
				controlNav:true,
				slideshow:true,
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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64021632-1', 'auto');
  ga('send', 'pageview');

</script>