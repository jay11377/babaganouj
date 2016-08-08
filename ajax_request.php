<?php
include("includes/top_includes.php");
$request = $_POST['request'];

switch($request){
	case 'getCity' :
		$cp = $_POST['cp'];
		$result_city = getCityFromCp($cp);
		if($connector->getNumRows($result_city)==0)
			$reponse['statut'] = 0;
		else{
			$reponse['statut'] = 1;
			$row_city = $connector->fetchArray($result_city);
			$reponse['msg'] = getCorrectCityName($row_city['name']);	
		}
		break;
}

header('Content-Type: application/json');
echo json_encode($reponse);

?>