<?php 

//Start session, do includes
session_start();
if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");

include '../lib/toolbox.php';
include "../lib/menu.php";

//setup db connection
require_once '../lib/connectDB.php';
setTable("network");
sanitizeAll();

//get the network id we're working with
$id = $_SESSION['netid'];

//$pos = strpos($_GET["macs"], "?");
$mac = substr($_GET["macs"], 0, (strpos($_GET["macs"], "?")));
$bypassed = substr($_GET["macs"], (strpos($_GET["macs"], "?"))+1);
$macs = trim($bypassed);
if (strlen($bypassed) < 17) {$bypassed = "";}

if (strpos(" ".$bypassed, $mac)<1) {  //No vuelve a a�adirla si ya est� bloqueada
    if (trim($bypassed) != "") {$macs = trim($bypassed).",".$mac;} else {$macs = $mac;}
    $query = "UPDATE network SET bypass_list = '".$macs."' WHERE id='".$id."'";
    mysql_query($query, $conn) or die("Error running: ".mysql_error($conn));
} else

?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>CloudController | User Bypass</title>
 	<script type="text/javascript">	NiftyLoad=function(){Nifty("div.comment");} </script>
  <LINK REL=STYLESHEET HREF="../lib/style.css" TYPE="text/css">
</head></html>
<br><br><center><b><?php echo " User MAC  ".$mac." has successfully activated bypass for new connections."; ?></center><br>
<a href="listusers.php">Back</a>
<br><br><br><br><br><br><br><br>
<br><br><center><b><?php echo " Users currently in this network bypass:"; ?></center>
<br><center><b><?php echo $macs; ?></center>
