<?php include('config.php');
require_once "Mail.php";

session_start();

if(isset($_GET['email'])) {

$email = $_GET['email'];

$getUser_sql = "SELECT * FROM admin WHERE adminemail='". $email . "'";
$getUser = mysql_query($getUser_sql);
$getUser_result = mysql_fetch_assoc($getUser);
$getUser_RecordCount = mysql_num_rows($getUser);

if($getUser_RecordCount < 1) { 
		echo '-1';
	} else { 
	echo '1';

$textmail = file_get_contents("newpassword.txt");

# replace the variables in the contact text

$textmail=str_replace("%password%",$getUser_result['adminpassword'],$textmail);


$from = "";
$to = $getUser_result['adminemail'];
$subject = "CloudController Portal Password Recovery";
$body = $textmail;
$host = "smtp.gmail.com";
$username = "";
$password = "manager";
$headers = array ('From' => $from,
'To' => $to, 
'Subject' => $subject);
$smtp = Mail::factory('smtp',
array ('host' => $host,
'auth' => true,
'username' => $username,
'password' => $password));
$mail = $smtp->send($to, $headers, $body);




}
}
