<?php 
/* Name: edit.php
 * Purpose: edit network settings.

 */

//Make sure person is logged in
session_start();

if ($_SESSION['user_type']!='admin') 
	header("Location: ../entry/login.php?rd=net_settings/edit");

//Set up database connection
require '../lib/connectDB.php';
setTable('network');

//Select the network from the database and get the values
$netid = $_SESSION["netid"];
$query = "SELECT * FROM ".$dbTable." WHERE id='".$netid."'";
$result = mysql_query($query, $conn);
$resArray = mysql_fetch_array($result, MYSQL_ASSOC);

//Get all the current values from the database
$net_name = $resArray['net_name'];
$display_name = $resArray['display_name'];
$email1 = $resArray['email1'];
$email2 = $resArray['email2'];
$ap1_essid = $resArray['ap1_essid'];
$ap1_key = $resArray['ap1_key'];
$download_limit = $resArray['download_limit'];
$upload_limit = $resArray['upload_limit'];
$access_control_list = $resArray['access_control_list'];
$splash_enable = $resArray['splash_enable'];
$splash_redirect_url = $resArray['splash_redirect_url'];
$splash_idle_timeout = $resArray['splash_idle_timeout'];
$splash_force_timeout = $resArray['splash_force_timeout'];
$ap2_enable = $resArray['ap2_enable'];
$ap2_hide   = $resArray['ap2_hide'];
$ap2_essid = $resArray['ap2_essid'];
$ap2_key = $resArray['ap2_key'];
$node_pwd = $resArray['node_pwd'];
$lan_block = $resArray['lan_block'];
$ap1_isolate = $resArray['ap1_isolate'];
$ap2_isolate = $resArray['ap2_isolate'];

$stand_alone = $resArray['stand_alone'];

$test_firmware_enable = $resArray['test_firmware_enable'];
// test_firmware_enable is not yet used
$radio_channel = $resArray['radio_channel'];
$olsr_enable = $resArray['olsr_enable'];
$ssl_enable = $resArray['ssl_enable'];
$dashboard_url = $resArray['dashboard_url'];
// new parameters 2/25/09
$use_node  = $resArray['use_node'];
$cp_handler= $resArray['cp_handler'];
$spl_page  = $resArray['spl_page'];
$spl_gwname  = $resArray['spl_gwname'];
$spl_logo  = $resArray['spl_logo'];
$access_disable_list  = $resArray['access_disable_list'];
$radius_svr_1 = $resArray['radius_svr_1'];
$radius_svr_2 = $resArray['radius_svr_2'];
$radius_secret= $resArray['radius_secret'];
$radius_nasid = $resArray['radius_nasid'];
$uam_server = $resArray['uam_server'];
$uam_secret = $resArray['uam_secret'];
$uam_url = $resArray['uam_url'];
$uam_domain = $resArray['uam_domain'];
$frz_version = $resArray['frz_version'];
$strict_mesh = $resArray['strict_mesh'];
// new parameters 9/22/09
$custm_sh_on = $resArray['custm_sh_on'];
$custm_sh_url = $resArray['custm_sh_url'];

//Check if the user just updated the network
$updated = $_SESSION['updated'];
unset($_SESSION['updated']);
$created = $_SESSION['created'];
unset($_SESSION['created']);
$_MSG    = $_SESSION['message'];
unset($_SESSION['message']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--
 * (c) 2009 Trigmax Solutions, LLC, MeshConnect
 * Modified by staff, Trigmax Solutions, LLC
 * 
 * (c) 2008 Open-Mesh, Inc. and Orange Networking.
 * Written By: Shaddi Hasan, Mike Burmeister-Brown, Ashton Mickey
 * Last Modified: November 7, 2008
-->
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Edit Network</title>
	<?php include '../lib/style.php';?>
 	<?php  include "../lib/validateInput.js"; ?>

	<script type=text/javascript>

	function show_acc (){
		document.getElementById("acc_name").style.display="";
		document.getElementById("dis_name").style.display="none";
		document.getElementById("changepw").style.display="";
		document.getElementById("m_email").style.display="";
                // disable showing the second email. Useless.
		document.getElementById("c_email").style.display="none";
  	}
  	function hide_acc (){
		document.getElementById("acc_name").style.display="none";
		document.getElementById("dis_name").style.display="none";
		document.getElementById("changepw").style.display="none";
		document.getElementById("m_email").style.display="none";
		document.getElementById("c_email").style.display="none";
  	}

        // Set the default fields for CoovaAAA
	function set_cp_readonly () {
            document.editNetwork.radius_svr_1.readOnly = true;
            document.editNetwork.radius_svr_1.value = "rad01.coova.org";

            document.editNetwork.radius_svr_2.readOnly = true;
            document.editNetwork.radius_svr_2.value = "rad02.coova.org";

            document.editNetwork.radius_nasid.readOnly = true;
            document.editNetwork.radius_nasid.value = "Open-Mesh";

            document.editNetwork.uam_server.readOnly = true;
            document.editNetwork.uam_server.value = "coova.org";

            document.editNetwork.uam_secret.readOnly = true;
            document.editNetwork.uam_secret.value = ""; // Leave blank

            document.editNetwork.uam_url.readOnly = true;
            document.editNetwork.uam_url.value = "/app/uam/chilli";
        }

	function def_spl_templt () {
            document.editNetwork.spl_page.value = "wifimesh.trigmax.com/meshconnect/lib/splash.txt";
        }

	function def_cp5_domain () {
            document.editNetwork.uam_domain.value = "coova.org,.facebook.com,.recaptcha.net,.fbcdn.net,open-mesh.com";
        }

	function set_lanblk (lanb_value) {
          document.editNetwork.lan_block.value = lanb_value;
        }

	function set_chilli (cp_n_value) {
          document.editNetwork.cp_handler.value = cp_n_value;
          // set the fields to default value for CoovaAAA
          if (cp_n_value == '5') {
            // call a function to for the readonly fields
            set_cp_readonly();
          } else {
            document.editNetwork.radius_svr_1.readOnly = false;
            document.editNetwork.radius_svr_1.value = "";

            document.editNetwork.radius_svr_2.readOnly = false;
            document.editNetwork.radius_svr_2.value = "";

            document.editNetwork.radius_nasid.readOnly = false;
            document.editNetwork.radius_nasid.value = "";

            document.editNetwork.uam_server.readOnly = false;
            document.editNetwork.uam_server.value = "";

            document.editNetwork.uam_secret.readOnly = false;
            document.editNetwork.uam_secret.value = ""; // Leave blank

            document.editNetwork.uam_url.readOnly = false;
            document.editNetwork.uam_url.value = "";
          }
        }

	function chk_portal () {
          var cp_v = document.editNetwork.cp_handler.value;
          if (cp_v == '1') {
            document.getElementById("sel_cp_ng").style.display="";
            document.getElementById("sel_cp_ch").style.display="none";
            show_nodog();
            hide_chilli();
          } else {
            show_portal();
            if (cp_v == '5') {
              set_cp_readonly();
            }
          }
        }

	function sel_portal () {
          var cp_v = document.editNetwork.cp_handler.value;
          if (cp_v == '1') {
            // change to chilli (default to type 6)
            document.editNetwork.cp_handler.value="6";
          }
          show_portal();
        }

	function show_portal () {
          document.getElementById("sel_cp_ng").style.display="none";
          document.getElementById("sel_cp_ch").style.display="";
//alert(" sel_portal cp_handler="+ document.editNetwork.cp_handler.value);
          hide_nodog();
          show_chilli();
          // set the radio according to the cp_handler
          var cp_name  = "chi_aaa";
          if (document.editNetwork.cp_handler.value=="6") {
            cp_name  = "chi_xxx";
          }
          // set the radio selection for "chilli_type"
          var radioObj = document.editNetwork.chilli_type;
	  for(var i = 0; i < radioObj.length; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == cp_name) {
			radioObj[i].checked = true;
		}
	  }
        }

	function init_lanblk() {
          var nb_name  = "blk_none";
          <?php 
            if ($lan_block =='1') {
              echo "nb_name = 'blk_all';";
            } else if ($lan_block =='2') {
              echo "nb_name = 'blk_ap1';";
            } else if ($lan_block =='3') {
              echo "nb_name = 'blk_ap2';";
            }
          ?>
          var rObj = document.editNetwork.lanblk_type;
	  for(var i = 0; i < rObj.length; i++) {
		rObj[i].checked = false;
		if(rObj[i].value == nb_name) {
			rObj[i].checked = true;
		}
	  }
        }

	function sel_nodog(){
          document.editNetwork.cp_handler.value="1";
		document.getElementById("sel_cp_ng").style.display="";
		document.getElementById("sel_cp_ch").style.display="none";
          show_nodog();
          hide_chilli();
        }
	function show_AP_1(){
          document.getElementById("net_ssid").style.display="";
          document.getElementById("net_key").style.display="";
          document.getElementById("use_node").style.display="";
          var handler_v = document.editNetwork.cp_handler.value;
          if( handler_v=="1") { // select "nodogsplash" 
		document.getElementById("sel_cp_ng").style.display="";
		document.getElementById("sel_cp_ch").style.display="none";
                show_nodog();
                hide_chilli();
          } else { // select "Other Chilli Portal"
		document.getElementById("sel_cp_ng").style.display="none";
		document.getElementById("sel_cp_ch").style.display="";
                hide_nodog();
                show_chilli();
          }
  	}
  	function hide_AP_1(){
		document.getElementById("net_ssid").style.display="none";
		document.getElementById("net_key").style.display="none";
		document.getElementById("use_node").style.display="none";

		document.getElementById("sel_cp_ng").style.display="none";
		document.getElementById("sel_cp_ch").style.display="none";

                hide_nodog();
                hide_chilli();
  	}

  	function show_nodog(){
		document.getElementById("white_ls").style.display="";
		document.getElementById("black_ls").style.display="";
		document.getElementById("up_limit").style.display="";
		document.getElementById("dw_limit").style.display="";
		document.getElementById("spl_gwname").style.display="";
		document.getElementById("spl_page").style.display="";
		document.getElementById("splash_enable").style.display="";
		document.getElementById("splash_redirect_url").style.display="";
		document.getElementById("splash_idle_timeout").style.display="";
		document.getElementById("splash_force_timeout").style.display="";
  	}
  	function hide_nodog(){
		document.getElementById("white_ls").style.display="none";
		document.getElementById("black_ls").style.display="none";
		document.getElementById("up_limit").style.display="none";
		document.getElementById("dw_limit").style.display="none";
		document.getElementById("spl_gwname").style.display="none";
		document.getElementById("spl_page").style.display="none";
		document.getElementById("splash_enable").style.display="none";
		document.getElementById("splash_redirect_url").style.display="none";
		document.getElementById("splash_idle_timeout").style.display="none";
		document.getElementById("splash_force_timeout").style.display="none";
  	}
  	function show_chilli(){
		document.getElementById("radius_svr_1").style.display="";
		document.getElementById("radius_svr_2").style.display="";
		document.getElementById("radius_secret").style.display="";
		document.getElementById("radius_nasid").style.display="";
		document.getElementById("uam_server").style.display="";
		document.getElementById("uam_secret").style.display="";
		document.getElementById("uam_url").style.display="";
		document.getElementById("uam_domain").style.display="";
		document.getElementById("chilli_sel").style.display="";
  	}
  	function hide_chilli(){
		document.getElementById("radius_svr_1").style.display="none";
		document.getElementById("radius_svr_2").style.display="none";
		document.getElementById("radius_secret").style.display="none";
		document.getElementById("radius_nasid").style.display="none";
		document.getElementById("uam_server").style.display="none";
		document.getElementById("uam_secret").style.display="none";
		document.getElementById("uam_url").style.display="none";
		document.getElementById("uam_domain").style.display="none";
		document.getElementById("chilli_sel").style.display="none";
  	}

	function show_AP_2(){
		document.getElementById("enableAP2").style.display="";
		document.getElementById("AP2_name").style.display="";
		document.getElementById("AP2_key").style.display="";
  	}
  	function hide_AP_2(){
		document.getElementById("enableAP2").style.display="none";
		document.getElementById("AP2_name").style.display="none";
		document.getElementById("AP2_key").style.display="none";
  	}

	function showAdvanced(){
		document.getElementById("root_pwd").style.display="";
		document.getElementById("net_block").style.display="";
		document.getElementById("ap1_isolate").style.display="";
		document.getElementById("ap2_isolate").style.display="";
		document.getElementById("channel").style.display="";
		document.getElementById("olsr_enable").style.display="";
		document.getElementById("frz_version").style.display="";
		document.getElementById("test_firmware_enable").style.display="none";
		document.getElementById("strict_mesh").style.display="";
		document.getElementById("custom_sh").style.display="";
		document.getElementById("stand_alone").style.display="";

  	}
  	function hideAdvanced(){
		document.getElementById("root_pwd").style.display="none";
		document.getElementById("net_block").style.display="none";
		document.getElementById("ap1_isolate").style.display="none";
		document.getElementById("ap2_isolate").style.display="none";
		document.getElementById("channel").style.display="none";
		document.getElementById("olsr_enable").style.display="none";
		document.getElementById("frz_version").style.display="none";
		document.getElementById("test_firmware_enable").style.display="none";
		document.getElementById("strict_mesh").style.display="none";
		document.getElementById("custom_sh").style.display="none";
		document.getElementById("stand_alone").style.display="none";
  	}
	function show_dash(){
		document.getElementById("enable_SSL").style.display="";
		document.getElementById("dashboard_url").style.display="";
  	}
  	function hide_dash(){
		document.getElementById("enable_SSL").style.display="none";
		document.getElementById("dashboard_url").style.display="none";
  	}
  	</script>
</head>
<?php 

//determines the value of a boolean in the db
function isChecked($field){
	if ($field==0) return "";
        else if ($field==1) return 'checked="checked"';
	else return "";
}

?>

<body onload=initFormValidation();show_acc();show_AP_1();hide_AP_2();hideAdvanced();hide_dash();Nifty("div.comment");chk_portal();init_lanblk();>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>

<?php 
//setup the menu
include '../lib/menu.php';

// Load the language pack
if($ulang !='en') require '../lib/lang_edit.php';

?>


<form method="POST" action="c_edit.php" name="editNetwork" onsubmit="if(!isFormValid()){ alert('The fields highlighted in red have errors. Please correct this and resubmit.');show_AP_1();show_AP_2();showAdvanced();show_dash();return false;}" >
<input type="hidden" name="cp_handler" value="<?php echo $cp_handler ?>">
<input type="hidden" name="lan_block" value="<?php echo $lan_block ?>">

<table align="center" cellpadding="0" cellspacing="0" border=0 width=950>
<tr><td align=center>

<?php 
if ($created=='true') {
  if($ulang=='en') echo "<div class=success>You have successfully created a network!</div>";
  else echo "<div class=success>".$cn_ecre_m ."</div>";
} else if ($updated=='true') {
  if ($_MSG=='M1') { 
    if($ulang=='en') echo "<div class=success>Password changed!</div>";
    else echo "<div class=success>".$cn_eum1_m ."</div>";
  } else if ($_MSG=='M2') {
    if($ulang=='en') echo "<div class=success>Network settings are updated successfully! </div>";
    else echo "<div class=success>".$cn_eum2_m ."</div>";
  }
}

$query = "SELECT * FROM node WHERE netid='".$netid."'";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) {
if($ulang=='en') echo "<div class=error>There are no nodes associated with this network yet. You might want to <a href=\"../nodes/addnode.php\">add node</a>.</div>";
else echo "<div class=error>&#27492;&#32593;&#32476;&#30446;&#21069;&#23578;&#26080;&#33410;&#28857;&#12290;&#35831;&#28857;&#20987; <a href=\"../nodes/addnode.php\">&#27492;&#22788;&#21152;&#20837;&#33410;&#28857;</a>.</div>";
}
?>
<!--
<h1><?php echo $display_name ?></h1>
-->
    </td>
</tr>
<tr><td>
<table cellpadding="0" cellspacing="0" border=0 width=950>
  <tr></td><td align=center colspan=4>
           <font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#ff9900;"><?php if($ulang=='en') echo "Network System Environment Settings"; else echo $cn_titl_t; ?> </font>
      </td>
      <td width=10></td>
  </tr>
        <tr><td height=20></td></tr>
</table>
<table cellpadding="0" cellspacing="0" border=0 width=950>
  	<tr id="acc_name">
          <td width=170></td>
          <td width=300 align='right'><?php if($ulang=='en') echo "Network Name (Dashboard)"; else echo $cn_nnam_p; ?></td>
          <td width=10></td>
          <td width=300><input readonly="readonly" name="net_name" value="<?php echo $net_name ?>"></td>
          <td width=170></td>
	</tr>
  	<tr id="dis_name">
          <td></td>
          <td align='right'><?php if($ulang=='en') echo "Display name"; else echo $cn_dnam_p; ?></td>
          <td></td>
          <td><input name="display_name" value="<?php echo $display_name?>"></td>
          <td></td>
	</tr>
  	<tr id="changepw"><td></td>
          <td align='right'><?php if($ulang=='en') echo "Password"; else echo $cn_cpwd_p; ?></td>
          <td></td>
          <td><a href="password.php"><?php if($ulang=='en') echo "Change Password"; else echo $cn_cpwd_c; ?></a></td>
          <td></td>
	</tr>
	<tr><td></td> <td height=10></td> </tr>
  	<tr id="m_email"><td></td>
          <td align='right'><?php if($ulang=='en') echo "Contact Email"; else echo $cn_ceml_p; ?></td>
          <td></td>
          <td><input name="email1" value='<?php echo $email1?>' required="1" mask="email"></td>
          <td></td>
	</tr>
   	<tr id="m_email"><td></td>
          <td align='right'><?php if($ulang=='en') echo "Alerts Email"; else echo $cn_ceml_p; ?></td>
          <td></td>
          <td><input name="email2" value='<?php echo $email2?>' mask="email"></td>
          <td></td>
	</tr>
  	<tr id="c_email"><td></td> </tr>
</table>


<table id="c_line" cellpadding="0" cellspacing="0" border=0 width=950>
<tr><td height=15></td</tr>
<tr><td width=100% align='center'><DIV style="font-size:1px; line-height:1px; width:900px; height:1px; background-color:#bcbcbc">&nbsp;</DIV> </td</tr>
</table>

<table id="edit_net" align="left" cellpadding="4" cellspacing="0" border=0 width=950>
	<tr><td height=20></td> </tr>
	<tr><td width=10></td>
		<td colspan=4 width=940>
                <table><tr><td>
<h2><?php if($ulang=='en') echo "Access Point 1 (Public) Settings"; else echo $cn_sap1_t; ?></h2>
                </td><td width=10></td>
                
                <td><a href="javascript:show_AP_1();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hide_AP_1();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>

                </tr></table>
                </td>
	</tr>
  	<tr id="net_ssid">
           <td width=10></td>
           <td width=180><?php if ($ulang=='en') echo "Network Name (AP1 SSID)"; else echo $cn_sid1_p; ?></td>
           <td width=260><input name="ap1_essid" value="<?php echo $ap1_essid ?>" size=30 required="1" mask="keyID"></td>
           <td width=500><div class="comment"><?php if ($ulang=='en') echo "The SSID of public network (AP1). If the option [Use Node Name] is checked, individual node name will be used instead."; else  echo $cn_sid1_c; ?></div></td>
	</tr>
  	<tr id="net_key"><td></td>
		<td><?php if ($ulang=='en') echo "Network WPA Key"; else echo $cn_wpa1_p; ?></td>
		<td><input name="ap1_key" value="<?php echo $ap1_key?>" size=30></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Password (key) for the this access point. Leave blank for an open network. KEYS MUST BE 8 CHARACTERS OR LONGER."; else  echo $cn_wpa1_c; ?></div></td>
	</tr>
  	<tr id="use_node">
           <td></td>
           <td><?php if ($ulang=='en') echo "Use Node Name"; else echo $cn_nodn_p; ?></td>
  		<td><input <?php echo isChecked($use_node) ?> name="use_node" value=1 type="checkbox"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Allow each access point to broadcast its own node name."; else  echo $cn_nodn_c; ?></div></td>
  	</tr>
  	<tr id="sel_cp_ng" height=80><td></td>
           <td><b><?php if ($ulang=='en') echo "Captive Portal Method"; else echo $cn_capp_p; ?></b></td>
  		<td colspan=2><font style="color:662200;"><b><?php if ($ulang=='en') echo "Use Simple Access Control"; else echo $cn_ndog_t; ?></b></font> &nbsp; <a href="javascript:sel_portal();"><?php if ($ulang=='en') echo "Use Captive Portal"; else echo $cn_cova_t; ?></a></td>
  	</tr>
  	<tr id="sel_cp_ch">
          <td></td>
          <td height=80><b><?php if ($ulang=='en') echo "Captive Portal Method"; else echo $cn_capp_p; ?></b></td>
  		<td colspan=2><font style="color:662200;"><b><?php if ($ulang=='en') echo "Use Captive Portal"; else echo $cn_cova_t; ?></b></font> &nbsp; <a href="javascript:sel_nodog();"><?php if ($ulang=='en') echo "Use Simple Access Control"; else echo $cn_ndog_t; ?></a> </td>
  	</tr>
  	<tr id="dw_limit"><td></td>
		<td><?php if ($ulang=='en') echo "Download Limit"; else echo $cn_dlmt_p; ?></td>
		<td><input name="download_limit" value='<?php echo $download_limit?>'></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "Download speed limit (bit-rate in Kbits/sec)"; else echo $cn_dlmt_c; ?></div></td>
	</tr>
  	<tr id="up_limit"><td></td>
		<td><?php if ($ulang=='en') echo "Upload Limit"; else echo $cn_ulmt_p; ?></td>
		<td><input name="upload_limit" value='<?php echo $upload_limit?>'></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "Upload speed limit (bit-rate in Kbits/sec)"; else echo $cn_ulmt_c; ?></div></td>
	</tr>
  	<tr id="white_ls"><td></td>
		<td><?php if ($ulang=='en') echo "Whitelist"; else echo $cn_whte_p; ?></td>
		<td><textarea cols="20" rows="4" name="access_control_list"><?php echo $access_control_list?></textarea></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "A list of MACs for clients allowed to access the network. One MAC per line, such as:<br>00:1E:3A:B8:93:84<br>00:21:15:A5:8E:76<br><br>Leave this field blank if you don't want to set a restriction."; else echo $cn_whte_c; ?></div></td>
	</tr>
  	<tr id="black_ls"><td></td>
		<td><?php if ($ulang=='en') echo "Blacklist"; else echo $cn_blck_p; ?></td>
		<td><textarea cols="20" rows="4" name="access_disable_list"><?php echo $access_control_list?></textarea></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "A list of MAC to be disallowed to access the network. Enter the list in comma separated string, such as 00:1E:3A:B8:93:84,00:21:15:A5:8E:76"; else echo $cn_blck_c; ?></div></td>
	</tr>
  	<tr id="splash_enable"><td></td>
		<td><?php if ($ulang=='en') echo "Enable Splash"; else echo $cn_sple_p; ?></td>
  		<td><input name="splash_enable" <?php echo isChecked($splash_enable) ?>value=1 type="checkbox"> </td>
		<td><div class="comment"><?php if ($ulang=='en') echo "Enable splash will force the browser to open a splash page first when broser is open. Splash page can then direct user to a restricted domain or the unrestricted Internet if the redirection page is not specified."; else echo $cn_sple_c; ?></div></td>
  	</tr>
  	<tr id="spl_gwname"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Page Title"; else echo $cn_spgn_p; ?></td>
<!-- default the splash page to -->
<!-- www.open-mesh.com/users/anselmi/splash.txt -->
  		<td><input name="spl_gwname" value="<?php echo $spl_gwname?>" maxlength=60 size=30></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "A title for your splash page customization. Please use letters, numbers, spaces, and dash line. No special characters are allowed.</span>"; else echo $cn_spgn_c; ?></div></td>
  	</tr>
  	<tr id="spl_page"><td></td>
		<td><?php if ($ulang=='en') echo "Splash template URL"; else echo $cn_sptm_p; ?></td>
<!-- default the splash page to -->
<!-- www.open-mesh.com/users/anselmi/splash.txt -->
  		<td><input name="spl_page" value="<?php echo $spl_page?>" size=30> &nbsp; <a href="javascript:def_spl_templt();">Default template</a></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "A URL where the splash template can be downloaded (e.g. http://wifimesh.trigmax.com/meshconnect/lib/splash.txt). <span class='warn'>Warning: an incorrect URL would prevent users from accessing the network due to improper display of splash page.</span>"; else echo $cn_sptm_c; ?></div></td>
  	</tr>
  	<tr id="splash_redirect_url"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Redirect URL"; else echo $cn_sprd_p; ?></td>
  		<td><input name="splash_redirect_url" value='<?php echo $splash_redirect_url?>' size=30></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "The home page of a restricted domain to display after the Splash page (e.g. http://www.google.com). Leave it blank will link to browser's default home."; else echo $cn_sprd_c; ?></div></td>
  	</tr>
  	<tr id="splash_idle_timeout"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Idle Interval"; else echo $cn_spii_p; ?></td>
		<td><input name="splash_idle_timeout"  value='<?php echo $splash_idle_timeout?>'></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "Minutes client is idle before showing Splash Page."; else echo $cn_spii_c; ?></div></td>
	</tr>
  	<tr id="splash_force_timeout"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Fixed Timeout"; else echo $cn_spfi_p; ?></td>
		<td><input name="splash_force_timeout" value="<?php echo $splash_force_timeout?>"></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "Minutes to show splash page regardless of activity."; else echo $cn_spfi_c; ?></div></td>
	</tr>
  	<tr id="chilli_sel"><td></td>
          <td><?php if ($ulang=='en') echo "Chilli Operator"; else echo $cn_copt_p; ?></td>
          <td colspan='2'>
            <table><tr><td><input type="radio" name="chilli_type" value="chi_aaa" onClick = "set_chilli('5');"></td><td>CoovaAAA</td><td width=20></td><td><input type="radio" name="chilli_type" value="chi_xxx" onClick = "set_chilli('6');"></td> <td>Other Chilli</td></tr></table>
          </td>
	</tr>
  	<tr id="radius_svr_1"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Server 1"; else echo $cn_rdu1_p; ?></td>
          <td><input name="radius_svr_1" value="<?php echo $radius_svr_1?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "URL for RADIUS service."; else echo $cn_rdu1_c; ?></div></td>
	</tr>
  	<tr id="radius_svr_2"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Server 2"; else echo $cn_rdu2_p; ?></td>
		<td><input name="radius_svr_2" value="<?php echo $radius_svr_2?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Second URL for RADIUS service."; else echo $cn_rdu2_c; ?></div></td>
	</tr>
  	<tr id="radius_secret"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Secret"; else echo $cn_rdpw_p; ?></td>
		<td><input name="radius_secret" value="<?php echo $radius_secret?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Secret password for RADIUS access."; else echo $cn_rdpw_c; ?></div></td>
	</tr>
  	<tr id="radius_nasid"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS NAS ID"; else echo $cn_nasi_p; ?></td>
		<td><input name="radius_nasid" value="<?php echo $radius_nasid?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Network Access Server (NAS) ID. Leave it blank for Coova.net."; else echo $cn_nasi_c; ?></div></td>
	</tr>
  	<tr id="uam_server"><td></td>
          <td><?php if ($ulang=='en') echo "UAM Server Name"; else echo $cn_uams_p; ?></td>
		<td><input name="uam_server" value="<?php echo $uam_server?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "User Access Management (UAM) server name. (e.g. https://www.coova.net)"; else echo $cn_uams_c; ?></div></td>
	</tr>
  	<tr id="uam_secret"><td></td>
          <td><?php if ($ulang=='en') echo "UAM Secret"; else echo $cn_uapw_p; ?></td>
		<td><input name="uam_secret" value="<?php echo $uam_secret?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Secret password for UAM access."; else echo $cn_uapw_c; ?></div></td>
	</tr>
  	<tr id="uam_url"><td></td>
          <td><?php if ($ulang=='en') echo "UAM User URL"; else echo $cn_uamu_p; ?></td>
		<td><input name="uam_url" value="<?php echo $uam_url?>" size=30></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "UAM user interface URL. (e.g. /hotspot for Coova.net server, which is to be attached to the UAM server name.)"; else echo $cn_uamu_c; ?></div></td>
	</tr>
  	<tr id="uam_domain"><td></td>
          <td ><?php if ($ulang=='en') echo "Unrestricted Domain"; else echo $cn_ures_p; ?></td>
		<td ><input name="uam_domain" value="<?php echo $uam_domain?>" size=30> &nbsp; <a href="javascript:def_cp5_domain();">Default URLs</td>
          <td valign='top'><div class="comment"><?php if ($ulang=='en') echo "List of domains not restricted by the portal. Make sure your portal domain is on the list, and the payment service domain is on the list as well."; else echo $cn_ures_c; ?></div></td>
	</tr>

	<tr><td></td> <td height=20></td> </tr>
	<tr><td></td>
		<td colspan=3>
                <table><tr><td>
                 <h2><?php if($ulang=='en') echo "Access Point 2 (Private) Settings"; else echo $cn_sap2_t; ?></h2>
                </td><td width=10></td>
                <td><a href="javascript:show_AP_2();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hide_AP_2();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>
                </tr></table>
                </td>
	</tr>
  	<tr id="enableAP2"><td></td>
          <td><?php if ($ulang=='en') echo "Enable AP2"; else echo $cn_ap2e_p; ?></td>
		<td>
                <table><tr><td>
<input <?php echo isChecked($ap2_enable) ?> name="ap2_enable" value='1' type="checkbox">
                  </td>
                  <td width=10> </td>
                  <td>Hide ESSID </td>
                  <td>
<input <?php echo isChecked($ap2_hide) ?> name="ap2_hide" value='1' type="checkbox">
                  </td>
                  </tr>
                </table>
                </td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Check to enable, uncheck to disable this access point."; else echo $cn_ap2e_c; ?></div></td>
	</tr>
  	<tr id="AP2_name"><td></td>
          <td><?php if ($ulang=='en') echo "Network Name (AP2 SSID)"; else echo $cn_ap2n_p; ?></td>
		<td><input name="ap2_essid" value="<?php echo $ap2_essid ?>" required="1" mask="keyID"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "The SSID of private access point. "; else echo $cn_ap2n_c; ?></div></td>
	</tr>
  	<tr id="AP2_key"><td></td>
          <td><?php if ($ulang=='en') echo "Network Key"; else echo $cn_ap2k_p; ?></td>

		<td><input name="ap2_key" value="<?php echo $ap2_key ?>" required="1" mask="keyID"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Password (key) for this access point. It is NOT possible to leave this field blank and have this be an open AP. MUST BE 8 CHARACTERS OR LONGER."; else echo $cn_ap2k_c; ?></div></td>
	</tr>
	<tr><td></td><td height=20></td> </tr>
	<tr><td></td><td colspan=3>
            <table><tr><td>
                 <h2><?php if($ulang=='en') echo "Advanced Settings"; else echo $cn_advs_t; ?></h2>
              </td>
              <td width=10></td>
              <td align=left><a href="javascript:showAdvanced();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hideAdvanced();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>
  	    </tr>
  	    </table>
  	</td>
  	</tr>
  	<tr id="root_pwd"><td></td>
          <td><?php if ($ulang=='en') echo "Root Password for Nodes"; else echo $cn_arpw_p; ?></td>
  		<td><input name="node_pwd" value="<?php echo $node_pwd?>"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Root password to login to all nodes on your network (via ssh or other secured methods). You should change this for security."; else echo $cn_arpw_c; ?></div></td>
  	</tr>

  	<tr id="net_block"><td></td>
          <td colspan='2'>
            <table cellpadding="0" cellspacing="0" border=0>
            <tr><td><?php if ($ulang=='en') echo "LAN Block"; else echo $cn_alnb_p; ?></td></tr></table>
            <table cellpadding="0" cellspacing="0" border=0>
            <tr><td width=25></td><td><input type='radio' name='lanblk_type' value='blk_none' onClick = "set_lanblk('0');"></td><td>Un-block</td><td width=10></td><td><input type='radio' name='lanblk_type' value='blk_all' onClick = "set_lanblk('1');"></td><td>Block AP1 and AP2</td></tr>
                   <tr><td></td><td><input type='radio' name='lanblk_type' value='blk_ap1' onClick = "set_lanblk('2');"></td><td>Block AP1 only</td><td></td>
                       <td><input type='radio' name='lanblk_type' value='blk_ap2' onClick = "set_lanblk('3');"></td><td>Block AP2 only</td></tr>
             </table>
          </td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Block users on the wireless networks (AP1, AP2, or both) from accessing your wired LAN (on the new version of ROBIN firmware only)."; else echo $cn_alnb_c; ?></div></td>
  	</tr>

  	<tr id="ap1_isolate"><td></td>
          <td><?php if ($ulang=='en') echo "AP1 Isolation"; else echo $cn_ap1b_p; ?></td>
          <td><input <?php echo isChecked($ap1_isolate) ?>name="ap1_isolate" value=1 type="checkbox"></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Check this box to prevent your AP#1 users from being able to access each other's computers."; else echo $cn_ap1b_c; ?></div></td>
  	</tr>
  	<tr id="ap2_isolate"><td></td>
          <td><?php if ($ulang=='en') echo "AP2 Isolation"; else echo $cn_ap2b_p; ?></td>
  		<td><input <?php echo isChecked($ap2_isolate) ?> name="ap2_isolate" value=1 type="checkbox"></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Check this box to prevent your AP#2 users from being able to access each other's computers."; else echo $cn_ap2b_c; ?></div></td>
  	</tr>
  	<tr id="channel"><td></td>
          <td><?php if ($ulang=='en') echo "Radio Channel"; else echo $cn_ardo_p; ?></td>
  		<td><input name="radio_channel" size=3 maxlength=2 value='<?php echo $radio_channel?>'></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "WiFi radio channel (1 - 11 in USA). If you change the channel, you may experience network outage during the transition. The transition may take up to 30 minutes."; else echo $cn_ardo_c; ?></div></td>
	</tr>
  	<tr id="olsr_enable"><td></td>
  		<td><?php if($ulang=='en') echo "OLSR Protocol"; else echo $cn_olsr_p; ?></td>
  		<td><input <?php echo isChecked($olsr_enable) ?> name="olsr_enable" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Check the box to select OLSR protocol. Otherwise BATMAN protocol will be used. (Warning: All nodes have to be up when switching the protocol. Nodes off the network may not be able to re-join after the switch.)"; else echo $cn_olsr_c; ?></div></td>
	</tr>
  	<tr id="frz_version"><td></td>
  		<td><?php if($ulang=='en') echo "Freeze Firmware Version"; else echo $cn_frzv_p; ?></td>
  		<td><input <?php echo isChecked($frz_version) ?> name="frz_version" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Keep the current version of firmware. Stop auto-upgrade."; else echo $cn_frzv_c; ?></div></td>
	</tr>
  	<tr id="test_firmware_enable"><td></td>
  		<td>Use test firmware<br>Use test firmwar</td>
  		<td><input <?php echo isChecked($test_firmware_enable) ?> name="test_firmware_enable" value=1 type="checkbox"></td>
		<td><div class="comment">Allow to upgrade to the test version of firmware. Not rcommended.</div></td>
	</tr>
  	<tr id="strict_mesh"><td></td>
  		<td><?php if($ulang=='en') echo "Strict Mesh"; else echo $cn_strc_p; ?></td>
  		<td><input <?php echo isChecked($strict_mesh) ?> name="strict_mesh" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Separate nodes of each network from sharing the gateway of others."; else echo $cn_strc_c; ?></div></td>
	</tr>
	<tr id="stand_alone"><td></td>
  		<td><?php if($ulang=='en') echo "Stand Alone"; else echo $cn_stda_p; ?></td>
  		<td><input <?php echo isChecked($stand_alone) ?> name="stand_alone" value=1 type="checkbox"></td>
                <td><div class="comment"><?php if($ulang=='en') echo "Enable stand-alone mode (operate as a private network when internet connection is not available)."; else echo $cn_stda_c; ?></div></td>
  	</tr>

  	<tr id="custom_sh"><td></td>
                <td colspan=2>
                  <table cellpadding="0" cellspacing="0" border=0>
                  <tr>
  		    <td><?php if($ulang=='en') echo "Enable Custom Shell"; else echo $cn_cust_p; ?></td>
                    <td width=20></td>
  		    <td><input <?php echo isChecked($custm_sh_on) ?> name="custm_sh_on" value=1 type="checkbox"></td>
                  </tr>
                  <tr>
  		    <td><?php echo "URL "; ?></td>
                    <td width=20></td>
  		    <td><input name="custm_sh_url" size=30 value="<?php echo $custm_sh_url?>"></td>
                  </tr>
                  </table>
                </td>

		<td><div class="comment"><?php if($ulang=='en') echo "Enable a custom script 'custom.sh'. IMPORTANT: This is an advanced feature. Please verify your script first as this has the potential to crash your network. "; else echo $cn_cust_c; ?></div></td>
	</tr>

	<tr><td></td> <td height=20></td> </tr>
	<tr><td></td>
		<td colspan=3>
                <table><tr><td>
<h2><?php if($ulang=='en') echo "Dashboard Server Settings"; else echo $cn_dset_t; ?> </h2>
                </td><td width=10></td>
              <td align=left><a href="javascript:show_dash();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hide_dash();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>
                </tr></table>
             </td>
  	</tr>
  	<tr id="enable_SSL"><td></td>
  		<td><?php if($ulang=='en') echo "Enable SSL"; else echo $cn_essl_p; ?></td>
  		<td><input <?php echo isChecked($ssl_enable) ?> name="ssl_enable" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Set nodes to checkin to the dashboard in SSL. Uncheck this box if the dashboard server does not support SSL. (Warning: your nodes may be orphaned if the box is checked but the dashboard is not on an SSL server.)"; else echo $cn_essl_c; ?></div></td>
	</tr>
  	<tr id="dashboard_url"><td></td>
  		<td><?php if($ulang=='en') echo "Alternate Dashboard"; else echo $cn_dash_p; ?></td>
  		<td><input name="dashboard_url" size=30 value="<?php echo $dashboard_url?>"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Move the network to an alternate dashboard. Please remember to clear the field after forwarding the network to another dashboard so as to allow the network to come back to this dashboard.  (Default: leave it blank.)"; else echo $cn_dash_c; ?></div></td>
  	</tr>


	<tr><td height=20></td>
	<tr><td></td>
		<td colspan=3 align=center><input name="submit" value="<?php if($ulang=='en') echo 'Save Settings'; else echo $cn_save_t; ?>" type="submit"></td>
	</tr>
	<tr><td height=20></td>
  </table>
  </td>
</tr>
</table>
 	
</form>
</td></tr></table>

</body>
</html>
