<?php 
/* Name: c_deletenode.php
 * Purpose: Controller for deleting a node

 */

//Basic setup
require_once '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();

// All of the items below have not yet been implemented
//
// 1. Check if user is logged in and is administrator
// 2. Check if he is administrator of the network and is allowed to remove nodes
// 3. Make sure that this user is only able to remove nodes from his own network or networks!
//
// conclusion: big time security hole

// Set up session, get session variables
//
session_start();
$utype    = $_SESSION[ 'user_type' ];
$netid    = $_SESSION[ 'netid'     ];
$net_name = $_SESSION[ 'net_name'  ];
$updated  = $_SESSION[ 'updated'   ];

// Redirect to login page if the user is not an administrator
//
if ( $utype != 'admin' )
{
  header("Location: ../entry/login.php");
}

// Get all needed info
//
$mac         = $_POST[ "mac"      ];
$net_name    = $_POST[ "net_name" ];
$result      = mysql_query("SELECT * FROM network WHERE net_name='$net_name'", $conn);

if ( $resArray = mysql_fetch_array( $result, MYSQL_ASSOC ) )
{
    $netid = $resArray[ 'id' ];
}

//Change the DB flag
mysql_query("DELETE FROM node WHERE mac='$mac' AND netid='$netid'", $conn);

$query="SELECT * FROM client WHERE nodeid='".$_POST["id"]."'";
$client_result = mysql_query($query, $conn);
if(mysql_num_rows($client_result)>0) {

	//Borra cada registro
	while($client = mysql_fetch_assoc($client_result)) {

    	$query1 = "DELETE FROM client WHERE id = ". $client['id'];
    	$delete_result = mysql_query($query1, $conn);

	}

}

?>
