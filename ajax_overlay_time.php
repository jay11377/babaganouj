<?php 
include("includes/top_includes.php");
//session_start();
$login = (isset($_POST['login']))?1:0;
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

$min = "+".$next_open_min. " days";
$next_open_day =  date(date('d', strtotime($min)).'/'.date('m', strtotime($min)).'/'.date('Y', strtotime($min)));
$date_value = (isset($_SESSION['deliveryDate']) && dateFRtoEN($_SESSION['deliveryDate'])>=dateFRtoEN($next_open_day))?$_SESSION['deliveryDate'] : $next_open_day;
$date_value_en = dateFRtoEN($date_value);
$next_open_day_en = dateFRtoEN($next_open_day);
// add 3 days to date
$in_3_days_value_en = date('m/d/y', strtotime("+3 days"));
?>

<script>
	<?php
	echo 'var year = '.json_encode($year).';';
	echo 'var month = '.json_encode($month).';';
	echo 'var day = '.json_encode($day).';';
	?>
	var array_jours=new Array();
	for (var i=1;i<8;i++)
    {
		temp = new Date(year[i], month[i], day[i]);
    	array_jours[i] = temp; 
    	// array_jours[i] = temp.valueOf(); 
    }
	
	now = new Date(<?php echo date('Y') ?>, <?php echo (int)(((int)date('m'))-1) ?>, <?php echo date('d') ?>);
	in3days = new Date(<?php echo date('Y', strtotime('+3 days')) ?>, <?php echo (int)(((int)date('m', strtotime('+3 days')))-1) ?>, <?php echo date('d', strtotime('+3 days')) ?>);
	nextweek = new Date(<?php echo date('Y', strtotime('+7 days')) ?>, <?php echo (int)(((int)date('m', strtotime('+7 days')))-1) ?>, <?php echo date('d', strtotime('+7 days')) ?>);
	$(function(){
		var deliveryCityRegistered = <?php echo isset($_SESSION['deliveryCity']) ? "1" : "0" ?>;
		if(parseInt(deliveryCityRegistered)==1){
			$.post(		
				"ajax_update_time.php", 
				{date : '<?php echo osql($date_value) ?>', next_open_min : '<?php echo osql($next_open_min) ?>', duration : <?php echo $_SESSION['orderTime'] ?>}, 
				function(data){
					  var login = <?php echo $login ?>;
					  $("#heure").html(data.msg);
					  if(data.open_now!='')
					  {
						$("#importantMsg").html(data.open_now);
						$(".info_overlay").show();
					  }
					  else
					  {
					  	$(".info_overlay").hide();
					  }
					  // when you log in
					  /*
					  if(login==1){
						  $.post(
								"update_delivery_time.php", 
								{date : $("#date").val(), heure : $("#heure").val()}
						   );
						  $("#livraison_date").html($("#date").val());
						  $("#livraison_heure").html($("#heure").val());
					  }
					  */
					  
				},
				"json"	
			);
		}
		/*
		else
		{
			var array = $("#timeOverlay .cityName").attr('rel').split('-');
			var duration = array[1];
			$.post(		
				"ajax_update_time.php", 
				{date : '<?php echo osql($date_value) ?>', next_open_min : '<?php echo osql($next_open_min) ?>', duration : duration}, 
				function(data){
					  if(data.open_now!='')
					  {
						$(".info_overlay").html(data.open_now);
						$(".info_overlay_td").show();
					  }
					  else
						$(".info_overlay_td").hide(); 
				},
				"json"	
			);
		}
		*/

		/*
		$('#date').DatePicker({
			eventName:'focus',
			format:'d/m/Y',
			date: $('#date').val(),
			current: $('#date').val(),
			starts: 1,
			position: 'right',
			onRender: function(date) {
				return {
					disabled: (date.valueOf() < now.valueOf() || date.valueOf() > nextweek.valueOf() || date.valueOf()==array_jours[1] || date.valueOf()==array_jours[2] || date.valueOf()==array_jours[3] || date.valueOf()==array_jours[4] || date.valueOf()==array_jours[5] || date.valueOf()==array_jours[6] || date.valueOf()==array_jours[7])
				}
			},
			onBeforeShow: function(){
				$('#date').attr("disabled","disabled");
			},
			onChange: function(formated, dates){
				$('#date').removeAttr("disabled");
				$('#date').val(formated);
				$('#date').DatePickerHide();
				
				var array = $("#timeOverlay .cityName").attr('rel').split('-');
				var duration = array[1];
				$.post(
						"ajax_update_time.php", 
						{date : formated, next_open_min : '<?php echo osql($next_open_min) ?>', duration : duration}, 
						function(data){
							  $("#heure").html(data.msg);
						},
						"json"
				);
			},
			onHide: function(){
				$('#date').removeAttr("disabled");
			}
		});
		*/
	});
</script>	
<div class="row">
	<div class="col-md-12">
		<h2><?php showLang('PICK_TIME') ?></h2>
	</div>
</div>
<div class="row top15">
	<div class="col-md-12">
		<form class="form-horizontal" role="form" method="post" name="form" action="">
			<div class="form-group info_overlay">
                <div class="col-md-12">
                    <p class="bg-danger" id="importantMsg"></p>
                </div>
            </div>
			<div class="form-group">
	            <label class="col-md-4 control-label" for="date"><?php showLang('DELIVERY_DATE') ?> <span class="required">*</span></label>
	            <div class="col-md-8">
	                <!-- <input type="text" class="form-control" value="<?php echo $date_value ?>" id="date" name="date" readonly> -->
		            <div class='input-group date' id='datetimepicker1'>
		                <input type='text' class="form-control" id="date" name="date" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
	            </div>
	            <script type="text/javascript">
			        $(function () {
			            $('#datetimepicker1').datetimepicker({
			                locale: 'fr',
			                format: 'DD/MM/YYYY',
			                defaultDate: '<?php echo $date_value_en ?>',
			                minDate : '<?php echo $next_open_day_en ?>',
			                maxDate : '<?php echo $in_3_days_value_en ?>',
							disabledDates: [
			                        array_jours[1],
			                        array_jours[2],
			                        array_jours[3],
			                        array_jours[4],
			                        array_jours[5],
			                        array_jours[6],
			                        array_jours[7]
			                    ]
			            })
			            .on('dp.change', function (ev) {
						    $.post(
									"ajax_update_time.php", 
									{date : $('#date').val()}, 
									function(data){
										$("#heure").html(data.msg);
									},
									"json"
							);
						});
			        });
			    </script>
	        </div>
	        <div class="form-group">
	            <label class="col-md-4 control-label" for="heure"><?php showLang('DELIVERY_TIME2') ?> <span class="required">*</span></label>
	            <div class="col-md-8">
	                <select name="heure" id="heure" class="time">
					</select>
	            </div>
	        </div>
	         <div class="form-group">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary" type="submit" name="submit" id="validateTime" value="<?php showLang('SUBMIT') ?>"><?php showLang('SUBMIT') ?></button>
                </div>
            </div>
    	</form>
	</div>
</div>
