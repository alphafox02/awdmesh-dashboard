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

//Determine node approval status based on type of logged in user
switch($utype){
	case 'admin':
		$_POST['approval_status'] = 'A';
		break;
	case 'user':
		$_POST['approval_status'] = 'P';
		break;
	default:
		header("Location: ../entry/select.php");
		break;
}

//Get other node vars
$mac = $_POST['mac'];
//$net_name = $_POST['net_name'];

//Get the network id
//$result = mysql_query("SELECT * FROM network WHERE net_name='$net_name'", $conn);
//if($resArray = mysql_fetch_array($result, MYSQL_ASSOC)){
//	$_POST['netid'] = $resArray['id'];
//	$netid = $_POST['netid'];
//}
//else {
//	header("HTTP/1.1 400 Bad Request");
//	die("Error: Network does not exist! ");
//}

//Find the node
$result = mysql_query('SELECT * FROM node WHERE mac="'.$mac.'" AND netid="'.$netid.'"', $conn);
if(mysql_num_rows($result)==0 && $_POST['form_name']!="addNode"){
	die("Error: Tried to update a non-existent node! ");
}
$resArray = mysql_fetch_assoc($result);

//Get all the variables we need from POST
switch($_POST['form_name']){
	case 'addNode':
		$name = $_POST['name'];
		$description = $_POST['description'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$approval_status = $_POST['approval_status'];
		$owner_name = $_POST['owner_name'];
		$owner_email = $_POST['owner_email'];
		$owner_phone = $_POST['owner_phone'];
		$owner_address = $_POST['owner_address'];
		break;
	case 'basicEdit':
		$name = $_POST['name'];
		$description = $_POST['description'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$approval_status = $resArray['approval_status'];
		$owner_name = $resArray['owner_name'];
		$owner_email = $resArray['owner_email'];
		$owner_phone = $resArray['owner_phone'];
		$owner_address = $resArray['owner_address'];
		break;
	case 'ownerEdit':
     	$name = $resArray['name'];
		$description = $resArray['description'];
		$latitude = $resArray['latitude'];
		$longitude = $resArray['longitude'];
		$approval_status = $resArray['approval_status'];
		$owner_name = $_POST['owner_name'];
		$owner_email = $_POST['owner_email'];
		$owner_phone = $_POST['owner_phone'];
		$owner_address = $_POST['owner_address'];
		break;
	default:
		$name = $resArray['name'];
		$description = $resArray['description'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$approval_status = $resArray['approval_status'];
		$owner_name = $resArray['owner_name'];
		$owner_email = $resArray['owner_email'];
		$owner_phone = $resArray['owner_phone'];
		$owner_address = $resArray['owner_address'];
		break;
};

$name = str_replace(" ","*",$name);
//Make update
if(is_mac($mac)){
    //checks if the mac already exist
    if(mysql_num_rows(mysql_query("SELECT * FROM node WHERE mac='".$mac."'", $conn))>0){
        if($_POST['form_name']=="addNode"){
            header("HTTP/1.1 400 Bad Request");
	        die("   Error: This mac ".$mac." already exist.");
        }
    }

    if(mysql_num_rows(mysql_query("SELECT * FROM node WHERE mac='$mac' AND netid='$netid'", $conn))>0){
		//update the existing entry
        $query = "UPDATE node SET name='$name', description='$description',latitude='$latitude',longitude='$longitude',approval_status='$approval_status',owner_name='$owner_name',owner_email='$owner_email',owner_phone='$owner_phone',owner_address='$owner_address' WHERE mac='$mac' AND netid='$netid'";
        mysql_query($query, $conn);
	} else {

	//comprueba que la mac está permitida para alta
		if (!file_exists("limit_add_mac_README.txt"))	{$limit_mac=0;} else {$limit_mac=1;}
		if(mysql_num_rows(mysql_query("SELECT * FROM mac WHERE mac='".$mac."'", $conn))<1 && $limit_mac==1){
			//mac no registrada en tabla mac
			header("HTTP/1.1 400 Bad Request");
        	die("The CloudController only supports AWD equipment. Our records show that ".$mac." is not a supported MAC address.\n\nIf you feel you have reached this message in error, please email a copy of this warning to support@awdmesh.com.");

		} else { 
			 //add the node
			$fields = array('mac','netid','name','description','latitude','longitude','approval_status','owner_name','owner_email','owner_phone','owner_address');
            if(isset($_POST['name'])){$_POST['name'] = str_replace(" ","*",$_POST['name']);}
			$values = getValuesFromPOST($fields);
        	$query = "INSERT INTO node";
	    	$query .= " (".implode(",",$fields).") ";
        	$query .= "VALUES('".implode("','",$values)."')";
        	mysql_query($query, $conn) or die("Error: Unable to register the node. ".mysql_error($conn));
                     // Manda una respuesta completa a todos los nodos para que reconozcan el nuevo nodo por si hay strict mesh activado
			$query = "UPDATE network SET last_dash_update = '".date("Y-m-d H:i:s")."' WHERE id='".$netid."'";
              mysql_query($query, $conn) or die("Error: ".mysql_error($conn));
		}
	}
	mysql_close($conn);
}

else
//	header('HTTP/1.1 400 Bad Request');
?>
