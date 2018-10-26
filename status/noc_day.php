<?php
/* Name: view.php
 * Purpose: master view for network settings.


 */

session_start();

//if ($_SESSION['user_type']!='admin')
//	header("Location: ../entry/login.php");
require_once '../lib/connectDB.php';



//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}

$query0 = "SELECT * FROM network  WHERE master_netid='".$_SESSION["masternetid"]."' ORDER BY net_name";
$result0 = mysql_query($query0, $conn);
if(mysql_num_rows($result0)<1) {die("No networks found");}
//die("No networks found");
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Network status</title>

	<?php include '../lib/style.php';?>
	
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});

<?php
$red=array(); $texto=array();

while($row0 = mysql_fetch_array($result0, MYSQL_ASSOC)) 
{

$time_window = date('Y-m-d H:i:s', strtotime('-24 hours'));

$query = "SELECT * FROM client  WHERE netid='".$row0["id"]."' AND c_time > '$time_window' ORDER BY c_time DESC";
$result = mysql_query($query, $conn);
//if(mysql_num_rows($result)<1) {echo("No activity data");$red[$row0["id"]]="0";} else {$red[$row0["id"]]="1";}
//if(mysql_num_rows($result)<1) {$texto[$row0["id"]]="No activity data"; continue;} else {$red[$row0["id"]]="1";}
  if ( mysql_num_rows( $result ) < 1 ) 
	{ 
		$texto[ $row0[ "id" ] ] = "No activity data"; 
	    continue;
	}
	else 
	{
		$red[ $row0[ "id" ] ] = "1";
	}
	
	echo "google.setOnLoadCallback(drawChart".$row0['id'].");\n";

?>


      function drawChart<?php echo $row0['id'];?>() {
        var data<?php echo $row0['id'];?>= new google.visualization.DataTable();
        data<?php echo $row0['id'];?>.addColumn('string', 'Tiempo');

        data<?php echo $row0['id'];?>.addColumn('number', 'Download (MB)');
        data<?php echo $row0['id'];?>.addColumn('number', 'Upload (MB)');
        data<?php echo $row0['id'];?>.addColumn('number', 'Clients');
        data<?php echo $row0['id'];?>.addRows([

<?php

$data0=array(); $data1=array(); $numusers=array(); $totalusers=1; $totalkbd=0;  $totalkbu=0;

while ($i < 288) {$data0[$i]= 0; $data1[$i]= 0; $numusers[$i]= 0; $i++;}
$slotnow = (date('H', time()) * 12) + intval(date ('i', time()) / 5)+1;

while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$slot = ((date('H', strtotime($row['c_time']))) * 12) + intval(date ('i', strtotime($row['c_time'])) / 5);
    if($row['ckbd_hist'] < 0) {$row['ckbd_hist']=0;}
    if($row['ckbu_hist'] < 0) {$row['ckbu_hist']=0;}
	if (($slot-$slotnow) < 0) {
		$data0[($slot-$slotnow)+288] = round($data0[($slot-$slotnow)+288] + ($row['ckbd_hist'] / 1024),2);
		$data1[($slot-$slotnow)+288] = round($data1[($slot-$slotnow)+288] + ($row['ckbu_hist'] / 1024),2);
		$time[($slot-$slotnow)+288] = substr($row["c_time"],11,5);
		if(strrpos($macusers[($slot-$slotnow)+288], $row['c_mac'])==false) {
			$macusers[($slot-$slotnow)+288] .= $row['c_mac']." ";
			++$numusers[($slot-$slotnow)+288];
		}
		$totalkbd = $totalkbd + round($row['ckbd_hist'] / 1024,2);
		$totalkbu = $totalkbu + round($row['ckbu_hist'] / 1024,2);
		if(strrpos($macs, $row['c_mac'])==false) {++$totalusers; $macs .= $row['c_mac']." ";}
	} else {
		$data0[($slot-$slotnow)] = round($data0[($slot-$slotnow)] + ($row['ckbd_hist'] / 1024),2);
		$data1[($slot-$slotnow)] = round($data1[($slot-$slotnow)] + ($row['ckbu_hist'] / 1024),2);
		$time[($slot-$slotnow)] = substr($row["c_time"],11,5);
		if(strrpos($macusers[($slot-$slotnow)], $row['c_mac'])==false) {
			$macusers[($slot-$slotnow)] .= $row['c_mac']." ";
			++$numusers[($slot-$slotnow)];
		}
		$totalkbd = $totalkbd + round($row['ckbd_hist'] / 1024,2);
		$totalkbu = $totalkbu + round($row['ckbu_hist'] / 1024,2);
		if(strrpos($macs, $row['c_mac'])==false) {++$totalusers; $macs .= $row['c_mac']." ";}
	}
}
$totalusers=$totalusers-1;
$i=287;
while ($i > 0) {
	$min="-".(string)((288-$i)*5)." minutes";
	if(!$time[$i]) {$time[$i]=date('H:i', strtotime($min));}
	if(!$data0[$i]) {$data0[$i]=0;}
	if($numusers[$i]<1) {$numusers[$i]=0;}
	echo "['".$time[$i]."',".$data0[$i].",".$data1[$i].",".$numusers[$i]."],";
	--$i;
}


?>

        ]);

        var chart<?php echo $row0['id'];?> = new google.visualization.AreaChart(document.getElementById('chart_div<?php echo $row0['id'];?>'));
        chart<?php echo $row0['id'];?>.draw(data<?php echo $row0['id'];?>, {width: 500, height: 200, title: '24 Hours | \r \n \r \n \r \n \r \n \r <?php echo " Download: ".number_format($totalkbd/1024,2)."GB | Upload: ".number_format($totalkbu,2)."MB | Clients: ".$totalusers;?>', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 1}, pointSize: '0',lineWidth: '1',colors:['#0084ff','#2f8319','#ffff00'], legend: 'bottom',

			hAxis: {title: '', showTextEvery:'72', direction:'-1', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 10}}



		});

      }


<?php

$query = "SELECT nodeid, SUM(ckbd_hist) AS tkbd, SUM(ckbu_hist) AS tkbu, COUNT(DISTINCT c_mac) AS clients FROM client WHERE netid='".$row0["id"]."' AND c_time > '$time_window' GROUP BY nodeid;";
$result = mysql_query($query, $conn);
//if(mysql_num_rows($result)<1) {echo("No activity data");$grafico="";} else {$grafico="<div id='chart_div'></div>";}

?>




    function drawVisualization<?php echo $row0['id'];?>() {
    var datapie<?php echo $row0['id'];?> = new google.visualization.DataTable();
      datapie<?php echo $row0['id'];?>.addRows(<?php echo mysql_num_rows($result);?>);
      datapie<?php echo $row0['id'];?>.addColumn('string', 'Node');
      datapie<?php echo $row0['id'];?>.addColumn('number', 'Total Download');


<?php




$i=0;
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $querynode = "SELECT name FROM node WHERE id='".$row['nodeid']."'";
	$resultnode = mysql_query($querynode, $conn);
    $resultname = mysql_fetch_assoc($resultnode);
	echo "            datapie".$row0['id'].".setValue(".$i.", 0, '". str_replace("*"," ",$resultname['name'])." - Clients ".$row['clients']."');\n";
	echo "            datapie".$row0['id'].".setValue(".$i.", 1, ".number_format($row['tkbd']/2048,2).");\n";
	++$i;
}


?>
        new google.visualization.PieChart(document.getElementById('pie_div<?php echo $row0['id'];?>')).
            draw(datapie<?php echo $row0['id'];?>, {title:"% Usage by Node (GB):"});
      }


      google.setOnLoadCallback(drawVisualization<?php echo $row0['id'];?>);


<?php
}
?>

    </script>

  </head>
  <body>

<?php

//echo $grafico;
echo "<br><table>";

$result0 = mysql_query($query0, $conn);
while($row0 = mysql_fetch_array($result0, MYSQL_ASSOC)) {
	if($red[$row0['id']]=="1") {
		echo "<tr><td colspan='2' align='center'><font color='#76A741' face='Trebuchet MS' size='6'><b>".$row0['net_name']."</b></font></td></tr>\n";
		echo "<tr><td><div id='chart_div".$row0['id']."'></div>";
		echo "</td><td><div id='pie_div".$row0['id']."' style='width: 550px; height: 200px;'></div></td></tr>\n";
		echo "<tr><td colspan='2'><h1>&nbsp;</h1></td></tr>\n";
	} else {
		echo "<tr><td colspan='2' align='center'><font color='#76A741' face='Trebuchet MS' size='6'><b>".$row0['net_name']."</b></font></td></tr>\n";
		echo "<tr><td colspan='2' align='center'>".$texto[$row0['id']]."</td></tr>\n";
		echo "<tr><td colspan='2'><h1>&nbsp;</h1></td></tr>\n";
	}
	
}


echo "</table>";


?>



</body>

</html>

