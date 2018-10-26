<?php 
/* Name: password_lost.php
 * Purpose: change the password of a network.

 */
session_start();
$_msg  = $_SESSION['message'] ;
unset($_SESSION['message']);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <?php  include "../lib/menu.php"; ?>
  <?php  include "../lib/validateInput.js"; ?>
  <?php
    require '../lib/connectDB.php';
    setTable('network');
  ?>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Password &amp; Network Recovery</title>
 	<script type="text/javascript">	NiftyLoad=function(){Nifty("div.comment");} </script>
  <LINK REL=STYLESHEET HREF="../lib/style.css" TYPE="text/css">
</head>
<body onLoad="initFormValidation();">
	<form method="POST" action="c_password_lost.php" name="createNetwork"  onsubmit="if(net_email.value == '') {alert('E-Mail is empty.'); return false;} if(!isFormValid()){ alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}">
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
    <table cellpadding="0" cellspacing="0" border=0 width=1040>
<!--
	<form method="POST" action="c_password.php" name="createNetwork" onsubmit="if(!isFormValid()){ alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}">
-->
      <tr><td align='center'>
      <font style="font-family:Helvetica,Arial,sans-serif; font-size:28px; color:#666666;"><br><?php if($ulang=='en') echo 'Lost Password or Network Name'; else echo '<b>&#20462;&#25913;&#32593;&#32476;&#24080;&#25143;&#30340;&#30331;&#24405;&#23494;&#30721;</b><br>('.$display_name.')'; ?></font>
      </td></tr>
      <tr><td height=10></td></tr>
    </table>

    <table cellpadding="0" cellspacing="0" border=0 width=1040>
          <?php 
            if ($_msg == "E1") {
              if($ulang=='en') echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; Error: The passwords you entered did not match!</div></td></tr>";
              else echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; &#25805;&#20316;&#38169;&#35823;&#65306;&#20004;&#27425;&#23494;&#30721;&#36755;&#20837;&#19981;&#19968;&#33268;&#12290;</div></td></tr>";
            } else if ($_msg == "E2") {
              if($ulang=='en') echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; (Error 7118) You did not provide the correct current password.</div></td></tr>";
              else  echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; &#21407;&#23494;&#30721;&#36755;&#20837;&#38169;&#35823;&#12290;</div></td></tr>";
            }
          ?>

    </table>

		<div class="note" id="tip">To retrieve your password, please enter both your network name and the network admin e-mail. <br> If you are unsure of your network name, please enter only your e-mail address to recover it.</div>

	
	
	
	
    <table id="edit_net">
	<tr>
		<tr>
			<td><?php if($ulang=='en') echo 'Network Name:'; else echo '&#26032;&#23494;&#30721;'; ?></td>
			<td><input name="net_name" type="text"></td>
		</tr>
		<tr>
			<td><?php if($ulang=='en') echo 'E-Mail:'; else echo '&#37325;&#36755;&#26032;&#23494;&#30721;' ?> </td>
			<td><input id="net_email" name="net_email" type="text" mask="email"></td>
		<tr>
<?php 
if($ulang=='en') echo <<< PROMPT
			<td></td>
			<td><input name="submit" value="Submit" type="submit"></td>
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
