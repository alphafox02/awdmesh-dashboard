<?php  
/* Name: view.php

 */

//Setup session
session_start();
if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");


//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/select.php");
        exit();
}

//Do includes

require "../lib/connectDB.php";

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

if ($_GET["type"] == "MAC") {
    if ($_GET["value"] != "All") {$sql1 = " AND c_mac='".$_GET["value"]."'";} else {$sql1 = "";}
} else if ($_GET["type"] == "PC") {
	if ($_GET["value"] != "All") {$sql1 = " AND c_name='".$_GET["value"]."'";} else {$sql1 = "";}
} else if ($_GET["type"] == "NODE") {
	if ($_GET["value"] != "All") {$sql1 = " AND nodeid='".substr($_GET["value"],0,strpos($_GET["value"], "-"))."'";} else {$sql1 = "";}
}

if ($_GET["startdate"] && (strlen($_GET["startdate"]) < 12)) {$startdate = rtrim($_GET["startdate"])." 00:00:00";} else {$startdate = $_GET["startdate"];}
if ($_GET["enddate"] && (strlen($_GET["enddate"]) < 12)) {$enddate = rtrim($_GET["enddate"])." 23:59:59";} else {$enddate = $_GET["enddate"];}
if ($startdate && $enddate) {$sql2 = " AND (c_time BETWEEN '".$startdate."' AND '".$enddate."')";} else {$sql2 = "";}
if ($startdate && ($enddate == "")) {$sql2 = " AND (c_time >= '".$startdate."')";}
if (($startdate == "") && $enddate) {$sql2 = " AND (c_time <= '".$enddate."')";}

//Get nodes that match network id from database
$query = "SELECT client.*, node.name FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.netid='".$_SESSION["netid"]."'".$sql1." ".$sql2." ORDER BY client.c_time";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>".$query." There are no records that match your selection. <a href=\"../users/users.php\">Back</a></div>");


//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
//-----
// Added "version" as value to the array, which is the index to node properties
// By Nodalis Staff.
//-----
$node_fields = array("PC" => "c_name","MAC" => "c_mac","Node" => "name","Checkin" => "c_time",
  "Down kb" => "ckbd_hist","Up kb" => "ckbu_hist");


//Output the top row of the table (display names)
// echo "<td>" . $key . "</td>";

foreach($node_fields as $key => $value) {
        $csv_output .= $key."; ";
}

    $csv_output .= "\n";

//Output the rest of the table
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    foreach($node_fields as $key => $value) {

        $csv_output .= $row[$value]."; ";
        if ($value=="ckbd_hist") {
            $kbd = $kbd + $row[$value];
            $kbt = $kbt + $row[$value];
        }
        elseif ($value=="ckbu_hist") {
            $kbu = $kbu + $row[$value];
            $kbt = $kbt + $row[$value];
        }
    }

    $csv_output .= "\n";
}

$csv_output .= "\n";
$csv_output .= "TOTAL Download: ".$kbt.";\n";
$csv_output .= "\n";
$csv_output .= "TOTAL Upload: ".$kbu.";\n";


$startdate1 = rtrim(ltrim($startdate));
$startdate1 = str_replace(" ", "_", $startdate1);
$startdate1 = str_replace(":", "-", $startdate1);
if ($startdate1 == '') $startdate1 = "2010-01-01";
$enddate1 = rtrim(ltrim($enddate));
$enddate1 = str_replace(" ", "_", $enddate1);
$enddate1 = str_replace(":", "-", $enddate1);
if ($enddate1 == '') $enddate1 = date("Y-m-d_H-i-s",time());

$filename = "list_".$resArray['net_name']."_from_".$startdate1."_to_".$enddate1;

$csv_output .= "\n";
$csv_output .= "Generated ".date("Y-m-d_H:i:s",time()).";\n";


header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename . ".csv");

print $csv_output;
exit;
//Finish our HTML needed for NiftyCorners
?>


