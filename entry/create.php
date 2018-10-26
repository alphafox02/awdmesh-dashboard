<?php 
/* Name: create.php
 * Purpose: create a new network in the dashboard.

 */

  session_start();
  $_msg  = $_SESSION['message'] ;
  $_name = $_SESSION['net_name'] ;
//  if($_SESSION['masteremail']!="") {$_email=$_SESSION['masteremail'];$masterlogin="1";} else {$_email= $_SESSION['email1'];$masterlogin="0";}
  if($_SESSION['masterlogin']=="9") {$masterlogin="1";$masternetid=$_SESSION['masternetid'];} else {$masterlogin="0";$masternetid="0";}

  $_email2= $_SESSION['email2'] ;
  unset($_SESSION['message']);
  unset($_SESSION['net_name']);
  unset($_SESSION['email1']);
  unset($_SESSION['email2']);
 ?>

<!DOCTYPE html>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
    <head>
        <title>Create New Network</title>
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script type="text/javascript" src="js/cufon-yui.js"></script>
        <script type="text/javascript" src="js/Mr_Jones_400.font.js"></script>
        <script type="text/javascript">
            Cufon.replace('h1')('h2')('h3')('h4')('h5')('h6'); 
        </script>
        <?php  include "../lib/validateInput.js"; ?>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  	 <script type="text/javascript">
  	 NiftyLoad=function(){
  	 Nifty("div.comment");
		}

	</script>
        <style type="text/css">
<!--
.style6 {
	color: #333333;
	font-weight: bold;
}
.style7 {
	font-size: 24px;
	font-weight: bold;
	color: #666666;
}
.style8 {color: #666666}
.style9 {color: #CC3300}
.style11 {color: #666666; font-size: 10px; }
-->
        </style>
</head> 
<body onLoad="initFormValidation();">
<?php
echo <<< RESPONSE
	<form method="POST" action="c_create.php" onsubmit="if(!isFormValid()){ alert('The highlighted fields contain errors. Try again.');return false;}" name="createNetwork">
RESPONSE;
?>

        
        <div id="rail_top">
            <p>
                <a href="/status/view.php" style="color:#8AD355"><strong>Return to your network</strong></a> </p>
</div>
        
        <div id="wrap">
            
            <div id="page">
                	<form method="POST" action="c_create.php"  name="createNetwork">
                      <div align="center">
                      <!--  <p><img src="img/awdauthpagelogo.jpg" width="256" height="95"></p> -->
                        <table width="500" border="0">
                          <tr>
                            <td height="66"><div align="center" class="style7">Create New Network</div></td>
                          </tr>
                          <tr>
                            <td><div align="left" class="style8">Fill in the following information to create your new network.</div></td>
                          </tr>
                        </table>
                        <br><?php 
            if ($_msg == "E1") {
              echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; <center>The passwords entered do not match.</center></div></td></tr>";
            } else if ($_msg == "E2") {
             echo "<tr><td align='left'><div name='msgbody' id='msgbody' class='error'>&nbsp; &nbsp; <center>The network name $_name is taken, choose a different network name.</center></div></td></tr>";
            }
            ?><br>
                        </p>
                        <table width=500 align="center" id=edit_net>
                          <tr>
            
                            <!-- Network name -->
                            <td width="112"><span class="style6">Network Name:</span></td>
                        <td width="192"><input name="net_name" required="1" mask="keyID"</td>
                        <td width="253" bgcolor="#BFF0A5"><div class="comment">Login/Network Name</div></td>
                          </tr>
                          <tr>
                            <!-- Password -->
                            <td><span class="style6">Password:</span></td>
                            <td><input name="password" required="1" mask="keyPWD" type="password"></td>
                            <td bgcolor="#BFF0A5"><div class="comment">Administrator Password</div></td>
                          </tr>
                          <tr>
                            <!-- confirm Password -->
                            <td><span class="style6">Confirm Password:</span></td>
                            <td><input name="confirm_pass" required="1" type="password"></td>
                            <td bgcolor="#BFF0A5"><div class="comment">
                            Confirm Password</div></td>
                          <tr>
                              <!-- Contact email -->
                              <td><span class="style6">E-Mail:</span></td>
                              <td><input name="email1" required="1" mask="email" value="<?php echo $_email;?>"></td>
                              <td bgcolor="#BFF0A5"><div class="comment">Admin E-Mail Address</div></td>
                          </tr>
                          <tr>
                            <!-- Alerts email -->
                            <td><span class="style6">E-Mail for Alerts:</span></td>
                            <td><input name="email2" mask="email" value="<?php echo $_email2;?>"></td>
                            <td bgcolor="#BFF0A5"><div class="comment">E-Mail for Network Notifications. Separate multiple addresses with spaces.</div></td>
                          </tr>
                          <tr>
                            <!-- Network location -->
                            <td><span class="style6">Network Address:</span></td>
                            <td><input name="net_location" value=""></td>
                            <td bgcolor="#BFF0A5"><div class="comment">
                            Physical location of network. If you don't have an address enter ZIP code, City/Country or Lat/Long.</div></td>
                          </tr>
                          <tr>
                            <td></td>
                            <input type="hidden" name="master_login" value="<?php echo $masterlogin; ?>">
                            <input type="hidden" name="master_netid" value="<?php echo $masternetid; ?>">
                            <td><input name="submit" type="submit" value="Create Network">
                              <br>
                            <input name="reset" type="reset" value="Reset" ></td>
                            <td><span class="style11">* Fields highlighted in<span class="style9"> red</span> are mandatory.</span></td>
                          </tr>
                          <tr></tr>
                        </table>
                        </td>
                        </tr>
                        </table>
                            </div>
               	</form>
                    <div align="center">
                      <!-- Typography Test -->
                          </div>
              <h1 align="center">&nbsp;</h1>
          </div>
            
</div>
        
        <div id="rail_bottom">
    <div align="center"><font color="#5C8B38">Copyright © 2010 AWD. All rights reserved.</font></div>
        
    <script type="text/javascript">Cufon.now();</script>
    </body>
</html>