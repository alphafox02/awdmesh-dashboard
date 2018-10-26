<?php

session_start(); // This connects to the existing session 
$action="";
$alert="";

$_SESSION['login']="authorise.php";

if (isset($_POST['token'])) {
	$_SESSION['token']=$_POST['token'];
} else {
	$_SESSION['token']="authorise.php";
	$alert="Not Called from a HotSpot,Wont work";
}


$dbHost = "localhost"; // the mySQL server machine relative to apache
$dbUser = "db80716_dash"; // user name on the mySQL db
$dbPass = "o3tKccVfJ";	//be sure to change this!
$dbName = "meshcontroller"; // database name

$showwelcome="0";
$showlogin="0";


error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_COOKIE["awdaccess"])) {
			$voucher=$_COOKIE["awdaccess"];
			} else {
			$voucher="";	}

if ($voucher!="") {

	$con = mysql_connect($dbHost, $dbUser, $dbPass) or die("Error connecting to database: ".$dbHost);
		if (!$con)
  		{
			  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("meshcontroller", $con);
		$result=mysql_query(sprintf("select * from vouchers where voucherid='%s'",$voucher));

		if (mysql_num_rows($result)==1) {
			$vs=mysql_result($result,0,"voucherstate");
			$ve=mysql_result($result,0,"voucherexpires");
				if ($vs<>"1" ) {
					$alert= "Voucher ".$voucher." no longer valid";
					$showlogin=1;
					} else {
					$showwelcome=1;
					$showlogin=0;
					$_SESSION['vouchergood']=1;
					$_SESSION['login']=$_SESSION['token'];
					$showwelcome=1;	}				
		} else {
		$showlogin=1;}
}

else {
$showlogin=1;
}

if ($showlogin==1) {
$voucherpage="<form method='post' action=''><p align='center'>
<input type='text' name='vouchercodenm' id='vouchercode'></br><p></p><p align='center'>
<input type='button' name='submit' value='Activate Voucher' id='submit' onclick='javascript:activatevoucher();' ></form>
</p>";

if ($alert!="") {
$voucherpagescript="<body onload=\"document.getElementById('vouchercode').focus(); alert('$alert'); \">";
} else {
$voucherpagescript="<body onload=\"document.getElementById('vouchercode').focus(); \">";
}

}
if ($showwelcome==1) {
$voucherpage="<form method='post' action=''><p align='center'><input type='button' name='submit' value='Log In' id='submit' onclick='javascript:voucherlogin();' /></form>
</p>";

if ($alert!="") {
$voucherpagescript="<body onload=\"alert('$alert'); \">";
} else {
$voucherpagescript="<body>";
}

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="keywords" content="" />
<meta name="author" content="" />

<title>Voucher System</title>
<style type="text/css">
body,td,th {	font-family: Tahoma;	font-size: 12px;color: #FDFCFF;}body {background-color: #45311f;margin-left: 1px;margin-top: 1px;margin-right: 1px;margin-bottom: 1px;background-image: url(bg.gif);background-repeat: repeat-x;}a:hover {color: #005080;text-decoration: none;}.style1 {font-size: 16px;font-weight: bold;color: #CCCCCC;}.style3 {font-size: 18px;color: #662626;}.style4 {font-size: 11px;color: #000000;font-family: Arial, Helvetica, sans-serif;}a:link {	color: #00406A;text-decoration: none;}.style5 {	font-size: 9px}.style7 {font-size: 9px; color: #FFFFFF; }.style8 {color: #CCCCCC}a:visited {color: #003F69;text-decoration: none;}.style9 {font-size: 18px;color: #61839E;}a:active {text-decoration: none;}.style14 {color: #000000}.style15 {font-family: Arial, Helvetica, sans-serif}.style16 {color: #000000; font-family: Arial, Helvetica, sans-serif; }</style>


<script type="text/javascript">
function createObject() {
var request_type;
var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_type = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_type = new XMLHttpRequest();
	}
return request_type;
}

var http = createObject();
var nocache = 0;

function activatevoucher() {
var voucher = encodeURI(document.getElementById('vouchercode').value);
nocache = Math.random();
http.open('get', 'activatevoucher.php?voucher='+voucher+'&amp;nocache='+nocache);
http.onreadystatechange = loginReply;
http.send(null);
}

function voucherlogin() {
setTimeout('go_to_redir_page()', 0);
}

function loginReply() {
	if(http.readyState == 4){
		var response = http.responseText;
			if(parseInt(response) == -1){
			setTimeout('go_to_private_page()', 0);
			} else {
			alert(response);
			}
	}
}

function go_to_private_page(voucherid) {
voucher = encodeURI(document.getElementById('vouchercode').value);
window.location = 'redirect.php?voucherid='+voucher; // Members Area
}

function go_to_redir_page() {
window.location = 'redirect.php';
}


</script>
</head>
<?php echo $voucherpagescript; ?>
<p>&nbsp;</p>
<p>&nbsp;</p>

<center>
	<table border="0" cellpadding="0" cellspacing="0" style="height:206px" width="740">
		<tbody>
			<tr>
				<td>
					<img alt="" src="sign(1).jpg" style="width: 374px; height: 236px;" /><img alt="" src="pool.jpg" style="width: 362px; height: 236px;" /></td>

			</tr>
		</tbody>
	</table>
	<table bgcolor="#ffffff" border="0" cellpadding="12" cellspacing="1" width="740">
		<tbody>
			<tr>
				<td height="149" width="487">
					<p align="left" class="style1 style14">
						<br />
						<br />
						<span class="style15">Welcome to  Enterprise Mobile home & RV park Wi-Fi network!</span></p>
					<p align="left" class="style16">
						This network access is provided to you on behalf of the management. We hope you enjoy your experience. While you surf, please help us keep the network experience great for everyone. Save large uploads and downloads for a private connection.</p>
					<p align="left" class="style16">
						Thanks again,</p>
					<p align="left" class="style14">
						<span class="style15">Colin</span><br />
						<br />
						&nbsp;</p>
					<p align="left" class="style8">
						&nbsp;</p>
				</td>
				<td width="9">
					<p align="left" class="style3">
						<img height="100" src="Camp-Spacer.gif" width="5" /></p>
				</td>
				<td width="168">
					<p align="center" class="style9">
						<span class="style16">Begin browsing</span></p>
					<p align="left" class="style3">
						<?php echo $voucherpage;?></p>
					<p align="left" class="style4">
						Bike trails open year round!</p>
				</td>
			</tr>
		</tbody>
	</table>
	<table bgcolor="#2a7729" border="0" cellpadding="0" cellspacing="0" width="740">
		<tbody>
			<tr>
				<td>
					<div align="center">
						<span class="style7">&copy; AWD</span></div>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="style5">
		&nbsp;</p>
	<p class="style5">
		&nbsp;</p>
	<p class="style5">
		&nbsp;</p>
</center>
</body>
</html>
