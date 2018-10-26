<?php  
/* Name: info.php
 * Purpose: view and edit node information.


 */

//Start session
session_start();

$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Edit Nodes | <?php  echo $net_name; ?></title>
<head>
<script language="javascript" type="text/javascript">
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<script src='../lib/sorttable.js'></script>
<?php 

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid']))  {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/select.php");
        exit();
}

//Includes
include "../lib/style.php";
?>
</head>
<body onload=Nifty("div.comment#tip");>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php 
include "../lib/menu.php";

//Setup database connection
require_once "../lib/connectDB.php";
setTable("node");

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}
?>
<!--
<h2>Node Information List for <?php echo $display_name;?></h2>
-->
  <table width="900"  border="0" cellpadding="0" cellspacing="0" >
  <tr><td align='center'>
<h1><?php echo '<img src="process.png" border=0 ALIGN=ABSMIDDLE>Editing network nodes '.$display_name; ?></h1>
  </td></tr>

  </table>

<!--
<div class="note" id="tip">You can edit node information by clicking on the node's name. <a href="javascript:close()">hide</a></div>
-->
<?php if($ulang=='en') 
echo <<< RESPONSE
<div class="note" id="tip">Click the node to edit or delete them.&nbsp; <a href="javascript:close()">hide notes</a></div>
RESPONSE;
else
echo <<< RESPONSE
<div class="note" id="tip">&#25552;&#31034;&#65306;&#33509;&#38656;&#21024;&#38500;&#25110;&#20462;&#25913;&#33410;&#28857;&#21442;&#25968;&#65292;&#35831;&#28857;&#20987;&#33410;&#28857;&#21517;&#12290; &nbsp; <a href="javascript:close()">&#38544;&#21435;</a></div>
RESPONSE;
?>

<?php 
//Get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysql_query($query, $conn);
if($ulang=='en') {
  if(mysql_num_rows($result)==0) die("<div class=error>The network node has no. <a href=\"../nodes/addnode.php\">Add nodes</a></div>");
} else {
  if(mysql_num_rows($result)==0) die("<div class=error>&#32593;&#32476;&#20013;&#23578;&#26080;&#33410;&#28857;&#12290;&#33509;&#38656;&#21152;&#20837;&#33410;&#28857;&#65292;&#28857;&#20987;<a href=\"../nodes/addnode.php\">&#27492;&#22788;</a>.</div>");
}

//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
$node_fields = array("Node Name" => "name","MAC" => "mac", "Activation Status" => "approval_status");

//Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column.
echo "";
echo "<table class='sortable' border='1'>";

//Output the top row of the table (display names)
echo "<tr class=\"fields\">";
foreach($node_fields as $key => $value) {
//    echo "<td>" . $key . "</td>";
    echo "<td align='center'>";
            if ($value=="name") {
              // node name
              if($ulang=='en') echo "Name";
              else echo "&#33410;&#28857;&#21517;";
            } elseif ($value=="mac") {
              // MAC address
              echo "MAC";
            } elseif ($value=="owner_name") {
              // owner name
              if($ulang=='en') echo "Administrator";
              else echo "&#31649;&#29702;&#21592;&#22995;&#21517;";
            } elseif ($value=="owner_email") {
              // owner email
              echo "Email";
            } elseif ($value=="owner_phone") {
              // owner phone
              if($ulang=='en') echo "Telephone";
              else echo "&#30005;&#35805;";
            } elseif ($value=="owner_address") {
              // owner address
              if($ulang=='en') echo "Address";
              else echo "&#21333;&#20301;&#22320;&#22336;";
            } elseif ($value=="approval_status") {
              // Activation status
              if($ulang=='en') echo "Status";
              else echo "&#21551;&#21160;&#29366;&#24577;";
            } else {
    	      echo $key;
            }
    echo "</td>";
}
echo "</tr>";

//Output the rest of the table
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    if ($row["approval_status"] == "A" ||     //show only activated, pending or deactivated nodes
        $row["approval_status"] == "P" || 
        $row["approval_status"] == "D") {       
        echo "<tr>\n"; 
        foreach($node_fields as $key => $value) {
            echo "<td align='center' height=20>\n";
            if ($value=="name") {
               echo '<img src="editnode.png" border=0 ALIGN=ABSMIDDLE><a href="node_info.php?mac=' . $row["mac"] . '">' . str_replace("*"," ",$row[$value]) . '<br>('.$row['description'].')</a><br>';
            }
            elseif ($value=="approval_status") {    //Translate approval flags into English
                switch ($row[$value]) {
                    case "A": echo '<img src="accept.png" border=0 ALIGN=ABSMIDDLE>'; break;
                    case "P": echo '<img src="clock.png" border=0 ALIGN=ABSMIDDLE>'; break;
                    case "D": echo '<img src="delete.png" border=0 ALIGN=ABSMIDDLE>'; break;
                }
            }
            else {
                echo $row[$value];
            }
            echo "</td>\n";
        }
        echo "</tr>\n";
    }
}
echo "</table>";

//Display NiftyCorners effects
?>
<br>
</td></tr></table>

</body>

</html>
