<?php  
/* Name: view.php
 * Purpose: master view for network settings.

 * Modified by Trigmax/MeshConnect Staff
 * Last Modified: Jan. 10, 2009

 * Written By: Shaddi Hasan, Mac Mollison, Ashton Mickey
 * Last Modified: November 7, 2008
 *
 * (c) 2008 Orange Networking.
 *  
 * This file is part of OrangeMesh.
 *
 * OrangeMesh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version. This license is similar to the GNU
 * General Public license, but also requires that if you extend this code and
 * use it on a publicly accessible server, you must make available the 
 * complete source source code, including your extensions.
 *
 * OrangeMesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with OrangeMesh.  If not, see <http://www.gnu.org/licenses/>.
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
        header("Location: ../select.php");
        exit();
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Nodalis Meshcontroller | Estado de la red</title>

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
require_once "../lib/connectDB.php";
setTable("node");
include '../lib/toolbox.php';

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

if($ulang=='en') {
echo <<<TITLE
<table width=1040><tr><td align='right' height=80>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#ff9900;">Estado de la red $display_name</font>
</td>
<td width=30> </td>
<td align='left' width=300><a href="view.php">Menos info</a></td>

</tr></table>
<div class="note" id="tip">Los nodos en
<font style="color:ff3300;"><b>color rojo</b></font> tienen problemas.<br>
Los nodos en <b>negrita</b> (saltos=0) son gateways.<br>Haga click en los nombres de los campos para ordenarlos.<br>
<a href="javascript:close()">Ocultar nota</a></div>
TITLE;

//Get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>There are no nodes associated with this network yet. Please <a href=\"../nodes/addnode.php\">add nodes</a>.</div>");

} else {
echo <<<TITLE
<table width=1040><tr><td align='right' height=80>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#ff9900;">&#32593;&#32476;&#36816;&#34892;&#29366;&#24577; &nbsp; (&#32593;&#32476;&#21517;&#65306;$display_name)</font>
</td>
<td width=30> </td>
<td align='left' width=300><a href="view.php">&#22522;&#26412;&#21442;&#25968;</a></td>
</tr></table>
TITLE;

//Get all fields from "node" table that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>&#30446;&#21069;&#32593;&#32476;&#19978;&#23578;&#26410;&#21152;&#20837;&#33410;&#28857;&#12290;&#33509;&#38656;&#22686;&#21152;&#33410;&#28857;&#65292;&#35831;<a href=\"../nodes/addnode.php\">&#28857;&#20987;&#27492;&#22788;</a>.</div>");

}


//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
//-----
// Added "version" as value to the array, which is the index to node properties
// By MeshConnect Staff.
//-----
$node_fields = array("Node Name" => "name","Uptime" => "uptime",
  "Quality" => "gw-qual","Hops" => "hops","Down kb" => "kbdown","Up kb" => "kbup",
  "Last Checkin" => "time", "MAC" => "mac", "Version"=>"version");

$node_adv_fields = array("Node Name"=>"name", "Hops"=>"hops", "Quality"=>"gw-qual", "Network Trans. Rate"=>"NTR",
  "Ping RTT"=>"RTT", "RSSI"=>"rssi", "Number of Clients"=>"users",
  "MAC"=>"mac","IP"=>"ip", "Ruta a GW"=>"routes", "Clients Data"=>"top_users");

echo "<table class='sortable' border='1'>";

//Output the top row of the table (display names)
// echo "<td>" . $key . "</td>";
echo "<tr class=\"fields\">";
foreach($node_adv_fields as $key => $value) {
    echo "<td align='center'>";
            if ($value=="name") {
              // node name
              if($ulang=='en')
              echo "Nombre nodo y notas";
              else
              echo "&#33410;&#28857;&#21517;/&#25551;&#36848;";
            } elseif ($value=="hops") {
              // number of hops to g/w
              if($ulang=='en')
              echo "Saltos";
              else
              echo "&#36339;&#25509;";
            } elseif ($value=="gw-qual") {
              // connection quality to g/w
              if($ulang=='en')
              echo "Calidad enlace";
              else
              echo "&#36830;&#25509;&#36136;&#37327;";
            } elseif ($value=="NTR") {
              if($ulang=='en')
              echo "Rate";
              else
              echo "NTR";
            } elseif ($value=="RTT") {
              echo "Ping RTT";
            } elseif ($value=="rssi") {
              echo "RSSI";
            } elseif ($value=="users") {
              if($ulang=='en')
              echo "Usuarios";
              else 
              echo "&#32456;&#31471;&#20010;&#25968;";
            } elseif ($value=="mac") {
              // MAC address
              echo "MAC";
            } else {
    	      echo $key;
            }
    echo "</td>";
}

echo "</tr>";

//Output the rest of the table
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    if ($row["approval_status"] == "A") {    //show only activated nodes
        if($currentTime - strtotime($row['time']) >= $OK_DOWNTIME) {
    	    echo "<tr class=\"down\">";
        }
        else {
    	    echo "<tr>";
        }
        foreach($node_adv_fields as $key => $value) {
            echo "<td align='center'>";
            if ($value=="name") {
              if ($row["gateway_bit"]==1) 
                echo "<b>" . $row[$value] ."<br>(". $row["description"]. ")</b>";                      
              else 
                echo  $row[$value] . "<br>(". $row["description"].")";                      
            }
            elseif ($value=="hops" && $row["gateway_bit"]==1) {
                echo "0";                      
            }
            elseif ($value=="gw-qual") {    //Convert rank from x {x | 0 < x < 255} to %
                echo floor(100 * ($row[$value] / 255)) . "%";
            }
            else {
                echo $row[$value];
            }
            echo "</td>";
        }
        echo "</tr>";
    }
}
echo "</table>";

//Finish our HTML needed for NiftyCorners
?>
<br>
</td></tr></table>
</body>

</html>
