<?php
$duration = 30;
		
if(!(isOpenedNow() || willBeOpenIn($duration)))
{
	$query_jours = "SELECT id_horaire FROM jours WHERE ouvert_midi=0 AND ouvert_soir=0";
	$result_jours = $connector->query($query_jours);
	$array_jours = array();
	$array_jours_fermes = array();
	while($row_jours = $connector->fetchArray($result_jours)){
		$array_jours_fermes[] = $row_jours['id_horaire'];
	}

	$next_open_min = 100;
	for($i=1;$i<8;$i++){
		$temp = $i - date('N');
		if($temp<0)
			$temp+=7;
		if(in_array($i, $array_jours_fermes) || ($temp==0 && stillOpenToday()==false))
		{
			$temp =  "+".$temp. " days";
			$year[$i] = date('Y', strtotime($temp));
			$month[$i] = (int)(((int)date('m', strtotime($temp)))-1);
			$day[$i] = date('d', strtotime($temp));
		}
		else
		{
			if($temp<$next_open_min){
				if($temp==0)
				{
					if(stillOpenToday())
						$next_open_min = $temp;		
				}
				else
					$next_open_min = $temp;
			}
			$temp =  "+".$temp. " days";
			$year[$i] = '1980';
			$month[$i] = '1';
			$day[$i] = '1';
		}
	}
	
	if($next_open_min==0)
		$when = getLang('TODAY');
	else if($next_open_min==1)
		$when = getLang('TOMORROW');
	else
		$when = getLang('THE')." ".$date;
	$dayoftheweek = $next_open_min + date('N');
	if($dayoftheweek>7)
		$dayoftheweek-=7;
	echo '
	<div class="row">
		<div class="col-md-12">
            <p class="bg-danger">'.getLang('THE_RESTAURANT_IS_CLOSED_NOW')." ".getLang('REOPENING')." ".$when." ".getLang('AT')." ".getOpenTime($dayoftheweek).'</p>
        </div>
    </div>';
}
//<p class="bg-danger">'.getLang('THE_RESTAURANT_IS_CLOSED_NOW')." ".getLang('REOPENING')." ".$when." ".getLang('AT')." ".getOpenTime($dayoftheweek).".<br>".getLang('ORDER_IN_ADVANCE').'</p>
?>