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
<title>AWD Mesh | Historical network users</title>
<script>
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<!-- Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column. -->
<script src='../lib/sorttable.js'></script>
<link rel="stylesheet" type="text/css" href="../lib/js_date/datechooser.css">

</head>
<script src="../lib/js_date/date-functions.js" type="text/javascript"></script>
<script src="../lib/js_date/datechooser.js" type="text/javascript"></script>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php
include "../lib/style.php";
include "../lib/menu.php";
require "../lib/connectDB.php";
setTable("node");
include '../lib/toolbox.php';



//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

echo "</table>";
echo <<<TITLE
<td align='left' width=300><a href="../status/mapusers.php"><img src="anaptyxlogo.png" border="" ALIGN=ABSMIDDLE></a> | <a href="listusers.php"><img src="anaptyxlogo.png" border="" ALIGN=ABSMIDDLE></a></td>
<h1><table width=1040><tr><td align='center' height=40>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><img src="full_page.png" border=0 ALIGN=ABSMIDDLE>Historical List of network users: $display_name</font>
</table></h1>
TITLE;

//Get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION['netid'];
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>There is no record of users.</div>");


echo "<table align='center' height=160><td align='center'><form name='list_mac' action='list_mac.php' method='post'>
	Select MAC: <select name='MAC'>";

$sql = "SELECT * FROM client WHERE netid=".$_SESSION["netid"]." ORDER BY c_mac";
$res = mysql_query($sql, $conn);
$m = $row["c_mac"];

echo "<option>All</option>";
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if ($m != $row["c_mac"]) {
        $m = $row["c_mac"];
        echo "<option>".$row["c_mac"]."</option>";
    }
}
echo "</select><br/><br/>";

?>
	From Date: <input name="startdate" type="text" id="startdate" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'startdate', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; 	width: 160px;"></div><br><br>
	Until: <input name="enddate" type="text" id="enddate" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'enddate', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; width: 160px;"></div>
    <br><br><input type='submit' value='View' name='submit' title='Online consultations up to 1500 records'> &nbsp; <input type='submit' value='Export xls' name='xls' title='Download unlimited records'>

    </ul></form>
    </td><td width=40></td><td align='center'><form name='list_pc' action='list_pc.php' method='post'>
	<img src="search.png" border=0 ALIGN=ABSMIDDLE>Select Client: <select name='PC'>
<?php
$sql = "SELECT * FROM client WHERE netid=".$_SESSION["netid"]." ORDER BY c_name";
$res = mysql_query($sql, $conn);
$m = $row["c_mac"];
echo "<option>All</option>";
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if ($m != $row["c_name"]) {
        $m = $row["c_name"];
        echo "<option>".$row["c_name"]."</option>";
    }
}
echo "</select><br><br>";
?>
	From Date: <input name="startdate1" type="text" id="startdate1" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'startdate1', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; 	width: 160px;"></div><br><br>
	Until: <input name="enddate1" type="text" id="enddate1" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'enddate1', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; width: 160px;"></div>
    <br><br><input type='submit' value='View' name='submit' title='Online consultations up to 1500 records'> &nbsp; <input type='submit' value='Exportar xls' name='xls' title='Download unlimited records'>

    </ul></form>
    </td><td width=40></td><td align='center'><form name='list_node' action='list_node.php' method='post'>
	<img src="search.png" border=0 ALIGN=ABSMIDDLE>Choose Node: <select name='NODE'>
<?php
$sql = "SELECT client.nodeid, node.name, client.netid FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.netid=".$_SESSION["netid"]." ORDER BY node.name";
$res = mysql_query($sql, $conn);
$m = $row["nodeid"]."-".$row["name"];
echo "<option>All</option>";
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if ($m != $row["nodeid"]."-".$row["name"]) {
        $m = $row["nodeid"]."-".$row["name"];
        echo "<option>".$row["nodeid"]."-".$row["name"]."</option>";
    }
}
echo "</select><br><br>";
?>
	From Date: <input name="startdate2" type="text" id="startdate2" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'startdate2', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; 	width: 160px;"></div><br><br>
	Until: <input name="enddate2" type="text" id="enddate2" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'enddate2', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; width: 160px;"></div>
    <br><br><input type='submit' value='View' name='submit' title='Online consultations up to 1500 records'> &nbsp; <input type='submit' value='Exportar xls' name='xls' title='Download unlimited records'>

</ul></form></table></td>
<?php

echo <<<TITLE
<h1><table width=1040><tr><td align='center' height=40>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><img src="chart_pie.png" border=0 ALIGN=ABSMIDDLE>Historical graph of network users: $display_name</font>
</table></h1>
TITLE;

echo "<table align='center'><td align='center'><form name='graph_mac' action='graph_mac.php' method='post'>
	Select MAC: <select name='MAC'>";

$sql = "SELECT * FROM client WHERE netid=".$_SESSION["netid"]." ORDER BY c_mac";
$res = mysql_query($sql, $conn);
$m = $row["c_mac"];
echo "<option>All</option>";
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if ($m != $row["c_mac"]) {
        $m = $row["c_mac"];
        echo "<option>".$row["c_mac"]."</option>";
    }
}
echo "</select><br/><br/>";
?>
	From Date: <input name="startdate3" type="text" id="startdate3" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'startdate3', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; 	width: 160px;"></div><br><br>
	Until: <input name="enddate3" type="text" id="enddate3" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'enddate3', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; width: 160px;"></div>
    <br><br><input type='submit' value='View' name='submit'>

    </ul></form>
    </td><td width=40></td><td align='center'><form name='graph_pc' action='graph_pc.php' method='post'>
	<img src="search.png" border=0 ALIGN=ABSMIDDLE>Select Client: <select name='PC'>
<?php

$sql = "SELECT * FROM client WHERE netid=".$_SESSION["netid"]." ORDER BY c_name";
$res = mysql_query($sql, $conn);
$m = $row["c_mac"];
echo "<option>All</option>";
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if ($m != $row["c_name"]) {
        $m = $row["c_name"];
        echo "<option>".$row["c_name"]."</option>";
    }
}
echo "</select><br/><br/>";
?>
	From Date: <input name="startdate4" type="text" id="startdate4" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'startdate4', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; 	width: 160px;"></div><br><br>
	Until: <input name="enddate4" type="text" id="enddate4" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'enddate4', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; width: 160px;"></div>
    <br><br><input type='submit' value='Consultar' name='submit'>

    </ul></form>
    </td><td width=40></td><td align='center'><form name='graph_node' action='graph_node.php' method='post'>
	<img src="search.png" border=0 ALIGN=ABSMIDDLE>Select Node: <select name='NODE'>
<?php
$sql = "SELECT client.nodeid, node.name, client.netid FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.netid=".$_SESSION["netid"]." ORDER BY node.name";
$res = mysql_query($sql, $conn);
$m = $row["nodeid"]."-".$row["name"];
echo "<option>All</option>";
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if ($m != $row["nodeid"]."-".$row["name"]) {
        $m = $row["nodeid"]."-".$row["name"];
        echo "<option>".$row["nodeid"]."-".$row["name"]."</option>";
    }
}
echo "</select><br><br>";
?>
	From Date: <input name="startdate5" type="text" id="startdate5" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'startdate5', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; 	width: 160px;"></div><br><br>
	Until: <input name="enddate5" type="text" id="enddate5" onClick='javascript:__displayTooltip();' value="">
	<img src="calendar.png" ALIGN=ABSMIDDLE onclick="showChooser(this, 'enddate5', 'chooserSpan', 2010, 2050, 'Y-m-d', false);">
	<div id="chooserSpan" class="dateChooser select-free" style="display: none; visibility: hidden; width: 160px;"></div>
    <br><br><input type='submit' value='Consultar' name='submit'>
    
</ul></form></table></td>


<br></td></tr>
</body>

</html>
