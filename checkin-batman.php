<?php 
/* Name: checkin-batman.php
 * Purpose: checking script for nodes.

 */

//Establish database connection
require_once 'lib/connectDB.php';

// Create an array to store the param received from an AP node
// Each element has to be a value in the existing SQL database.
// Add "top_users". format: +<total Kb, down Kb, up Kb, MAC, machine name>
$keys = array('ip',
 'mac',
 'robin',
 'batman',
 'memfree',
 'ssid',
 'pssid',
 'users',
 'kbup',
 'kbdown',
 'gateway',
 'gw-qual',
 'NTR',
 'routes',
 'hops',
 'RTT',
 'nbs',
 'rank',
 'nodes',
 'rssi',
 'uptime',
 'top_users',
 'RR');

// use the value of $keys array as hash key. Initialize a $robin_vars hash.
// PHP 4
foreach($keys as $get_names)  {$robin_vars[$get_names] = '';}

// PHP 5
// $robin_vars = array_fill_keys($keys, ''); 


// Move the ROBIN variables from $_GET to variables of the same names
// If a ROBIN variable is not present in the $_GET, the field is kept empty.
foreach($robin_vars as $key => $value) { $robin_vars[$key] = $_GET[$key]; }

//----- debug -----
//die("MAC=".$robin_vars["mac"]);
//----- end debug -----

//We must at least have received a MAC address; fail if we don't.
if ($robin_vars["mac"] == '') die("No MAC address.");

// Load data from the "node" TABLE for calculations based histories
// While we're at it, get the memlow, usershi, and netid variables to use later.
// Use the MAC to read the DB and get the network ID 'netid' in order to
// retrieve the network setting
// If mySQL don't have a DB row for this MAC address, create one.
$query = sprintf("SELECT memlow, usershi, netid, name, id, rssi_hist, ntr_hist, usr_hist, last_node_update FROM node WHERE mac='%s'",$robin_vars["mac"]);
$result = mysql_query($query, $conn);

// check if the node has been added to the network
if (mysql_num_rows($result) == 0) {
    //$query = sprintf("INSERT INTO node (mac) VALUES ('%s')",$robin_vars["mac"]);
    //mysql_query($query, $conn);
    die("The node has yet to be added a network. MAC=". $robin_vars["mac"]);
}
$row = mysql_fetch_array($result);

// lowest level of memory reported by the node in the given period
$memlow = $row['memlow'];

// highest number of users reported by the node in the given period
$usershi = $row['usershi'];

$netid = $row['netid'];
$node_name = $row['name'];
$last_node_update = $row['last_node_update'];

//Prepare the update string with the ROBIN vars
$update = "UPDATE node SET ";
foreach($robin_vars as $key => $value) $update .= "`" . $key . "`='" . $value . "', ";

//Add the time and derivative ROBIN vars to the update string
// format: `<field>`='<value>',`<field>`='<value>',
// CURRENT_TIMESTAMP is a nondeterministic function printing date and time
// $update .= "`time`=CURRENT_TIMESTAMP, ";
$update .= "`ip_public`='".$_SERVER['REMOTE_ADDR']."', ";
$update .= "`time`='".date("Y-m-d H:i:s")."', ";

//Registro estadisticas clientes conectados en tabla client -- Valentin
if ($robin_vars["users"]>0) {
    $query = "SELECT * FROM node WHERE mac='".$robin_vars['mac']."'";
    $node_result = mysql_query($query, $conn);
    if(mysql_num_rows($node_result)>0) {
        $node_last = mysql_fetch_assoc($node_result);
        if($node_last["log_users"] == 1){
            //$clients="+30914,28780,2134,00:1F:3A:B9:93:84,Walnut+15848,15279,569,00:22:15:A0:8E:77,cherry";
            //Format: +<total KB, down-KB, up-KB, client-MAC, PC-name>
         //   $order   = array("\r\n", "\n", "\r", chr(13), chr(10), chr(32), " ");
            $clientslastcheckin = str_replace(chr(32), "+", $node_last["top_users"]);
        //    $clientslastcheckin = str_replace(ord(13), "+", $node_last["top_users"]);
        //    $clientslastcheckin = str_replace(chr(13), "+", $node_last["top_users"]);
            $clientslastcheckin = explode("+", "+".$clientslastcheckin);
            $clientsnow = str_replace(chr(32), "+", $robin_vars["top_users"]);
        //    $clientsnow = str_replace(ord(13), "+", $robin_vars["top_users"]);
        //    $clientsnow = str_replace(chr(13), "+", $robin_vars["top_users"]);
            $clientsnow = explode("+", "+".$clientsnow);
                foreach ($clientsnow as &$value) {
                    $datanow = explode(",", $value);
                    foreach ($clientslastcheckin as &$valuelast) {
                        $datalastcheckin = explode(",", $valuelast);
                        if ($datanow[3] == $datalastcheckin[3]) { //misma mac
                            if ($datanow[1]>$datalastcheckin[1] || $datanow[2]>$datalastcheckin[2]) {
                              // $pos  = strpos("HP", $node_last["top_users"]);
                              //$datanow[3] = $clientslastcheckin;
                              //  $datanow[4] = chr(substr($node_last["top_users"],0,1));
                                $sql = "INSERT INTO client (netid, c_mac, c_name, c_time, ckbd_hist, ckbu_hist, nodeid) VALUES('".$netid."', '".$datanow[3]."', '".$datanow[4]."', '".date("Y-m-d H:i:s")."', '".((double)$datanow[1]-$datalastcheckin[1])."', '".((double)$datanow[2]-$datalastcheckin[2])."', '".$node_last['id']."')";
                                mysql_query($sql, $conn);
                            }
                        }
                    }
                }
        }
    }
}
//$robin_vars["top_users"] = str_replace($order, "+", $robin_vars["top_users"]);
//Registro estadisticas RSSI y NTR -- Valentin
//Añade historico RSSI en rssi_hist serializado en 288 slots (5min. x 12 cada hora x 24 = 288 dia)
//Añade historico NTR en ntr_hist serializado en 288 slots (5min. x 12 cada hora x 24 = 288 dia)
//Guarda hora del ckeckin en usr_hist serializado en 288 slots
$slot = intval(date('H', time ()) * 12 + date ('i', time ()) / 5);
$rssi_hist = unserialize($row['rssi_hist']);
$ntr_hist = unserialize($row['ntr_hist']);
$usr_hist = unserialize($row['usr_hist']);
$rssi_hist[$slot] = $robin_vars["gw-qual"];
$ntr_hist[$slot] = $robin_vars["NTR"];
$usr_hist[$slot] = date("Y-m-d H:i:s");
$update .= "`rssi_hist`='".serialize($rssi_hist)."', ";
$update .= "`ntr_hist`='".serialize($ntr_hist)."', ";
$update .= "`usr_hist`='".serialize($usr_hist)."', ";

if ($memlow == '' || $memlow > $robin_vars["memfree"]) $update .= "`memlow`='" . $robin_vars['memfree'] . "', ";
if ($usershi < $robin_vars["users"]) $update .= "`usershi`='" . $robin_vars['users'] . "', ";
//If $gateway is in $nbs array, the gateway is a neighbor (i.e. another node), which means this node itself is not a gateway. (For actual gateway nodes, the 'gateway' field will show the IP address of the wired router.)
//if (in_array($robin_vars["gateway"],split(";",$robin_vars["nbs"]))) $update .= "`gateway_bit`=0, "; 
//if (in_array($robin_vars["gateway"],explode(";",$robin_vars["nbs"]))) $update .= "`gateway_bit`=0, ";
if (substr($robin_vars["gateway"],0,2)=="5.") $update .= "`gateway_bit`=0, ";
else $update .= "`gateway_bit`=1, ";    

//Cap off the update string and make the update the DB record
$update = rtrim($update, ", ");
$update .= sprintf(" WHERE mac='%s'",$robin_vars["mac"]);
mysql_query($update, $conn);



//----- debug -----
//die("Finish writing to node. string=".$update."; network=".$conn);
//----- end debug -----

// Load data from the "network" TABLE to get the network settings variables
$query = sprintf("SELECT * FROM network WHERE id='%s'",$netid);
$result = mysql_query($query, $conn);
if (mysql_num_rows($result) == 0) die("No such network");
$row = mysql_fetch_array($result);
// define a list keys to retrieve the DB's fields
$fields = array("ap1_essid","ap1_key","ap2_essid","ap2_key","ap1_isolate",
  "ap2_isolate","ap2_hide","ap2_enable","node_pwd","download_limit","upload_limit",
  "throttling_enable","lan_block","splash_redirect_url","splash_idle_timeout",
  "splash_force_timeout","test_firmware_enable","ssl_enable","olsr_enable",
  "use_node","frz_version","strict_mesh","access_disable_list","cp_handler","bypass_list",
  "spl_logo", "radius_svr_1","radius_svr_2","radius_secret","radius_nasid",
  "uam_server","uam_secret","uam_url","uam_domain",
  "custm_sh_url","custm_sh_on","stand_alone","checkin_period","country_code",
  "access_control_list","spl_page","spl_gwname","dashboard_url","radio_channel","radio_channel2","radio_channel9","splash_enable",
  "display_name", "checker_name", "net_name","reboot_freq","reboot_freq_hour","upgrade_f","upgrade_t","SMTP",
  "DNS1","DNS2","last_dash_update","transparent_bridge", "transparent_bridge_vlan", 'wired_clients');

// pass the DB query result to individual variables as named by $fields
foreach ($fields as $field) $$field = $row[$field];

if(strtotime($last_dash_update)>strtotime($last_node_update) || $robin_vars["RR"]=="1") {  ////// NO ZERO LENGTH RESPONSE //////
// Register last time full response to node
$query = "UPDATE node SET last_node_update='".$last_dash_update."' WHERE mac='".$robin_vars["mac"]."'";
mysql_query($query, $conn) or die("Error executing query: ".mysql_error($conn));

// Load Splash Page Data from nodeinc file in user folder
$splashincludefile = "users_splash/{$row['net_name']}/nodeinc.txt";
if (file_exists($splashincludefile))
{
	$fh = fopen($splashincludefile,"r");
	$splashinclude = fread($fh,filesize($splashincludefile));
	fclose($fh);
}


//Create any other special strings needed for the response

// check "strict_mesh". Load the nodes list if the option is set
$node_count=0;
$node_list="";
//if ($strict_mesh == 1) {
  // load the list of nodes in the network
  $result = mysql_query('SELECT * FROM node WHERE netid="'.$netid.'"', $conn);
  if(mysql_num_rows($result) > 0) {
    // $resArray = mysql_fetch_assoc($result);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      if ($row["ip"])  {
        if ($row["gateway_bit"]==1)  {
          // gateway
          $node_list .= "G ". $row["ip"] ." ". $row["name"]." ".$row["mac"]."\n";
          $node_count ++;
        } else {
          // repeater
          $node_list .= "R ". $row["ip"] ." ". $row["name"]." ".$row["mac"]."\n";
          $node_count ++;
        }
      }
    }
  }
//}

$set_strict_list = 0;
if ($node_count > 0) {
  $set_strict_list = 1;
}

// Clear the RedirectURL field if the value is not presented.
if ($splash_enable == 1) {
  if (strlen($splash_redirect_url)>0) $splash_redirect_url_string = "RedirectURL " . $splash_redirect_url;
  else $splash_redirect_url_string = "";
} else {
  $splash_redirect_url_string = "";
}

// Clear the blacklist field if the list is not present
// the blacklist is a comma separated string (single line)
if (strlen($access_disable_list)>0) $blacklist_string = "BlockedMACList  " . $access_disable_list;
else $blacklist_string = "";


if (strlen($bypass_list)>0) $bypass_string = "TrustedMACList  " . $bypass_list;
else $bypass_string = "";

if ($reboot_freq != "never") $reboot = $reboot_freq."@".$reboot_freq_hour;
else $reboot = "never";

if ($test_firmware_enable == 1) $base = "trunk";
else $base = "beta";
if ($splash_enable == 1) $authenticate_immediately = 0; 
else $authenticate_immediately = 1;
if ($olsr_enable == 1) $route_protocol = 2; 
else $route_protocol = 1;
if ( strlen($ap1_key) >= 8) $ap_psk = 1; 
else $ap_psk = 0;

// set a default value for gateway name
if ($spl_gwname == '') {
  $spl_gwname = "AWD Mesh Splash";
}

// run custom.sh script
// toggle: custom.sh script
// 	0=dont run custom.sh script
//	1=download and run custom.sh script
$custom_sc = $custm_sh_on;

// broadcast private AP2 network
// toggle: 
//   0=broadcast private AP SSID
//   1=hide private AP
$hide_ap2_ssid = 0;
if ($ap2_enable == 1) {
  if ($ap2_hide == '') {
  } else {
    // allow to set 'ap2_hide' only if ap2 is enabled
    $hide_ap2_ssid = $ap2_hide;
  }
}

//Output response to node. It comes from Antonio's sample, with some bug fixes.
echo <<< RESPONSE
#@#config node
general.net $net_name
#@#config management
enable.base $base
enable.rootpwd $node_pwd
enable.defessid 0
enable.https $ssl_enable
enable.custom_update $custom_sc
enable.freeze_version $frz_version
enable.sm $strict_mesh
enable.ap2hidden $hide_ap2_ssid
enable.stand_alone_mode $stand_alone
enable.update_rate $checkin_period
enable.country_code $country_code
enable.gmt_offset 0
enable.force_reboot $reboot
enable.upgrade_f $upgrade_f
enable.upgrade_t $upgrade_t
enable.transparent_bridge $transparent_bridge
enable.transparent_bridge_vlan $transparent_bridge_vlan
enable.wired_clients $wired_clients
#@#config mesh
ap.up 1
Myap.up $ap2_enable
ap.psk $ap_psk
#@#config ra_switch
main.which_handler $route_protocol
#@#config radio
channel.alternate $radio_channel
channel.alternate9 $radio_channel9
channel.alternate2 $radio_channel2
#@#config wireless
private.ssid $ap2_essid
private.key $ap2_key

RESPONSE;
if ($use_node) {
 echo"public.ssid $node_name\n";
} else {
 echo"public.ssid $ap1_essid\n";
}
if ($ap_psk) echo "public.key $ap1_key\n";


if ($set_strict_list) {
echo <<< RESPONSE
#@#config nodes
$node_list
RESPONSE;
}

//if ((strlen($dashboard_url) > 0) || ($custm_sh_on == 1) || (strlen($checker_name) > 0)) {
echo"#@#config general\n";
echo"services.name_srv1 $DNS1\n";
echo"services.name_srv2 $DNS2\n";
 if (strlen($dashboard_url) > 0) {
   echo"services.updt_srv $dashboard_url\n";
 }
 if (strlen($checker_name) > 0) {
   echo"services.checker $checker_name\n";
 }
 if ($custm_sh_on == 1) {
   echo"services.cstm_srv $custm_sh_url\n";
 }
//}

if(strlen($SMTP) > 0) {$rdir_SMTP=1;} else {$rdir_SMTP=0;}

echo <<< RESPONSE
#@#config iprules
filter.SMTP_rdir $rdir_SMTP
filter.SMTP_dest $SMTP
filter.AP1_bridge $ap1_isolate
filter.AP2_bridge $ap2_isolate

RESPONSE;
if (($lan_block == 0) || ($lan_block == 1)) {
   echo"filter.LAN_BLOCK $lan_block\n";
   echo"filter.LAN_BLOCK2 $lan_block\n";
} else if ($lan_block == 2) {
   echo"filter.LAN_BLOCK 1\n";
   echo"filter.LAN_BLOCK2 101\n";
} else {
   echo"filter.LAN_BLOCK 1\n";
   echo"filter.LAN_BLOCK2 102\n";
}

echo <<< RESPONSE
#@#config secondary
backend.update 0
backend.server
backend.ssl 0

RESPONSE;

if (strlen(trim($access_control_list))>0) {
echo <<< RESPONSE
#@#config acl
mac.mode_ap1 1

RESPONSE;
} else {
echo <<< RESPONSE
#@#config acl
mac.mode_ap1 0

RESPONSE;
}


// Set ROBIN handler to 5 in both cases of CoovaAAA and other Chilli
// Currently, either 1 or 6 are forwarded by the user setting
echo "#@#config cp_switch\n";
if (($cp_handler == 5) || ($cp_handler == 6)) {
 echo"main.which_handler 5\n";
} else {
 echo"main.which_handler $cp_handler\n";
}

if (strlen(trim($access_control_list))>0) {
echo <<< RESPONSE
#@#config maclist1
$access_control_list

RESPONSE;
}

if ($cp_handler == 1) {
echo <<< RESPONSE
#@#config nodog
GatewayName $spl_gwname
$splash_redirect_url_string
ClientIdleTimeout $splash_idle_timeout
ClientForceTimeout $splash_force_timeout
AuthenticateImmediately $authenticate_immediately
TrafficControl $throttling_enable
DownloadLimit $download_limit
UploadLimit $upload_limit
MaxClients 200
$blacklist_string
$bypass_string




RESPONSE;
}
if (($cp_handler == 5) || ($cp_handler == 6)) {
echo <<< RESPONSE
#@#config chilli
agent.radiusserver1 $radius_svr_1
agent.radiusserver2 $radius_svr_2
agent.uamserver $uam_server
agent.uamurl $uam_url
agent.uamsecret $uam_secret
agent.radiussecret $radius_secret
agent.radiusnasid $radius_nasid
agent.uamurlextras
agent.uamdomain $uam_domain
agent.custom1
agent.custom2
agent.custom3

RESPONSE;
}
if ($cp_handler == 5) {
echo <<< RESPONSE
agent.service coova_aaa

RESPONSE;
}
if ($cp_handler == 6) {
echo <<< RESPONSE
agent.service otherchilli

RESPONSE;
}

if ($splash_enable == 1) {
   if (strlen(trim($spl_page))>0) {
      echo "#@#config splash-HTML\n";
      echo "page $spl_page\n";
   } else {
      echo "#@#config splash-HTML\n";
      echo "$splashinclude\n";
      $splash = "users_splash/" . $net_name . "/splash.txt";
      if (file_exists($splash)) {
         $segundos = filemtime($splash);
         echo "#bogus2 $segundos\n";
         }
   }
}


} else {  ////// END NO ZERO LENGTH RESPONSE //////
echo '';
}
?>
