<?php  
/* Name: view.php
 * Purpose: master view for network settings.


 */

//Setup session
session_start();
if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");

//Set how long a node can be down before it's name turns red (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/select.php");
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
<SCRIPT LANGUAGE="Javascript" SRC="includes/FusionCharts.js"></SCRIPT>

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
include("includes/FusionCharts.php");

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

echo <<<TITLE
<h1><table width=1040><tr><td align='right' height=20>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><img src="chart.png" border=0 ALIGN=ABSMIDDLE>Historical network users $display_name</font>
</td><td width=30></td>
<td align='left' width=300><a href='users.php'>back</a></td>
</tr></table></h1>
TITLE;

if ($_POST["MAC"] != "All") {$sql1 = " AND c_mac='".$_POST["MAC"]."'";} else {$sql1 = "";}
if ($_POST["startdate3"] && (strlen($_POST["startdate3"]) < 12)) {$startdate = rtrim($_POST["startdate3"])." 00:00:00";} else {$startdate = $_POST["startdate3"];}
if ($_POST["enddate3"] && (strlen($_POST["enddate3"]) < 12)) {$enddate = rtrim($_POST["enddate3"])." 23:59:59";} else {$enddate = $_POST["enddate3"];}
if ($startdate && $enddate) {$sql2 = " AND (c_time BETWEEN '".$startdate."' AND '".$enddate."')";} else {$sql2 = "";}
if ($startdate && ($enddate == "")) {$sql2 = " AND (c_time >= '".$startdate."')";}
if (($startdate == "") && $enddate) {$sql2 = " AND (c_time <= '".$enddate."')";}

//Get nodes that match network id from database
$query = "SELECT * FROM client WHERE netid='".$_SESSION["netid"]."'".$sql1." ".$sql2." ORDER BY c_time";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>There are no records that match your selection. <a href=\"../users/users.php\">Back</a></div>");
$pointCount = mysql_num_rows($result);
$paso = 1 + floor($pointCount/1000000); // Comprime grafico si se seleccionan +1000 registros (1000 máximo)

//$strXML will be used to store the entire XML document generated
//Generate the graph element
$strXML = "<graph caption='Activated MAC ".$_POST["MAC"]."' subCaption='' showgridbg='1' lineThickness='1' animation='1' showNames='1' showValues='0' yaxisminvalue='0' numVDivLines='40' formatNumberScale='0' rotateNames='1' areaAlpha='90' showLimits='1' decimalPrecision='1' showAlternateHGridColor='0' divLineDecimalPrecision='0' limitsDecimalPrecision='0' showAreaBorder='0' yAxisName='kbps' xAxisName=' ' >";
$cat = "<categories>";
$data1 = "<dataset seriesname='Download' color='00C080' areaBorderColor='000000'>";
$data2 = "<dataset seriesname='Upload' color='0080C0' areaBorderColor='000000'>";

while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

    if ($pointCount<25) {
        $cat .= "<category name='".$row["c_time"]."' showName='1' />";
	    $data1 .= "<set value='".$row["ckbd_hist"]."' />";
	    $data2 .= "<set value='".$row["ckbu_hist"]."' />";
    } else if (($i % round($pointCount/20)) == 0) {
        $cat .= "<category name='".$row["c_time"]."' showName='1' />";
	    $data1 .= "<set value='".$row["ckbd_hist"]."' />";
	    $data2 .= "<set value='".$row["ckbu_hist"]."' />";
    } else if (($i % $paso) == 0) {
        $cat .= "<category name='".$row["c_time"]."' showName='0' />";
        $data1 .= "<set value='".$row["ckbd_hist"]."' />";
    	$data2 .= "<set value='".$row["ckbu_hist"]."' />";
    }
    $i++;

}

	//Finally, close <graph> element
	$strXML .= $cat."</categories>".$data2."</dataset>".$data1."</dataset></graph>";

	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChart("includes/FCF_StackedArea2D.swf", "", $strXML, "FlashVars", 1000, 500);



//Finish our HTML needed for NiftyCorners
?>

<br>
</td></tr></table>
</body>

</html>
