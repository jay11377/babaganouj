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
    </div>
    <div class="container">		
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('CONTACT_EMAIL') ?></td>
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
                            	<a href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><?php echo osql($row['email']); ?></a>
                            </td>
                            <td>
                            	<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><img src="images/chomp/led-ico/pencil.png" alt="<?php showLang('EDIT') ?>" /></a>
                            </td>
                        </tr>
                <?php
                        $i++;
                     } 
                ?>
                
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="2">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>