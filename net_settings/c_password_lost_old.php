<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <?php  include "../lib/menu.php"; ?>
  <?php  include "../lib/validateInput.js"; ?>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>CloudController | Recover password and network name</title>
 	<script type="text/javascript">	NiftyLoad=function(){Nifty("div.comment");} </script>
  <LINK REL=STYLESHEET HREF="../lib/style.css" TYPE="text/css">
</head></html>

<?php 
/* Name: c_password_lost.php
 * Purpose: process password change.


 */

//get the toolbox
include '../lib/toolbox.php';
		


 
	
		
		
//setup database connection
require '../lib/connectDB.php';
setTable('network');
sanitizeAll();

//configure mail sending options
require '../mail/Mail.php';
$smtp_params['host'] = "localhost";	// Must change this to your smtp server
$smtp_params['auth'] = FALSE; // Change to true if your smtp server requires authentication
$params['username'] = ""; // The username to use for SMTP authentication.
$params['password'] = ""; // The password to use for SMTP authentication.
$params['persist'] = FALSE; // Allows you to use one SMTP connection for multiple emails.
$from = "alerts@awdmesh.com";
$headers .= "From: $from";
//$headers['X-Mailer'] = "AWD Mesh Mailer PHP /".phpversion();	// Makes this look less like spam
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers['Content-Type'] = "text/html; charset=iso-8859-1";  // Activa lenguaje HTML


//Select the network with this name
$query = "SELECT * FROM network WHERE net_name='".$_POST["net_name"]."'";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)>0) {
    $net =  mysql_fetch_assoc($result);
    $red_email = $net['email1'];

    $query = "SELECT * FROM network WHERE email1='".$_POST["net_email"]."'";
    $result = mysql_query($query, $conn);
    //Send email if network exists and no input email
    if(mysql_num_rows($result)==0) {
        $body = "There is a network<b><i>".$_POST["net_name"]."</b></i><br><br>You can retrieve your password <a href='http://dashboard.awdmesh.com".$_SESSION['dashboard']."/net_settings/password_lost.php'> here;</a>";
	    $recipients = $red_email;
	    $headers['To'] = $recipients;
	    //$headers['Subject'] = 'CloudController account lost password';
           $subject = "account lost password";
	    //Send the message
	    $mail_object =& Mail::factory('smtp', $smtp_params);
           //mail($recipients, $subject, $headers, $body);
	    if($mail_object->send($recipients, $headers, $body)){
		    echo "<br><br> Email sent to ".$recipients."<br><br> Check your inbox or spam folder.<br><br><a href='../index.php'> Back</a>";
	    } else {echo "Interupted process...";}

    } else {
        //Send email if network and email coincidence
        $body = "CloudController. Click the link below to reset your password:<br><br>";
	    $recipients= $_POST["net_email"];
	    $headers['To'] = $recipients;
	    //$headers['Subject'] = 'CloudController recover password';
           $subject = "account recover password";
        $caracteres = array_merge(range('a','z'), range('A','Z'), range(0, 9));
        $codigo .= $caracteres[mt_rand(0, (count($caracteres)-1))];
        while (strlen($codigo)<65) {
            $codigo .= $caracteres[mt_rand(0, (count($caracteres)-1))];
        }

        $body .= "<br>http://dashboard.awdmesh.com".$_SESSION['dashboard']."/net_settings/reminder_pass.php?email=".$_POST["net_email"]."&netname=".$_POST["net_name"]."&code=".$codigo;
 //       recuperar.php?email=$email&code=$codigo
   	    //Send the message
	    $mail_object =& Mail::factory('smtp', $smtp_params);
           //mail($recipients, $subject, $headers, $body);
	    if($mail_object->send($recipients, $headers, $body)){
		    echo "<br><br> Email sent to ".$recipients."<br><br> Check your inbox or spam folder.<br><br><a href='../index.php'> Back</a>";
            //Update table passlost with netname, email and hash code

			$query = "SELECT * FROM passlost WHERE email='".$_POST["net_email"]."' AND netname='".$_POST["net_name"]."'";
    		$result = mysql_query($query, $conn);
	        if(mysql_num_rows($result)>0) {
		        //update the existing entry
		        $query1 = "UPDATE passlost SET netname='".$_POST["net_name"]."', email='".$_POST["net_email"]."', code='".$codigo."', time='".date("Y-m-d H:i:s")."' WHERE netname='".$_POST["net_name"]."' AND email='".$_POST["net_email"]."'";
		        mysql_query($query1, $conn);
	        } else {
		        //add no existing entry
		        $query1 = "INSERT INTO passlost (netname, email, code, time) VALUES('".$_POST["net_name"]."', '".$_POST["net_email"]."', '".$codigo."', '".date("Y-m-d H:i:s")."')";
                mysql_query($query1, $conn) or die("Error: Can not record hash: ".$_POST["net_name"].$_POST["net_email"].$query1.mysql_error($conn));
	        }

            //    mysql_close($conn);



	    } else {echo "Process interupted...";}
    }

} else {
    //Send email with all network names (no input network name or bad network name)
    $query = "SELECT * FROM network WHERE email1='".$_POST["net_email"]."'";
    $result = mysql_query($query, $conn);
    //Select the networks with this email
    if(mysql_num_rows($result)>0) {
//      $net =  mysql_fetch_assoc($result);
//      $red_name =.$net['net_name'];
        $body = "Partner networks ".$_POST["net_email"]."<br><br>";
	    $recipients = $_POST["net_email"];
	    $headers['To'] = $recipients;
	    //$headers['Subject'] = 'CloudController recovery password';
           $subject = "account recovery password";
        //For every network in the dashboard
        while($net = mysql_fetch_assoc($result)) {
            $body .= "<br><b><i>".$net['net_name']."</i></b>";
        }
        //Send the message
        $body .= "<br>";
	    $mail_object =& Mail::factory('smtp', $smtp_params);
           //mail($recipients, $subject, $headers, $body);
	    if($mail_object->send($recipients, $headers, $body)){
		    echo "<br><br> Email sent to ".$recipients."<br><br> Check your inbox or spam folder.<br><br><a href='../index.php'> Back</a>";
	    } else {echo "Process interupted...";}

    } else {
        echo "<br><br> Network does not exist <b><i>".$_POST["net_name"]."</b></i><br><br> No networks associated with this email <a href='mailto:".$_POST["net_email"]."'>".$_POST["net_email"]."</a><br><br><a href='../net_settings/password_lost.php'> Back</a>";
    }
}





