<?php 
/* Name: c_edit.php
 * Purpose: controller for network edit page. processes input from edit.php.

 */


//Start session, do includes
session_start();
include '../lib/toolbox.php';

//setup db connection
require_once '../lib/connectDB.php';
setTable("network");
sanitizeAll();

//get the network id we're working with
//$id = $_SESSION['netid'];
// jvz/security advisory: make sure the current logged in user is allowed to change anything
//                        to the tranmitted net-id
$id = $_POST[ 'net_id' ];

//echo "Debug: NetID from the session: " . $_SESSION['netid'] . "<br />";

if($_POST['download_limit']<56){$_POST['download_limit'] = 56;}
if($_POST['upload_limit']<56){$_POST['upload_limit'] = 56;}
if($_POST['splash_force_timeout']<30){$_POST['splash_force_timeout'] = 30;}
if($_POST['splash_idle_timeout']<30){$_POST['splash_idle_timeout'] = 30;}

//process all the checkbox-based values
if(!isset($_POST['splash_enable'])){$_POST['splash_enable'] = 0;}
if(!isset($_POST['ap2_hide'])){$_POST['ap2_hide'] = 0;}
if(!isset($_POST['ap2_enable'])){$_POST['ap2_enable'] = 0;}
if(!isset($_POST['transparent_bridge'])){$_POST['transparent_bridge'] = 0;}
if(!isset($_POST['transparent_bridge_vlan'])){$_POST['transparent_bridge_vlan'] = 1;}
if(isset($_POST['transparent_bridge_vlan'])){
  if(!is_numeric($_POST['transparent_bridge_vlan']) || ($_POST['transparent_bridge_vlan'] <= 1) || ($_POST['transparent_bridge_vlan'] >= 4096) )
    $_POST['transparent_bridge_vlan'] = 1;
}


if(!isset($_POST['wired_clients'])){$_POST['wired_clients'] = 0;}
if(!isset($_POST['lan_block'])){$_POST['lan_block'] = 0;}
if(!isset($_POST['ap1_isolate'])){$_POST['ap1_isolate'] = 0;}
if(!isset($_POST['ap2_isolate'])){$_POST['ap2_isolate'] = 0;}
//if(!isset($_POST['migration_enable'])){$_POST['migration_enable'] = 0;}

// Force OLSR=1. BATMAN is not working at this time (Dec 2009)
// To re-enable the setting, use the line below:
//   if(!isset($_POST['olsr_enable'])){$_POST['olsr_enable'] = 0;}
if(!isset($_POST['olsr_enable'])){$_POST['olsr_enable'] = 1;}

if(!isset($_POST['ssl_enable'])){$_POST['ssl_enable'] = 0;}

if(!isset($_POST['use_node'])){$_POST['use_node'] = 0;}
if(!isset($_POST['frz_version'])){$_POST['frz_version'] = 0;}
if(!isset($_POST['strict_mesh'])){$_POST['strict_mesh'] = 0;}
if(!isset($_POST['custm_sh_on'])){$_POST['custm_sh_on'] = 0;}

if(!isset($_POST['stand_alone'])){$_POST['stand_alone'] = 0;}
if(isset($_POST['ap1_essid'])){$_POST['ap1_essid'] = str_replace(" ","*",$_POST['ap1_essid']);}
if(isset($_POST['ap2_essid'])){$_POST['ap2_essid'] = str_replace(" ","*",$_POST['ap2_essid']);}

if(isset($_POST['access_disable_list'])){$_POST['access_disable_list'] = str_replace(" ","",$_POST['access_disable_list']);}
if(isset($_POST['access_disable_list'])){$_POST['access_disable_list'] = str_replace( array("\\n","\\r","\\r\\n"), ",", $_POST['access_disable_list']);}
if(isset($_POST['access_disable_list'])){$_POST['access_disable_list'] = str_replace(",,",",",$_POST['access_disable_list']);}


if(isset($_POST['bypass_list'])){$_POST['bypass_list'] = str_replace(" ","",$_POST['bypass_list']);}
if(isset($_POST['bypass_list'])){$_POST['bypass_list'] = str_replace( array("\\n","\\r","\\r\\n"), ",", $_POST['bypass_list']);}
if(isset($_POST['bypass_list'])){$_POST['bypass_list'] = str_replace(",,",",",$_POST['bypass_list']);}

if(isset($_POST['uam_domain'])){$_POST['uam_domain'] = str_replace(" ","",$_POST['uam_domain']);}

if(isset($_POST['splash_redirect_url'])){
    if ((false === strpos($_POST['splash_redirect_url'], '://')) && $_POST['splash_redirect_url']!= "") {
        $_POST['splash_redirect_url'] = 'http://' . $_POST['splash_redirect_url'];
}
}

//$_POST['display_name'] = $_POST['country_code'];
//$_POST['country_code'] = substr($_POST['country_code'], -1, 3);
//echo  $_POST['country_code'];
//exit();
//generate string of values to update in dashboard
foreach ($network_fields as $f){
	//if the originating form didn't sent a value for this field, skip it
	if(!isset($_POST[$f])){continue;}
	
	//add the field to the result array: "field = 'value'"
	$temp=$f." = "."'".$_POST[$f]."'";
	$result[] = $temp;
}

//turn result array into result string
$result = implode(", ",$result);
$result .= ", checker_name = '".$_POST['checker_name']."'";
if($_POST['download_limit']==6000 && $_POST['upload_limit']==6000) {
    $result .=", throttling_enable='0'";
} else {
    $result .=", throttling_enable='1'";
}
//$country = substr($_POST['country_code'], -3);
$result .= ", last_dash_update = '".date("Y-m-d H:i:s")."'";
//$result .= ", country_code = '" . $country ."'";
//create query string using result string
$query = "UPDATE ".$dbTable." SET ".$result." WHERE id='".$id."'";

// debug display
//echo "debug: " . $query;
// die('0');

//execute query
mysql_query($query, $conn) or die("Error executing query: ".mysql_error($conn));

//if we got here, everything went ok
mysql_close($conn);
$_SESSION["updated"] = 'true';
$_SESSION['message'] = "M2";
//die('0');
        // Redirect
        //echo "<meta http-equiv=\"Refresh\" content=\"0;url=edit.php\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: edit.php");
        exit();

//echo '<HTML><HEAD><META HTTP-EQUIV="refresh" CONTENT="0; URL=edit.php"></HEAD></HTML>';
?>
