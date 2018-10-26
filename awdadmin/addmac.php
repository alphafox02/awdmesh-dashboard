<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>CloudController | MAC Authorization</title>
<?php include "../lib/style.php"; ?>

<script type="text/javascript">
  function show_msg (){
	document.getElementById("pgmsg").style.display="";
  }
  function hide_msg (){
	document.getElementById("pgmsg").style.display="none";
  }
  function clear_req(form) {
    form.mac.style.border='1px solid #7F9DB9';
  }
</script>

</head>
<body bgcolor="#FFFFFF" align="center" onLoad="Nifty('div.comment');hide_msg();">
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php
include '../lib/menu.php';
require_once "../lib/connectDB.php";
?>

<form name="addnode_manual" method="POST">
<center>
  <table width="600"  border="0" cellpadding="0" cellspacing="0" >
  <tr><td align='center'>
      <h1><font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0075ad;">MAC Address Dashboard Authorization</font></h1>
  </td><td width=0></td>
  </td></tr>
  <tr>
</tr>
  <tr><td height=20></td></tr>
  </table>
  <table width="600"  border="0" cellpadding="0" cellspacing="0" >
  <tr id="pgmsg"><td><div name='msgbody' id='msgbody' class='error'>Error por defecto</div>
  </td></tr>
  </table>

<div name='pgbody' id='pgbody'>
  <table width="600"  border="0" cellpadding="0" cellspacing="0" id="node">
			<tr><td height=10></td></tr>
			<tr>
				 <td width=125><span class="style1"></span></td>
				 <td><textarea cols="25" rows="6"  name="mac"></textarea></td>
				 <td><div class="comment">Seperate MAC Multiple MAC addresses by commas. Example:<br><br><b>00:1E:3A:B8:93:84,00:21:15:A5:8E:76</b></div></td>
			</tr><tr>
<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
<td></td>
				 <td align="right"><input type="button" name="Add" value="Authorize" onClick="addNode(this.form)"></td></tr>
    </table>
</div>

</center>
</form>


<div align="center" id="top">

</div>
</td></tr></table>

<script type="text/javascript">
// place the script here so that the menu.php setting can be passed into the code
  function addNode(form) {
    hide_msg();
    clear_req(form);
    var mactexts = form.mac.value;
    var macs = mactexts.split(",");
    var newmacs = "";
    for(var i=0;i<macs.length;i++){
        if(macs[i].indexOf(":") == -1){
            var to_mac = "";
            to_mac = macs[i].replace(/(\S{2})/g,"$1:");
            to_mac = to_mac.replace(/:$/,"");
            newmacs += to_mac
        }
        else
            newmacs += macs[i];
        if(i != macs.length-1)
            newmacs+=",";
    }
    
    var req;

		if (window.XMLHttpRequest)
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");
		req.open("POST", "c_addmac.php", false);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.setRequestHeader('Cache-Control', 'private');
		var encoded = "mac=" + newmacs;

		req.send(encoded);
		if (req.status != 200) {
  			alert("Hubo un problema al grabar la mac: " + req.responseText);
		} else if (req.responseText.search("Error") == 0){
  			alert(req.responseText);
		}else {
			document.getElementById('pgbody').innerHTML= "<center><br><br><font style='font-family: arial; font-size: 18px; color:898989; line-height: 1.4;'>La/s mac/s se ha/n agregado satisfactoriamente.</font></center>";
		}
	}
</script>

<br><br>
</body>
</html>
