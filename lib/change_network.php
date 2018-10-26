<?php
	session_start();
	
	require_once '../lib/connectDB.php';
	include '../lib/toolbox.php';
	sanitizeAll();
	
	$_SESSION[ 'netid'    ] = $_POST[ 'net' ];
	$_SESSION[ 'net_name' ] = "";
	
	// Hack to fix problem with deleting nodes when switched to a subnetwork
	//
	$strQuery  = "SELECT net_name FROM network WHERE id='" . $_SESSION[ 'netid' ] ."'";
	$resResult = mysql_query( $strQuery, $conn );
	
	if ( false !== $resResult && mysql_num_rows( $resResult ) > 0 )
	{
		if ( $aRow = mysql_fetch_assoc( $resResult ) )
		{
			$_SESSION[ 'net_name' ] = $aRow[ "net_name" ];
		}
	}
	
?>
