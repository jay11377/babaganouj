<?php
if(!isset($_GET['id_option'])){} else{ ?><?php $id_option= $_GET['id_option'];

// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle=ucfirst($module);
$result_option_name = $connector->query("SELECT DISTINCT(name) from options where id =".$_GET['id_option']);
$row_option_name = $connector->fetchArray($result_option_name);

// Change the active status if it was changed
if(isset($_GET['toggleactive'])){
	$page_id=$_GET['toggleactive'];
	$result_toggle = $connector->query("SELECT active FROM options_items WHERE id=".$page_id);
	$row_toggle = $connector->fetchArray($result_toggle);
	$active = ($row_toggle['active']==1)?0:1;
	$query="UPDATE options_items SET active=".$active." WHERE id=".$page_id;
	if (!($connector->query($query))){
		// It hasn't worked so stop. Better error handling code would be good here!
		echo '<div class="msgbox">
				<div class="msg msg-error"><p>'.getLang('STATUS_ERROR').'</p></div>
			 </div>';
	}
}

// Display a message
if(isset($_SESSION['pagemsg']))
{
	echo '<div class="msgbox">';
	echo $_SESSION['pagemsg'];
	echo '</div>';
	unset($_SESSION['pagemsg']);
}
?>


<div class="box">
	<div class="header">
    	<h3><?php echo $row_option_name['name'] ?></h3>
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&amp;action=add_item&amp;id_option=<?php echo $id_option ?>"><?php showLang('ADD_OPTION_DISH') ?></a>
        <?php if($id_option != ''){ ?>
         <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=listsorted_items<?php if($id_option!='') echo '&amp;id_option='.$id_option.''?>"><?php showLang('REORDER_DISHES') ?></a>
    	<?php }?>
    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('QUANTITY') ?></td>
                    <td><?php showLang('EXTRA_WITH_TAX') ?></td>
                    <td><?php showLang('ACTIVE') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
					$query = 'SELECT A.id, A.id_option, A.id_item, A.quantity, A.prix_ttc, A.order_position, A.active, B.name FROM options_items A, plats B WHERE B.id = A.id_item ';
					if($id_option != '') 
						$query .=  'AND A.id_option = '.isql($id_option) ;
								  
					$query.=' ORDER BY A.order_position';
					
                    $result = $connector->query($query);
					while ($row = $connector->fetchArray($result)){ 
						$class=($i%2==0)?'even':'odd';
						if($i==1)
							$class.=" first";
                    ?>
                        <tr class="<?php echo $class; ?>">
                            <td valign="top">
                            	<?php echo osql($row['name']); ?>
                            </td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo showPriceCurrency($row['prix_ttc']); ?></td>
                            <td><?php
                            	$img=($row['active']==1)? "images/chomp/led-ico/accept.png" : "images/chomp/led-ico/cross_octagon.png"; ?> 
								<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=default_option_items&toggleactive=<?php echo $row['id']; if($id_option != '')echo'&amp;id_option='.$id_option.'';?>"><img src="<?php echo $img ?>" /></a>
                            </td>
                            <td>
                                <a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=delete_item&amp;id_option=<?php echo $id_option ?>&id=<?php echo $row['id']; ?>" title="<?php showLang('DELETE') ?>"><img src="images/chomp/led-ico/delete.png" alt="<?php showLang('DELETE') ?>" onclick="return confirm('<?php showLang('DELETE_MSG') ?>');" /></a>
                            </td>
                        </tr>
                <?php
                        $i++;
                     } 
                ?>
                
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="5">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>

<?php } ?>