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


<div class="box">
	<div class="header">
    	<h3><?php echo $pagetitle; ?></h3>
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add"><?php showLang('ADD_TAX_RATE') ?></a>
    </div>
    <div class="container">
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('TAX_RATE') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
					$query = "SELECT * FROM $module ORDER BY value";
                    $result = $connector->query($query);
					while ($row = $connector->fetchArray($result)){ 
                        $class=($i%2==0)?'even':'odd';
						if($i==1)
							$class.=" first";
                    ?>
                        <tr class="<?php echo $class; ?>">
                            <td valign="top">
                            	<a href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><?php echo osql($row['name']); ?></a>
                            </td>
                            <td><?php echo osql($row['value']); ?>%</td>
                            <td>
                            	<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
                                <?php
                                $result_tva = $connector->query("SELECT id FROM plats WHERE id_tva=".$row["id"]);
								if($connector->getNumRows($result_tva)==0) : ?>
                                	<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=delete&id=<?php echo $row['id']; ?>" title="<?php showLang('DELETE') ?>"><img src="images/chomp/led-ico/delete.png" alt="<?php showLang('DELETE') ?>" onclick="return confirm('<?php showLang('DELETE_MSG') ?>');" /></a><?php
								endif; ?>
                            </td>
                        </tr>
                <?php
                        $i++;
                     } 
                ?>
                
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="3">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>