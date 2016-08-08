<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle = getLang('MEZZES_OPTIONS');

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
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add_options"><?php showLang('ADD_OPTION') ?></a>

    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('NAME_ADMIN') ?></td>
                    <td></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
					$query = "SELECT * FROM options ORDER BY NAME";
                    $result = $connector->query($query);
					while ($row = $connector->fetchArray($result)){ 
						$query ="SELECT count(*) as nb_items from options_items where id_option = ".$row['id']."";
                        $result_nb_items = $connector->query($query);
						$row_item = $connector->fetchArray($result_nb_items);
						$class=($i%2==0)?'even':'odd';
						if($i==1)
							$class.=" first";
                    ?>
                        <tr class="<?php echo $class; ?>">
                            <td valign="top">
                            	<a href="moduleinterface.php?module=<?php echo $module; ?>&action=default_option_items&id_option=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><?php echo osql($row['name']); ?></a>
                            </td>
                            <td valign="top">
                            	<?php echo osql($row['name_admin']); ?>
                            </td>
                            <td>			
                                <?php if($row_item['nb_items']==0) showLang('OPTION_NOT_CONFIGURED_YET'); ?>
                            </td>
                            <td>
                            	<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=edit_options&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
                                <a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=delete_options&id=<?php echo $row['id']; ?>" title="<?php showLang('DELETE') ?>"><img src="images/chomp/led-ico/delete.png" alt="<?php showLang('DELETE') ?>" onclick="return confirm('<?php showLang('DELETE_MSG') ?>');" /></a>
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