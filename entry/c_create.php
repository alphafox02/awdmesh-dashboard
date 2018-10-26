<?php 
/* Name: c_create.php
 * Purpose: process network creation data.

 * 

 */

function genRandomString() {
    $length = mt_rand(6, 10);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

session_start();

//get the toolbox
include '../lib/toolbox.php';
		
//setup database connection
require_once '../lib/connectDB.php';
setTable('network');
sanitizeAll();



//first check that the passwords entered matched
if($_POST["password"]!=$_POST["confirm_pass"]){
    $_SESSION['net_name'] = $_POST['net_name'];
    $_SESSION['email1'] = $_POST['email1'];
    $_SESSION['email2'] = $_POST['email2'];
    $_SESSION['message'] = "E1";

//eader("Refresh: 3 url=create.php");
//nclude "../lib/menu.php";
//nclude "../lib/style.php";
//ie("<div class=error>&#20004;&#27425;&#23494;&#30721;&#19981;&#31526;&#12290; (Error 3182) The passwords you entered did not match!</div>");
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=create.php\">";
} else {
	
  //make sure there is not a duplicate network
  $query = 'SELECT * FROM network WHERE net_name="'.$_POST['net_name'].'"';
  $result = mysql_query($query, $conn);
  if(mysql_num_rows($result)>0){
    $_SESSION['net_name'] = $_POST['net_name'];
    $_SESSION['email1'] = $_POST['email1'];
    $_SESSION['email2'] = $_POST['email2'];
    $_SESSION['message'] = "E2";

        //header("Refresh: 3 url=create.php");
        //include "../lib/menu.php";
        //include "../lib/style.php";
        //die("<div class=error>&#32593;&#32476;&#21517;&#24050;&#34987;&#21344;&#29992;&#12290; (Error 3183) Network name is taken, please enter a new network name.</div>");
        echo "<meta http-equiv=\"Refresh\" content=\"0;url=create.php\">";
  } else {

	/*if($_SESSION['masterlogin']!='9') {
  		$query = 'SELECT * FROM network WHERE email1="'.$_POST['email1'].'"';
  		$result = mysql_query($query, $conn);
  		if(mysql_num_rows($result)<1){
    		$_SESSION['masterlogin']="9";
    		$_SESSION['masternetid']=$_SESSION['netid'];
    		$_POST['master_login']="9";
    		$_POST['master_netid']=$_SESSION['netid'];
		}
	}*/
  	
	//if ( $_SESSION[ 'masterlogin' ] == 9 ) 
	$bMasterLogin = $_SESSION[ 'bMasterLogin' ];
  	if ( true == $bMasterLogin ) 
	{
		// We were logged in into the system under a master login account
		// now we created a new network
		// master_login should be 1, indicating it is not a master login
		// master_netid should become the netid of the master_login network
		//
    	$_POST[ 'master_login' ] = 1;
    	$_POST[ 'master_netid' ] = $_SESSION[ 'masternetid' ];
    	$bCreateMasterNetwork    = false;
	}
	else 
	{
		// We are just creating a new network
		// master_netid needs to become the new-id
		//
    	$_POST[ 'master_login' ] = 9;
    	$_POST[ 'master_netid' ] = NULL;
    	$bCreateMasterNetwork    = true;
	}
  	
    //the fields we want to insert into the database
    $fields 		     = array( "net_name", "password", "email1", "net_location", "email2", "master_login", "master_netid" );

    //hash the input password
    $pass 			     = $_POST[ "password" ];
    $_POST[ "password" ] = md5( $_POST[ "password" ] );

    //get the values corresponding to the above fields from the user input 
    $values = getValuesFromPOST( $fields );
    
    $fields[] = "node_pwd";
    $values[] = genRandomString();
		
    //insert the values into the database
    insert( $dbTable, $fields, $values );
    
    if ( true == $bCreateMasterNetwork )
    {
    	// update the master_netid value to just created netid 
    	$query = 'SELECT ID FROM network WHERE net_name="'.$_POST[ 'net_name' ].'"';
    	$res = mysql_query( $query, $conn );
    	if ( $resArray = mysql_fetch_assoc( $res ) )
    	{
    		$id = $resArray[ "ID" ];
    		if ( true == is_numeric( $id ) )
    		{
	    	    $query = "update network set master_netid=$id where id=$id";
    		    $res   = mysql_query( $query, $conn);
    		}
		}
    }

    // if we're here, everything went as planned. tell the user that and log them in.
    //
    $query    = 'SELECT * FROM network WHERE net_name="'.$_POST[ 'net_name' ].'"';
    $res      = mysql_query( $query, $conn );
    $resArray = mysql_fetch_assoc( $res );
    
    $_SESSION[ 'netid'       ] = $resArray[ 'id'           ];
    $_SESSION[ 'net_name'    ] = $resArray[ 'net_name'     ];
    
    // don't set these below: we need the original values from the initial login
    // because the system uses it to detect the master_login and the master_netid
    //
    //$_SESSION[ 'masterlogin' ] = $resArray[ 'master_login' ];
    //$_SESSION[ 'masternetid' ] = $resArray[ 'master_netid' ];
    
    $_SESSION[ 'user_type'   ] = 'admin';
    $_SESSION[ "created"     ] = 'true';
    
//   header("Refresh: 3 url=../net_settings/edit.php");

//   include "../lib/menu.php";
//   include "../lib/style.php";
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=../net_settings/edit.php\">";
  }
}
?>
