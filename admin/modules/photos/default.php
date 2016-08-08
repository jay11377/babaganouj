<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle=ucfirst($module);

// Change the active status if it was changed
if(isset($_GET['toggleactive'])){
	$page_id=$_GET['toggleactive'];
	$result_toggle = $connector->query("SELECT active FROM $module WHERE id=".$page_id);
	$row_toggle = $connector->fetchArray($result_toggle);
	$active = ($row_toggle['active']==1)?0:1;
	$query="UPDATE $module SET active=".$active." WHERE id=".$page_id;
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
    	<h3><?php echo $pagetitle; ?></h3>
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add"><?php showLang('ADD_PHOTO') ?></a>
         <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=listsorted"><?php showLang('REORDER_PHOTOS') ?></a>
    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('IMAGE') ?></td>
                    <td><?php showLang('TITLE') ?></td>
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
                             <td width="250"><?php $img=$sitedir.$row['thumbnail']; ?>
                                <img src="<?php echo $img ?>" /></a>
                            </td>
                            <td>
								<?php echo osql($row['titre']); ?>
                            </td>
                            <td><?php
                            	$img=($row['active']==1)? "images/chomp/led-ico/accept.png" : "images/chomp/led-ico/cross_octagon.png"; ?> 
								<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=default&toggleactive=<?php echo $row['id'] ?>"><img src="<?php echo $img ?>" /></a>
                            </td>
                            <td>
                            	<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
                                <a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=delete&id=<?php echo $row['id']; ?>" title="<?php showLang('DELETE') ?>"><img src="images/chomp/led-ico/delete.png" alt="<?php showLang('DELETE') ?>" onclick="return confirm('<?php showLang('DELETE_MSG') ?>');" /></a>
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