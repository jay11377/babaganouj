<?php
include("includes/top_includes.php");
$conn = new DbConnector();
$date = $_POST['date'];
$next_open_min = $_POST['next_open_min'];
$duration = intval($_POST['duration']);
$date_array = explode("/", $date);
$day = $date_array[0];
$month = $date_array[1];
$year = $date_array[2];
$datestring = $year.'-'.$month.'-'.$day.' 00:00:00';
$now = date('Y').'-'.date('m').'-'.date('d').' 00:00:00';
$now_h = date('G');
$now_m = date('i');
$delivery_h = date('H', strtotime("+".$duration." minutes"));
$delivery_m = date('i', strtotime("+".$duration." minutes"));
$id_jour = date('N', strtotime($datestring));

if($duration < 35)
	$delay_after_opening = 0;
else
	$delay_after_opening = 0;


$query = "SELECT * FROM horaires H 
		  LEFT JOIN jours J ON H.id=J.id_horaire
		  WHERE id=".$id_jour;
$result = $conn->query($query);
$row = $conn->fetchArray($result);

$reponse['msg']='';

// if($datestring==$now && (isOpenedNow() || willBeOpenIn($duration)))
if($datestring==$now && (isOpenedNow() || willBeOpenIn(30)))
	$reponse['msg'] .= '<option value="'.getLang('ASAP').'">'.getLang('ASAP').'</option>';

$ouvert_midi = $row['ouvert_midi'];
if($ouvert_midi){
	$ouverture_midi_h = $row['ouverture_midi_h'];
	$ouverture_midi_m = $row['ouverture_midi_min'];
	$fermeture_midi_h = $row['fermeture_midi_h'];
	$fermeture_midi_m = $row['fermeture_midi_min'];
	
	if($ouverture_midi_m + $delay_after_opening >= 60){
		$ouverture_midi_m = $ouverture_midi_m + $delay_after_opening - 60;
		$ouverture_midi_h ++;
	}
	else{
		$ouverture_midi_m+=$delay_after_opening;
	}	
	if($fermeture_midi_m + $duration >= 60){
		$fermeture_midi_m = $fermeture_midi_m + $duration - 60;
		$fermeture_midi_h ++;
	}
	else{
		$fermeture_midi_m+=$duration;
	}	
	
	for($h=$ouverture_midi_h; $h<=$fermeture_midi_h; $h++)
	{
		for($m=0; $m<=55; $m=$m+5){
			if(!($h==$ouverture_midi_h && $m<$ouverture_midi_m) && !($h==$fermeture_midi_h && $m>$fermeture_midi_m) && !($datestring==$now && ((int)$delivery_h>$h || ((int)$delivery_h==$h && (int)$delivery_m>$m))))
			{
				$sel="";
				$m_string=($m<10) ? "0".$m : $m;
				$option_value = $h.getLang('HOURS_SEPARATOR').$m_string;
				if(isset($_SESSION['deliveryTime'])){
					if($_SESSION['deliveryTime']==$option_value)
						$sel = 'selected="selected"';	
				}
				if($datestring==$now){
					if((int)$delivery_h==(int)$h && (int)$delivery_m>=$m){
						if(!(isset($_SESSION['deliveryTime'])))
							$sel = 'selected="selected"';
					}
				}
				$reponse['msg'] .= '<option value="'.$option_value.'" '.$sel.'>'.$h.getLang('HOURS_SEPARATOR').$m_string.'</option>';
			}
		}
	}
}

$ouvert_soir = $row['ouvert_soir'];
if($ouvert_soir){
	$ouverture_soir_h = $row['ouverture_soir_h'];
	$ouverture_soir_m = $row['ouverture_soir_min'];
	$fermeture_soir_h = $row['fermeture_soir_h'];
	$fermeture_soir_m = $row['fermeture_soir_min'];
	
	if($ouverture_soir_m + $delay_after_opening >= 60){
		$ouverture_soir_m = $ouverture_soir_m + $delay_after_opening - 60;
		$ouverture_soir_h ++;
	}
	else{
		$ouverture_soir_m+=$delay_after_opening;
	}	
	if($fermeture_soir_m + $duration >= 60){
		$fermeture_soir_m = $fermeture_soir_m + $duration - 60;
		$fermeture_soir_h ++;
	}
	else{
		$fermeture_soir_m+=$duration;
	}	
	
	for($h=$ouverture_soir_h; $h<=$fermeture_soir_h; $h++)
	{
		for($h=$ouverture_soir_h; $h<=$fermeture_soir_h; $h++)
		{
			for($m=0; $m<=55; $m=$m+5){
				if(!($h==$ouverture_soir_h && $m<$ouverture_soir_m) && !($h==$fermeture_soir_h && $m>$fermeture_soir_m) && !($datestring==$now && ((int)$delivery_h>$h || ((int)$delivery_h==$h && (int)$delivery_m>$m))))
				{
					$sel="";
					$m_string=($m<10) ? "0".$m : $m;
					$option_value = $h.getLang('HOURS_SEPARATOR').$m_string;
					if(isset($_SESSION['deliveryTime'])){
						if($_SESSION['deliveryTime']==$option_value)
							$sel = 'selected="selected"';	
					}
					if($datestring==$now){
						if((int)$delivery_h==(int)$h && (int)$delivery_m>=$m){
							if(!(isset($_SESSION['deliveryTime'])))
								$sel = 'selected="selected"';
						}
					}
					$reponse['msg'] .= '<option value="'.$option_value.'" '.$sel.'>'.$h.getLang('HOURS_SEPARATOR').$m_string.'</option>';
				}
			}
		}
	}
}


if(!(isOpenedNow() || willBeOpenIn($duration)))
{
	if($next_open_min==0)
		$when = getLang('TODAY');
	else if($next_open_min==1)
		$when = getLang('TOMORROW');
	else
		$when = getLang('THE')." ".$date;
	$dayoftheweek = $next_open_min + date('N');
	if($dayoftheweek>7)
		$dayoftheweek-=7;
	// $reponse['open_now'] = getLang('THE_RESTAURANT_IS_CLOSED_NOW')." ".getLang('REOPENING')." ".$when." ".getLang('AT')." ".getOpenTime($dayoftheweek).".<br>".getLang('ORDER_IN_ADVANCE');
	$reponse['open_now'] = getLang('THE_RESTAURANT_IS_CLOSED_NOW')." ".getLang('REOPENING')." ".$when." ".getLang('AT')." ".getOpenTime($dayoftheweek).".";
}
else
	$reponse['open_now'] = '';
header('Content-Type: application/json');
echo json_encode($reponse);
?>