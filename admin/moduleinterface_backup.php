<?php

// Get the config file
require_once('includes/fr.php');
require_once('includes/config.php');
require_once('includes/Sentry.php');
include_once('lib/thumb/thumbnail.inc.php');

$theSentry = new Sentry();
if (!$theSentry->checkLogin() )
{ 
	header("Location: login.php"); die(); 
}

// Set default module and action
$module="commandes";
$action="default";
if(isset($_GET['module']) && isset($_GET['action'])){
	$module=$_GET['module'];
	$action=$_GET['action'];
}
else if(isset($_POST['module']) && isset($_POST['action'])){
	$module=$_POST['module'];
	$action=$_POST['action'];
} 
$page="modules/".$module."/".$action.".php";

ob_start();
include($page);
$content_page = ob_get_clean();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php showLang('ADMIN_TITLE') ?></title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/colorpicker.js"></script>
<script type="text/javascript" src="js/eye.js"></script>
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/jquery.selectlist.js"></script>
<script src="js/ui/ui.core.js"></script>
<script src="js/ui/ui.sortable.js"></script>
<script src="js/ui/ui.datepicker.js"></script>
<script src="ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="ckeditor/ck_sd26.js" type="text/javascript"></script>
<script src="ckeditor/adapters/jquery.js" type="text/javascript"></script>
<script type="text/javascript"> 

function setSortedList(){
	document.getElementById('sortedlist').value=$('#list').sortable('toArray');
	return true;
}
function setSortedList2(){
	document.getElementById('sortedlist').value=$('#list2').sortable('toArray');
	return true;
}
</script>
<script>
$(document).ready(function(){
	  
	  var selectedTab = $('#tabs ul li a.active');
	  
	  // SubNav Activated on click
	  var selectedSubtab = $('#subnav div.subtab_active');
	  $('#tabs ul li a').click(function(){
		if(!$(this).hasClass("tablink")){
			$('#tabs ul li a.active').removeClass('active');
			$(this).addClass('active');
			$('#subnav div.subtab_active').removeClass('subtab_active');
			$(this.hash).addClass('subtab_active');
			return false;
		}
	  });
	
	var config = {
	toolbar:
	[
		['Bold','Italic','Underline'],
		['Link','Unlink']
	],
	width:500,
	height:300,
	enterMode : CKEDITOR.ENTER_BR
	};

	$('.ckeditor').ckeditor(config);
	
	CKEDITOR.on( 'instanceReady', function( ev )
    {
		ev.editor.dataProcessor.writer.lineBreakChars = '';
		ev.editor.dataProcessor.writer.setRules( 'br',
		{
			breakAfterOpen : 0,
			breakBeforeOpen : 0,
			breakBeforeClose : 0,
			breakAfterClose : 0,
			indent : 0
		});
    });
	
	$("#date").datepicker({
		dateFormat: "MM, d, yy",
		defaultDate:null,
		showOn: "both", 
		buttonImage: "images/datepicker/calendar.gif", 
		buttonImageOnly: true 
	});
	
	$("#list").sortable({
		axis: "y",
		cursor: "move"
	});
	
	$("#list2").sortable({
		axis: "y",
		cursor: "move"
	});	

});
</script>
<link href="css/jquery.treeTable.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker.css" />
<link href="css/datepicker.css" rel="stylesheet" type="text/css" />
<base href="<?php echo $admindir; ?>" />
</head>
</head>
<body>
<?php require_once('includes/header.php'); ?>
<div id="content" class="center">
	<?php echo $content_page; ?></div>
</div>

</body>
</html>