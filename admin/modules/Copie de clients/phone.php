<?php
// Create an instance of DbConnector
$connector = new DbConnector();
$result = $connector->query("SELECT C.id, C.nom, C.prenom, A.telephone, COUNT(COM.id) AS nb_commandes
							   FROM clients C
						  LEFT JOIN adresses A ON (A.id_client=C.id AND A.defaut=1)
						  LEFT JOIN commandes COM ON C.id=COM.id_client
							    WHERE A.telephone LIKE '06%'
								AND COM.id_statut=4
						   GROUP BY C.id
						   ORDER BY nb_commandes DESC");
$pagetitle=getLang('PHONE_LIST')." (".$connector->getNumRows($result).")";

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
                <td>&nbsp;</td>
                <td><?php showLang('FIRST_NAME') ?></td>
                <td><?php showLang('LAST_NAME') ?></td>
                <td><?php showLang('PHONE') ?></td>
                <td><?php showLang('TOTAL_ORDERS') ?></td>
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
            	<td valign="top"><?php echo $i ?>.</td>
            	<td valign="top"><?php echo ucfirst(strtolower(osql($row_client['prenom']))) ?></td>
				<td valign="top"><?php echo strtoupper(osql($row_client['nom'])) ?></td>
                <td><?php echo osql($row_client['telephone']) ?></td>
                <td><?php echo $row_client['nb_commandes'] ?></td>
			</tr><?php 
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