<?php
/**
 * show_image.php
 * 
 * Example utility file for dynamically displaying images
 * 
 * @author      Ian Selby
 * @version     1.0 (php 4 version)
 */

//reference thumbnail class
include_once('thumbnail.inc.php');

$thumb = new Thumbnail($_GET['filename']);
$thumb->resizeForCrop(353,265);
$thumb->cropFromCenterSize(353,265);
$thumb->show();
$thumb->destruct();
exit;
?>