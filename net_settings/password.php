<?php 
/* Name: create.php
 * Purpose: change the password of a network.

 */


 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <?php  include "../lib/menu.php"; ?>
  <?php  include "../lib/validateInput.js"; ?>
  <?php
    require_once '../lib/connectDB.php';
    setTable('network');

    // Get the network name
    $result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
    $resArray = mysql_fetch_assoc($result);
    if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
    else {$display_name = $resArray['display_name'];}
  ?>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Change Password | <?php  echo $display_name; ?></title>
  <LINK REL=STYLESHEET HREF="../lib/style.css" TYPE="text/css">
</head>
<body onLoad="initFormValidation();">
	<form method="POST" action="c_password.php" name="createNetwork" >
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
    <table cellpadding="0" cellspacing="0" border=0 width=1040>
<!--
	<form method="POST" action="c_password.php" name="createNetwork" onsubmit="if(!isFormValid()){ alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}">
-->
      <tr><td align='center'>
      <font style="font-family:Helvetica,Arial,sans-serif; font-size:25px; color:#666666;"><?php if($ulang=='en') echo '<br>Change Network Password'; else echo '<b>&#20462;&#25913;&#32593;&#32476;&#24080;&#25143;&#30340;&#30331;&#24405;&#23494;&#30721;</b><br>('.$display_name.')'; ?></font>
      </td></tr>
      <tr><td height=10></td></tr>
    </table>

    <table cellpadding="0" cellspacing="0" border=0 width=1040>
          <?php 
            if ($_msg == "E1") {
              if($ulang=='en') echo "<tr><td align='center'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; Confirm the new password.</div></td></tr>";
              else echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; &#25805;&#20316;&#38169;&#35823;&#65306;&#20004;&#27425;&#23494;&#30721;&#36755;&#20837;&#19981;&#19968;&#33268;&#12290;</div></td></tr>";
            } else if ($_msg == "E2") {
              if($ulang=='en') echo "<tr><td align='center'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; The password you entered is incorrect.</div></td></tr>";
              else  echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; &#21407;&#23494;&#30721;&#36755;&#20837;&#38169;&#35823;&#12290;</div></td></tr>";
            }
          ?>

    </table>

    <table id="edit_net">
	<tr>
		<td><?php if($ulang=='en') echo 'Current password'; else echo '&#24403;&#21069;&#23494;&#30721;'; ?></td>
			<td><input name="old_pass" type="password" required="1" mask="keyPWD"></td>
		</tr>
		<tr>
			<td><?php if($ulang=='en') echo 'New password'; else echo '&#26032;&#23494;&#30721;'; ?></td>
			<td><input name="new_pass" type="password" required="1" mask="keyPWD"></td>
		</tr>
		<tr>
			<td><?php if($ulang=='en') echo 'Verify new password'; else echo '&#37325;&#36755;&#26032;&#23494;&#30721;' ?> </td>
			<td><input name="confirm_pass" type="password" required="1" mask="keyPWD"></td>
		<tr>
<?php 
if($ulang=='en') echo <<< PROMPT
			<td></td>
			<td><input name="submit" value="Change Password" type="submit"></td>
PROMPT;
else echo <<< PROMPT
			<td></td>
			<td><input name="submit" value="&nbsp; &#35774;&#31435;&#26032;&#23494;&#30721; &nbsp;" type="submit"></td>
PROMPT;
?>
		</tr>
	</table>
	</form>
</td></tr>
</table>
	</body>
</html>

<!-- May need to add the abilit of using "UPDATE" command to reset the password
     mySQL query command is in a format as below (OM is using MD5 to encrypt)
     UPDATE `om_sandbox`.`network` SET `password` = MD5( 'mypassword' ) WHERE `network`.`id` =28 LIMIT 1 ;
-->
