<?php
ob_start();
session_start();

if(empty($_SESSION['nref']))
{
    $url1 = 'https://'.$_SERVER['HTTP_HOST'].'/tickets/login.html';
    header("location:$url1");
	exit();
}
else
{
    header ("Expires: Mon, 26 Jul 2000 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
	header ("Cache-control: no-cache, no-store"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
//header("HTTP/1.0 302 Not Found");
}
?>
