<?php 
/* Name: c_node_info.php
 * Purpose: controller for node info page


 */

//Setup session and db connection
session_start();
require_once '../lib/connectDB.php';
setTable("node");

//Sanitize input info
include '../lib/toolbox.php';
sanitizeAll();

if(isset($_POST['name'])){$_POST['name'] = str_replace(" ","*",$_POST['name']);}

//Generate string of values to update in dashboard
foreach ($node_fields as $f){
	//if the originating form didn't sent a value for this field, skip it
	if(!isset($_POST[$f])){continue;}
	
	//add the field to the result array: "field = 'value'"
	$temp=$f." = "."'".$_POST[$f]."'";
	$result[] = $temp;
}

//Turn result array into result string
$result = implode(", ",$result);
$result .=", last_node_update=''";
if($_POST['alerts'] == '1' || $_POST['alerts'] == 'on'){$result .= ", alerts = '1'";} else {$result .= ", alerts = '0'";}
if($_POST['log_users'] == '1' || $_POST['log_users'] == 'on'){$result .= ", log_users = '1'";} else {$result .= ", log_users = '0'";}

if ($_POST["approval_status"] == "X") {
  $query = "DELETE FROM node WHERE mac='" . $_POST["mac"] . "'";
} else {

  //Create query string using result string
  $query = "UPDATE ".$dbTable." SET ".$result." WHERE mac='" . $_POST["mac"] . "'";

}

  //Execute query
  mysql_query($query, $conn) or die("Error executing query: ".mysql_error($conn));
  mysql_close($conn);

  //If we got here, everything went ok
  $_SESSION["updated"] = 'true';
  //echo '<HTML><HEAD><META HTTP-EQUIV="refresh" CONTENT="0; URL=nodes_info.php"></HEAD></HTML>';
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /status/view.php");
        exit();

?>
