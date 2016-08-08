<?php 
include("includes/top_includes.php");
$sql = "SELECT * FROM villes ORDER BY order_position";
$result = $connector->query($sql);
?>
Nous livrons dans les villes suivantes :<br /><br /> 
<ul><?php
  while($row = $connector->fetchArray($result)){ ?>
    <li><?php echo osql($row['name']) ?></li><?php
  }  ?>
</ul>