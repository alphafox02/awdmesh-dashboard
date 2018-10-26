<?php  
/* Name: view.php

 */

//Setup session
session_start();
if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");


//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ../entry/login.php");
	exit();
}

if ($_POST['xls'] == 'Export xls') {
	header("Location: list_export.php?tipo=MAC&value=".$_POST['MAC']."&startdate=".$_POST['startdate']."&enddate=".$_POST['enddate']);
	exit();
}


?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>CloudController | Historical network users</title>

<script>
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<!-- Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column. -->
<script src='../lib/sorttable.js'></script>

<?php  

//Do includes
include "../lib/style.php";
?>
</head>
<body onload=Nifty("div.note");>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php 
include "../lib/menu.php";
require "../lib/connectDB.php";
setTable("node");
include '../lib/toolbox.php';


//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

echo <<<TITLE
<h1><table width=1040><tr><td align='right' height=20>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><img src="chart.png" border=0 ALIGN=ABSMIDDLE>Historical network users $display_name</font>
</td><td width=30></td>
<td align='left' width=300><a href='users.php'>Back</a></td>
</tr></table></h1>
TITLE;

if ($_POST["MAC"] != "All") {$sql1 = " AND c_mac='".$_POST["MAC"]."'";} else {$sql1 = "";}
if ($_POST["startdate"] && (strlen($_POST["startdate"]) < 12)) {$startdate = rtrim($_POST["startdate"])." 00:00:00";} else {$startdate = $_POST["startdate"];}
if ($_POST["enddate"] && (strlen($_POST["enddate"]) < 12)) {$enddate = rtrim($_POST["enddate"])." 23:59:59";} else {$enddate = $_POST["enddate"];}
if ($startdate && $enddate) {$sql2 = " AND (c_time BETWEEN '".$startdate."' AND '".$enddate."')";} else {$sql2 = "";}
if ($startdate && ($enddate == "")) {$sql2 = " AND (c_time >= '".$startdate."')";}
if (($startdate == "") && $enddate) {$sql2 = " AND (c_time <= '".$enddate."')";}

//Get nodes that match network id from database
$query = "SELECT client.*, node.name FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.netid='".$_SESSION["netid"]."'".$sql1." ".$sql2." ORDER BY client.c_time";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>There are no records that match your selection <a href=\"../users/users.php\">Back</a></div>");

//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
//-----
// Added "version" as value to the array, which is the index to node properties
// By MeshConnect Staff.
//-----
$node_fields = array("PC" => "c_name","MAC" => "c_mac","Node" => "name","Checkin" => "c_time",
  "Down kb" => "ckbd_hist","Up kb" => "ckbu_hist");

echo "<table class='sortable' border='1'>";

//Output the top row of the table (display names)
// echo "<td>" . $key . "</td>";
echo "<tr class=\"fields\">";
foreach($node_fields as $key => $value) {
    echo "<td align='center'>&nbsp;";
        echo $key;
    echo "&nbsp;</td>";
}

echo "</tr>";

//Output the rest of the table
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    foreach($node_fields as $key => $value) {
        echo "<td align='center'>&nbsp;";
        echo $row[$value];
        echo "&nbsp;</td>";
        if ($value=="ckbd_hist") {
            $kbd = $kbd + $row[$value];
            $kbt = $kbt + $row[$value];
        }
        elseif ($value=="ckbu_hist") {
            $kbu = $kbu + $row[$value];
            $kbt = $kbt + $row[$value];
        }
    }
    echo "</tr>";
    $i++;
    if ($i > 1500000) {
		$msg = "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b><font color='#FF0000'>&iexcl;CONSULTATION ABORTED. Exceeded PERMITTED OF 1500 RECORDS ONLINE! <u>THE CONSULTATION IS PARTIAL</u>.</b><br><br>";
		break;
	}
}
echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><b><font color='#FF0000'>TOTAL: ".$kbt."&nbsp;</td><td align='center'><b><font color='#FF0000'>".$kbd."</td><td align='center'><b><font color='#FF0000'>".$kbu."</td>";
echo "</tr><td>&nbsp;</td>";

if ($_SERVER['HTTPS'] != '') $protocol = "https"; else 	$protocol = "http";
echo "</tr></tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><b>Download &nbsp; <a href='".$protocol."://".$_SERVER['SERVER_NAME']."/". $_SESSION['dashboard'] ."/users/list_export.php?type=MAC&value=".$_POST['MAC']."&startdate=".$_POST['startdate']."&enddate=".$_POST['enddate']."' target='_blank'><img src='../lib/images/excel.ico' width='15' height='15' border='0'></a></td>";

echo "</table>";
echo $msg;

//Finish our HTML needed for NiftyCorners
?>

<br>
</td></tr></table>
</body>

</html>
