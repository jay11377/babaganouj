<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle=ucfirst($module);

$id_zone = (isset($_GET['id_zone']))?$_GET['id_zone']:'';
$result = $connector->query('SELECT * FROM zones_livraison'); /*To have the list of categories to display in the select option form */			



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
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add"><?php showLang('ADD_CITY') ?></a>
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=listsorted"><?php showLang('REORDER_CITIES') ?></a>
    </div>
    <div class="container">		
    	<form action="moduleinterface.php?module=<?php echo $module; ?>&action=default" method="GET">
      		<input type="hidden" name="module" value="<?php echo $module; ?>" />
            <input type="hidden" name="action" value="<?php echo $action; ?>" />
        	<label for="id_zone">Categories</label>
            <select id="id_zone" name="id_zone" onchange="submit()">
            	<option value=''><?php showLang('ALL') ?></option>
				<?php
					while ($row = $connector->fetchArray($result)){    
				?>
                <option value="<?php echo $row['id'] ?>" <?php if($id_zone == $row['id']) echo 'selected = "selected"'?> ><?php echo $row['name'] ?></option>
                <?php } ?>
            	
            </select>
        </form>
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('POSTCODE') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
					$query = "SELECT * FROM $module ";
					
					if($id_zone != '')
						$query.=' WHERE id_zone = '.isql($id_zone).' ';
					$query.=' ORDER BY order_position';

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
                            <td valign="top"><?php echo $row['postcode'] ?></td>
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
                	<td colspan="3">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>