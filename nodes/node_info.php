<?php
/* Name: node_info.php
 * Purpose: Form to edit node information.

 */

//Start session
session_start();

$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
    //header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}






//Make sure person is logged in
session_start();

if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");



//Select the network from the database and get the values

//






?>


<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<title>Node Settings | <?php  echo $net_name; ?></title>

	<?php include "../lib/style.php"; ?>

  	<script type="text/javascript">
  		function changestatus(){
			if(!confirm('Are you sure?\n\nBackup clients traffic records in this node before delete!')) {
				return false;
			}
            document.editNode.approval_status.value="X";
   			document.editNode.submit();
		}

	</script>
	
</head>
<body onload=Nifty("div.comment");>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php
include "../lib/menu.php";
//determines the value of a boolean in the db
function isChecked($field){
	if ($field==0) return "";
        else if ($field==1) return 'checked="checked" value=1';
	else return "";
}
?>

<table align="center" width=600 cellpadding="4" cellspacing="0" border=0>
<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
<tr><td align='center'>
<?php
//Setup database connection
require_once "../lib/connectDB.php";
setTable("node");

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

$mac_name = $_GET["mac"];
if($ulang=='en') echo <<< PROMPT
<br>
<font style="font-family:Helvetica,Arial,sans-serif; font-size:28px; color:#666666;">Node Settings</font>
PROMPT;
else  echo <<< PROMPT
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0075ad;">&#31649;&#29702;&#21442;&#25968;&#35774;&#32622; (MAC = $mac_name)</font>
PROMPT;

//Get nodes that match MAC address from GET string
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"] . " AND mac='" . $_GET['mac'] . "'";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) {
if($ulang=='en') die("<br><br><div class=error>Node not found on this network.</div>");
else die("<br><br><div class=error>&#33410;&#28857;&#35760;&#24405;&#19981;&#23384;&#22312;&#12290;</div>");
}

$row = mysql_fetch_array($result, MYSQL_ASSOC);

$alerta = $row["alerts"];
$logusers  = $row["log_users"];
$name = str_replace("*"," ",$row['name']);
//Set up variables needed to display current activation status properly
if ($row["approval_status"] == A) {
    $selected_flag_letter = "A";
    $selected_flag = "Activated";
    $other_flag_letter = "D";
    $other_flag = "Deactivated";
}
else {
    $selected_flag_letter = "D";
    $selected_flag = "Deactivated";
    $other_flag_letter = "A";
    $other_flag = "Activated";
}
?>

<form method="POST" action="c_node_info.php" name="editNode">
<input name="mac" type="hidden" value="<?php echo $_GET["mac"];?>">    <!--Need to send MAC address on as POST field-->
<table align="left" cellpadding="4" cellspacing="0" border=0>
    <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
	<tr>
		<td width=120>Node Name:</td>
		<td><input name="name" maxlength=40 value="<?php echo $name;?>"></td>
		<td><div class="comment"><?php if($ulang=='en') echo 'Name of this node.'; else echo '&#33410;&#28857;&#21517;&#19981;&#21487;&#25913;&#21160;&#12290;&#21487;&#23558;&#27492;&#33410;&#28857;&#35760;&#24405;&#21024;&#38500;&#65307;&#20877;&#24314;&#31435;&#26032;&#33410;&#28857;&#12290;'; ?></div></td>
	</tr>
	<tr>
		<td><span class="style1"><?php if($ulang=='en') echo 'Description:'; else echo '&#33410;&#28857;&#25551;&#36848;'; ?></span></td>
		<td><input name="description" maxlength=40 value="<?php echo $row["description"];?>"></td>
		<td><div class="comment"><?php if($ulang=='en') echo 'Optional - Brief description of this node. If filled in, this will be displayed along with node name on the "Status" page.'; else echo '&#33410;&#28857;&#31616;&#21333;&#25551;&#36848;&#12290;&#22914;&#65306; WiFi Lab 505B'; ?></div></td>
	</tr>
                     <tr>

				 <td><span class="style1" style="color: #000000">Node Notes:</span></td>
				 <td><textarea name="nodenotes" style="color: #000000"><?php echo $row["nodenotes"];?></textarea></td>
				 <td><div class="comment">Optional - notes for your own reference.</div></td>
			</tr>

			<tr>
                             <!-- hide the latitude input. Set value to 0 -->
				 <td><span class="style1">Latitude:</span></td>
				 <td><input type="text" name="latitude" value="<?php echo $row["latitude"];?>"></td>
				 <td><div class="comment">Latitude of the node location.</div></td>
			</tr>
			<tr>
                             <!-- hide the longitude input. Set value to 0 -->
				 <td><span class="style1">Longitude:</span></td>
				 <td><input type="text" name="longitude" value="<?php echo $row["longitude"];?>"></td>
				 <td><div class="comment">Longitude of the node location.</div></td>
			</tr>




			<!--			<tr>

				 <td><span class="style1"><img src="anaptyxlogo.png"></span></td>
				 <td><input type="text" name="cover1" value="<?php echo $row["cover1"];?>"></td>
				 <td><div class="comment">Good goverage radius in meters.</div></td>
			</tr>



						<tr>

				 <td><span class="style1"><img src="anaptyxlogo.png"></span></td>
				 <td><input type="text" name="cover2" value="<?php echo $row["cover2"];?>"></td>
				 <td><div class="comment">Regular coverage radius in meters.</div></td>
			</tr>


						<tr>

				 <td><span class="style1"><img src="anaptyxlogo.png"></span></td>
				 <td><input type="text" name="cover3" value="<?php echo $row["cover3"];?>"></td>
				 <td><div class="comment">Poor coverage radius in meters.</div></td>
			</tr> -->



			

<!--	<tr>
		<td><span class="style1">Email</span></td>
		<td><input name="owner_email" maxlength=60 value="<?php echo $row["owner_email"];?>"></td>
                <td><div class="comment"><?php if($ulang=='en') echo 'Email the owner of the node. <b>This email may filter your property nodes Group option.</b>'; else echo '&#31649;&#29702;&#21592;&#30340;&nbsp;Email&nbsp;&#22320;&#22336;&#12290;&#23427;&#20063;&#23558;&#29992;&#20316;&#20026;&#26816;&#32034;&#27492;&#33410;&#28857;&#20998;&#23646;&#30340;&#23376;&#32593;&#32476;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>
	</tr> -->
	<tr>
		<td><span class="style1">Users</span></td>
		<td><input name="log_users" type="checkbox" <?php echo isChecked($logusers) ?>></td>
                <td><div class="comment"><?php if($ulang=='en') echo 'Check this box to track user connections at this node.'; else echo '&#31649;&#29702;&#21592;&#30340;&nbsp;Email&nbsp;&#22320;&#22336;&#12290;&#23427;&#20063;&#23558;&#29992;&#20316;&#20026;&#26816;&#32034;&#27492;&#33410;&#28857;&#20998;&#23646;&#30340;&#23376;&#32593;&#32476;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>
	</tr>
	<tr>
		<td><span class="style1">Alerts</span></td>
		<td><input name="alerts" type="checkbox" <?php echo isChecked($alerta) ?>></td>
                <td><div class="comment"><?php if($ulang=='en') echo 'Check this box to receive email notifications when this node has issues.'; else echo '&#31649;&#29702;&#21592;&#30340;&nbsp;Email&nbsp;&#22320;&#22336;&#12290;&#23427;&#20063;&#23558;&#29992;&#20316;&#20026;&#26816;&#32034;&#27492;&#33410;&#28857;&#20998;&#23646;&#30340;&#23376;&#32593;&#32476;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>
	</tr>
       <tr>
<!--
<?php
if($ulang=='en') echo '<td><span class="style1">State</span></td>';
else echo '<td><span class="style1">&#21551;&#21160;&#25110;&#21024;&#38500;</span></td>';
echo <<< PROMPT

                <td>
                    <SELECT NAME="approval_status">
                    <OPTION VALUE= $selected_flag_letter SELECTED> $selected_flag
                    <OPTION VALUE= $other_flag_letter > $other_flag
                    </SELECT>

PROMPT;
?>
<br>
<input type="button" name="Delete" value="&nbsp; &nbsp; &nbsp;Delete&nbsp; &nbsp; &nbsp;" onClick="changestatus(this);">
</td>
                <td><div class="comment"><?php if($ulang=='en') echo '[Activated] Node enabled. <br>[Deactivated] Node disabled.'; else echo '&#22914;&#26524;&#24744;&#35201;&#23558;&#33410;&#28857;&#31227;&#32622;&#21040;&#19968;&#20010;&#19981;&#21516;&#30340;&#32593;&#32476;&#65292;&#35831;&#23558;&#33410;&#28857;&#21024;&#38500;&#12290;&#22914;&#26524;&#24744;&#26242;&#26102;&#19981;&#24819;&#22312;&#32593;&#32476;&#29366;&#24577;&#21015;&#34920;&#20013;&#26174;&#31034;&#27492;&#33410;&#28857;&#65292;&#21487;&#36873;&#25321;Deactivated&#65288;&#38386;&#32622;&#65289;&#12290;&#38386;&#32622;&#20043;&#21518;&#21487;&#22312;&#27492;&#29992;Activated&#65288;&#21551;&#21160;&#65289;&#26469;&#24674;&#22797;&#29366;&#24577;&#26174;&#31034;&#12290;'; ?></div></td>
        </tr> -->
    <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=3 align=center><input value="Save Changes" type="submit" ></td>
	</tr>
 <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
    </table>
</td></tr>
</table>
</form>
</td></tr></table>
</body>

</html>

