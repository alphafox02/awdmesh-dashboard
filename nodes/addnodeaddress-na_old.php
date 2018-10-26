<?php 
/* Name: addnode.php
 * Purpose: add a node to network


 */

//Set up session, get session variables
session_start();
$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

if($utype!='admin') {
		header("Location: ../entry/select.php");
}

?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Add Multiples Nodes | <?php  echo $net_name; ?></title>
<?php  
include "../lib/style.php";
include "../lib/mapkeys.php";
?>
<!--
<script type="text/javascript" src="../lib/map.js"></script>
-->

<script type="text/javascript">
  function show_msg (){
	document.getElementById("pgmsg").style.display="";
  }
  function hide_msg (){
	document.getElementById("pgmsg").style.display="none";
  }

  function validNode(node_name) {
    if (node_name.match(/^[A-Z 0-9_-]+$/i)) {
      return true;
    } else {
      return false;
    }
  }
  function validEmail(email) {
    if (email.match(/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i)) {
      return true;
    } else {
      return false;
    }
  }

  function clear_req(form) {
    form.node_name.style.border='1px solid #7F9DB9';
    form.mac.style.border='1px solid #7F9DB9';
    form.owner_email.style.border='1px solid #7F9DB9';
  }

  	function localizadireccion(direccion) {
		geocoder = new GClientGeocoder();
		if (geocoder) {
			geocoder.getLatLng(direccion.value, function(point) {if (point) {document.addnode_manual.longitude.value = point.x; document.addnode_manual.latitude.value = point.y;}});
		}
 }

</script>

</head>
<body bgcolor="#FFFFFF" align="center" onLoad="Nifty('div.comment');hide_msg();">
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php
  include '../lib/menu.php';
// Load the language pack
if($ulang !='en') require '../lib/lang_misc.php';

//Setup database connection
require_once "../lib/connectDB.php";
setTable("node");

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {
  if($resArray['net_name']=="") {
    $display_name = 'undefined';
  } else {
    $display_name = $resArray['net_name'];
  }
}
else {$display_name = $resArray['display_name'];}
 ?>

<?php
// Begin manual form ------------------
// seanyliu, MIT '10
?>

<!--
MANUAL FORM:
-->
<form method="POST" action="c_addnodeaddress.php" name="addnode_manual">
<center>
  <table width="600"  border="0" cellpadding="0" cellspacing="0" >
  <tr><td align='center'>
      <font style="font-family:Helvetica,Arial,sans-serif; font-size:23px; color:#666666;"><br><br><?php if($ulang=='en') echo 'Add Node(s) by Address'; else echo '<b>&#28155;&#21152;&#33410;&#28857;</b> (&#32593;&#32476;&#21517;&#65306; '.$display_name.')'; ?></font>
      <br><br><a href="addnode.php">Add Nodes by Map</a><br><br>
  </td><td width=0></td>
  </td></tr>
  <tr>
  <td  colspan=2 align='center'>
  </td></tr>
  <tr><td height=20></td></tr>
  </table>
  <table width="600"  border="0" cellpadding="0" cellspacing="0" >
  <tr id="pgmsg"><td><div name='msgbody' id='msgbody' class='error'>Default Error</div>
  </td></tr>
  </table>

<div name='pgbody' id='pgbody'>
  <table width="600"  border="0" cellpadding="0" cellspacing="0" id="node">
			<tr><td height=10></td></tr>
			<tr>
				 <td width=125><span class="style1"><?php if($ulang=='en') echo 'Node Name:'; else echo '&#33410;&#28857;&#21517;'; ?></span></td>
				 <td><input type="text" size="20" name="name" required="1"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Alphanumeric characters only - no spaces. If adding multiple nodes, input only one name and the CloudController will automatically group and label all newly added devices.'; else echo '&#35831;&#29992;&#33521;&#25991;&#23383;&#27597;&#65292;&#25968;&#23383;&#21644;&#19979;&#21010;&#32447;&#12290;&#19981;&#33021;&#21547;&#26377;&#31354;&#26684;&#12290;'; ?></div></td>
			</tr><tr>
				 <td><span class="style1"><?php if($ulang=='en') echo "MAC Address(es):"; else echo '&#35774;&#22791;&#21495;&#30721;'; ?></span></td>
				 <td><input type="text" size="20" name="mac"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo "Node MAC address (formatted xx:xx:xx:xx:xx:xx). For multiple MAC addresses, separate by commas.<br><br>Example:<b> 00:02:4B:EA:3F:79,00:02:4B:EA:3F:80</b>"; else echo '&#35265;&#35774;&#22791;&#24213;&#37096;&#12290;&#22914;&#65306; 00:02:4B:EA:3F:79'; ?></div></td>
			</tr>			<tr>
				 <td><span class="style1"><?php if($ulang=='en') echo 'E-Mail:'; else echo '&#31649;&#29702;&#21592;&#32852;&#32476;&#37038;&#31665;'; ?></span></td>
				 <td><input type="text" size="20" name="email"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Network administrator e-mail address.'; else echo '&#31649;&#29702;&#21592;&#30340;&nbsp;Email&nbsp;&#22320;&#22336;&#12290;&#23427;&#20063;&#23558;&#29992;&#20316;&#20026;&#26816;&#32034;&#27492;&#33410;&#28857;&#20998;&#23646;&#30340;&#23376;&#32593;&#32476;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>
			</tr><tr>
				 <td><span class="style1"><?php if($ulang=='en') echo 'Address:'; else echo '&#32852;&#32476;&#22320;&#22336;'; ?></span></td>
				 <td><input type="text" size="20" value="" name="address" onChange="localizadireccion(this);"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Address to place node. Minimum required is city & state. <br><br><b>Example:<br> 100 Federal Street, Boston, MA 22202</b>'; else echo '&#31649;&#29702;&#21592;&#30340;&nbsp;Email&nbsp;&#22320;&#22336;&#12290;&#23427;&#20063;&#23558;&#29992;&#20316;&#20026;&#26816;&#32034;&#27492;&#33410;&#28857;&#20998;&#23646;&#30340;&#23376;&#32593;&#32476;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>

			</tr><tr>
			
<!--
			<tr>
				 <td><span class="style1"><?php if($ulang=='en') echo 'Notes'; else echo '&#33410;&#28857;&#25551;&#36848;'; ?></span></td>
				 <td><input type="text" size="20"  name="description"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Notas acerca de este nodo.'; else echo '&#33410;&#28857;&#31616;&#21333;&#25551;&#36848;&#12290;&#22914;&#65306; WiFi Lab 505B'; ?></div></td>
			</tr><tr>

				 <td><span class="style1"><?php if($ulang=='en') echo 'Latitud'; else echo '&#33410;&#28857;&#25551;&#36848;'; ?></span></td>
				 <td><input type="text" size="20"  name="latitude" value="40"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Latitud del nodo.'; else echo '&#33410;&#28857;&#31616;&#21333;&#25551;&#36848;&#12290;&#22914;&#65306; WiFi Lab 505B'; ?></div></td>

			</tr><tr>

				 <td><span class="style1"><?php if($ulang=='en') echo 'Longitud'; else echo '&#33410;&#28857;&#25551;&#36848;'; ?></span></td>
				 <td><input type="text" size="20"  name="longitude" value="-3"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Longitud del nodo.'; else echo '&#33410;&#28857;&#31616;&#21333;&#25551;&#36848;&#12290;&#22914;&#65306; WiFi Lab 505B'; ?></div></td>


			</tr><tr>
				 <td><span class="style1"><?php if($ulang=='en') echo 'Propietario'; else echo '&#31649;&#29702;&#21592;&#21517;&#31216;'; ?></span></td>
				 <td><input type="text" size="20" name="owner_name"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Propietario del nodo, que puede ser diferente del administrador de la red.'; else echo '&#21517;&#31216;&#35831;&#29992;&#33521;&#25991;&#12289;&#25968;&#23383;&#12289;&#25110;ASCII&#20195;&#30721;&#12290;&#33410;&#28857;&#31649;&#29702;&#20154;&#21592;&#21487;&#20197;&#19981;&#21516;&#20110;&#24635;&#20307;&#32593;&#32476;&#30340;&#31649;&#29702;&#20154;&#21592;&#12290;&#33410;&#28857;&#31649;&#29702;&#20154;&#30340;&#32852;&#32476;&#37038;&#31665;&#22320;&#22336;&#23558;&#20316;&#20026;&#20998;&#32452;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>
			</tr><tr>
				 <td><span class="style1"><?php if($ulang=='en') echo 'Telefono'; else echo '&#32852;&#32476;&#30005;&#35805;'; ?></span></td>
				 <td><input type="text" size="20" name="owner_phone"></td>
				 <td><div class="comment"><?php if($ulang=='en') echo 'Telefono del propietario del nodo.'; else echo '&#31649;&#29702;&#21592;&#30340;&nbsp;Email&nbsp;&#22320;&#22336;&#12290;&#23427;&#20063;&#23558;&#29992;&#20316;&#20026;&#26816;&#32034;&#27492;&#33410;&#28857;&#20998;&#23646;&#30340;&#23376;&#32593;&#32476;&#30340;&#26631;&#35782;&#12290;'; ?></div></td>

			</tr>


-->

<td><input type="hidden" name="user_type" value="<?php echo $utype;?>">
<input type="hidden" name="longitude" value="">
<input type="hidden" name="latitude" value="">
<input type="hidden" name="form_name" value="addNode">
<input type="hidden" name="net_name" value="<?php  print $net_name?>">
</td>

				 <td align="right"><input name="submit" type="submit" onmouseover="localizadireccion(this.form.address);" onclick="localizadireccion(this.form.address);" name="Add" value="<?php if($ulang=='en') echo 'Add Node(s)'; else echo '&#21152;&#20837;&#33410;&#28857;'; ?>"></td></tr>
    </table>
</div>

</center>
</form>



<div align="center" id="top">
  <input name="net_name" id="net_name" type=hidden value="<?php  print $net_name?>" >
</div>
</td></tr></table>



<br><br>
</body>
</html>
