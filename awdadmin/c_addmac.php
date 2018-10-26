<?php 
/* Name: c_addnode.php
 * Purpose: controller for addnodes.php


 */

require_once '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();

//Get other node vars
$mac = $_POST['mac'];
$mac = str_replace(" ", ",", $mac);
$mac = str_replace(chr(32), ",", $mac);

$macs = explode(",", $mac);

foreach ($macs as &$value) {
	$query = "INSERT INTO mac VALUES ('".$value."');";
	mysql_query($query, $conn) or die("Error: No se puede dar de alta la mac/s. ".mysql_error($conn));
}

mysql_close($conn);




?>
