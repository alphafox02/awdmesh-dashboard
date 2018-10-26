<?php

session_start();

$net_name = $_SESSION['net_name'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid']))  {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}

if(!isset($_GET['graph'])) {$grafico="graph_net_6h.php";} else {$grafico=$_GET['graph'];}
if($_GET['graph']=="graph_net_day.php"){$graf="graph_net_week.php";}else{$graf="graph_net_6h.php"; }

//Includes
include "../lib/style.php";

?>







<head>
  <title>Network Usage | <?php  echo $net_name; ?></title>

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
include_once "../lib/connectDB.php";
setTable("node");
include '../lib/toolbox.php';

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

if($ulang=='en') {
echo <<<TITLE
<table width=1040 border=0><tr><td align='right' height=20>
</td>

<td width=50> </td>
</tr></table>

TITLE;





//Get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>No data</div>");

} else {
echo <<<TITLE
<table width=1040><tr><td align='right' height=80>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><b>&#32593;&#32476;&#36816;&#34892;&#29366;&#24577;</b> &nbsp; (&#32593;&#32476;&#21517;&#65306;$display_name)</font>
</td>
<td width=30> </td>
<td align='left' width=300><a href="view_adv.php">&#26356;&#22810;&#21442;&#25968;</a></td>
</tr></table>

<span style="color:ff3300;"><b>&#32418;&#33394;</b></span>&#30340;&#33410;&#28857;&#38656;&#35201;&#26816;&#26597;
<b>&#31895;&#20307;&#23383;</b>&#34920;&#31034;&#27492;&#33410;&#28857;&#26159;&#32593;&#20851; &nbsp; &nbsp;
<a href="javascript:close()">&#38544;&#21435;</a></div>
TITLE;

}



?>

<a href="viewg.php?graph=graph_net_6h.php"><img src="images/6hour.jpg" border=0></a>
<a href="viewg.php?graph=graph_net_day.php"><img src="images/24hour.jpg" border=0></a>
<a href="viewg.php?graph=graph_net_week.php"><img src="images/week.jpg" border=0></a>
<a href="viewg.php?graph=graph_net_month.php"><img src="images/monthly.jpg" border=0></a><br>
<br>
<?php
include($grafico);
//include("graph_net_day.php");
?>
<br>
</td></tr></table>
</body>
