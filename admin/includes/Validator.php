<?php
require_once('SystemComponent.php');
class Validator extends SystemComponent {

	var $errors=array(); // A variable to store a list of error messages

	// Validate something's been entered
	// NOTE: Only this method does nothing to prevent SQL injection
	// use with addslashes() command
	function validateGeneral($theinput,$description = ''){
		if (trim($theinput) != ""){
			return true;
		}else{
			$this->errors[] = $description;
			return false;
		}
	}
	
	// Validate user name, make sure that this user name doesn't already exist in the database
	function validateUserName($theinput,$description = ''){
		global $langvars;
		if (trim($theinput) == "") {
			$this->errors[] = $description;
			return false;
		}
		else if (!preg_match("/^[a-zA-Z0-9\._ ]+$/", $theinput)){
			$this->errors[] = getLang('USER_NAME_LETTERS');
			return false;
		}	
		else{
			global $action;
			if($action=='adduser'){
				$connector=new DbConnector();
				$result = $connector->query("SELECT * FROM users WHERE username='".$theinput."'");
				if($connector->getNumRows($result)>0){
					$this->errors[] = getLang('USER_NAME_USED');
					return false;
				}
				else{
					return true;
				}
			}
			else{
				return true;
			}		
		}
	}
	
	// Validate the voucher
	function validateVoucher($theinput,$description = ''){
		$connector=new DbConnector();
		$result = $connector->query("SELECT * FROM remises WHERE code='".isql($theinput)."'");
		if($connector->getNumRows($result)>0){
			$this->errors[] = $description;
			return false;
		}
		else{
			return true;
		}
	}
	
	// Validate password (at least 5 characters)
	function validatePassword($theinput,$description = ''){	
		global $langvars;
		if (trim($theinput) == "") {
			$this->errors[] = $description;
			return false;		
		}
		else if(strlen($theinput) < 5) {
			// Make sure that this error has not be stocked, as the function is launched twice(confirmation password)
			$search=getLang('PASSWORD_CHARACTERS');
			if(!in_array($search,$this->errors))
				$this->errors[] = $search;
			return false;
		}
		else if (!preg_match("/^[a-zA-Z0-9\._ ]+$/", $theinput)){
			$search=getLang('PASSWORD_LETTERS');
			if(!in_array($search,$this->errors))
				$this->errors[] = $search;
			return false;
		}
		else{
			return true;
		}
	}
	
	// Functions compare: compare 2 strings
	function compare($string1,$string2,$description = ''){		
		if ($string1!=$string2) {
			$this->errors[] = $description;
			return false;		
		}
		else{
			return true;
		}
	}
	
	// Validate Upload image
	function validateUploadImage($theinput,$description = '',$module,$width = false,$height = false){
		if( isset($_FILES[$theinput]) && $_FILES[$theinput]['name'] != '' )
		  {
				if( $_FILES[$theinput]['error'] != 0 || $_FILES[$theinput]['tmp_name'] == '' )
				  {
					$this->errors[] = addslashes($description);
					return false;
				  }
				else
				  {
					$value = $this->handle_upload_image($theinput,$module,$width,$height);
					if( $value === FALSE )
					  {
						return false;
					  }
					else 
					  {
						return $value;
					  }
				  }
		  }
		else
		{
			$this->errors[] = addslashes($description);
			return false;
		}
	}
		
  function handle_upload_image($theinput,$module,$width,$height)
  {  
	  
	  global $langvars, $allowed_images_types ,$edit, $id;
	  

	  // Image has to be < 2MB
	  if( $_FILES[$theinput]['size'] > 2000000)
	    {
		  $this->errors[] = getLang('MAX_IMAGE_UPLOAD');
	      return FALSE;
	    }
		
	  $filename = basename($_FILES[$theinput]['name']);
	  //if($theinput == 'largeimage') // Avoid to have 2 images with the same name 
	  	//$filename="large_".$filename;

	  // Get the files extension
	  $ext = substr(strrchr($filename, '.'), 1);

	  // compare it against the 'allowed extentions' defined in config.php
	  if( !in_array( $ext, $allowed_images_types ) ) 
		{
		  //$this->errors[] = 'sdfsf';
		  $this->errors[] = getLang('CANT_UPOLOAD_TYPE_FILE');
		  return false;
		}
	   
	  $f=$_FILES[$theinput]['tmp_name']; 
	  
	  
	  // Make sure that the image has the good dimensions
	  if($width!=0 && $height!=0){
		  list($w, $h) = getimagesize($f);
				
		  if($w!=$width || $h!=$height)
		  {
			$this->errors[] = getLang('DIMENSIONS_INCORRECT').$width."x".$height."px";
			return false;
		  }
	   }
	   
	   
	   // Make sure that the image has the good ratio
	   /*
	   if($width!=0 && $height!=0){
		  list($w, $h) = getimagesize($f);
				
		  if($w/$width != $h/$height)
		  {
			$this->errors[]=$langvars['RATIO_INCORRECT'].$width."/".$height;
			return false;
		  }
	   }
	   */
	   
	   
	   
	   // Upload the file only if there was no other errors	
	   if( !($this->foundErrors()) ){ 
			  
			  // Get the existing folder in case of editing
			  if(isset($edit) && $edit!='')
			  {
				  //if(ini_get('safe_mode') == 'Off') 
				  	$p = "../uploads/".$module."/id".$id."/";
				  //else
				  	//$p = "../uploads/".$module."/";
			  }
			  // Get the name of the new folder in case of inserting
			  else
			  {
				  // Get Next Auto increment
				  $connector2 = new DbConnector();
				  $result2 = $connector2->query("SHOW TABLE STATUS LIKE '".$module."'");
				  $row2 = $connector2->fetchArray($result2);
				  //if(ini_get('safe_mode') == 'Off') 
				  	$p = "../uploads/".$module."/id".$row2['Auto_increment']."/";
				  //else
				  	//$p = "../uploads/".$module."/";
			 }
			 // Create the new folder where we'll upload the image
			  if (!is_dir($p)) {
				  $newdir = @mkdir($p);
				  chmod($p, 0775);
				  if( $newdir === FALSE )
					{
					  $this->errors[] = getLang('DIRECTORY_CREATION_ISSUE');
					  return FALSE;
					}
			  }
			  // Upload the image
			  $dest = $p.$filename;
			  if(!(move_uploaded_file($f,$dest)))
			  {
				  $this->errors[] = getLang('FILE_CREATION_ISSUE');
				  $this->errors[] = $dest;
				  return FALSE;
			   }
			  return substr($dest,3);
		}
		else
			return true; // retur true if the image is valid but there were other errors
  }
  
  // Validate Upload File (non image, specific extension)
  function validateUploadFile($theinput,$description = '',$module,$extension){
		 global $langvars;
		if( isset($_FILES[$theinput]) && $_FILES[$theinput]['name'] != '' )
		  {
				if( $_FILES[$theinput]['error'] != 0 || $_FILES[$theinput]['tmp_name'] == '' )
				  {
					$this->errors[] = getLang('UPLOADING_ISSUE').$extension.' '.getLang('FILE');
					return false;
				  }
				else
				  {
					$value = $this->handle_upload_file($theinput,$module,$extension);
					if( $value === FALSE )
					  {
						return false;
					  }
					else 
					  {
						return $value;
					  }
				  }
		  }
		else
		{
			$this->errors[] = $description;
			return false;
		}		
  }
		
  function handle_upload_file($theinput,$module,$extension='')
  {  
	  
	   global $langvars;
	  // File has to be < 3MB
	  if( $_FILES[$theinput]['size'] > 2000000)
	    {
		  $this->errors[] = getLang('MAX_IMAGE_UPLOAD');
	      return FALSE;
	    }
	  
	  $filename = basename($_FILES[$theinput]['name']);
	  
	  // Get the files extension
	  $ext = substr(strrchr($filename, '.'), 1);

	  // compare it against the extension
	  if(is_array($extension)){
		if( !in_array( $ext, $extension ) ){
			 $error_ext=getLang('FILE_NOT');
			 foreach($extension as $e){
			 	$error_ext.=".".$e." ".getLang('OR')." ";
			 }
			 $this->errors[] = substr($error_ext,0,strlen($error_ext)-4);
		 	 return FALSE;
		}	 
	  }
	  else if($ext!=$extension) 
		{
		  $this->errors[] = getLang('FILE_NOT').$extension;
		  return FALSE;
		}
	   
	   // Upload the file only if there was no other errors	
	   if( !($this->foundErrors()) ){ 
			  global $id;
			  $connector2 = new DbConnector();
			  $result2 = $connector2->query("SHOW TABLE STATUS LIKE '".$module."'");
			  $row2 = $connector2->fetchArray($result2);
			  // Get the existing folder in case of editing
			  if(isset($id) && $id!='')
			  {
				  //if(ini_get('safe_mode') == 'Off') 
				  	$p = "../uploads/".$module."/id".$id."/";
				  //else
				  	//$p = "../uploads/".$module."/";
			  }
			  // Get the name of the new folder in case of inserting
			  else
			  {
				  // Get Next Auto increment
				   //if(ini_get('safe_mode') == 'Off') 
				  	$p = "../uploads/".$module."/id".$row2['Auto_increment']."/";
				  //else
				  	//$p = "../uploads/".$module."/";
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
			  // Upload the file
			  $dest = $p.$filename;
			  $f=$_FILES[$theinput]['tmp_name'];
			  if(!(move_uploaded_file($f,$dest)))
			  {
				  $this->errors[] = getLang('FILE_CREATION_ISSUE');
				  return FALSE;
			   }
			  return substr($dest,3);
		}
		else
			return true; // retur true if the image is valid but there were other errors
  }
	
	
	// Validate text only
	function validateTextOnly($theinput,$description = ''){
		// $result = ereg ("^[A-Za-z0-9\ ]+$", $theinput );
		$result = preg_match ("/^[A-Za-z0-9\ ]+$/", $theinput );
		if ($result){
			return true;
		}else{
			$this->errors[] = $description;
			return false; 
		}
	}

	// Validate text only, no spaces allowed
	function validateTextOnlyNoSpaces($theinput,$description = ''){
		$result = ereg ("^[A-Za-z0-9]+$", $theinput );
		if ($result){
			return true;
		}else{
			$this->errors[] = $description;
			return false; 
		}
	}
		
	// Validate email address
	function validateEmail($themail,$description = ''){
		$result = ereg ("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $themail );
		if ($result){
			return true;
		}else{
			$this->errors[] = $description;
			return false; 
		}		
	}
	
	//Check if the Emails is in the format email1@gmail.com, email2@gmail.com
	function validateMultipleEmails($theinput, $description=''){
		$theinput = str_replace(' ','',$theinput);
		$the_sub_input = explode(',', $theinput);
		foreach($the_sub_input as $input){
			if(!$this->validateEmail($input, $description)){
				$this->errors[] = $description;
				return false;
			}
		}
		return true;	
	}
	
	// Validate email address and check that this email is not already used
	function validateEmailAccount($themail,$description = ''){
		global $langvars;	
		
		$result = ereg ("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $themail );
		if ($result){
			$connector=new DbConnector();
			$result = $connector->query("SELECT * FROM clients WHERE email='".$themail."'");
			if($connector->getNumRows($result)>0){
				$this->errors[] = getLang('EMAIL_USED');
				return false;
			}
			else{
				return true;
			}	
		}else{
			$this->errors[] = $description;
			return false; 
		}
	}
	
	// Validate Price
	function validatePrice($price,$description = ''){
		if(is_numeric($price)){
		  return sprintf('%01.2f', round($price, 2));
		}else{
		  $this->errors[] = $description;
		  return false;
		}
	}
	
	// Validate URL
	function validateURL( $url,$description = '' )
	{
		$url = @parse_url($url);

		if ( ! $url) {
			$this->errors[] = $description;
			return false;
		}

		$url = array_map('trim', $url);
		$url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];
		$path = (isset($url['path'])) ? $url['path'] : '';

		if ($path == '')
		{
			$path = '/';
		}

		$path .= ( isset ( $url['query'] ) ) ? "?$url[query]" : '';

		if ( isset ( $url['host'] ) AND $url['host'] != gethostbyname ( $url['host'] ) )
		{
			if ( PHP_VERSION >= 5 )
			{
				$headers = get_headers("$url[scheme]://$url[host]:$url[port]$path");
			}
			else
			{
				$fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);

				if ( ! $fp )
				{
					$this->errors[] = $description;
					return false;
				}
				fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
				$headers = fread ( $fp, 128 );
				fclose ( $fp );
			}
			$headers = ( is_array ( $headers ) ) ? implode ( "\n", $headers ) : $headers;
			return ( bool ) preg_match ( '#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers );
		}
		$this->errors[] = $description;
		return false;
	}
		
	
	// Validate numbers only
	function validateNumber($theinput,$description = ''){
		if (is_numeric($theinput)) {
			return true; // The value is numeric, return true
		}else{ 
			$this->errors[] = $description; // Value not numeric! Add error description to list of errors
			return false; // Return false
		}
	}
	
	// Validate numbers Over 0only
	function validateNumberOver0($theinput,$description = ''){
		if (is_numeric($theinput) && $theinput>0 ) {
			return true; // The value is numeric, return true
		}else{ 
			$this->errors[] = $description; // Value not numeric! Add error description to list of errors
			return false; // Return false
		}
	}
	
	// Validate date
	function validateDate($thedate,$description = ''){
		if (strtotime($thedate) === -1 || $thedate == '') {
			$this->errors[] = $description;
			return false;
		}else{
			return true;
		}
	}
	
	// Validate date
	function validateTime($time,$description = ''){
		if(trim($theinput) != ""){
			$this->errors[] = $description;
			return false;
		}	
		$hour = substr($time,0,2);
		$separator = substr($time,2,1);
		$minute = substr($time,3,2);
		if(is_numeric($hour) && is_numeric($minute) && ($separator=='h' || $separator==':' || $separator=='.')) {
			return true;
		}else{
			$this->errors[] = $description;
			return false;
		}
	}
	
	function validateCompareDates($date1, $date2, $description = '') {
		$date1_array = explode("-",$date1);
		$date2_array = explode("-",$date2);
		$timestamp1 = mktime(0,0,0,$date1_array[1],$date1_array[2],$date1_array[0]);
		$timestamp2 = mktime(0,0,0,$date2_array[1],$date2_array[2],$date2_array[0]);
		
		if ($timestamp1<=$timestamp2)
			return true;
		else{
			$this->errors[] = $description;
			return false;
		}
	} 
	
	// Compare two times
	function compareTime($starttime,$endtime,$description = ''){
		$tabstart=explode(" ",$starttime);
		$tabend=explode(" ",$endtime);
		if($tabstart[1]=='PM' && $tabend[1]=='AM'){
			$this->errors[] = $description;
			return false;
		}
		else if($tabstart[1]==$tabend[1]){
			$tabstart=explode(":",$tabstart[0]);
			$tabend=explode(":",$tabend[0]);
			if($tabstart[0]>$tabend[0]){
				$this->errors[] = $description;
				return false;
			}
			else if($tabstart[0]==$tabend[0]){
				if($tabstart[1]>=$tabend[1]){
					$this->errors[] = $description;
					return false;
				}
			}			
		}
		return true;
	}
	
	//Check if the Postal code is in the following format: 247896,456687,458765
	function validateMultiplePostCode($theinput, $description=''){
		$theinput = str_replace(' ','',$theinput);
		$the_sub_input = explode(',', $theinput);
		foreach($the_sub_input as $input){
			if(strlen($input) != 5 || !$this->validateNumber($input, getLang('POSTCODE_IS_NUMERIC'))){
				$this->errors[] = $description;
				return false;
			}
		}
		return true;	
	}
	
	
	// Check whether any errors have been found (i.e. validation has returned false)
	// since the object was created
	function foundErrors() {
		if (count($this->errors) > 0){
			return true;
		}else{
			return false;
		}
	}

	// Return a string containing a list of errors found,
	// Seperated by a given deliminator
	function listErrors($delim = ' '){
		return implode($delim,$this->errors);
	}
	
	// Manually add something to the list of errors
	function addError($description){
		$this->errors[] = $description;
	}	
		
}
?>