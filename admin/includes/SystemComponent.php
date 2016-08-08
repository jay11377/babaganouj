<?php

class SystemComponent {

var $settings;

function getSettings() {

// Database variables
$settings['dbhost'] = 'localhost';
$settings['dbusername'] = 'root';
$settings['dbpassword'] = '';
$settings['dbname'] = 'babaganouj';


return $settings;

}

}
?>