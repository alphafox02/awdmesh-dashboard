<?php 
/* Name: c_select.php
 * Purpose: process input from select page form

 */
session_start();

require_once '../lib/connectDB.php';
setTable('network');

include '../lib/toolbox.php';
sanitizeAll();

//get the network id
$net_name = $_GET["net_name"];
$query = "SELECT id FROM ".$dbTable." WHERE net_name='".$net_name."'";
$result = mysql_query($query, $conn);

//if we have rows, we have a matching network
if(mysql_num_rows($result)>=1){
  $resArray = mysql_fetch_array($result, MYSQL_ASSOC);
  $_SESSION['netid'] = $resArray['id'];
  $_SESSION['net_name'] = $net_name; // $resArray['net_name'];
  //set the user type to 'user'
  $_SESSION['user_type'] = 'user';
  $_SESSION['error'] = false;

  //header('location: ../status/map.php');
  // Goto view.php (not Google Map) 
 
  //echo "<meta http-equiv=\"Refresh\" content=\"0;url=../status/view.php\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../status/view.php");
        exit();
}

//otherwise there was no matching network
else {
	$_SESSION['error'] = true;
	unset($_SESSION['user_type']);
  //header('location: select.php');
  //echo "<meta http-equiv=\"Refresh\" content=\"0;url=select.php?cls=0\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: select.php?cls=0");
        exit();
}

?>
