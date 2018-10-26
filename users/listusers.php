<?php
/* Name: listusers.php
 * Purpose: list online users.


 */

//Setup session
session_start();

//Set how long a node can be down before it's name turns red (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}


//ESTO LO HE AGREGADO YO. Make sure person is logged in
session_start();

if ($_SESSION['user_type']!='admin') 
	header("Location: ../entry/login.php");



//Select the network from the database and get the values

//ESTO LO HE AGREGADO YO.
?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>CloudController | List of network users</title>

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
//include "libchart/classes/libchart.php";











//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$result2 = mysql_fetch_array($result, MYSQL_ASSOC);
$macs_blocked = $result2["access_disable_list"];
$macs_bypassed = $result2["bypass_list"];
if($result2["display_name"]=="") {$display_name = $result2["net_name"];}
else {$display_name = $result2["display_name"];}


echo <<<TITLE
<h1><table width=1040><tr><td align='right' height=20>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><img src="users.png" border=0 ALIGN=ABSMIDDLE>Network users $display_name</font>
</td><td width=30> </td>
<td align='left' width=300>
<a href="../status/mapusers.php"><img src="anaptyxlogo.png" border="" ALIGN=ABSMIDDLE></a><a href="users.php"><img src="anaptyxlogo.png" border="" ALIGN=ABSMIDDLE></a>


</td>
</tr></table></h1>





<div class="note" id="tip">List of recent activity of network users. Users are shown originating traffic. Blocking users have priority over the bypass and both are only possible <STRONG>Only used with Splash Enabled on AP1.</STRONG> 
<a href=javascript:close()>hide notes</a>
<align="center"><div align="center"></div>

</div>





TITLE;

//Selecciona sólo los ids en client de las MAC con checkin más reciente
//$query = "SELECT client.*, MAX(client.c_time) AS hora, node.name FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.netid='".$_SESSION["netid"]."' AND client.c_time > '".date('Y-m-d')." 00:00:00' GROUP BY c_mac";
$query0 = "SELECT MAX(c_time) AS c_time, MAX(id) AS clients_ids, COUNT(netid), c_mac FROM client WHERE netid='".$_SESSION["netid"]."' GROUP BY c_mac ORDER BY c_time DESC;";
$result0 = mysql_query($query0, $conn);
if(mysql_num_rows($result0)<1) die("<div class=error>There are no active users connected and no current or recent connection log.</div>");


//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
//-----
// Added "version" as value to the array, which is the index to node properties
// By MeshConnect Staff.
//-----
$node_fields = array("Status" => "Estado","User" => "c_name","MAC" => "c_mac","Node" => "name","Time" => "c_time",
  "Down (Kb)" => "ckbd_hist","Up (Kb)" => "ckbu_hist","Blacklist" => "Bloquear","Whitelist" => "Bypass");

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

while($row0 = mysql_fetch_array($result0, MYSQL_ASSOC)) {

$query = "SELECT client.*, node.name FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.id='".$row0['clients_ids']."'";
$result = mysql_query($query, $conn);


//Output the rest of the table
$row = mysql_fetch_array($result, MYSQL_ASSOC);  // First, users online in last checkin
if($currentTime < strtotime($row["c_time"])+330) {
    $fuente = '<font color="#008000">';
    foreach($node_fields as $key => $value) {
        echo "<td align='center'>&nbsp;";
        if ($value=="Estado") {if($currentTime < strtotime($row["c_time"])+330) {echo '<img src="uonline.png" border=0 ALIGN=ABSMIDDLE>';}
        } elseif (($value=="name") && ($row[$value]=="")){echo $fuente."NODO BORRADO";
        } elseif ($value=="Bloquear") {echo '<a href='."'".'block.php?macs='.$row["c_mac"].'?'.$macs_blocked."'".'><img src="add.png" border=0></a>';
		} elseif ($value=="Bypass") {echo '<a href='."'".'bypass.php?macs='.$row["c_mac"].'?'.$macs_bypassed."'".'><img src="add.png" border=0></a>';

        } else {echo $fuente.$row[$value];}
        echo "&nbsp;</td>";
//        if ($value=="ckbd_hist") {$kbd = $kbd + $row[$value]; $kbt = $kbt + $row[$value];}
//        elseif ($value=="ckbu_hist") {$kbu = $kbu + $row[$value]; $kbt = $kbt + $row[$value];}
    }
    echo "</tr>";
} else {
    $fuente = '<font color="#C0C0C0">';
    foreach($node_fields as $key => $value) {
        echo "<td align='center'>&nbsp;";
        if ($value=="Estado") {if($currentTime > strtotime($row["c_time"])+330) {echo '<img src="uoffline.png" border=0 ALIGN=ABSMIDDLE>';}
        } elseif (($value=="name") && ($row[$value]=="")){echo $fuente."Node Terminated";
        } elseif ($value=="Bloquear") {echo '<a href='."'".'block.php?macs='.$row["c_mac"].'?'.$macs_blocked."'".'><img src="add.png" border=0></a>';
		} elseif ($value=="Bypass") {echo '<a href='."'".'bypass.php?macs='.$row["c_mac"].'?'.$macs_bypassed."'".'><img src="add.png" border=0></a>';
        } else {echo $fuente.$row[$value];}
        echo "&nbsp;</td>";
//        if ($value=="ckbd_hist") {$kbd = $kbd + $row[$value]; $kbt = $kbt + $row[$value];}
//        elseif ($value=="ckbu_hist") {$kbu = $kbu + $row[$value]; $kbt = $kbt + $row[$value];}
    }
    echo "</tr>";
}


}
//echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><b><font color='#FF0000'>TOTAL: ".$kbt."&nbsp;</td><td align='center'><b><font color='#FF0000'>".$kbd."</td><td align='center'><b><font color='#FF0000'>".$kbu."</td>";
echo "</table>";

//Finish our HTML needed for NiftyCorners



?>

<br>
</td></tr></table>

</body>

</html>
