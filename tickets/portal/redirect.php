<?php
$vo="";
$vo=$_GET['voucherid'];

if ($vo!="") {
$expire=time()+(3600*24)*60;
setcookie('awdaccess', $vo, $expire,"/");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
<meta http-equiv="refresh" content="5;url=<? session_start();echo $_SESSION['login']; ?>">
<title>Please wait...</title>
</head>
<body bgcolor="#ffffff">
<p align="center"><strong><font size="7"></font></strong>&nbsp;</p>
<p align="center"><strong><font size="7">Yipee!</font></strong></p>
<p align="center"><strong><font size="4">You are now online</font></strong></p>
<p align="center"><strong><font size="4">please wait whilst we redirect 
you.</font></strong></p>
<p align="center"><img border="0" hspace="0" src="loader.gif" width="32" 
height=32></p>
<p><strong><font size="7"></font></strong>&nbsp;</p>
</body>
</html>
