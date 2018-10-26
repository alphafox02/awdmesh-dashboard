<?php 
/* Name: c_password.php
 * Purpose: process password change.


 */

//Make sure the person is logged in
session_start();
if(!isset($_SESSION['netid'])){
	// header("Refresh: 0 url=../entry/login.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}

//get the toolbox
include '../lib/toolbox.php';
		
//setup database connection
require_once '../lib/connectDB.php';
setTable('network');
sanitizeAll();



//first check that the passwords entered matched
if($_POST["new_pass"]!=$_POST["confirm_pass"]){
   $_SESSION['message'] = "E1";
   //echo "<meta http-equiv=\"Refresh\" content=\"0;url=password.php\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: password.php");
        exit();
// die("<div class=error>&#20004;&#27425;&#23494;&#30721;&#19981;&#31526;&#12290; (Error 7115) The passwords you entered did not match!</div>");
} ELSE {
  //check to see if the user entered the correct current password
  $password = md5($_POST["old_pass"]);
  $query = "SELECT * FROM ".$dbTable." WHERE id='".$_SESSION['netid']."' AND password='".$password."'";
  $result = mysql_query($query, $conn);
  $num = mysql_num_rows($result);

  //if yes, update the password
  if($num > 0){
	$query = "UPDATE network SET password='".md5($_POST["new_pass"])."' WHERE id='".$_SESSION['netid']."' AND password='".$password."'";
	$result = mysql_query($query, $conn);

        $_SESSION['message'] = 'M1';
        $_SESSION['updated'] = 'true';
        //echo "<meta http-equiv=\"Refresh\" content=\"0;url=../net_settings/edit.php\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../net_settings/edit.php");
        exit();
//header("Refresh: 10 url=../net_settings/edit.php");
//include "../lib/menu.php";
//include "../lib/style.php";
//echo '<div class=success>&#23494;&#30721;&#20462;&#25913;&#23436;&#25104;&#12290; Password changed!</div>';
	
  } else {
    //if no, don't update the password
    $_SESSION['message'] = "E2";
    //echo "<meta http-equiv=\"Refresh\" content=\"0;url=password.php\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: password.php");
        exit();
  }
}
