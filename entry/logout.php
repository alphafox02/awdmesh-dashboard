<?php 
/* Name: logout.php
 * Purpose: logs user out of dashboard.

 */
session_start();

$ulang='en';
// keep language setting
if(isset($_SESSION['lang_selc'])) $ulang = $_SESSION['lang_selc'];

// Unset all of the session variables to force new login
$_SESSION = array();

// recover the language
$_SESSION['lang_selc'] = $ulang;
	
//echo "<meta http-equiv=\"Refresh\" content=\"0;url=../index.php\">";
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../index.php");
        exit();

//Header("Location: ../index.php");
?>
