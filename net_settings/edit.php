<?php 
/* Name: edit.php

 */

//Make sure person is logged in
session_start();

if ($_SESSION['user_type']!='admin') 
	header("Location: ../entry/login.php");

//Set up database connection
require_once '../lib/connectDB.php';
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
$ap1_essid = str_replace("*"," ",$resArray['ap1_essid']);
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

$transparent_bridge = $resArray['transparent_bridge'];
$transparent_bridge_vlan = $resArray['transparent_bridge_vlan'];
if( $transparent_bridge_vlan == 1 )
  $transparent_bridge_vlan = "";
$wired_clients = $resArray['wired_clients'];

$ap2_essid = str_replace("*"," ",$resArray['ap2_essid']);
$ap2_key = $resArray['ap2_key'];
$node_pwd = $resArray['node_pwd'];
$lan_block = $resArray['lan_block'];
$ap1_isolate = $resArray['ap1_isolate'];
$ap2_isolate = $resArray['ap2_isolate'];
$net_location = $resArray['net_location'];

$stand_alone = $resArray['stand_alone'];

$test_firmware_enable = $resArray['test_firmware_enable'];
// test_firmware_enable is not yet used
$radio_channel2 = $resArray['radio_channel2'];
$radio_channel = $resArray['radio_channel'];
$radio_channel_country = $resArray['radio_channel_country'];
$radio_channel9 = $resArray['radio_channel9'];
$checkin_period = $resArray['checkin_period'];
$countrycode = $resArray['country_code'];
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
$bypass_list  = $resArray['bypass_list'];
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
$checker = $resArray['checker_name'];
$floor_plan = $resArray['floor_plan'];

$reboot_freq = $resArray['reboot_freq'];
$reboot_freq_hour = $resArray['reboot_freq_hour'];
$min_nodedown = $resArray['min_nodedown'];
$upgrade_f = $resArray['upgrade_f'];
$upgrade_t = $resArray['upgrade_t'];
$DNS1 = $resArray['DNS1'];
$DNS2 = $resArray['DNS2'];
$DNS = $resArray['DNS'];
$SMTP = $resArray['SMTP'];

//Check if the user just updated the network
$updated = $_SESSION['updated'];
unset($_SESSION['updated']);
$created = $_SESSION['created'];
unset($_SESSION['created']);
$_MSG    = $_SESSION['message'];
unset($_SESSION['message']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Network Configuration | <?php  echo $net_name; ?></title> 
	<?php include '../lib/style.php';?>
 	<?php  include "../lib/validateInput.js"; ?>
 	<script src="../lib/slider/slider.js" language="javascript" type="text/javascript"></script>
 	<script src="../lib/jquery.js" language="javascript" type="text/javascript"></script>
	<link href="../lib/slider/slider.css" rel="stylesheet" type="text/css" />
	<script type=text/javascript>

	function show_acc (){
		document.getElementById("acc_name").style.display="";
		document.getElementById("dis_name").style.display="";
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

        // Set the default fields for AWD
	function set_cp_readonly () {
            document.editNetwork.radius_svr_1.readOnly = true;
            document.editNetwork.radius_svr_1.value = "hotspot.anaptyx.com";

            document.editNetwork.radius_svr_2.readOnly = true;
            document.editNetwork.radius_svr_2.value = "hotspot.anaptyx.com";

           // document.editNetwork.radius_nasid.readOnly = false;
            //document.editNetwork.radius_nasid.value = "";

            document.editNetwork.uam_server.readOnly = true;
            document.editNetwork.uam_server.value = "https://hotspot.anaptyx.com";

            document.editNetwork.uam_secret.readOnly = true;
            document.editNetwork.uam_secret.value = "testing123"; // Leave blank
			
	     document.editNetwork.radius_secret.readOnly = true;
            document.editNetwork.radius_secret.value = "testing123";

           // document.editNetwork.uam_url.readOnly = false;
           // document.editNetwork.uam_url.value = "";
        }

	function def_spl_templt () {
            document.editNetwork.spl_page.value = "http://dashboard.awdmesh.com/splash/splash.txt";
        }

	function def_cp5_domain () {
            document.editNetwork.uam_domain.value = "hotspot.anaptyx.com,www.google.com,google.com,maps.google.com,www.paypal.com,www.paypalobjects.com,paypalobjects.com,paypal.112.2o7.net,altfarm.mediaplex.com,mp.apmebf.com,b.stats.paypal.com";
        }

	function set_lanblk (lanb_value) {
          document.editNetwork.lan_block.value = lanb_value;
        }

	function set_chilli (cp_n_value) {
          document.editNetwork.cp_handler.value = cp_n_value;
          // set the fields to default value for Mi Radius
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
			
	     document.editNetwork.radius_secret.readOnly = false;
            document.editNetwork.radius_secret.value = "";

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
	<!--	document.getElementById("white_ls").style.display=""; -->
		document.getElementById("black_ls").style.display="";
		document.getElementById("bypass_ls").style.display="";
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
	<!--	document.getElementById("white_ls").style.display="none"; -->
		document.getElementById("black_ls").style.display="none";
		document.getElementById("bypass_ls").style.display="none";
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
		document.getElementById("transparent_bridge").style.display="";
		
		if(document.getElementById("transparent_bridge_check").checked == true){
		    document.getElementById('wired_clients').style.display='';
		    document.getElementById("transparent_bridge_vlan").style.display="";
		}
		else{
		    document.getElementById('wired_clients').style.display='none';
		    document.getElementById("transparent_bridge_vlan").style.display="none";
		}
  	}
  	function hide_AP_2(){
		document.getElementById("enableAP2").style.display="none";
		document.getElementById("AP2_name").style.display="none";
		document.getElementById("AP2_key").style.display="none";
		document.getElementById("transparent_bridge").style.display="none";
		document.getElementById("transparent_bridge_vlan").style.display="none";
		document.getElementById('wired_clients').style.display='none';
  	}

	function showAdvanced(){
		document.getElementById("root_pwd").style.display="";
		document.getElementById("net_block").style.display="";
		document.getElementById("ap1_isolate").style.display="";
		document.getElementById("ap2_isolate").style.display="";
		document.getElementById("channel2").style.display="";
		document.getElementById("channel").style.display="";
              document.getElementById("channel9").style.display="";
<!--		document.getElementById("olsr_enable").style.display="none"; -->
		document.getElementById("frz_version").style.display="";
		document.getElementById("test_firmware_enable").style.display="none";
		document.getElementById("strict_mesh").style.display="";
		document.getElementById("stand_alone").style.display="";
		document.getElementById("alerts_email").style.display="";
		document.getElementById("countrycode").style.display="none";
		document.getElementById("checkinperiod").style.display="none"; 
		document.getElementById("rebootfreq").style.display="";
		document.getElementById("nodedown_min").style.display="";
		document.getElementById("upgrade_win").style.display="";
		document.getElementById("plan").style.display="";
    	}
  	function hideAdvanced(){
		document.getElementById("root_pwd").style.display="none";
		document.getElementById("net_block").style.display="none";
		document.getElementById("ap1_isolate").style.display="none";
		document.getElementById("ap2_isolate").style.display="none";
		document.getElementById("channel2").style.display="none";
		document.getElementById("channel").style.display="none";
              document.getElementById("channel9").style.display="none";
<!--		document.getElementById("olsr_enable").style.display="none"; -->
		document.getElementById("frz_version").style.display="none";
		document.getElementById("test_firmware_enable").style.display="none";
		document.getElementById("strict_mesh").style.display="none";
		document.getElementById("stand_alone").style.display="none";
		document.getElementById("alerts_email").style.display="none";
		document.getElementById("countrycode").style.display="none";
		document.getElementById("checkinperiod").style.display="none";
		document.getElementById("rebootfreq").style.display="none";
		document.getElementById("nodedown_min").style.display="none";
		document.getElementById("upgrade_win").style.display="none";
		document.getElementById("plan").style.display="none";		
   	}
	function show_dash(){
		document.getElementById("dashboard_url").style.display="";
              document.getElementById("DNS").style.display="";
              document.getElementById("SMTP_server").style.display="";
              document.getElementById("custom_sh").style.display="";
  	}
  	function hide_dash(){
		document.getElementById("dashboard_url").style.display="none";
              document.getElementById("DNS").style.display="none";
              document.getElementById("SMTP_server").style.display="none";
              document.getElementById("custom_sh").style.display="none";   
  	}
 	function asignaDNS(DNS) {
		  if (DNS.selectedIndex == '1') {  //OpenDNS
            document.editNetwork.DNS1.value='208.67.222.222';
            document.editNetwork.DNS2.value='208.67.220.220';
          } else if (DNS.selectedIndex == '2') {  //FamilyShield
            document.editNetwork.DNS1.value='208.67.222.123';
            document.editNetwork.DNS2.value='208.67.220.123';
          } else if (DNS.selectedIndex == '3') {  //Google
            document.editNetwork.DNS1.value='8.8.8.8';
            document.editNetwork.DNS2.value='8.8.4.4';
          } else {
            // document.editNetwork.DNS1.value='';
            // document.editNetwork.DNS2.value='';
		  }
	}
	
	function change_channel_country(country){
            if(country == "manual"){
                    var text_input = document.getElementById("radio_channel_input");
                    var select_input = document.getElementById("radio_channel_selector");
                    
                    text_input.name = "radio_channel";
                    select_input.name = "_radio_channel";
                    text_input.style.display = "";
                    select_input.style.display = "none";
                }
             else{
                    var text_input = document.getElementById("radio_channel_input");
                    var select_input = document.getElementById("radio_channel_selector");
                    
                    text_input.name = "_radio_channel";
                    select_input.name = "radio_channel";
                    text_input.style.display = "none";
                    select_input.style.display = "";
                    
                    var channel_options = $("#radio_channel_selector").find("option");
                    $("#radio_channel_selector").find("option").remove();
                    $("#radio_channel_selector").html($(channel_options).filter("."+country));
                    $("#radio_channel_selector").find("option[value=<?php echo $radio_channel?>]").attr("selected","selected");
                 }
        }
        
        $(document).ready(function(){                
                change_channel_country($("#radio_channel_country_selector").val());
            });
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

<body onload=initFormValidation();show_acc();show_AP_1();hide_AP_2();hideAdvanced();hide_dash();Nifty("div.comment");chk_portal();init_lanblk();StartSlider();>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>

<?php 
//setup the menu
include '../lib/menu.php';

// Load the language pack
if($ulang !='en') require '../lib/lang_edit.php';

?>


<form method="POST" action="c_edit.php" name="editNetwork" onSubmit="if(!isFormValid()){ alert('The fields highlighted in red have errors. Please correct this and resubmit.');show_AP_1();show_AP_2();showAdvanced();show_dash();return false;}" >
<input type="hidden" name="cp_handler" value="<?php echo $cp_handler ?>">
<input type="hidden" name="lan_block" value="<?php echo $lan_block ?>">
<input type="hidden" name="net_id" value="<?php echo $_SESSION['netid'] ?>">

<table align="center" cellpadding="0" cellspacing="0" border=0 width=900>
<tr><td align=center>

<?php 
if ($created=='true') {
  if($ulang=='en') echo '<div class=success><img src="ok.png" border=0 ALIGN=ABSMIDDLE>The network was successfully created</div>';
  else echo "<div class=success>".$cn_ecre_m ."</div>";
} else if ($updated=='true') {
  if ($_MSG=='M1') { 
    if($ulang=='en') echo '<div class=success><img src="ok.png" border=0 ALIGN=ABSMIDDLE>The password was successfully changed</div>';
    else echo "<div class=success>".$cn_eum1_m ."</div>";
  } else if ($_MSG=='M2') {
    if($ulang=='en') echo '<div class=success><img src="ok.png" border=0 ALIGN=ABSMIDDLE>Changes have been updated</div>';
    else echo "<div class=success>".$cn_eum2_m ."</div>";
  }
}

$query = "SELECT * FROM node WHERE netid='".$netid."'";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) {
if($ulang=='en') echo "<div class=error>The network has no associated nodes. <a href=\"../nodes/addnode.php\">Add Node</a></div>";
else echo "<div class=error>&#27492;&#32593;&#32476;&#30446;&#21069;&#23578;&#26080;&#33410;&#28857;&#12290;&#35831;&#28857;&#20987; <a href=\"../nodes/addnode.php\">&#27492;&#22788;&#21152;&#20837;&#33410;&#28857;</a>.</div>";
}
?>
    </td>
</tr>
<tr><td>
<table cellpadding="0" cellspacing="0" border=0 width=950>
  <tr></td><td align=center colspan=4>
      </td>
      <td width=40></td>
  </tr>
  
  
  
  
  
  <tr>
	<td align='center' width=100%>
	

	<div class="comment"><font style='font-family:Helvetica; font-size:12px; color:#000000;'><img src="warning.png" border=0 ALIGN=ABSMIDDLE> <span class='warn'> Warning: </span>Entering improper settings can cause noticeable downtime or a permanent outage.<br> Please do not make changes without understanding them first.</font></div>
	
	
	</td>
	</tr>
  
  
  
  
  
  
  
  
        <tr><td height=20></td></tr>
</table>
<table cellpadding="0" cellspacing="0" border=0 width=900>
  	<tr id="acc_name">
          <td width=170></td>
          <td width=300 align='right'><?php if($ulang=='en') echo "Network Name:"; else echo $cn_nnam_p; ?></td>
          <td width=40></td>
          <td width=300><input readonly="readonly" name="net_name" value="<?php echo $net_name ?>"></td>
          <td width=170></td>
	</tr>
  	<tr id="dis_name">
          <td></td>
          <td align='right'><?php if($ulang=='en') echo "Network Owner:"; else echo $cn_dnam_p; ?></td>
          <td></td>
          <td><input name="display_name" value="<?php echo $display_name?>" size=40></td>
          <td></td>
	</tr>
  	<tr id="changepw"><td></td>
          <td align='right'><?php if($ulang=='en') echo "Change Password:"; else echo $cn_cpwd_p; ?></td>
          <td></td>
          <td><a href="password.php"><?php if($ulang=='en') echo "click to change"; else echo $cn_cpwd_c; ?></a></td>
          <td></td>
	</tr>
	<tr><td></td> <td height=10></td> </tr>
  	<tr id="m_email"><td></td>
          <td align='right'><?php if($ulang=='en') echo "Admin E-Mail:"; else echo $cn_ceml_p; ?></td>
          <td></td>
          <td><input name="email1" value='<?php echo $email1?>' required="1" mask="email" size=30></td>
          <td></td>
	</tr>
	
  	<tr id="c_email"><td></td> </tr>
   	<tr><td></td>
          <td align='right'><?php echo "Network Location:"; ?></td>
          <td></td>
          <td><input name="net_location" value='<?php echo $net_location?>' size=40></td>
          <td></td>
	</tr>
</table>


<table id="c_line" cellpadding="0" cellspacing="0" border=0 width=900>
<tr><td height=15></td></tr>
<tr><td width=400% align='center'><DIV style="font-size:1px; line-height:1px; width:900px; height:0px; background-color:#bcbcbc">&nbsp;</DIV> </td></tr>
</table>

<table id="edit_net" align="left" cellpadding="4" cellspacing="0" border=0 width=900>
	<tr><td height=20></td> </tr>
	<tr><td width=40></td>
		<td colspan=4 width=900>
                <table><tr><td>
<h2><?php if($ulang=='en') echo 'SSID #1 (Public)'; else echo $cn_sap1_t; ?></h2>
                </td><td width=40></td>
                
                <td><a href="javascript:show_AP_1();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hide_AP_1();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>

                </tr></table>
                </td>
	</tr>
  	<tr id="net_ssid">
           <td width=40></td>
           <td width=230><?php if ($ulang=='en') echo "Public SSID"; else echo $cn_sid1_p; ?></td>
           <td width=100><input name="ap1_essid" value="<?php echo $ap1_essid ?>" size=20 required="1" mask="keyID"></td>
           <td width=400><div class="comment"><?php if ($ulang=='en') echo "The name (SSID) you'd like to broadcast. Check box below to use each node's name for its SSID instead."; else  echo $cn_sid1_c; ?></div></td>
	</tr>
       <tr id="use_node">
           <td></td>
           <td><?php if ($ulang=='en') echo "Use Node Name:"; else echo $cn_nodn_p; ?></td>
  		<td><input <?php echo isChecked($use_node) ?> name="use_node" value=1 type="checkbox"></td>
<!--              <td><div class="comment"><?php if ($ulang=='en') echo ""; else  echo $cn_nodn_c; ?></div></td> -->
  	</tr>
  	<tr id="net_key"><td></td>
		<td><?php if ($ulang=='en') echo "WPA-PSK/PSK2 Key (Password):"; else echo $cn_wpa1_p; ?></td>
		<td><input name="ap1_key" value="<?php echo $ap1_key?>" size=20></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Password (key) for this SSID. Leave blank for an open/unencrypted network. KEYS MUST BE 8 CHARACTERS OR LONGER with no spaces allowed.</span>"; else  echo $cn_wpa1_c; ?></div></td>
	</tr>
    	<tr id="sel_cp_ng" height=80><td></td>
           <td><b><?php if ($ulang=='en') echo "<u>Captive Portal:</u>"; else echo $cn_capp_p; ?></b></td>
  		<td colspan=2><font style="color:662200;"><b><?php if ($ulang=='en') echo "Open Access/Splash Page"; else echo $cn_ndog_t; ?></b></font> &nbsp; <a href="javascript:sel_portal();"><?php if ($ulang=='en') echo "Billing & Custom RADIUS"; else echo $cn_cova_t; ?></a></td>
  	</tr>
  	<tr id="sel_cp_ch">
          <td></td>
          <td height=80><b><?php if ($ulang=='en') echo "<u>Captive Portal:</u>"; else echo $cn_capp_p; ?></b></td>
  		<td colspan=2><font style="color:662200;"><b><?php if ($ulang=='en') echo "Billing & Custom RADIUS"; else echo $cn_cova_t; ?></b></font> &nbsp; <a href="javascript:sel_nodog();"><?php if ($ulang=='en') echo "Open Access/Splash Page"; else echo $cn_ndog_t; ?></a> </td>
  	</tr>
	

	<tr id="splash_enable"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Page:"; else echo $cn_sple_p; ?></td>
  		<td><input name="splash_enable" <?php echo isChecked($splash_enable) ?>value=1 type="checkbox"><input type="button" onClick="window.open('../splash/index.php','_blank','width=900,height=600')" name="edit_splash" value="Edit Splash Page"> </td>
		<td><div class="comment"><?php if ($ulang=='en') echo "Click to enable. The splash page is a page users will see first and must click on enter link to use the network."; else echo $cn_sple_c; ?></div></td>
  	</tr> 
	<tr id="splash_redirect_url"><td></td>
		<td><?php if ($ulang=='en') echo "Re-direct URL:"; else echo $cn_sprd_p; ?></td>
  		<td><input name="splash_redirect_url" value='<?php echo $splash_redirect_url?>' size=20></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "The page to display after user views splash page. Leave blank to display the user's requested page."; else echo $cn_sprd_c; ?></div></td>
  	</tr>	
	<tr id="splash_idle_timeout"><td></td>
 		<td><table width="100%">
			<tr><td width="51%">Client Idle Timeout:</td>
			<td width="49%">
				<DIV class=carpe_horizontal_slider_track><DIV class=carpe_slider_slit></DIV>
				<DIV class=carpe_slider id=slider4 display="display4" style="left:<?php echo $splash_idle_timeout/60;?>px"></DIV>
			</td></tr></table>
		</td>
		<td><input name="splash_idle_timeout" id="display4" value='<?php echo $splash_idle_timeout?>'></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "Minutes client is idle before showing Splash Page. (1 day=1440)"; else echo $cn_spii_c; ?></div></td>
	</tr>
  	<tr id="splash_force_timeout"><td></td>
 		<td><table width="100%">
			<tr><td width="51%">Client Force Timeout:</td>
			<td width="49%">
				<DIV class=carpe_horizontal_slider_track><DIV class=carpe_slider_slit></DIV>
				<DIV class=carpe_slider id=slider3 display="display3" style="left:<?php echo $splash_force_timeout/60;?>px"></DIV>
			</td></tr></table>
		</td>
		<td><input name="splash_force_timeout" id="display3" value="<?php echo $splash_force_timeout?>"></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "Minutes to force client splash page view. (1 day=1440)"; else echo $cn_spfi_c; ?></div></td>
	</tr>
  	<tr id="dw_limit"><td></td>
 		<td><table width="100%">
			<tr><td width="40%">Download Limit:</td>
			<td width="60%">
				<DIV class=carpe_horizontal_slider_track><DIV class=carpe_slider_slit></DIV>
				<DIV class=carpe_slider id=slider1 display="display1" style="left:<?php echo $download_limit/60;?>px"></DIV>
			</td></tr></table>
		</td>
		<td><input name="download_limit" id="display1" value='<?php echo $download_limit?>'></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "Download limit (throttling) in Kbits/sec."; else echo $cn_dlmt_c; ?></div></td>
	</tr>
  	<tr id="up_limit"><td></td>
 		<td><table width="100%">
			<tr><td width="40%">Upload Limit:</td>
			<td width="60%">
				<DIV class=carpe_horizontal_slider_track><DIV class=carpe_slider_slit></DIV>
				<DIV class=carpe_slider id=slider2 display="display2" style="left:<?php echo $upload_limit/60;?>px"></DIV>
			</td></tr></table>
		</td>
		<td><input name="upload_limit" id="display2" value='<?php echo $upload_limit?>'></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "Upload limit (throttling) in Kbits/sec."; else echo $cn_ulmt_c; ?></div></td>
	</tr>
       <tr id="bypass_ls"><td></td>
		<td><?php if ($ulang=='en') echo "Whitelist:"; else echo $cn_blck_p; ?></td>
		<td><textarea cols="20" rows="4" name="bypass_list"><?php echo str_replace(",","\n", $bypass_list)?></textarea></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "MAC addresses that will bypass the splash page (if enabled). Useful for game consoles or other devices that lack a web browser. One MAC per line. <br><br>Example: 00:1E:3A:B8:93:84<br/>00:21:15:A5:8E:76"; else echo $cn_blck_c; ?></div></td>
	</tr>
<!--  	<tr id="white_ls"><td></td>
		<td><?php if ($ulang=='en') echo "Access Control List:"; else echo $cn_whte_p; ?></td>
		<td><textarea cols="20" rows="4" name="access_control_list"><?php echo $access_control_list?></textarea></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "List MAC address allowed access this SSID - one per line. All other MAC addresses will not be able to access this SSID. Leave blank to allow all MAC addresses."; else echo $cn_whte_c; ?></div></td>
	</tr> -->
 	<tr id="black_ls"><td></td>
		<td><?php if ($ulang=='en') echo "Blacklist/Block:"; else echo $cn_blck_p; ?></td>
		<td><textarea cols="20" rows="4" name="access_disable_list"><?php echo str_replace(",","\n", $access_disable_list)?></textarea></td>
		<td><div class="comment"><?php if ($ulang=='en') echo "List MAC addresses to be blocked. One MAC per line<br><br>Example: 00:1E:3A:B8:93:84<br/>00:21:15:A5:8E:76"; else echo $cn_blck_c; ?></div></td>
	</tr> 
	
	
	
	

	
 	<tr id="spl_page"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Page Template"; else echo $cn_sptm_p; ?></td>
  		<td><input name="spl_page" value="<?php echo $spl_page?>" size=20> &nbsp; <a href="javascript:def_spl_templt();">Default template</a></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "Optional URL for custom splash.txt files. This template takes precedence over the one created with the editor. A defective or non-existent template will prevent access to the network.</span>"; else echo $cn_sptm_c; ?></div></td>
  	</tr>
	
	
	
  	<tr id="spl_gwname"><td></td>
		<td><?php if ($ulang=='en') echo "Splash Page Title:"; else echo $cn_spgn_p; ?></td>
  		<td><input name="spl_gwname" value="<?php echo $spl_gwname?>" maxlength=60 size=20></td>
  		<td><div class="comment"><?php if ($ulang=='en') echo "Title of your splash page. Special characters are not allowed.</span>"; else echo $cn_spgn_c; ?></div></td>
  	</tr> 

      	<tr id="chilli_sel"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Service Type:"; else echo $cn_copt_p; ?></td>
		
          <td colspan='2'>
            <table><tr><td><input type="radio" name="chilli_type" value="chi_aaa" onClick = "set_chilli('5');"></td><td><a href="http://hotspot.anaptyx.com/admin" target="_blank"><img src="billingcontroller.png" border=0></a></td><td width=20></td><td><input type="radio" name="chilli_type" value="chi_xxx" onClick = "set_chilli('6');"></td> <td><target="_blank"> Other </a></td><td width=20></td><td></a></td><td></td></tr></table>
          </td>
		  
		  
	</tr>
  	<tr id="radius_svr_1"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Server 1:"; else echo $cn_rdu1_p; ?></td>
          <td><input name="radius_svr_1" value="<?php echo $radius_svr_1?>" size=20></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "IP of your first RADIUS server.</span>"; else echo $cn_rdu1_c; ?></div></td>
	</tr>
  	<tr id="radius_svr_2"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Server 2:"; else echo $cn_rdu2_p; ?></td>
		<td><input name="radius_svr_2" value="<?php echo $radius_svr_2?>" size=20></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "IP of your second RADIUS server.</span>"; else echo $cn_rdu2_c; ?></div></td>
	</tr>
  	<tr id="radius_secret"><td></td>
          <td><?php if ($ulang=='en') echo "RADIUS Secret"; else echo $cn_rdpw_p; ?></td>
		<td><input name="radius_secret" value="<?php echo $radius_secret?>" size=20></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "RADIUS Secret (shared key)."; else echo $cn_rdpw_c; ?></div></td>
	</tr>
  	<tr id="radius_nasid"><td></td>
          <td><?php if ($ulang=='en') echo "<b>Hotspot (NASID):</b>"; else echo $cn_nasi_p; ?></td>
		<td><input name="radius_nasid" value="<?php echo $radius_nasid?>" size=20>&nbsp; <a target="_blank" href="http://<?php echo $radius_svr_1?>/landingpage.php?res=notyet&nasid=<?php echo $radius_nasid?>">Preview</td>
          <td><div class="comment"><?php if ($ulang=='en') echo "NASID assigned to you by your RADIUS provider."; else echo $cn_nasi_c; ?></div></td>
	</tr>
  	<tr id="uam_server"><td></td>
          <td><?php if ($ulang=='en') echo "UAM Server:"; else echo $cn_uams_p; ?></td>
		<td><input name="uam_server" value="<?php echo $uam_server?>" size=20></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Address of the Redirect Server (Web Authentication Portal)."; else echo $cn_uams_c; ?></div></td>
	</tr>
  	<tr id="uam_secret"><td></td>
          <td><?php if ($ulang=='en') echo "UAM Secret:"; else echo $cn_uapw_p; ?></td>
		<td><input name="uam_secret" value="<?php echo $uam_secret?>" size=20></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Secret password for your UAM server."; else echo $cn_uapw_c; ?></div></td>
	</tr>
  	<tr id="uam_url"><td></td>
          <td><?php if ($ulang=='en') echo "UAM User URL:"; else echo $cn_uamu_p; ?></td>
		<td><input name="uam_url" value="<?php echo $uam_url?>" size=20></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Locations of the login script URL on the UAM Server."; else echo $cn_uamu_c; ?></div></td>
	</tr>
  	<tr id="uam_domain"><td></td>
          <td ><?php if ($ulang=='en') echo "Allowed Domains:"; else echo $cn_ures_p; ?></td>
		<td ><input name="uam_domain" value="<?php echo $uam_domain?>" size=20> &nbsp; <a href="javascript:def_cp5_domain();">Default sites</td>
          <td valign='top'><div class="comment"><?php if ($ulang=='en') echo "A list of domains separated by commas, which users can access prior to authentication (also known as walled garden). Do not remove default domains from this list or your captive portal may not function. Clear this list to return to default settings."; else echo $cn_ures_c; ?></div></td>
	</tr>

	<tr><td></td> <td height=20></td> </tr>
	<tr><td></td>
		<td colspan=3>
                <table><tr><td>
                 <h2><?php if($ulang=='en') echo 'SSID #2 (Private)'; else echo $cn_sap2_t; ?></h2>
                </td><td width=40></td>
                <td><a href="javascript:show_AP_2();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hide_AP_2();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>
                </tr></table>
                </td>
	</tr>
  	<tr id="enableAP2"><td></td>
          <td><?php if ($ulang=='en') echo "Enable:"; else echo $cn_ap2e_p; ?></td>
		<td>
                <table><tr><td>
<input <?php echo isChecked($ap2_enable) ?> name="ap2_enable" value='1' type="checkbox">
                  </td>
                  <td width=100> </td>
                  <td>Hide:</td>
                  <td>
<input <?php echo isChecked($ap2_hide) ?> name="ap2_hide" value='1' type="checkbox">
                  </td>
                  </tr>
                </table>
                </td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Uncheck to disable this SSID. Check hide while enabled for hidden SSID."; else echo $cn_ap2e_c; ?></div></td>
	</tr>
	<tr id="transparent_bridge"><td></td>
        <td>
            Bridge:
        </td>
        <td>
            <input id="transparent_bridge_check" <?php echo isChecked($transparent_bridge) ?> name="transparent_bridge" value='1' type="checkbox" onclick="if(this.checked==true){document.getElementById('wired_clients').style.display='';document.getElementById('transparent_bridge_vlan').style.display='';}else{document.getElementById('wired_clients').style.display='none';document.getElementById('transparent_bridge_vlan').style.display='none';}">
        </td>
        <td>
            <div class="comment"><span style="color:#ee0000;">(Beta)</span> Check to bridge SSID#2 with the LAN and disable NAT. This lets your LAN or internet modem assign all client DHCP addresses and gives clients access to LAN resources.</div>
        </td>
	</tr>
	
	<tr id="wired_clients"><td></td>
        <td>
            Wired Clients:
        </td>
        <td>
            <input <?php echo isChecked($wired_clients) ?> name="wired_clients" value='1' type="checkbox">
        </td>
        <td>
            <div class="comment"><span style="color:#ee0000;">(Beta)</span> Check to have clients who connect via Ethernet use these SSID#2 settings. (If unchecked, Ethernet clients use SSID#1 settings).</div>
        </td>
    </tr>

	<tr id="transparent_bridge_vlan"><td></td>
	  <td>
	      VLAN Tag:
	  </td>
	  <td>
	      <input id="transparent_bridge_vlan_input" name="transparent_bridge_vlan" value='<?php echo $transparent_bridge_vlan?>' type="text"  onkeyup="this.value = this.value.replace (/\D/, '');if(parseInt(this.value)>4096)this.value=4096;">
	  </td>
	  <td>
	      <div class="comment"><span style="color:#ee0000;">(Beta)</span> Optional Tag for this SSID (allowed values are 2-4096). Must be used with a 802.1Q compatible switch. Do not use with standard switches/routers.</div>
	  </td>
	</tr>
	
  	<tr id="AP2_name"><td></td>
          <td><?php if ($ulang=='en') echo "Network Name:"; else echo $cn_ap2n_p; ?></td>
		<td><input name="ap2_essid" value="<?php echo $ap2_essid ?>" required="1" mask="keyID"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "The SSID to use to connect to this access point."; else echo $cn_ap2n_c; ?></div></td>
	</tr>
  	<tr id="AP2_key"><td></td>
          <td><?php if ($ulang=='en') echo "WPA-PSK/PSK2 Key (Password):"; else echo $cn_ap2k_p; ?></td>

		<td><input name="ap2_key" value="<?php echo $ap2_key ?>" mask="keyID"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Password (key) for this SSID. This MUST be filled in. KEYS MUST BE 8 CHARACTERS OR LONGER with no spaces allowed."; else echo $cn_ap2k_c; ?></div></td>
	</tr>

	


	<tr><td></td><td height=20></td> </tr>
	<tr><td></td><td colspan=5>
            <table><tr><td>
                 <h2><span class='warn'><?php if($ulang=='en') echo 'Advanced'; else echo $cn_advs_t; ?></span></h2>
              </td>
              <td width=40></td>
              <td align=left><a href="javascript:showAdvanced();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hideAdvanced();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>
  	    </tr>
  	    </table>
  	</td>
  	</tr>

  	<tr id="plan"><td></td>
		<td><table width="100%"><td width="40%">Floor plan</td><td><a href="upload_file.php?net_name=<?php echo $net_name; ?>">Upload/Change</a></td></table></td>
		<td><input name="floor_plan" value='<?php echo $floor_plan?>' size=20></td>
		<td><div class="comment"><div>Custom image/floorplan (150Kb max size).</div></div></td>
	</tr>

  	<tr id="root_pwd"><td></td>
          <td><?php if ($ulang=='en') echo "Root Password:"; else echo $cn_arpw_p; ?></td>
  		<td><input name="node_pwd" value="<?php echo $node_pwd?>"></td>
                <td><div class="comment"><?php if ($ulang=='en') echo "Root password for all nodes on your network (used for ssh). You should change this for security."; else echo $cn_arpw_c; ?></div></td>
  	</tr>

  	<tr id="net_block"><td></td>
          <td colspan='2'>
            <table cellpadding="0" cellspacing="0" border=0>
            <tr><td><?php if ($ulang=='en') echo "<b>Gateway Lan Block:</b>"; else echo $cn_alnb_p; ?></td></tr></table>
            <table cellpadding="0" cellspacing="0" border=0>
            <tr><td width=25></td><td><input type='radio' name='lanblk_type' value='blk_none' onClick = "set_lanblk('0');"></td><td>Off</td><td width=40></td><td><input type='radio' name='lanblk_type' value='blk_all' onClick = "set_lanblk('1');"></td><td>SSID#1 & SSID#2</td></tr>
                   <tr><td></td><td><input type='radio' name='lanblk_type' value='blk_ap1' onClick = "set_lanblk('2');"></td><td>SSID#1</td><td></td>
                       <td><input type='radio' name='lanblk_type' value='blk_ap2' onClick = "set_lanblk('3');"></td><td>SSID#2</td></tr>
             </table>
          </td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Prevents users on the wireless networks from accessing your wired LAN."; else echo $cn_alnb_c; ?></div></td>
  	</tr>

  	<tr id="ap1_isolate"><td></td>
          <td><?php if ($ulang=='en') echo "AP Isolation (SSID #1)"; else echo $cn_ap1b_p; ?></td>
          <td><input <?php echo isChecked($ap1_isolate) ?>name="ap1_isolate" value=1 type="checkbox"></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Prevents users from accessing eachother's computers. Unchecking this box will allow you to do things like share a printer attached to the mesh, but may allow potential malicious users' access to the network. It is recommended you un-check this ONLY if all users have a firewall enabled on their computers."; else echo $cn_ap1b_c; ?></div></td>
  	</tr>
  	<tr id="ap2_isolate"><td></td>
          <td><?php if ($ulang=='en') echo "AP Isolation (SSID #2)"; else echo $cn_ap2b_p; ?></td>
  		<td><input <?php echo isChecked($ap2_isolate) ?> name="ap2_isolate" value=1 type="checkbox"></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Same as above - for SSID #2."; else echo $cn_ap2b_c; ?></div></td>
	</tr>
	
	<tr id="channel2"><td></td>
          <td><?php if ($ulang=='en') echo "2.4 GHz Channel:"; else echo $cn_ardo_p; ?></td>
  		<td><select name="radio_channel2">
  		       <option <?php if($radio_channel2=="1"):?>selected="selected"<?php endif;?> value="1">1</option>
			<option <?php if($radio_channel2=="2"):?>selected="selected"<?php endif;?> value="2">2</option>
			<option <?php if($radio_channel2=="3"):?>selected="selected"<?php endif;?> value="3">3</option>
			<option <?php if($radio_channel2=="4"):?>selected="selected"<?php endif;?> value="4">4</option>
			<option <?php if($radio_channel2=="5"):?>selected="selected"<?php endif;?> value="5">5</option>
			<option <?php if($radio_channel2=="6"):?>selected="selected"<?php endif;?> value="6">6</option>
			<option <?php if($radio_channel2=="7"):?>selected="selected"<?php endif;?> value="7">7</option>
			<option <?php if($radio_channel2=="8"):?>selected="selected"<?php endif;?> value="8">8</option>
			<option <?php if($radio_channel2=="9"):?>selected="selected"<?php endif;?> value="9">9</option>
  		       <option <?php if($radio_channel2=="10"):?>selected="selected"<?php endif;?> value="10">10</option>
			<option <?php if($radio_channel2=="11"):?>selected="selected"<?php endif;?> value="11">11</option>
  		    </select>
  		    <!--input name="radio_channel9" size=2 maxlength=2 value='<?php //echo $radio_channel9?>'-->
  		</td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Channel for mesh on single band devices. Channel changes take 30-45 minutes to occur. During this time, your network will have outages so this is best done during low-usage hours."; else echo $cn_ardo_c; ?></div></td>
	</tr>

  	<tr id="channel"><td></td>
          <td><?php if ($ulang=='en') echo "5 GHz Channel:"; else echo $cn_ardo_p; ?></td>
            <td><select id="radio_channel_country_selector" name="radio_channel_country" onchange="change_channel_country(this.value)">
                    <option <?php if($radio_channel_country == "us"):?>selected="selected"<?php endif;?> value="us">United States</option>
                    <option <?php if($radio_channel_country == "manual"):?>selected="selected"<?php endif;?> value="manual">Manual</option>
                </select>
                <select id="radio_channel_selector" name="radio_channel">
                    <option class="us" value="36">36</option>
                    <option class="us" value="40">40</option>
                    <option class="us" value="44">44</option>
                    <option class="us" value="48">48</option>
                    <option class="us" value="149">149</option>
                    <option class="us" value="153">153</option>
                    <option class="us" value="157">157</option>
                    <option class="us" value="161">161</option>
                    <option class="us" value="165">165</option>
                    <option class="us" value="42">42*</option>
                    <option class="us" value="50">50*</option>
                    <option class="us" value="58">58*</option>
                    <option class="us" value="152">152*</option>
                    <option class="us" value="160">160*</option>                    
                </select>
                <input id="radio_channel_input" name="_radio_channel" size=3 maxlength=3 value='<?php echo $radio_channel?>' style="display:none;"/>
            </td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Channel for mesh on dual band devices. Channel changes take 30-45 minutes to occur. During this time, your network will have outages so this is best done during low-usage hours. <br/> * available only on Super A hardware."; else echo $cn_ardo_c; ?></div></td>
	</tr>
	
	
       <tr id="channel9"><td></td>
          <td><?php if ($ulang=='en') echo "900 MHz Channel:"; else echo $cn_ardo_p; ?></td>
  		<td><select name="radio_channel9">
  		        <option <?php if($radio_channel9=="5"):?>selected="selected"<?php endif;?> value="5">912 MHz</option>
  		        <option <?php if($radio_channel9=="6"):?>selected="selected"<?php endif;?> value="6">917 MHz</option>
  		    </select>
  		    <!--input name="radio_channel9" size=2 maxlength=2 value='<?php //echo $radio_channel9?>'-->
  		</td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Channel for mesh on dual band devices. Channel changes take 30-45 minutes to occur. During this time, your network will have outages so this is best done during low-usage hours."; else echo $cn_ardo_c; ?></div></td>
	</tr>
	
	<tr id="countrycode"><td></td><td>
<?php
     echo 'Country Code:</td><td><select name="country_code" style="font-family: Courier New; font-size:12px">';
//     echo "<option"; if (208 == $countrycode) {echo " selected";} echo ">DENMARK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 208</option>";
//     echo "<option"; if (246 == $countrycode) {echo " selected";} echo ">FINLAND&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 246</option>";
//     echo "<option"; if (250 == $countrycode) {echo " selected";} echo ">FRANCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 250</option>";
//     echo "<option"; if (276 == $countrycode) {echo " selected";} echo ">GERMANY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 276</option>";
//     echo "<option"; if (380 == $countrycode) {echo " selected";} echo ">ITALY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 380</option>";
//     echo "<option value='392'"; if (392 == $countrycode) {echo " selected";} echo ">392 EUROPA (1-14)</option>";
//     echo "<option"; if (578 == $countrycode) {echo " selected";} echo ">NORWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 578</option>";
//     echo "<option"; if (620 == $countrycode) {echo " selected";} echo ">PORTUGAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 620</option>";
//     echo "<option"; if (724 == $countrycode) {echo " selected";} echo ">SPAIN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 724</option>";
//     echo "<option"; if (752 == $countrycode) {echo " selected";} echo ">SWEDEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 752</option>";
//     echo "<option"; if (826 == $countrycode) {echo " selected";} echo ">UNITED KINGDOM&nbsp; 826</option>";
     echo "<option value='840'"; if (840 == $countrycode) {echo " selected";} echo ">840 EEUU (1-11)</option>";

?>
      </td><td><div class="comment"><?php if ($ulang=='en') echo " Select the country according to the channel to use. Recommended not to touch.</span>"; else echo $cn_ardo_c; ?></div></td>
	</tr>
	
	
	
	
	<tr id="checkinperiod"><td></td>
          <td><?php echo "Node Checkin:"; ?></td>
<?php
     echo '<td><select name="checkin_period">';
     echo "<option"; if (5 == $checkin_period) {echo " selected";} echo ">5</option>";
     echo "<option"; if (10 == $checkin_period) {echo " selected";} echo ">10</option>";
     echo "<option"; if (15 == $checkin_period) {echo " selected";} echo ">15</option>";
     echo "<option"; if (20 == $checkin_period) {echo " selected";} echo ">20</option>";
	 echo "<option"; if (25 == $checkin_period) {echo " selected";} echo ">25</option>";
?>
          <td><div class="comment"><?php if ($ulang=='en') echo "Duration between checkins<span class='warn'>A long period does not cause immediate changes in the modified network parameters.</span>"; else echo $cn_ardo_c; ?></div></td>
	</tr>

	<tr id="rebootfreq"><td></td>
		<td><?php echo "Scheduled Reboot:"; ?></td>
		<?php
		echo '<td><table><tr><td><select name="reboot_freq">';
		echo "<option value='never'"; if ("never" == $reboot_freq) {echo " selected";} echo ">Disabled</option>";
		echo "<option value='24'"; if ("24" == $reboot_freq) {echo " selected";} echo ">every 24h.</option>";
		echo "<option value='48'"; if ("48" == $reboot_freq) {echo " selected";} echo ">every 48h.</option>";
		echo "<option value='w'"; if ("w" == $reboot_freq) {echo " selected";} echo ">every week</option>";
		echo "<option value='m'"; if ("m" == $reboot_freq) {echo " selected";} echo ">every month&nbsp;&nbsp;</option></td></tr>";

		echo '<tr><td><select name="reboot_freq_hour">';
		echo "<option value='00'"; if ("00" == $reboot_freq_hour) {echo " selected";} echo ">00:00h. &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</option>";
		echo "<option value='01'"; if ("01" == $reboot_freq_hour) {echo " selected";} echo ">01:00h.</option>";
		echo "<option value='02'"; if ("02" == $reboot_freq_hour) {echo " selected";} echo ">02:00h.</option>";
		echo "<option value='03'"; if ("03" == $reboot_freq_hour) {echo " selected";} echo ">03:00h.</option>";
		echo "<option value='04'"; if ("04" == $reboot_freq_hour) {echo " selected";} echo ">04:00h.</option>";
		echo "<option value='05'"; if ("05" == $reboot_freq_hour) {echo " selected";} echo ">05:00h.</option>";
		echo "<option value='06'"; if ("06" == $reboot_freq_hour) {echo " selected";} echo ">06:00h.</option>";
		echo "<option value='07'"; if ("07" == $reboot_freq_hour) {echo " selected";} echo ">07:00h.</option>";
		echo "<option value='08'"; if ("08" == $reboot_freq_hour) {echo " selected";} echo ">08:00h.</option>";
		echo "<option value='09'"; if ("09" == $reboot_freq_hour) {echo " selected";} echo ">09:00h.</option>";
		echo "<option value='10'"; if ("10" == $reboot_freq_hour) {echo " selected";} echo ">10:00h.</option>";
		echo "<option value='11'"; if ("11" == $reboot_freq_hour) {echo " selected";} echo ">11:00h.</option>";
		echo "<option value='12'"; if ("12" == $reboot_freq_hour) {echo " selected";} echo ">12:00h.</option>";
		echo "<option value='13'"; if ("13" == $reboot_freq_hour) {echo " selected";} echo ">13:00h.</option>";
		echo "<option value='14'"; if ("14" == $reboot_freq_hour) {echo " selected";} echo ">14:00h.</option>";
		echo "<option value='15'"; if ("15" == $reboot_freq_hour) {echo " selected";} echo ">15:00h.</option>";
		echo "<option value='16'"; if ("16" == $reboot_freq_hour) {echo " selected";} echo ">16:00h.</option>";
		echo "<option value='17'"; if ("17" == $reboot_freq_hour) {echo " selected";} echo ">17:00h.</option>";
		echo "<option value='18'"; if ("18" == $reboot_freq_hour) {echo " selected";} echo ">18:00h.</option>";
		echo "<option value='19'"; if ("19" == $reboot_freq_hour) {echo " selected";} echo ">19:00h.</option>";
		echo "<option value='20'"; if ("20" == $reboot_freq_hour) {echo " selected";} echo ">20:00h.</option>";
		echo "<option value='21'"; if ("21" == $reboot_freq_hour) {echo " selected";} echo ">21:00h.</option>";
		echo "<option value='22'"; if ("22" == $reboot_freq_hour) {echo " selected";} echo ">22:00h.</option>";
		echo "<option value='23'"; if ("23" == $reboot_freq_hour) {echo " selected";} echo ">23:00h.</option></td></tr></table></td>";
		?>
		<td><div class="comment"><?php if ($ulang=='en') echo "If enabled, you may set your nodes to reboot on a pre-defined schedule."; else echo $cn_ardo_c; ?></div></td>
	</tr>
	
	
	<tr id="upgrade_win"><td></td>
		<td><?php echo "Upgrade Window:"; ?></td>
		<?php
		echo '<td><table><tr><td><select name="upgrade_f">';
		echo "<option value='01'"; if ("01" == $upgrade_f) {echo " selected";} echo ">01:00h. &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</option>";
		echo "<option value='02'"; if ("02" == $upgrade_f) {echo " selected";} echo ">02:00h.</option>";
		echo "<option value='03'"; if ("03" == $upgrade_f) {echo " selected";} echo ">03:00h.</option>";
		echo "<option value='04'"; if ("04" == $upgrade_f) {echo " selected";} echo ">04:00h.</option>";
		echo "<option value='05'"; if ("05" == $upgrade_f) {echo " selected";} echo ">05:00h.</option>";
		echo "<option value='06'"; if ("06" == $upgrade_f) {echo " selected";} echo ">06:00h.</option>";
		echo "<option value='07'"; if ("07" == $upgrade_f) {echo " selected";} echo ">07:00h.</option>";
		echo "<option value='08'"; if ("08" == $upgrade_f) {echo " selected";} echo ">08:00h.</option>";
		echo "<option value='09'"; if ("09" == $upgrade_f) {echo " selected";} echo ">09:00h.</option>";
		echo "<option value='10'"; if ("10" == $upgrade_f) {echo " selected";} echo ">10:00h.</option>";
		echo "<option value='11'"; if ("11" == $upgrade_f) {echo " selected";} echo ">11:00h.</option>";
		echo "<option value='12'"; if ("12" == $upgrade_f) {echo " selected";} echo ">12:00h.</option>";
		echo "<option value='13'"; if ("13" == $upgrade_f) {echo " selected";} echo ">13:00h.</option>";
		echo "<option value='14'"; if ("14" == $upgrade_f) {echo " selected";} echo ">14:00h.</option>";
		echo "<option value='15'"; if ("15" == $upgrade_f) {echo " selected";} echo ">15:00h.</option>";
		echo "<option value='16'"; if ("16" == $upgrade_f) {echo " selected";} echo ">16:00h.</option>";
		echo "<option value='17'"; if ("17" == $upgrade_f) {echo " selected";} echo ">17:00h.</option>";
		echo "<option value='18'"; if ("18" == $upgrade_f) {echo " selected";} echo ">18:00h.</option>";
		echo "<option value='19'"; if ("19" == $upgrade_f) {echo " selected";} echo ">19:00h.</option>";
		echo "<option value='20'"; if ("20" == $upgrade_f) {echo " selected";} echo ">20:00h.</option>";
		echo "<option value='21'"; if ("21" == $upgrade_f) {echo " selected";} echo ">21:00h.</option>";
		echo "<option value='22'"; if ("22" == $upgrade_f) {echo " selected";} echo ">22:00h.</option>";
		echo "<option value='23'"; if ("23" == $upgrade_f) {echo " selected";} echo ">23:00h.</option></td></tr>";

		echo '<tr><td><select name="upgrade_t">';
		echo "<option value='01'"; if ("01" == $upgrade_t) {echo " selected";} echo ">01:00h. &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</option>";
		echo "<option value='02'"; if ("02" == $upgrade_t) {echo " selected";} echo ">02:00h.</option>";
		echo "<option value='03'"; if ("03" == $upgrade_t) {echo " selected";} echo ">03:00h.</option>";
		echo "<option value='04'"; if ("04" == $upgrade_t) {echo " selected";} echo ">04:00h.</option>";
		echo "<option value='05'"; if ("05" == $upgrade_t) {echo " selected";} echo ">05:00h.</option>";
		echo "<option value='06'"; if ("06" == $upgrade_t) {echo " selected";} echo ">06:00h.</option>";
		echo "<option value='07'"; if ("07" == $upgrade_t) {echo " selected";} echo ">07:00h.</option>";
		echo "<option value='08'"; if ("08" == $upgrade_t) {echo " selected";} echo ">08:00h.</option>";
		echo "<option value='09'"; if ("09" == $upgrade_t) {echo " selected";} echo ">09:00h.</option>";
		echo "<option value='10'"; if ("10" == $upgrade_t) {echo " selected";} echo ">10:00h.</option>";
		echo "<option value='11'"; if ("11" == $upgrade_t) {echo " selected";} echo ">11:00h.</option>";
		echo "<option value='12'"; if ("12" == $upgrade_t) {echo " selected";} echo ">12:00h.</option>";
		echo "<option value='13'"; if ("13" == $upgrade_t) {echo " selected";} echo ">13:00h.</option>";
		echo "<option value='14'"; if ("14" == $upgrade_t) {echo " selected";} echo ">14:00h.</option>";
		echo "<option value='15'"; if ("15" == $upgrade_t) {echo " selected";} echo ">15:00h.</option>";
		echo "<option value='16'"; if ("16" == $upgrade_t) {echo " selected";} echo ">16:00h.</option>";
		echo "<option value='17'"; if ("17" == $upgrade_t) {echo " selected";} echo ">17:00h.</option>";
		echo "<option value='18'"; if ("18" == $upgrade_t) {echo " selected";} echo ">18:00h.</option>";
		echo "<option value='19'"; if ("19" == $upgrade_t) {echo " selected";} echo ">19:00h.</option>";
		echo "<option value='20'"; if ("20" == $upgrade_t) {echo " selected";} echo ">20:00h.</option>";
		echo "<option value='21'"; if ("21" == $upgrade_t) {echo " selected";} echo ">21:00h.</option>";
		echo "<option value='22'"; if ("22" == $upgrade_t) {echo " selected";} echo ">22:00h.</option>";
		echo "<option value='23'"; if ("23" == $upgrade_t) {echo " selected";} echo ">23:00h.</option></td></tr></table></td>";
		?>
		<td><div class="comment"><?php if ($ulang=='en') echo "If enabled, you may set a date and time window when new firmware will be automatically installed. Takes effect only when automatic upgrades are allowed."; else echo $cn_ardo_c; ?></div></td>
	</tr>
	
	
	
	
<!--  	<tr id="olsr_enable"><td></td>
  		<td><?php if($ulang=='en') echo "OLSR"; else echo $cn_olsr_p; ?></td>
  		<td><input <?php echo isChecked($olsr_enable) ?> name="olsr_enable" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "<span class='warn'>Recomendable no tocar.</span> Activado por defecto para usar OLSR."; else echo $cn_olsr_c; ?></div></td>
	</tr> -->
  	<tr id="frz_version"><td></td>
  		<td><?php if($ulang=='en') echo "Disable Automatic Updates:"; else echo $cn_frzv_p; ?></td>
  		<td><input <?php echo isChecked($frz_version) ?> name="frz_version" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "<span class='warn'>Check to disable all automatic upgrades and freeze the firmware at the current version."; else echo $cn_frzv_c; ?></div></td>
	</tr>
  	<tr id="test_firmware_enable"><td></td>
  		<td>Use test firmware<br>Use Test Firmware:</td>
  		<td><input <?php echo isChecked($test_firmware_enable) ?> name="test_firmware_enable" value=0 type="checkbox"></td>
		<td><div class="comment">If you would like your network to track the latest TEST releases, check this box. WARNING: Do this ONLY if this is a test network in a location you can easily get to should reflashing be required. This is for development purposes ONLY! Do not turn this on for production networks!</div></td>
	</tr>
  	<tr id="strict_mesh"><td></td>
  		<td><?php if($ulang=='en') echo "Block Alien Nodes:"; else echo $cn_strc_p; ?></td>
  		<td><input <?php echo isChecked($strict_mesh) ?> name="strict_mesh" value=1 type="checkbox"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Limits mesh access only to nodes you add to the network."; else echo $cn_strc_c; ?></div></td>
	</tr>
	<tr id="stand_alone"><td></td>
  		<td><?php if($ulang=='en') echo "Stand Alone Mode:"; else echo $cn_stda_p; ?></td>
  		<td><input <?php echo isChecked($stand_alone) ?> name="stand_alone" value=1 type="checkbox"></td>
                <td><div class="comment"><?php if($ulang=='en') echo "Allows mesh to operate without an active connection to the Internet. All gateways must be plugged into a DHCP source in order for this to function properly."; else echo $cn_stda_c; ?></div></td>
  	</tr>

  	<tr id="alerts_email"><td></td>
 		<td colspan=2>
   			<table cellpadding="0" cellspacing="0" border=0>
            <tr>
    	<td><?php echo "E-Mail Alerts:"; ?></td>
    		<td width=40></td>
    	<td><input name="email2" size=33 value="<?php echo $email2?>"></td>
            </tr>
            </table>
        </td>

		<td><div class="comment"><?php if($ulang=='en') echo "Outage notifications will be sent to these addresses. Separate multiple e-mail addresses with spaces."; else echo $cn_cust_c; ?></div></td>
	</tr>
	
	
	<tr id="nodedown_min"><td></td>
		<td><?php echo "Alert Timer:"; ?></td>
		<td><input name="min_nodedown" size=19 value="<?php echo $min_nodedown?>"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Alerts will be sent out only once the node downtime exceeds the time limit set here (Must be greater than 30 minutes)."; else echo $cn_cust_c; ?></div></td>
	</tr>
	
	

	
	
	
	
	
	
	
	


	<tr><td></td> <td height=20></td> </tr>
	<tr><td></td>
		<td colspan=3>
                <table><tr><td>
<h2><span class='warn'><?php if($ulang=='en') echo 'Alternate Servers'; else echo $cn_dset_t; ?></span></h2>
                </td><td width=40></td>
              <td align=left><a href="javascript:show_dash();"><?php if($ulang=='en') echo "show"; else echo $cn_show_t; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:hide_dash();"><?php if($ulang=='en') echo "hide"; else echo $cn_hide_t; ?></a></td>
                </tr></table>
             </td>
  	</tr>

  	<tr id="dashboard_url"><td></td>
  		<td><?php if($ulang=='en') echo "Alternate Dashboard:"; else echo $cn_dash_p; ?></td>
  		<td><input name="dashboard_url" size=20 value="<?php echo $dashboard_url?>"></td>
		<td><div class="comment"><?php if($ulang=='en') echo "Migrate your network to a pre-configured control panel on an external server. <br> <br><span class='warn'>Failure to accurately do this may orphan your routers, requiring a reflash. Use this option with extreme caution!.</span> <br><br>Example: <b> checkin.awdmesh.com/</b>"; else echo $cn_dash_c; ?></div></td>
  	</tr>

       <tr id="DNS"><td></td>
		<td>DNS Servers
		<?php
		echo '<select name="DNS" onchange="asignaDNS(this)">';
		echo "<option value=''"; if ("" == $DNS1) {echo " selected";} echo "> </option>";
		echo "<option value='OpenDNS'"; if ("208.67.222.222" == $DNS1 && "208.67.220.220" == $DNS2) {echo " selected";} echo ">OpenDNS</option>";
		echo "<option value='FamilyShield'"; if ("208.67.222.123" == $DNS1 && "208.67.220.123" == $DNS2) {echo " selected";} echo ">FamilyShield</option>";
		echo "<option value='Google'"; if ("8.8.8.8" == $DNS1 && "8.8.4.4" == $DNS2) {echo " selected";} echo ">Google</option></select></td>";
        		?>
        <td><table><tr><td><input name="DNS1" value="<?php echo $DNS1;?>" required="1"></td></tr><tr><td><input name="DNS2" value="<?php echo $DNS2;?>" required="1"></td></tr></table></td>
  		<td><div class="comment"><div>DNS servers. Useful for filtering content. You can indicate your preference. <span class='warn'>Do not leave blank, or else clients may not be able to resolve web sites.</span></div></div></td>
	</tr>

       <tr id="SMTP_server"><td></td>
          <td><?php if ($ulang=='en') echo "SMTP Server:"; else echo $cn_ardo_p; ?></td>
  		<td><input name="SMTP" size=12 value='<?php echo $SMTP?>'></td>
          <td><div class="comment"><?php if ($ulang=='en') echo "Alternate SMTP server IP address for your network. This allows users to send SMTP email by using your ISP's SMTP server. (Format xx.xx.xx.xx). Leave blank to allow user defined SMTP (default)."; else echo $cn_ardo_c; ?></div></td>
	</tr>

       <tr id="custom_sh"><td></td>
                <td colspan=2>
                  <table cellpadding="0" cellspacing="0" border=0>
                  <tr>
  		    <td><?php if($ulang=='en') echo "<b>Enable custom.sh</b>"; else echo $cn_cust_p; ?></td>
                    <td width=40></td>
  		    <td><input <?php echo isChecked($custm_sh_on) ?> name="custm_sh_on" value=1 type="checkbox"></td>
                  </tr>
                  <tr>
  		    <td><?php echo "custom.sh Server:"; ?></td>
                    <td width=45></td>
  		    <td><input name="custm_sh_url" size=30 value="<?php echo $custm_sh_url?>"></td>
                  </tr>		  
                  </table>
                </td>

		<td><div class="comment"><?php if($ulang=='en') echo "Enter the URL for the custom.sh script to run after node check-ins. <span class='warn'><br><br>IMPORTANT: This script will not be verified so make sure you have tested locally first as this has the potential to crash your network.</span> <br><br>Example: <b>awdmesh.com/custom/</b> <br> Do not forget the / at the end of the URL."; else echo $cn_cust_c; ?></div></td>
	</tr>

	
	
	
	

	
	
	

	<tr><td height=20></td>
	<tr><td></td>
		<td colspan=3 align=center><input name="submit" value="<?php if($ulang=='en') echo 'Update Network Settings'; else echo $cn_save_t; ?>" type="submit"><br><br><font style='font-family:Helvetica; font-size:10px; color:#666666;'> (Please allow 5-10 minutes for changes to take effect.)</font></td>
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
