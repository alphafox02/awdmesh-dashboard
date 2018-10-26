
<html>
<head>
  <?php  include "../lib/menu.php"; ?>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>CloudController | Recover password and network name</title>
 	<script type="text/javascript">	NiftyLoad=function(){Nifty("div.comment");} </script>
  <LINK REL=STYLESHEET HREF="../lib/style.css" TYPE="text/css">
</head></html>


<?php 



/* Name: c_password.php
 * Purpose: process password change.

 */

//Setup db connection
require_once '../lib/connectDB.php';

//Check in passlost hash, network name and email
$query = "SELECT * FROM passlost WHERE netname='".$_POST["netnameOK"]."' AND email='".$_POST['emailOK']."'";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("Unauthorized access 0.");

$pass_result = mysql_fetch_assoc($result);
if($pass_result['email']!= $_POST["emailOK"]) die("Unauthorized access 1.");
if($pass_result['code']!= $_POST["codeOK"]) die("Unauthorized Access 2.");
		
//get the toolbox
include '../lib/toolbox.php';

setTable('network');
sanitizeAll();






//first check that the passwords entered matched
if($_POST["new_pass"]==$_POST["confirm_pass"]){

  $query = "SELECT * FROM network  WHERE net_name='".$_POST["netnameOK"]."' AND email1='".$_POST["emailOK"]."'";
  $result = mysql_query($query, $conn);
  $num = mysql_num_rows($result);

  //if yes, update the password
  if($num > 0){
    //if yes, update the password
	$query = "UPDATE network SET password='".md5($_POST["new_pass"])."' WHERE net_name='".$_POST["netnameOK"]."' AND email1='".$_POST["emailOK"]."'";
	$result = mysql_query($query, $conn);

    //logout COMENTO TODO LO QUE EMPIEZA POR XXX
    //XXXsession_start();
    //XXX$ulang='en';
    // keep language setting
    //XXXif(isset($_SESSION['lang_selc'])) $ulang = $_SESSION['lang_selc'];
    // Unset all of the session variables to force new login
    //XXX$_SESSION = array();
    // recover the language
    //XXX$_SESSION['lang_selc'] = $ulang;
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=password_changeok.php\">";
  }

} else die("The passwords do not match.<br>Click on the link in the email we sent you and try again.")


?>
