<?php
// Create an instance of DbConnector
$connector = new DbConnector();

// Change the active status if it was changed
if(isset($_GET['toggleactive'])){
	$page_id=$_GET['toggleactive'];
	$connector->query("UPDATE $module SET active = 0");
	if (!($result_toggle = $connector->query("UPDATE $module SET active = 1 WHERE id=".$page_id))){
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
    	<h3><?php showLang('CATERER') ?></h3>
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add"><?php showLang('ADD_CATERER_PICTURE') ?></a>
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=listsorted"><?php showLang('REORDER_CATERER') ?></a>
    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('IMAGE') ?></td>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('ACTIVE') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
                    $result = $connector->query("SELECT * FROM $module ORDER BY order_position");
					while ($row = $connector->fetchArray($result)){ 
                        $class=($i%2==0)?'even':'odd';
						if($i==1)
							$class.=" first";
                    ?>
                        <tr class="<?php echo $class; ?>">
                       		<td><?php $img=$sitedir.$row['thumbnail']; ?>
                                <img src="<?php echo $img ?>" /></a>
                            </td>
                            <td>
                            	<a href="moduleinterface.php?module=<?php echo $module; ?>&amp;action=edit&amp;id=<?php echo $row['id']; ?>" title="<?php showLang('VIEW') ?>"><?php echo osql($row['name']); ?></a>
                            </td>
                            <td><?php
                            	$img=($row['active']==1)? "images/chomp/led-ico/accept.png" : "images/chomp/led-ico/cross_octagon.png"; ?> 
								<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&amp;action=default&amp;toggleactive=<?php echo $row['id'] ?>"><img src="<?php echo $img ?>" /></a>
                            </td>
                            <td>
                            	<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&amp;action=edit&amp;id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
                                <a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&amp;action=delete&amp;id=<?php echo $row['id']; ?>" title="<?php showLang('DELETE') ?>"><img src="images/chomp/led-ico/delete.png" alt="<?php showLang('DELETE') ?>" onclick="return confirm('<?php showLang('DELETE_MSG') ?>');" /></a>
                            </td>
                        </tr>
                <?php
                        $i++;
                     } 
                ?>
                
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="4">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>