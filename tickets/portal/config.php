<?php
session_start();
$dbHost = "localhost"; // the mySQL server machine relative to apache
$dbUser = "db80716_dash"; // user name on the mySQL db
$dbPass = "o3tKccVfJ";	//be sure to change this!
$dbName = "meshcontroller"; // database name

$link=$db_con=mysql_connect($dbHost,$dbUser,$dbPass);


if (!$link) {
    die('-2: ' . mysql_error());
}
$connection_string=mysql_select_db($dbName);
// Connection
mysql_connect($dbHost,$dbUser,$dbPass);
mysql_select_db($dbName);
?>
