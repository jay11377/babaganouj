<?php
header("Content-type: image/png");

$src = $_GET['src'];
$nb_blocks = $_GET['nb_blocks'];
$imagepath = $_GET['imagepath'];

$img = imagecreatefrompng($src);

$sizeArray=array(4 => "four", 6 => "six", 8 => "eight", 10 => "ten", 12 => "twelve", 14 => "fourteen", 16 => "sixteen", 18 => "eighteen", 20 => "twenty", 22 => "twentytwo", 24 => "twentyfour", 26 => "twentysix", 28 => "twentyeight", 30 => "thirty", 32 => "thirtytwo");
$fontArray=array("arial.ttf" => "arial", "arialbd.ttf" => "arialbold", "calibri.ttf" => "calibri", "calibrib.ttf" => "calibribold", "georgia.ttf" => "georgia", "georgiab.ttf" => "georgiabold", "tahoma.ttf" => "tahoma", "tahomabd.ttf" => "tahomabold", "times.ttf" => "times", "timesbd.ttf" => "timesbold", "trebuc.ttf" => "trebuchet", "trebucbd.ttf" => "trebuchetbold", "verdana.ttf" => "verdana", "verdanab.ttf" => "verdanabold"); 

for($i=1;$i<=$nb_blocks;$i++){
	$text = $_GET['text_'.$i];
	$left = $_GET['left_'.$i];
	$top = $_GET['top_'.$i];
	$color = $_GET['color_'.$i];
	$class = explode(" ", $_GET['class_'.$i]);
	
	// Get font
	$font="arial.ttf";
	$found=false;
	$j=0;
	while($j<count($class) && $found==false){
		$key = array_search($class[$j], $fontArray);
		if($key!=false){
			$found=true;
			$font=$key;
		}
		$j++;
	}
	
	// Get size
	$size=12;
	$found=false;
	$j=0;
	while($j<count($class) && $found==false){
		$key = array_search($class[$j], $sizeArray);
		if($key!=false){
			$found=true;
			$size=$key;
		}
		$j++;
	}
	
	// Get color
	$colorArray = explode(',',substr($color,4,-1));
	$textcolor = imagecolorallocate($img, $colorArray[0], $colorArray[1], $colorArray[2]);
	
	$bbox = imagettfbbox($size, 0, 'fonts/'.$font, $text);
	$x = $left;
	$y = -$bbox[5] + $top + 5;
	imagettftext($img, $size, 0, $x, $y, $textcolor, 'fonts/'.$font, $text);
}
imagepng($img,$imagepath);
imagedestroy($img);

?>
