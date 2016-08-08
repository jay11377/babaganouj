<?php
////////////////////////////////////////////////////////////////////////////////////////
// Class: DbConnector
// Purpose: Connect to a database, MySQL version
///////////////////////////////////////////////////////////////////////////////////////
require_once('SystemComponent.php');

class DbConnector extends SystemComponent {

var $theQuery;
var $link;

//*** Function: DbConnector, Purpose: Connect to the database ***
function DbConnector(){

// Load settings from parent class
$settings = SystemComponent::getSettings();

// Get the main settings from the array we just loaded
$host = $settings['dbhost'];
$db = $settings['dbname'];
$user = $settings['dbusername'];
$pass = $settings['dbpassword'];

// Connect to the database
$this->link = new mysqli($host, $user, $pass, $db);

register_shutdown_function(array(&$this, 'close'));

}

//*** Function: query, Purpose: Execute a database query ***
function query($query) {

$this->theQuery = $query;
return mysqli_query($this->link, $query);

}

//*** Function: getNumRows, Purpose: Return row count, MySQL version ***
function getNumRows($result){
	return mysqli_num_rows($result);
}

//*** Function: fetchArray, Purpose: Get array of query results ***
function fetchArray($result) {

return mysqli_fetch_array($result);

}

//*** Function: close, Purpose: Close the connection ***
function close() {

mysqli_close($this->link);

}


}
?>