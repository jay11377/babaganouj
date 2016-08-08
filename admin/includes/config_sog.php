<?php
define("cHtmlFormat","0");
define("cTextFormat","1");
define("cHighPriority","1");
define("cNormalPriority","3");
define("cLowPriority","5"); 

define("siteEmail", "contact@mabento.fr");
define("siteName", "Ma Bento");
define("bgEmail", "#FFFFFF");
define("bgEmailLogo", "#FFFFFF");
define("textEmail", "#777777");
define("phoneEmail", "01 49 07 49 07");
define("deliveryPrice", 4.90);
define("deliveryFree", 18);


require_once('fr.php');

$sitedir = 'http://www.mabento.fr/';
$admindir = 'http://www.mabento.fr/admin/';

$allowed_images_types=array('gif','GIF','png','PNG','jpg','JPG','jpeg','JPEG');
$allowed_docs_types=array('pdf', 'PDF', 'ppt', 'PPT', 'doc', 'DOC', 'docx', 'DOCX', 'PPTX', 'PPTX');

$cookie_name = 'mabento';
$cookie_time = (3600 * 24 * 60); // 60 days
$domain = 'mabento.fr';

require_once("functions.php");
?>