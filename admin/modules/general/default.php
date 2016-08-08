<div class="msgbox" id="msgbox" style="display:none"></div>
<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle=ucfirst($module);


// Display a message
if(isset($_SESSION['pagemsg']))
{
	echo '<div class="msgbox">';
	echo $_SESSION['pagemsg'];
	echo '</div>';
	unset($_SESSION['pagemsg']);
}
?>


<?php 

if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
	
	/*modification des methodes de payments*/
	$array = array('Paypal', 'Carte_bancaire', 'Ticket_restaurant', 'Espece', 'Cheque');
	foreach($array as $method){
		$query = "UPDATE order_methods SET active = '$_POST[$method]' where name = '$method'";
		if (!$connector->query($query) ){?>
					<script>
						$('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR') ?></p></div>');
						$('#msgbox').show();
					</script><?php
		}
			
	}
	
	for($i=1; $i<=7;$i++){
		/*requete de modification des horaires dans la table "horaire"*/
		$query ="UPDATE horaires
				SET ouverture_midi_h = '".isql($_POST[$i.'_ouverture_midi_h'])."',
					ouverture_midi_min = '".isql($_POST[$i.'_ouverture_midi_min'])."',
					fermeture_midi_h = '".isql($_POST[$i.'_fermeture_midi_h'])."',
					fermeture_midi_min = '".isql($_POST[$i.'_fermeture_midi_min'])."',
					ouverture_soir_h = '".isql($_POST[$i.'_ouverture_soir_h'])."',
					ouverture_soir_min = '".isql($_POST[$i.'_ouverture_soir_min'])."',
					fermeture_soir_h = '".isql($_POST[$i.'_fermeture_soir_h'])."',
					fermeture_soir_min = '".isql($_POST[$i.'_fermeture_soir_min'])."'
							 where id = $i";
		/*requete de modification des plages d'ouverture (midi, soir) dans la table jours*/
		$query2 = "UPDATE jours
				SET ouvert_midi = '".isql($_POST[$i.'_ouvert_midi'])."',
					ouvert_soir = '".isql($_POST[$i.'_ouvert_soir'])."'
						where id_horaire = $i";
					
					
		if ($connector->query($query) && $connector->query($query2)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('SHOP_UPDATED').'</p></div>';
					//header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
		}else{ ?>
				
					<script>
						$('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR') ?></p></div>');
						$('#msgbox').show();
					</script><?php
		}
	}
}
// If Cancel has been clicked
else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
	header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
}
?>


<div class="box">
	<div class="header">
    	<h3><?php showLang('SHOP') ?></h3>
    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('OPEN_MIDDAY') ?></td>
                    <td><?php showLang('MIDDAY') ?></td>
                    <td><?php showLang('OPEN_EVENING') ?></td>
                    <td><?php showLang('EVENING') ?></td>
                </tr>
            </thead>
            <tbody>
            	<form  method="post" name="form" action="moduleinterface.php" enctype="multipart/form-data" >
                	  <div class="hidden">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="module" value="<?php echo $module; ?>" />
                        <input type="hidden" name="action" value="<?php echo $action; ?>" />
                   	  </div>
					<?php
					$result = $connector->query("SELECT * FROM horaires, jours where jours.id_horaire = horaires.id ORDER BY jours.id_horaire");
                        // Loop for each item in that array
                        $i=1;
						$j=1; /* pour diférencier les names des champs de formulaires de des jours. ( lundi -> 1, mardi ->
                         ...)*/
                        while ($row = $connector->fetchArray($result)){ 
                            $class=($i%2==0)?'even':'odd';
                            if($i==1)
                                $class.=" first";
                        ?>
                            <tr class="<?php echo $class; ?>">
                                <td valign="top">
                                    <a href="moduleinterface.php?module=villes&amp;action=default&amp;id_zone=<?php echo $row['id']; ?>" title="<?php showLang('VIEW') ?>"><?php echo osql($row['name']); ?></a>
                                </td>
                                <td>
                                    <input type="checkbox" id="<?php echo $j?>_ouvert_midi" name="<?php echo $j?>_ouvert_midi" value="1" <?php if($row['ouvert_midi'] == 1) echo 'checked="checked"' ?> />
                                </td>
                                <td>
                                 	<?php showLang('FROM') ?>
                                    <select name="<?php echo $j?>_ouverture_midi_h" id="<?php echo $j?>_ouverture_midi_h" style="width:60px">
                                    	<?php for($k=0; $k<=23;$k++){?>
                                        <option value="<?php echo $k ?>" <?php if($row['ouverture_midi_h'] == $k) echo 'selected="selected"'?>>
											<?php echo $k ?>
                                        </option>
										<?php }?>
                                    </select>
                                   
                                    <select id="<?php echo $j?>_ouverture_midi_min" name="<?php echo $j?>_ouverture_midi_min" style="width:60px">
                                    	<?php for($k=0; $k<=45;$k=$k+15){?>
                                        <option value="<?php echo $k ?>" <?php if($row['ouverture_midi_min'] == $k) echo 'selected="selected"'?>>
											<?php echo ($k==0)?"0".$k : $k ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                    <?php showLang('TO') ?>
                                    <select name="<?php echo $j?>_fermeture_midi_h" id="<?php echo $j?>_fermeture_midi_h" style="width:60px">
                                    	<?php for($k=0; $k<=23;$k++){?>
                                        <option value="<?php echo $k ?>" <?php if($row['fermeture_midi_h'] == $k) echo 'selected="selected"'?>>
											<?php echo $k ?>
                                        </option>
										<?php }?>
                                    </select>
                                    
                                    <select id="<?php echo $j?>_fermeture_midi_min" name="<?php echo $j?>_fermeture_midi_min" style="width:60px">
                                    	<?php for($k=0; $k<=45;$k=$k+15){?>
                                        <option value="<?php echo $k ?>" <?php if($row['fermeture_midi_min'] == $k) echo 'selected="selected"'?>>
											<?php echo ($k==0)?"0".$k : $k ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </td>
                                <td>
                                    <input type="checkbox" id="<?php echo $j?>_ouvert_soir" name="<?php echo $j?>_ouvert_soir" value="1" <?php if($row['ouvert_soir'] == 1) echo 'checked="checked"' ?>  />
                                </td>
                                <td>
                                 	<?php showLang('FROM') ?>
                                    <select name="<?php echo $j?>_ouverture_soir_h" id="<?php echo $j?>_ouverture_soir_h" style="width:60px">
                                    	<?php for($k=0; $k<=23;$k++){?>
                                        <option value="<?php echo $k ?>" <?php if($row['ouverture_soir_h'] == $k) echo 'selected="selected"'?>>
											<?php echo $k ?>
                                        </option>
										<?php }?>
                                    </select>
                                    <select id="<?php echo $j?>_ouverture_soir_min" name="<?php echo $j?>_ouverture_soir_min" style="width:60px">
                                    	<?php for($k=0; $k<=45;$k=$k+15){?>
                                        <option value="<?php echo $k ?>" <?php if($row['ouverture_soir_min'] == $k) echo 'selected="selected"'?>>
											<?php echo ($k==0)?"0".$k : $k ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                    <?php showLang('TO') ?>
                                    <select name="<?php echo $j?>_fermeture_soir_h" id="<?php echo $j?>_fermeture_soir_h" style="width:60px" >
                                    	<?php for($k=0; $k<=23;$k++){?>
                                        <option value="<?php echo $k ?>" <?php if($row['fermeture_soir_h'] == $k) echo 'selected="selected"'?>>
											<?php echo $k ?>
                                        </option>
										<?php }?>
                                    </select>
                                    <select id="<?php echo $j?>_fermeture_soir_min" name="<?php echo $j?>_fermeture_soir_min" style="width:60px">
                                    	<?php for($k=0; $k<=45;$k=$k+15){?>
                                        <option value="<?php echo $k ?>" <?php if($row['fermeture_soir_min'] == $k) echo 'selected="selected"'?>>
											<?php echo ($k==0)?"0".$k : $k ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </td>
                            </tr>
                    <?php
                            $j++;
							$i++;
                         } 
                    ?>
                    <tr>
                		<td colspan="5"><h3>Moyens de paiment</h3><br />
                        	<?php $result = $connector->query("SELECT * FROM order_methods");
								while($row=$connector->fetchArray($result)){
							 ?>
                        	<label for="<?php echo $row['name']?>">
                            	<img  src="<?php echo $sitedir.$row['image'] ?>" alt="<?php echo $row['name'] ?>" title="<?php echo str_replace('_', ' ', $row['name']) ?>"/>
								
                                </label>
                        	<input type="checkbox" name="<?php echo $row['name']?>" id="<?php echo $row['name']?>" value="1" <?php if($row['active'] == 1)echo 'checked="checked"';?>  /><br />
                            <?php }?>
                        </td>
              		</tr>
                    <tr>
                		<td colspan="5">
                		<div class="buttons">
                            <p>
                              <input name="submit" type="submit" value="<?php showLang('SUBMIT') ?>" class="button">
                              <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                            </p>
                        </div>
                        </td>
              		</tr>
                </form>
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="5">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>