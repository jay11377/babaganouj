<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$pagetitle=ucfirst($module);

$id_categorie = (isset($_GET['id_categorie']))?$_GET['id_categorie']:'';
$result = $connector->query('SELECT * FROM categories WHERE menu=0 ORDER BY name'); /*To have the list of categories to display in the select option form */			

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
        <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=add"><?php showLang('ADD_DISH') ?></a>
        <?php if($id_categorie != ''){ ?>
         <a class="button small alt" href="moduleinterface.php?module=<?php echo $module; ?>&action=listsorted<?php if($id_categorie!='') echo '&amp;id_categorie='.$id_categorie.''?>"><?php showLang('REORDER_DISHES') ?></a>
    	<?php }?>
    </div>
    <div class="container">		
    	<form action="moduleinterface.php?module=<?php echo $module; ?>&action=default" method="GET">
      		<input type="hidden" name="module" value="<?php echo $module; ?>" />
            <input type="hidden" name="action" value="<?php echo $action; ?>" />
        	<label for="id_categorie"><?php showLang('CATEGORIES') ?></label>
            <select id="id_categorie" name="id_categorie" onchange="submit()">
            	<option value=''><?php showLang('ALL') ?></option>
				<?php
					while ($row = $connector->fetchArray($result)){    
				?>
                <option value="<?php echo $row['id'] ?>" <?php if($id_categorie == $row['id']) echo 'selected = "selected"'?> ><?php echo $row['name'] ?></option>
                <?php } ?>
            </select>
        </form>
    	 <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td><?php showLang('IMAGE') ?></td>
                    <td><?php showLang('NAME') ?></td>
                    <td><?php showLang('MAIN_INGREDIENT') ?></td>
                    <td><?php showLang('PRICE_NO_TAX') ?></td>
                    <td><?php showLang('PRICE_WITH_TAX') ?></td>
                    <td><?php showLang('ACTIVE') ?></td>
                    <td><?php showLang('OPTIONS') ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop for each item in that array
                    $i=1;
					$query = "SELECT * FROM $module WHERE menu=0 ";
					
					if($id_categorie != '')
						$query.=' AND id_categorie = '.(int)$id_categorie.' ';
						
					$query.= "ORDER BY order_position";
                    $result = $connector->query($query);
					while ($row = $connector->fetchArray($result)){ 
                        $prix_ht = $row['prix_ht'];
						for($i=0;$i<4;$i++)
						{
							if(substr($prix_ht, -1)==0)
								$prix_ht = substr($prix_ht, 0, -1);
						}
						$class=($i%2==0)?'even':'odd';
						if($i==1)
							$class.=" first";
                    ?>
                        <tr class="<?php echo $class; ?>">
                             <td><img src="<?php echo "../".$row['thumbnail1'] ?>" /></td>
                            <td>
                            	<a href="moduleinterface.php?module=<?php echo $module; ?>&action=edit&id=<?php echo $row['id']; ?>" title="<?php showLang('EDIT') ?>"><?php echo osql($row['name']); ?></a>
                            </td>
                            <td><?php echo osql($row['ingredient_principal']); ?></td>
                            <td><?php echo $prix_ht ?> <?php showLang('CURRENCY') ?></td>
                            <td><?php echo $row['prix_ttc'] ?> <?php showLang('CURRENCY') ?></td>
                            <td><?php
                            	$img=($row['active']==1)? "images/chomp/led-ico/accept.png" : "images/chomp/led-ico/cross_octagon.png"; ?> 
								<a class="image" href="moduleinterface.php?module=<?php echo $module; ?>&action=default&toggleactive=<?php echo $row['id']; if($id_categorie != '')echo'&amp;id_categorie='.$id_categorie.'';?>"><img src="<?php echo $img ?>" /></a>
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
                	<td colspan="7">&nbsp;</td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>