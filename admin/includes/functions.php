<?php

/*
 * Sanitize input to prevent against XSS and other nasty stuff.
 * Taken from cakephp (http://cakephp.org)
 * Licensed under the MIT License
 */
function osql($string) {
	$string = htmlspecialchars(stripslashes($string), ENT_QUOTES);
	$string=stripslashes($string);
  	return $string;
}


function isql($string, $htmlOK = false) {
  if (get_magic_quotes_gpc())
    $string = stripslashes($string);
  if (!is_numeric($string))
  {
    $conn = new DbConnector();
    $link = $conn->link;
    if(function_exists('mysql_real_escape_string'))
      $string = mysql_real_escape_string($string, $link);
    else
      addslashes($string);
    if (!$htmlOK)
      $string = strip_tags(nl2br2($string));
  }
  return $string;
}

function nl2br2($string)
{
  return str_replace(array("\r\n", "\r", "\n"), '<br />', $string);
}

function showPrice($price){
	$num = number_format ($price, 2, ',', '');
	//$num = number_format (no_round($price, 2), 2, ',', '');
	return $num;
}

function showPriceCurrency($price){
	$num = number_format ($price, 2, ',', '');
	//$num = number_format (no_round($price, 2), 2, ',', '');
	$num.=" ".getLang('CURRENCY');
	return $num;
}

function no_round($val, $pre = 0)
{
    return (int) ($val * pow(10, $pre)) / pow(10, $pre);
}

function showPriceCurrencyAutocomplete($price){
	$num = number_format ($price, 2, ',', '');
	$num.=" Euros";
	return $num;
}

function _var($text){
	$text = htmlentities( $text, ENT_NOQUOTES, 'UTF-8', false);
	$text = str_replace(chr(128),'&euro;',$text);
	$text = str_replace('&lt;','<',$text);
	$text = str_replace('&gt;','>',$text);
	return($text);
}

function _db($text){
	$text = htmlentities($text);
	return($text);
} 
 
function cleanValue($val) {
	if ($val == "") {
		return $val;
	}
	//Replace odd spaces with safe ones
	$val = str_replace(" ", " ", $val);
	$val = str_replace(chr(0xCA), "", $val);
	//Encode any HTML to entities (including \n --> <br />)
	$val = cleanHtml($val);
	//Double-check special chars and remove carriage returns
	//For increased SQL security
	$val = preg_replace("/\\\$/", "$", $val);
	$val = preg_replace("/\r/", "", $val);
	$val = str_replace("!", "!", $val);
	$val = str_replace("'", "'", $val);
	//Allow unicode (?)
	$val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val);
	//Add slashes for SQL
	//$val = $this->sql($val);
	//Swap user-inputted backslashes (?)
	$val = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $val);
	return $val;
}

function getDiscountTaxRate()
{
	$connector = new DbConnector();
	$query = "SELECT value FROM tva WHERE id=1";
	$result = $connector->query($query);
	$row = $connector->fetchArray($result);
	return (float)($row['value']/100);
}

function jsDateToSqlDate($thedate){
	$monthNames = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$thedate = explode(", ",$thedate);
	$month=array_search($thedate[0],$monthNames) +1;
	return($thedate[2].'-'.$month.'-'.$thedate[1]);
}	

/*
 * Method to sanitize incoming html.
 * Take from cakephp (http://cakephp.org)
 * Licensed under the MIT License
 */
function cleanHtml($string, $remove = false) {
	if ($remove) {
		$string = strip_tags($string);
	} else {
		$patterns = array("/\&/", "/%/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
		$replacements = array("&amp;", "&#37;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
		$string = preg_replace($patterns, $replacements, $string);
	}
	return $string;
}


function cleanHtml_decode($string, $remove = false) {
	if ($remove) {
		$string = strip_tags($string);
	} else {
		$replacements = array("/\&/", "/%/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
		$patterns = array("&amp;", "&#37;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
		$string = preg_replace($patterns, $replacements, $string);
	}
	return $string;
}


//*** Function: shortenText, Purpose: summarize the content with nbchars ***
function shortenText($text,$nbchars) {
		$text = $text." ";
        $text = substr($text,0,$nbchars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
		return $text;
}

//*** Function: setPassword, Purpose: encrypt the password ***
function setPassword($password){
		return md5($password);
}

// Get the name of a file with a path as an argument

function getFileName($path){
	$tab=explode("/",$path);
	return($tab[count($tab)-1]);
}

// Convert the time for ical format
function icalTime($time){
	$time=explode(":",$time);
	$hours=$time[0] +1;
	$tab_minutes=explode(" ",$time[1]);
	$minutes=$tab_minutes[0];
	if($tab_minutes[1]=="PM")
		$hours+=12;
	return("T".$hours.$minutes."00Z");
}

// sort the datas of a table
function sortdata($table,$field,$sortedlist){
	$connector = new DbConnector();
	for ($i = 0; $i < count($sortedlist); $i++) {
	  $j=$i+1;
	  $connector->query("UPDATE ".$table." SET order_position=$j WHERE ".$field."=$sortedlist[$i]");
	}
}

// Build the XML Path for the Flash
function buildXMLPath($link){
	if(substr($link,0,4)=="http" || substr($link,0,3)=="www")
		return($link);
	else
		return(".".$link);	
}

/*-------------------------------------------------------------------------
* Function : SendMail( $inFrom, $inDest, $inSubject, $inBody , $inPrio, $inFormat)
* Parameters :
*       Str inFrom from recipient
*       Str inDest dest recipient
*       Str inSubject message subject
*       Str inBody message body
*       int inPrio message priority : 1:Highest / 3:Normal / 5: lowest
*       int inFormat message format : 0:TEXT / 1:HTML
* Return :
*       true if OK
*       false otherwise 
* Purpose : Send email in text or html mode
* External function(s) :
*       stripslashes() - PHP 3, PHP 4
*       nl2br() - PHP 3, PHP 4
*       mail() - PHP 3, PHP 4
* Comment :
* (c)opyright B2L-CYBER/BBDO 2001
*-------------------------------------------------------------------------*/
function sendMail ($nameFrom, $inFrom, $inDest, $inSubject, $inBody , $inPrio, $inFormat)
{
	 $headers = "From: ".$nameFrom." <". $inFrom . ">\n";  // Initialize mail Header
     
     // Set Message priority
     if ($inPrio!=cNormalPriority) $headers .= "X-Priority: ".$inPrio."\n";
     
     $headers .= "Return-Path: " . $inFrom ."\n"; // Return address
     $format_body = stripslashes( $inBody );
     if ($inFormat==cHtmlFormat) {
          $headers .= "Content-Type: text/html; charset=utf-8\n"; // Type MIME
          $format_body = nl2br ( $format_body );
     }
     $format_subject = stripslashes( $inSubject );

     return mail($inDest, $format_subject, $format_body , $headers );
}
//-----------End function : SendMail()------------------------------------

function genRandomString() {
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

function showLang($string){
	global $langvars;
	$utf8 = $langvars[$string];
	$result = '';
	$encodeTags = 0;
    for ($i = 0; $i < strlen($utf8); $i++) {
        $char = $utf8[$i];
        $ascii = ord($char);
        if ($ascii < 128) {
            // one-byte character
            $result .= ($encodeTags) ? htmlentities($char) : $char;
        } else if ($ascii < 192) {
            // non-utf8 character or not a start byte
        } else if ($ascii < 224) {
            // two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        } else if ($ascii < 240) {
            // three-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $unicode = (15 & $ascii) * 4096 +
                       (63 & $ascii1) * 64 +
                       (63 & $ascii2);
            $result .= "&#$unicode;";
            $i += 2;
        } else if ($ascii < 248) {
            // four-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $ascii3 = ord($utf8[$i+3]);
            $unicode = (15 & $ascii) * 262144 +
                       (63 & $ascii1) * 4096 +
                       (63 & $ascii2) * 64 +
                       (63 & $ascii3);
            $result .= "&#$unicode;";
            $i += 3;
        }
    }
    echo $result;
}

function getLang($string){
	global $langvars;
	$utf8 = $langvars[$string];
	$result = '';
	$encodeTags = 0;
    for ($i = 0; $i < strlen($utf8); $i++) {
        $char = $utf8[$i];
        $ascii = ord($char);
        if ($ascii < 128) {
            // one-byte character
            $result .= ($encodeTags) ? htmlentities($char) : $char;
        } else if ($ascii < 192) {
            // non-utf8 character or not a start byte
        } else if ($ascii < 224) {
            // two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        } else if ($ascii < 240) {
            // three-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $unicode = (15 & $ascii) * 4096 +
                       (63 & $ascii1) * 64 +
                       (63 & $ascii2);
            $result .= "&#$unicode;";
            $i += 2;
        } else if ($ascii < 248) {
            // four-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $ascii3 = ord($utf8[$i+3]);
            $unicode = (15 & $ascii) * 262144 +
                       (63 & $ascii1) * 4096 +
                       (63 & $ascii2) * 64 +
                       (63 & $ascii3);
            $result .= "&#$unicode;";
            $i += 3;
        }
    }
	return $result;
}

function getRawLang($string){
	global $langvars;
	return $langvars[$string];
}

function getOfferImagePath($module){
	$connector = new DbConnector();
  	$result = $connector->query("SHOW TABLE STATUS LIKE '".$module."'");
  	$row = $connector->fetchArray($result);
  	if(ini_get('safe_mode') == 'Off'){ 
		$p = "../uploads/".$module."/id".$row['Auto_increment']."/";
		$filepath = "../uploads/".$module."/id".$row['Auto_increment']."/image.png";
	}
  	else{
		$p = "../uploads/".$module."/";
		$filepath = "../uploads/".$module."/image".$row['Auto_increment'].".png";
	}
	
	// Create the new folder where we'll upload the image
	  if (!is_dir($p)) {
		  $newdir = @mkdir($p);
		  chmod($p, 0755);
		  if( $newdir === FALSE )
			{
			  $this->errors[] = getLang('DIRECTORY_CREATION_ISSUE');
			  return FALSE;
			}
	  }
	return $filepath;
}

function concatContentPosition($num){
	$content="content".$num;
	$contentx="content".$num."x";
	$contenty="content".$num."y";
	$contentfont="content".$num."font";
	$contentsize="content".$num."size";
	$contentcolor="content".$num."color";
	global $$content;
	$string = $$content;
	$string.="]]]".$_POST[$contentx]."]]]".$_POST[$contenty]."]]]".$_POST[$contentfont]."]]]".$_POST[$contentsize]."]]]".$_POST[$contentcolor];
	return $string;
}

function smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false )
  {
    if ( $height <= 0 && $width <= 0 ) {
      return false;
    }

    $info = getimagesize($file);
    $image = '';

    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;

    if($height_old <= 120){ // Added by JProjet
		$final_width = $width_old;
    	$final_height = $height_old;
	}
	
	else{
		if ($proportional) {
		  if ($width == 0) $factor = $height/$height_old;
		  elseif ($height == 0) $factor = $width/$width_old;
		  else $factor = min ( $width / $width_old, $height / $height_old);  
	
		  $final_width = round ($width_old * $factor);
		  $final_height = round ($height_old * $factor);
	
		}
		else {
		  $final_width = ( $width <= 0 ) ? $width_old : $width;
		  $final_height = ( $height <= 0 ) ? $height_old : $height;
		}
	}

    switch ( $info[2] ) {
      case IMAGETYPE_GIF:
        $image = imagecreatefromgif($file);
      break;
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($file);
      break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($file);
      break;
      default:
        return false;
    }
   
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
       
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $trnprt_indx = imagecolortransparent($image);
   
      // If we have a specific transparent color
      if ($trnprt_indx >= 0) {
   
        // Get the original image's transparent color's RGB values
        $trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
   
        // Allocate the same color in the new image resource
        $trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
   
        // Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $trnprt_indx);
   
        // Set the background color for new image to transparent
        imagecolortransparent($image_resized, $trnprt_indx);
   
     
      }
      // Always make a transparent background color for PNGs that don't have one allocated already
      elseif ($info[2] == IMAGETYPE_PNG) {
   
        // Turn off transparency blending (temporarily)
        imagealphablending($image_resized, false);
   
        // Create a new transparent color for image
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
   
        // Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $color);
   
        // Restore transparency blending
        imagesavealpha($image_resized, true);
      }
    }

    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
 
    if ( $delete_original ) {
      if ( $use_linux_commands )
        exec('rm '.$file);
      else
        @unlink($file);
    }
   
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }

    switch ( $info[2] ) {
      case IMAGETYPE_GIF:
        imagegif($image_resized, $output);
      break;
      case IMAGETYPE_JPEG:
        imagejpeg($image_resized, $output);
      break;
      case IMAGETYPE_PNG:
        imagepng($image_resized, $output);
      break;
      default:
        return false;
    }

    return true;
}
function stripAccents($string){
	return strtr($string,'‡·‚„‰ÁËÈÍÎÏÌÓÔÒÚÛÙıˆ˘˙˚¸˝ˇ¿¡¬√ƒ«»… ÀÃÕŒœ—“”‘’÷Ÿ⁄€‹›','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}
function displayDate($date, $full = false, $separator='-')
{
	$tmpTab = explode($separator, substr($date, 0, 10));
	$hour = ' '.substr($date, -8);
	return ($tmpTab[2].'/'.$tmpTab[1].'/'.$tmpTab[0].($full ? $hour : ''));
}
function dateFRtoEN($date){
	$array = explode("/", $date);
	$str = $array[2]."-".$array[1]."-".$array[0];
	return $str;
}
function dateLongENtoFR($date){
	$array = explode("-", $date);
	$day_time_array = explode(" ", $array[2]);
	$time_array = explode(":", $day_time_array[1]);
	$str = $day_time_array[0]."/".$array[1]."/".$array[0]."<br />".$time_array[0]."h".$time_array[1]; 
	return $str;
}
function dateLongPrintENtoFR($date){
	$array = explode("-", $date);
	$day_time_array = explode(" ", $array[2]);
	$time_array = explode(":", $day_time_array[1]);
	$str = $day_time_array[0]."/".$array[1]."/".$array[0]." - ".$time_array[0]."h".$time_array[1]; 
	return $str;
}
function dateENtoFR($date){
	$array = explode("-", $date);
	$str = $array[2]."/".$array[1]."/".$array[0];
	return $str;
}
function dateNoTime($date){
	$array = explode(" ", $date);
	$str = $array[0];
	return $str;
}
function getMinutes($ville){
	$connector = new DbConnector();
	$query = "SELECT duree_livraison FROM zones_livraison Z, villes V WHERE V.id_zone=Z.id AND V.name='".$ville."'";
	$result = $connector->query($query);
	$row = $connector->fetchArray($result);
	return $row['duree_livraison'];
}
function getTimeHours($time){
	$array = explode("h", $time); 
	return $array[0];
}
function getTimeMinutes($time){
	$array = explode("h", $time); 
	return $array[1];
}
function alreadyClient($id_client){
	$connector = new DbConnector();
	$query = "SELECT id FROM commandes WHERE id_client=".$id_client." AND id_statut=4";
	$result = $connector->query($query);
	if($connector->getNumRows($result)>0)
		return true;
	else
		return false;
}
// Prix avec tous les chiffres aprËs la virgule, exceptÈs les 0 ‡ la fin
function getPriceWithoutTaxFull($price){
	for($i=0;$i<4;$i++)
	{
		if((int)substr($price, -1)==0)
			$price = substr($price, 0, -1);
	}
	return $price;
}
/*
function getOrderMin($ville){
	$conn = new DbConnector();
	$result = $conn->query("SELECT minimum FROM zones_livraison Z, villes V WHERE V.id_zone=Z.id AND V.name='".$ville."'");
	$row = $conn->fetchArray($result);
	return $row['minimum'];
}
*/
function getCp($adresse){
	$conn = new DbConnector();
	$result = $conn->query("SELECT cp FROM adresses WHERE id=".(int)$adresse);
	$row = $conn->fetchArray($result);
	return $row['cp'];
}
function getCityFromAddress($adresse){
	$conn = new DbConnector();
	$result = $conn->query("SELECT ville FROM adresses WHERE id=".(int)$adresse);
	$row = $conn->fetchArray($result);
	return $row['ville'];
}
function getOrderMin($cp){
	$conn = new DbConnector();
	$result = $conn->query("SELECT minimum FROM zones_livraison Z, villes V WHERE V.id_zone=Z.id AND V.postcode LIKE '%".$cp."%'");
	$row = $conn->fetchArray($result);
	return $row['minimum'];
}
/*
function getOrderTime($ville){
	$conn = new DbConnector();
	$result = $conn->query("SELECT duree_livraison FROM zones_livraison Z, villes V WHERE V.id_zone=Z.id AND V.name='".$ville."'");
	$row = $conn->fetchArray($result);
	return $row['duree_livraison'];
}
*/
function getOrderTime($cp){
	$conn = new DbConnector();
	$result = $conn->query("SELECT duree_livraison FROM zones_livraison Z, villes V WHERE V.id_zone=Z.id AND V.postcode='".$cp."'");
	$row = $conn->fetchArray($result);
	return $row['duree_livraison'];
}
function getCpFromCity($ville){
	$connector_city = new DbConnector();
	$query_city="SELECT postcode FROM villes WHERE name = '".$ville."'";
	$result_city = $connector_city->query($query_city);
	$row_city = $connector_city->fetchArray($result_city);
	return $row_city['postcode'];
}
function getCityName($cp){
	$connector_city = new DbConnector();
	$query_city="SELECT name FROM villes WHERE postcode LIKE '%".$cp."%'";
	$result_city = $connector_city->query($query_city);
	$row_city = $connector_city->fetchArray($result_city);
	return $row_city['name'];
}
function getCityFromCp($cp){
	$connector_city = new DbConnector();
	$query_city="SELECT name FROM villes V WHERE postcode LIKE '%".$cp."%'";
	return $connector_city->query($query_city);
}

function getCorrectCityName($city){
	if (strpos($city, 'Paris') !== false)
		return 'Paris';
	else
		return $city;
}

function getCities(){
	$connector_cities = new DbConnector();
	$query_cities="SELECT V.name, Z.minimum, Z.duree_livraison 
					FROM villes V
			   LEFT JOIN zones_livraison Z ON V.id_zone=Z.id
				ORDER BY order_position";
	return $connector_cities->query($query_cities);
}

function getCloserCityName($city){
	$w1=strtolower(substr($city,0,8));
	$closer="";
	$percentage=0;
	if (strpos($city, 'Paris') !== false)
		return 'Paris';
	else{
		$connector_cities = new DbConnector();
		$result_cities = getCities();
		while($row_cities = $connector_cities->fetchArray($result_cities))
		{
			$w2 = strtolower(substr($row_cities['name'],0,8));
			$c = similar_text($w1, $w2, $p);
			if($p>$percentage && $p>70){
				$percentage=$p;
				$closer = $row_cities['name'];
			}
		}
		if($closer!="")
			return $closer;
		else
			return $city;
	}
}

function displayAddress($id_adresse){
	$connector_adresse = new DbConnector();
	$query_adresse="SELECT *
					FROM adresses
				   WHERE id=".(int)$id_adresse;
	$result_adresse = $connector_adresse->query($query_adresse);
	$row_adresse = $connector_adresse->fetchArray($result_adresse);
	$str="";
	if($row_adresse['societe']!='')
		$str.= osql($row_adresse['societe'])."<br />";
	 $str.= osql($row_adresse['prenom'])." ".osql($row_adresse['nom'])."<br />";
	 $str.= osql($row_adresse['adresse1'])."<br />";
	 if($row_adresse['adresse2']!='')
		$str.= osql($row_adresse['adresse2'])."<br />";
	 $str.= $row_adresse['cp']." ".osql($row_adresse['ville'])."<br />";
	 $str.= $row_adresse['telephone']."<br />----------------------------<br />";
	 if($row_adresse['code_entree']!='')
		$str.= getLang('ENTRY_CODE').' : '.osql($row_adresse['code_entree'])."<br />";
	 if($row_adresse['interphone']!='')
		$str.= getLang('INTERCOM').' : '.osql($row_adresse['interphone'])."<br />";
	 if($row_adresse['service']!='')
		$str.= getLang('SERVICE').' : '.osql($row_adresse['service'])."<br />";
	 if($row_adresse['escalier']!='')
		$str.= getLang('STAIRCASE').' : '.osql($row_adresse['escalier'])."<br />";
	 if($row_adresse['etage']!='')
		$str.= getLang('FLOOR').' : '.osql($row_adresse['etage'])."<br />";
	 if($row_adresse['numero_appartement']!='')
		$str.= getLang('APARTMENT_NUMBER').' : '.osql($row_adresse['numero_appartement'])."<br />";
	 if($row_adresse['remarque']!='')
		$str.= getLang('COMMENT').' : '.osql($row_adresse['remarque'])."<br />";
	 return $str;
}

function getDishes($id_category){ 
	$connector_plats = new DbConnector();
	// On regarde si il s'agit d'une catÈgorie comprenant des menus ou des plats
	$query_cat = "SELECT menu FROM categories WHERE id=".(int)$id_category;
	$result_cat = $connector_plats->query($query_cat);
	$row_cat = $connector_plats->fetchArray($result_cat);
	$menu = $row_cat['menu'];
	// Afichage de tous les plats ou menus
	$query_plats = "SELECT id, name, prix_ttc, vegetarien, epice FROM plats WHERE id_categorie=".(int)$id_category." AND active=1 ORDER BY order_position";
	$result_plats = $connector_plats->query($query_plats);
	$str='<table cellspacing="0" cellpadding="0" border="0">';
	$i=1;
	while($row_plat = $connector_plats->fetchArray($result_plats))
	{
		$class=($i%2==0)?'even':'odd';
		$str .= '<tr class="'.$class.'">';
		if($menu==1) //Menu
		{
			$str .= '<td>
						<a href="#" class="addToCartMezze" rel="'.$row_plat['id'].'">'.$row_plat['name'].'</a>';
						$query_mezze="SELECT M.id_option, O.name 
										    FROM mezzes M, options O 
										   WHERE M.id_option = O.id
											 AND active = 1
											 AND M.id_plat=".$row_plat['id']."
										ORDER BY order_position";
						$connector_mezze = new DbConnector();
						$result_mezze = $connector_mezze->query($query_mezze);
						$str .= '<table cellpadding="0" cellspacing="0" border="0">';
						while($row_mezze = $connector_mezze->fetchArray($result_mezze)){  
							$query_options = "SELECT O.id_item, O.quantity, P.name, P.photo1
												FROM options_items O, plats P
											   WHERE O.id_item = P.id
												 AND O.active = 1
												 AND P.active = 1
												 AND id_option = ".$row_mezze['id_option']."
											ORDER BY O.order_position";
							$result_options = $connector_mezze->query($query_options);
							$str .= '<tr>';
							$str .= '<td>'.$row_mezze['name'].'</td>';
							$str .= '<td><select class="select_long">';
							while($row_options = $connector_mezze->fetchArray($result_options)){
								$str .= '<option>'.$row_options['name'].'</option>';
							}
							$str .= '</select></td>';
							$str .= '</tr>';
						}
						$str .= '</table>';
			$str .= '<td>';
		}
		else
			$str .= '<td><a href="#" class="addToCart" rel="'.$row_plat['id'].'">'.$row_plat['name'].'</a></td>';
		$str .= '<td>';
		if($row_plat['epice']==1)
			$str .= ' <img src="../image/epice.png" width="13" height="13" />';
		if($row_plat['vegetarien']==1)
			$str .= ' <img src="../image/vegetarien.png" width="13" height="13" />';
		$str .= '</td>';
		$str .= '<td>'.showPriceCurrency($row_plat['prix_ttc']).'</td>';
		if($menu!=1) //Menu
		{
			$str .= '<td class="field td_qte"><input type="text" name="qte" value="1" class="tiny" /></td>';
			$str .= '<td><a href="#" class="addToCart" rel="'.$row_plat['id'].'"><img src="image/plus.png" /></a></td>';
		}
		$str .= '</tr>';
		$i++;
	}
	$str .='</table>';
	return $str;
}

function getPaymentMethodName($id_payment_method){
	$connector_payment = new DbConnector();
	$query_payment = "SELECT name FROM order_methods WHERE id=".(int)$id_payment_method;
	$result_payment = $connector_payment->query($query_payment);
	$row_payment = $connector_payment->fetchArray($result_payment);
	$str = str_replace('_',' ',$row_payment['name']);
	return $str;
}

function getStatus($id){
	$conn = new DbConnector();
	$query="SELECT statut FROM statut_commande WHERE id =".(int)$id;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	return $row['statut'];
}

function hasVoucher($id_commande){
	$conn = new DbConnector();
	$query = "SELECT id FROM ligne_commande WHERE id_commande=".(int)$id_commande." AND remise=1";
	$result = $conn->query($query);
	if($conn->getNumRows($result)>0)
		return true;
	else
		return false;
}

function email_client($id_commande,$delivery_time, $day){
	global $sitedir;
	if($day!=date('Y-m-d'))
		$today = 0;
	else
		$today = 1;	
	$conn = new DbConnector();
	$query = "SELECT LC.prix_ht, LC.prix_ttc, LC.quantite, LC.total_ht, LC.total_ttc, LC.options, P.name
						FROM ligne_commande LC
				   LEFT JOIN plats P on (LC.id_plat=P.id)
				   WHERE LC.id_commande=".$id_commande." ".
				   " AND remise=0";
	$result = $conn->query($query);
	$items = "<table border=\"0\" cellpadding=\"10\">";
	$items .= "<tr>";
	$items .= "<td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><strong>Plat</strong></td>";
	$items .= "<td align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><strong>Prix unitaire en &euro;</strong></td>";
	$items .= "<td align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><strong>Quantit&eacute;</strong></td>";
	$items .= "<td align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><strong>Total en &euro;</strong></td>";
	$items .= "</tr>";
	// Produits
	while($row = $conn->fetchArray($result)){
		$items .= "<tr>";
		$items .= "<td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . osql($row['name']); 
		if(!is_null($row['options'])): 
        	$items .= "<br /><i style=\"color:#555;\">".$row['options']."</i>";
        endif;
		$items .= "</td>";
		$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . showPrice($row['prix_ttc'])." &euro;</td>";
		$items .= "<td valign=\"top\" align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . $row['quantite'] . "</td>";
		$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . showPrice($row['total_ttc'])." &euro;</td>";
		$items .= "</tr>";
	}
	// Remises
	$query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$id_commande." AND remise=1";
	$result_vouchers = $conn->query($query_vouchers);
	while($row_voucher = $conn->fetchArray($result_vouchers))
	{
		$items .= "<tr>";
		$items .= "<td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . osql($row_voucher['description_remise']) . "</td>";
		$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . $row_voucher['prix_ttc']." &euro;</td>";
		$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . $row_voucher['quantite'] . "</td>";
		$items .= "<td align=\"right\" valign=\"top\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">" . $row_voucher['total_ttc']." &euro;</td>";
		$items .= "</tr>";
	} 
	// Total
	$query = "SELECT total_ht, total_ttc, date_livraison, creneau_livraison, message, email, prenom, nom, adresse_livraison, OM.name
				FROM commandes C
		   LEFT JOIN clients CL ON (C.id_client=CL.id)
		   LEFT JOIN order_methods OM ON (C.id_moyen_paiement=OM.id) 
		   WHERE C.id=".$id_commande;
	$result = $conn->query($query);
	$row = $conn->fetchArray($result);
	$items .= "<tr>";
	$items .= "<td colspan=\"3\" align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><b>Total en &euro; HT</b></td>";
	$items .= "<td align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><b>" . showPrice($row['total_ht'])." &euro;</b></td>";
	$items .= "</tr>";
	$items .= "<tr>";
	$items .= "<td colspan=\"3\" align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><b>Total en &euro; TTC</b></td>";
	$items .= "<td align=\"right\" style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\"><b>".showPrice($row['total_ttc'])." &euro;</b></td>";
	$items .= "</tr>";
	$items .= "</table>";

	$result_address = $conn->query("SELECT * FROM adresses WHERE id=".$row['adresse_livraison']);
	$row_address = $conn->fetchArray($result_address);
	// Envoi du mail
	$mail_Subject = "Commande ".siteName." - Numero ".$id_commande;
	$mail_Body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" bgcolor=\"".bgEmail."\">";
	$mail_Body .= "<tr>";
    $mail_Body .= "<td align=\"center\" valign=\"top\" width=\"600\">";
	$mail_Body .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"600\">";
	$mail_Body .= "<tr><td bgcolor=\"".bgEmailLogo."\"><br />&nbsp;<img src=\"".$sitedir."image/logoemail.png\" /><br /><br /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Bonjour ".osql($row['prenom']) . " " .osql($row['nom']).",</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">".siteName." a le plaisir de vous confirmer votre commande :</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	if($today)
		$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Votre commande num&eacute;ro ".$id_commande." sera livr&eacute;e vers ".$delivery_time."</td></tr>";
	else
		$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Votre commande num&eacute;ro ".$id_commande." sera livr&eacute;e le ".$delivery_day." vers ".$delivery_time."</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">(Sous r&eacute;serve d'un exceptionnel rallongement du d&eacute;lai de livraison d&ucirc; aux conditions de circulation)</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">";
	$mail_Body .= "====================================================";
	$mail_Body .= "<br />" . "<strong>Adresse de livraison : " . osql($row_address['titre_adresse']) ."</strong>";
	$mail_Body .= "<br />" . osql($row_address['societe']);
	$mail_Body .= "<br />" . osql($row_address['prenom']) . " " .osql($row_address['nom']);
	$mail_Body .= "<br />" . osql($row_address['adresse1']);
	if($row_address['adresse2']!='')
	$mail_Body .= "<br />" . osql($row_address['adresse2']);
	$mail_Body .= "<br />" . osql($row_address['cp']) . " " .osql($row_address['ville']);
	$mail_Body .= "<br />----------------------------<br />";
	if($row_address['code_entree']!='')
		$mail_Body .= getLang('ENTRY_CODE').' : '.osql($row_address['code_entree'])."<br />";
	if($row_address['interphone']!='')
		$mail_Body .= getLang('INTERCOM').' : '.osql($row_address['interphone'])."<br />";
	if($row_address['service']!='')
		$mail_Body .= getLang('SERVICE').' : '.osql($row_address['service'])."<br />";
	if($row_address['escalier']!='')
		$mail_Body .= getLang('STAIRCASE').' : '.osql($row_address['escalier'])."<br />";
	if($row_address['etage']!='')
		$mail_Body .= getLang('FLOOR').' : '.osql($row_address['etage'])."<br />";
	if($row_address['numero_appartement']!='')
		$mail_Body .= getLang('APARTMENT_NUMBER').' : '.osql($row_address['numero_appartement'])."<br />";
	if($row_address['remarque']!='')
		$mail_Body .= getLang('COMMENT').' : '.osql($row_address['remarque'])."<br />";
	if($row['message']!='')
		$mail_Body .= "<br />" . "Commentaire: " . osql($row['message']);
	//$mail_Body .= "<br />" . "Date de livraison: " . dateENtoFR($row['date_livraison']);;
	$mail_Body .= "<br />====================================================";
	$mail_Body .= "</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Votre commande :</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">".$items."</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Moyen de paiement : ".str_replace('_', ' ', $row['name'])."</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">L'&eacute;quipe de ".siteName." vous remercie de votre confiance et vous souhaite un excellent app&eacute;tit.</td></tr>";
	//$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	//$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	//$mail_Body .= "<tr><td><img src=\"".$sitedir."image/logoemail.png\" /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Suivez notre actualit&eacute; sur </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Facebook : <a style=\"color:".textEmail."\" href=\"https://www.facebook.com/pages/MaBento/254840191217166?ref=hl\">".siteName."</a></td></tr>";
  // $mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Twitter : <a style=\"color:".textEmail."\" href=\"\">'.siteName.'</a></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "</table>";
	$mail_Body .= "</td>";
	$mail_Body .= "</tr>";
	$mail_Body .= "</table>";
	
	$mail_From = siteEmail; 
	$mail_To = osql($row['email']);
	if(sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat)){
		// Changer le statut de la commande si le mail est correctement envoyÈ
		$conn->query("UPDATE commandes SET id_statut=3, heure_livraison='".$delivery_time."' WHERE id=".$id_commande);
		return true;
	}
	else{
		sendMail( siteName, $mail_From, $mail_From, "Probleme d'envoi de mail", "Le client de la commande ".$id_commande. "n'a pas pu recevoir l'email pour une raison technique. Il vaudrait mieux l'appeler afin de lui confirmer sa commande", cHighPriority, cHtmlFormat);
		$conn->query("UPDATE commandes SET id_statut=3, heure_livraison='".$delivery_time."' WHERE id=".$id_commande);
		//return true;
	}
	return false;
}

/*ModifiÈ par GrÈgory*/

function emailGoodCustomer($nom, $prenom, $email, $code){
	global $sitedir;
	
	$conf=array();
	$conn = new DbConnector();
	$result=$conn->query("SELECT `name` , `value` FROM `settings` WHERE id >=7 AND id <=9");
	while($row = $conn->fetchArray($result)){
		$conf[$row['name']]=$row['value'];
	}
	
	// Envoi du mail
	$mail_Subject = "Merci de votre fid&eacute;lit&eacute;";
	$mail_Body .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" bgcolor=\"".bgEmail."\">";
	$mail_Body .= "<tr>";
    $mail_Body .= "<td align=\"center\" valign=\"top\" width=\"600\">";
	$mail_Body .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"600\">";
	$mail_Body .= "<tr><td bgcolor=\"".bgEmailLogo."\"><br />&nbsp;<img src=\"".$sitedir."image/logoemail.png\" /><br /><br /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Bonjour ".osql($prenom). " " .osql($nom).",</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Vous avez command&eacute; pour un montant de plus de ".$conf['seuilBonClient']." &euro;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Pour vous remercier, nous vous offrons un code de r&eacute;duction d'une valeur de ".$conf['montantRemiseBonClient']." &euro; valable pour une dur&eacute;e de ".$conf['dureeRemiseBonClient']." jours.</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Le code de r&eacute;duction est le suivant : ".$code."</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Si vous rencontrez un probl&egrave;me pour acc&eacute;der &agrave; votre compte, rendez vous sur <a style=\"color:".textEmail."\" href=\"mailto:".siteEmail."\">".siteEmail."</a> ou contacter le service client au ".phoneEmail.".</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Cordialement</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">L'&eacute;quipe ".siteName." </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td><img src=\"".$sitedir."image/logoemail.png\" /></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Suivez notre actualit&eacute; sur </td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Facebook : <a style=\"color:".textEmail."\" href=\"https://www.facebook.com/pages/MaBento/254840191217166?ref=hl\">".siteName."</a></td></tr>";
  // $mail_Body .= "<tr><td style=\"font: 13px Arial, sans-serif;color: ".textEmail.";line-height:16px;\">Twitter : <a style=\"color:".textEmail."\" href=\"\">'.siteName.'</a></td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "<tr><td>&nbsp;</td></tr>";
	$mail_Body .= "</table>";
	$mail_Body .= "</td>";
	$mail_Body .= "</tr>";
	$mail_Body .= "</table>";
	
	$mail_From = siteEmail; 
	$mail_To = osql($email);
	if(sendMail( siteName, $mail_From, $mail_To, $mail_Subject, $mail_Body, cHighPriority, cHtmlFormat)){
		return true;
	}
	return false;
}

/*Fin modification*/
?>