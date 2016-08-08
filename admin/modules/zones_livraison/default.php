<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle=getLang('DELIVERY_ZONES');


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
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add"><?php showLang('ADD_ZONE') ?></a>
    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('MINIMUM') ?></td>
                    <td><?php showLang('DELIVERY_DURATION') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
                    $result = $connector->query("SELECT * FROM $module");
					while ($row = $connector->fetchArray($result)){ 
                        $class=($i%2==0)?'even':'odd';
						if($i==1)
							$class.=" first";
                    ?>
                        <tr class="<?php echo $class; ?>">
                            <td valign="top">
                            	<a href="moduleinterface.php?module=villes&amp;action=default&amp;id_zone=<?php echo $row['id']; ?>" title="<?php showLang('VIEW') ?>"><?php echo osql($row['name']); ?></a>
                            </td>
                            <td><?php echo osql($row['minimum']); ?> <?php showLang('CURRENCY') ?></td>
                            <td><?php echo osql($row['duree_livraison']); ?> <?php showLang('MN') ?></td>
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