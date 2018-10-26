<?php

session_start();

if ($_SESSION['user_type']!='admin') 
 header("Location: ../entry/login.php");

require_once '../lib/connectDB.php';
include_once("ckeditor/ckeditor.php");
include_once("ckfinder/ckfinder.php");

// Cambiar para cada servidor/dashboard
$dashboard = "";

setTable('network');
//Select the network from the database and get the values
$netid = $_SESSION["netid"];
$query = "SELECT * FROM ".$dbTable." WHERE id='".$netid."'";
$result = mysql_query($query, $conn);
$resArray = mysql_fetch_array($result, MYSQL_ASSOC);
$display_name = $resArray['net_name'];

$splashfile = "../users_splash/$display_name/editor.html";

// Set session variable for FCKeditor to use to store images to user folder. Modificada la siguiente linea por AAS para que se guarde todo en la carpete /users. Originalmente era $_SESSION["UserPath"] = "/users/$display_name/";
$_SESSION["UserPath"] =$dashboard ."/users_splash/$display_name/";

if (!file_exists("../users_splash/$display_name"))
{
	// User folder does not exist, let's create and copy the template in
	$rs = mkdir("../users_splash/$display_name",0777);
	if ($rs)
	{
		$rs = mkdir("../users_splash/$display_name/images");
		$rs = copy("../users_splash/template/splash.txt","../users_splash/$display_name/splash.txt");
		$rs = copy("../users_splash/template/splash.txt","../users_splash/$display_name/editor.html");
	}
}

if (file_exists($splashfile))
{
$fh = fopen($splashfile,'r');
$splashtext = fread($fh,filesize($splashfile));
fclose($fh);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Splash Page Editor | <?php  echo $display_name; ?></title>
</head>
<body>
<form method="post" action="save.php">

<?php
$a=chr(92).'"';
$b='"';
$splashtext = str_replace($a,$b,$splashtext);
//$oFCKeditor = new CKEditor('CKEditor1');
//$oFCKeditor->BasePath = 'fckeditor/';
//$oFCKeditor->Value = $splashtext;
//$oFCKeditor->Height = '550px';
//$oFCKeditor->Width = '880px';
//$oFCKeditor->Create();
$CKEditor = new CKEditor();
// $CKEditor->basePath = '/ckeditor/';

// CKFinder::SetupCKEditor($CKEditor, '/ckfinder/');
//  $ckeditor->editor('CKEditor1');



 $ckfinder = new CKFinder();
 $ckfinder->BasePath =$dashboard .'/splash/ckfinder/'; // Note: BasePath property in CKFinder class starts with capital letter
 $ckfinder->SetupCKEditorObject($CKEditor);
// $CKEditor->editor('CKEditor1');
$CKEditor->editor("CKEditor1", "<p>".$splashtext."</p>");


?>

<center><font face="verdana" size=3>Once finished, click the Save button on the toolbar.</font></center>
</form>
</body>
</html>
