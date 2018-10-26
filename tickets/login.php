<?php include('config.php'); ?>
<?php

session_start();

if(isset($_GET['email']) && isset($_GET['psw'])){

$email = $_GET['email'];
$psw = $_GET['psw'];

$getUser_sql = 'SELECT * FROM admin WHERE adminemail="'. $email . '" AND adminpassword = "' . $psw . '"';
$getUser = mysql_query($getUser_sql);
$getUser_result = mysql_fetch_assoc($getUser);
$getUser_RecordCount = mysql_num_rows($getUser);

if($getUser_RecordCount < 1){ echo '-1';} else { 
	echo $getUser_result['nick'];	
	$_SESSION['nref']=$getUser_result['nref'];
	$_SESSION['nick']=$getUser_result['nick'];
	$_SESSION['email']=$getUser_result['adminemail'];
	$_SESSION['seclevel']=$getUser_result['seclevel'];
}
}
