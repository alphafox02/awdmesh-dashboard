<?php 
/* Name: c_addnode.php
 * Purpose: controller for addnodes.php


 */

//Set up the session and includes
session_start();
require_once '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();
$netid = $_SESSION['netid'];
$utype = $_SESSION['user_type'];
$_POST['netid'] = $netid;

//Get other node vars
$mac = $_POST['mac'];
$net_name = $_POST['net_name'];

//Get the network id
$result = mysql_query("SELECT * FROM network WHERE net_name='$net_name'", $conn);
if($resArray = mysql_fetch_array($result, MYSQL_ASSOC)){
	$_POST['netid'] = $resArray['id'];
	$netid = $_POST['netid'];
}
else {
	header("HTTP/1.1 400 Bad Request");
	die("Error: There is no network! ");
}

//Find the node
$result = mysql_query('SELECT * FROM node WHERE mac="'.$mac.'" AND netid="'.$netid.'"', $conn);
if(mysql_num_rows($result)==0 && $_POST['form_name']!="addNode"){
	die("Error trying to update a nonexistent node! ");
}
$resArray = mysql_fetch_assoc($result);

//Get all the variables we need from POST
switch($_POST['updates']){
	case 'Cover':
//		$name = $_POST['name'];
		$cover1 = $_POST['cover1'];
  		$cover2 = $_POST['cover2'];
  		$cover3 = $_POST['cover3'];
        //Make update Cover
        if(is_mac($mac)){
	       if(mysql_num_rows(mysql_query("SELECT * FROM node WHERE mac='$mac' AND netid='$netid'", $conn))>0){
            $query = "UPDATE node SET cover1='$cover1', cover2='$cover2', cover3='$cover3' WHERE mac='$mac' AND netid='$netid'";
            mysql_query($query, $conn);
	        }
	        mysql_close($conn);
        } else header('HTTP/1.1 400 Bad Request');
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../status/mapcover.php");
		break;

	case 'Owner':
//     	$name = $resArray['name'];
		$owner_name = $_POST['owner_name'];
		$owner_email = $_POST['owner_email'];
		$owner_phone = $_POST['owner_phone'];
		$owner_address = $_POST['owner_address'];
        //Make update Owner
        if(is_mac($mac)){
	       if(mysql_num_rows(mysql_query("SELECT * FROM node WHERE mac='$mac' AND netid='$netid'", $conn))>0){
            $query = "UPDATE node SET owner_name='$owner_name',owner_email='$owner_email',owner_phone='$owner_phone',owner_address='$owner_address' WHERE mac='$mac' AND netid='$netid'";
            mysql_query($query, $conn);
	        }
	        mysql_close($conn);
        } else header('HTTP/1.1 400 Bad Request');
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../nodes/addnode.php");
		break;
	default:
 		break;
};

exit()
        
?>
