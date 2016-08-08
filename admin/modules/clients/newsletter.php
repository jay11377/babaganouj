<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$result = $connector->query("SELECT id, nom, prenom, email FROM clients WHERE newsletter=1 ORDER BY nom");
$pagetitle=getLang('NEWSLETTER_LIST')." (".$connector->getNumRows($result).")";

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
                <td><?php showLang('FIRST_NAME') ?></td>
                <td><?php showLang('LAST_NAME') ?></td>
                <td><?php showLang('EMAIL_ADDRESS') ?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		$i=1;
		while ($row_client = $connector->fetchArray($result)){
			$class=($i%2==0)?'even':'odd';
			if($i==1)
				$class.=" first"; ?>
			<tr class="<?php echo $class ?>">
            	<td valign="top"><?php echo ucfirst(strtolower(osql($row_client['prenom']))) ?></td>
				<td valign="top"><?php echo strtoupper(osql($row_client['nom'])) ?></td>
				<td><?php echo osql($row_client['email']) ?></td>
			</tr><?php 
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