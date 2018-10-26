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


if($utype!='admin') {
		header("Location: ../entry/select.php");
}

//Get other node vars
//$mac = $_POST['mac'];
//$net_name = $_POST['net_name'];

$errormac="";
$macs = explode(",", $_POST['mac']);
foreach ($macs as &$value) {

//die($value);
	//Get the network id
	$result = mysql_query("SELECT id FROM network WHERE id='$netid'", $conn);
	if($resArray = mysql_fetch_array($result, MYSQL_ASSOC)){
		//	$_POST['netid'] = $resArray['id'];
	} else {
		header("HTTP/1.1 400 Bad Request");
		die("Error: Network does not exist! ");
	}

	$i=0;
	$macs = explode(",", $_POST['mac']);
	foreach ($macs as &$value) {

		++$i;
		$name = $_POST['name']."-".$i;
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'] + (0.0001 * $i);
		$owner_email = $_POST['email'];
		$owner_address = $_POST['address'];


	    $mac=$value;
		//Make update
		if(is_mac($mac)){
    		//Find the node
			$result = mysql_query('SELECT id FROM node WHERE mac="'.$mac.'"', $conn);
			if(mysql_num_rows($result)>0){
				$errormac .="Error: Tried to add a existent mac: ".$mac."<br>";
			}

			//comprueba que la mac está permitida para alta
			if (!file_exists("limit_add_mac_README.txt"))	{$limit_mac=0;} else {$limit_mac=1;}
			if(mysql_num_rows(mysql_query("SELECT * FROM mac WHERE mac='".$mac."'", $conn))<1 && $limit_mac==1){
				//mac no registrada en tabla mac
				header("HTTP/1.1 400 Bad Request");
        		die("\n\nERROR!\n\nThis Mac ".$mac." is not authorized\n\nContact our support service: support@awdmesh.com.");

			} else {
				//add the node
        		$query = "INSERT INTO node (mac,netid,name,latitude,longitude,approval_status,owner_email,owner_address) ";
        		$query .= "VALUES('".$mac."','".$netid."','".str_replace(" ","*",$name)."','".$latitude."','".$longitude."','A','".$owner_email."','".$owner_address."');";
        		mysql_query($query, $conn) or $errormac .="ERROR in sql: ".mysql_error($conn)."<br>";
			}


		} else {
			$errormac .="ERROR Mac not valid: ".$mac."<br>";
		}
	}
}
//	mysql_close($conn);
//return $errormac;
if($errormac!="") {
	echo "<br>".$errormac;
	echo "<br><br><br>&nbsp; &nbsp; &nbsp; &nbsp; <a href='addnodeaddress.php'>Back</a>";
} else {

	header("HTTP/1.1 307 Temporary Redirect");
	header("Location: addnode.php#map");
	exit();
}

?>
