 <?php
require_once('includes/fr.php');
require_once('includes/config.php');
require_once('includes/Sentry.php');
require_once('includes/DbConnector.php');

$connector = new DbConnector();

$date_debut = date("Y-m-d");
$date_fin = date('Y-m-d"', strtotime("+1 week"));

$i=1;
$query = "SELECT C.id
			FROM commandes C
		   WHERE C.id_statut=2
		     AND C.date>='".$date_debut." 0:0:0'
			 AND C.date<='".$date_fin." 23:59:59'";
$result = $connector->query($query);
echo $connector->getNumRows($result);
?>