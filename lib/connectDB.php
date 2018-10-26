<?php 
/* Name: connectDB.php
 * Purpose: manages database connection.

 * 
 */

//Welcome to CloudController! If you are trying to configure your server, just look at the next section.
//After that, you can ignore the stuff in this file!

//Database configuration options
$dbHost = "localhost"; // the mySQL server machine relative to apache
$dbUser = "root"; // user name on the mySQL db
$dbPass = "awdmesh";	//be sure to change this!
$dbName = "meshcontroller"; // database name

$_SESSION["dashboard"] = "";
$_SESSION["password"] = "awdmesh";

//Create global dbTable variable
$dbTable;

//Create arrays of DB fields
$network_fields = array('id','net_name','display_name','password','email1',
  'email2', 'net_location','ap1_essid',
  'ap1_key','ap2_hide','ap2_enable','ap2_essid','ap2_key','node_pwd','splash_enable',
  'splash_redirect_url','splash_idle_timeout','splash_force_timeout',
  'throttling_enable','download_limit','upload_limit',
  'network_clients','network_bytes','access_control_list','lan_block',
  'ap1_isolate','ap2_isolate', 'radio_channel', 'radio_channel_country','radio_channel9', 'radio_channel2','olsr_enable','ssl_enable',
  'dashboard_url','use_node',
  'custm_sh_url','custm_sh_on','stand_alone',
  'cp_handler','spl_gwname','spl_page','spl_logo','access_disable_list','bypass_list',
  'radius_svr_1','radius_svr_2','radius_secret','radius_nasid','uam_server',
  'uam_secret','uam_url','uam_domain','frz_version','strict_mesh',
  'test_firmware_enable','migration_enable','checker_name','checkin_period','country_code','floor_plan',
  'reboot_freq','reboot_freq_hour','DNS','DNS1','DNS2','min_nodedown','upgrade_f','upgrade_t','SMTP', 'transparent_bridge', 'transparent_bridge_vlan', 'wired_clients');


$node_fields = array('id','netid','name','description','ip','mac','latitude','longitude','gateway',
  'gateway_bit','uptime','robin','batman','memfree','memlow','time','nbs','gw-qual','routes','users','usershi',
  'kbdown','kbup','owner_name','owner_email','owner_phone','owner_address','approval_status','hops','rank','cover1','cover2','cover3','nodemodel','nodenotes','webcamurl','twitterid');

//setTable function
if(!function_exists("setTable")){
function setTable($table){
	global $dbTable;
	$dbTable = $table;
}
}

//create connection to db
$conn = mysql_connect($dbHost, $dbUser, $dbPass) or die("Error connecting to the database: ".$dbHost);
$conn1 = mysqli_connect($dbHost, $dbUser, $dbPass) or die("Error connecting to the database: ".$dbHost);
$connDB = mysql_select_db($dbName, $conn);
?>
