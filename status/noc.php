<?php

session_start();



//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid']))  {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}

if(!isset($_GET['period'])) {$noc="noc_6h.php";} else {$noc=$_GET['period'];}

//Includes
include "../lib/style.php";

?>







<head>
  <title>Bird's Eye View</title>

  <!--<META HTTP-EQUIV="Refresh" CONTENT="60">-->

  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script>
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<!-- Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column. -->
<script src='../lib/sorttable.js'></script>
</head>
<body onload=Nifty("div.note");>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>





	





<?php
include "../lib/menu.php";
include "../lib/connectDB.php";
//setTable("node");
include '../lib/toolbox.php';

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

if($ulang=='en') {
echo <<<TITLE
<table width=1040><tr><td align='center'>
</td></tr></table>

TITLE;





//Get nodes that match network id from database
//$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
//$query = "SELECT * FROM networks as n WHERE n.master_netid=" . $_SESSION["masternetid"];
$query = "SELECT count(*) as sum FROM node as n, network as nw WHERE n.netid=nw.id and nw.master_netid=" . $_SESSION["masternetid"];
$result = mysql_query($query, $conn);
if ( false === $result )
{
	// Problem with querying the database
	//
	die("<div class=error>No data</div>");
}
else
	$aRow = mysql_fetch_assoc( $result );
	
	if ( 0 == $aRow[ "sum" ] )
	{
		// There are no nodes in the any of the networks connected to the master network
		// 
 		die("<div class=error>No data</div>");
	}
} else {
echo <<<TITLE
<table width=1040><tr><td align='right' height=80>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><b>&#32593;&#32476;&#36816;&#34892;&#29366;&#24577;</b> &nbsp; (&#32593;&#32476;&#21517;&#65306;$display_name)</font>
</td>
<td width=30> </td>
<td align='left' width=300><a href="view_adv.php">&#26356;&#22810;&#21442;&#25968;</a></td>
</tr></table>
<div class="note" id="tip">
<span style="color:ff3300;"><b>&#32418;&#33394;</b></span>&#30340;&#33410;&#28857;&#38656;&#35201;&#26816;&#26597;
<b>&#31895;&#20307;&#23383;</b>&#34920;&#31034;&#27492;&#33410;&#28857;&#26159;&#32593;&#20851; &nbsp; &nbsp;
<a href="javascript:close()">&#38544;&#21435;</a></div>
TITLE;

}



?>
<br><br>
<a href="noc.php?period=noc_6h.php"><img src="images/6hour.jpg" border=0></a>
<a href="noc.php?period=noc_day.php"><img src="images/24hour.jpg" border=0></a>
<a href="noc.php?period=noc_week.php"><img src="images/week.jpg" border=0></a>
<a href="noc.php?period=noc_month.php"><img src="images/monthly.jpg" border=0></a>
<br><br><h1></h1>
<?php
include($noc);

?>
<br>
</td></tr></table>
</body>
