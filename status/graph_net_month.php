<?php
/* Name: view.php
 * Purpose: master view for network settings.


 */

//session_start();

//if ($_SESSION['user_type']!='admin')
//	header("Location: ../entry/login.php");
//require '../lib/connectDB.php';


//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}


$time_window = date('Y-m-d H:i:s', strtotime('-30 days'));
$query = "SELECT COUNT(DISTINCT(c_mac)) as coun, SUM(IF(ckbd_hist>0, ckbd_hist, 0))/1024 as down, SUM(IF(ckbu_hist>0, ckbu_hist, 0))/1024 as up, DATE(c_time) as d, HOUR(c_time) as h, FLOOR(MINUTE(c_time)/5) as m, c_time FROM client WHERE netid='".$_SESSION["netid"]."' AND c_time > '$time_window' GROUP BY d,h,m ORDER BY c_time DESC";

$macq = "SELECT COUNT(DISTINCT(c_mac)) as macs FROM client WHERE netid='".$_SESSION["netid"]."' AND c_time > '$time_window'";
$macr = mysql_query($macq, $conn);
$macrow = mysql_fetch_row($macr);


$result = mysql_query($query, $conn);
if(mysql_num_rows($result)<1) {echo("No activity data");$grafico="";} else {$grafico="<div id='chart_div'></div>";}




?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Monthly Network Traffic</title>



<!-- load Google visualization api , from http://code.google.com/apis/visualization/documentation/gallery/areachart.html */ -->

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Time');
        data.addColumn('number', 'Download (MB)');
        data.addColumn('number', 'Upload (MB)');
        data.addColumn('number', 'Clients');
        data.addRows([

<?php
$down = 0;
$up = 0;
$tu = 0;
$first = true;
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($first) {$first = false;} else {echo ",";}
	echo "['".$row['c_time']."',".$row['down'].",".$row['up'].",".$row['coun']."]";
	$down+= $row['down'];
	$up += $row['up'];
	$tu += $row['macs'];
}
/*
	$slot = date('Y',  strtotime($row['c_time'])) + ((date('z',  strtotime($row['c_time'])))*24*12) + intval(date('H',  strtotime($row['c_time'])))*12 + intval(date ('i', $row['c_time']) / 5)+1;

	if($slotmin>$slot){$slotmin=$slot;}
	if($slotmax<$slot){$slotmax=$slot;}
	
	if($row['ckbd_hist'] < 0) {$row['ckbd_hist']=0;}
    if($row['ckbu_hist'] < 0) {$row['ckbu_hist']=0;}
    
	$data0[($slot)] = round($data0[($slot)] + ($row['ckbd_hist'] / 1024),2);
	$data1[($slot)] = round($data1[($slot)] + ($row['ckbu_hist'] / 1024),2);
	$time[($slot)] = $row["c_time"];
	if(strrpos($macusers[($slot)], $row['c_mac'])==false) {
		$macusers[($slot)] .= $row['c_mac']." ";
		++$numusers[($slot)];
	}
	$totalkbd = $totalkbd + round($row['ckbd_hist'] / 1024,2);
	$totalkbu = $totalkbu + round($row['ckbu_hist'] / 1024,2);

	if(strrpos($macs, $row['c_mac'])==false) {++$totalusers; $macs = $macs.$row['c_mac']." ";}

}
$totalusers=$totalusers-1;

$i=$slotmax;
while ($i > $slotmin) {
	if(!$time[$i]) {
//        echo "[' ',0,0],";
		--$i;
	} else {
		if(!$data0[$i]) {$data0[$i]=0;}
		if(!$data1[$i]) {$data1[$i]=0;}
		echo "['".$time[$i]."',".$data0[$i].",".$data1[$i].",".round($numusers[$i]-1)."],";
		--$i;
	}
}

*/
?>

        ]);
	if(document.getElementById('chart_div')){
		var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
		chart.draw(data, {width: 1000, height: 200, title: 'Usage: Month | <?php echo " Download: ".round($down/1024,2)."GB | Upload: ".round($up/1024,2)."GB | Clients: ".$macrow[0];?>', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 1}, pointSize: '0',lineWidth: '1',colors:['#0084ff','#2f8319','#f3b006'], legend: 'bottom',

		                  hAxis: {title: '', showTextEvery:'1000000', direction:'-1', textStyle: {color: '#ffffff', fontName: 'Arial', fontSize: 1}}



		                 });

	      }
	}
    </script>

  </head>

<?php
echo $grafico;








$currentTime = getdate();
$currentTime = $currentTime['0'];


//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$result2 = mysql_fetch_array($result, MYSQL_ASSOC);
$macs_blocked = $result2["access_disable_list"];
$macs_bypassed = $result2["bypass_list"];
if($result2["display_name"]=="") {$display_name = $result2["net_name"];}
else {$display_name = $result2["display_name"];}


//Select only the client ids in the MAC with latest checkin
//$query = "SELECT client.*, MAX(client.c_time) AS hora, node.name FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.netid='".$_SESSION["netid"]."' AND client.c_time > '".date('Y-m-d')." 00:00:00' GROUP BY c_mac";
$query0 = "SELECT MAX(c_time) AS c_time, MAX(id) AS clients_ids, COUNT(netid), c_mac FROM client WHERE netid='".$_SESSION["netid"]."' AND c_time > '".$time_window."' GROUP BY c_mac ORDER BY c_time DESC;";
$result0 = mysql_query($query0, $conn);
if(mysql_num_rows($result0)<1) die("<div class=error>There are no active users connected and no current or recent connection log.</div>");


//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
//-----
// Added "version" as value to the array, which is the index to node properties
// 
//-----
$node_fields = array("Status" => "Status","User" => "c_name","MAC" => "c_mac","Node" => "name","Last Seen" => "c_time",
  "Down (MB)" => "ckbd_hist","Up (MB)" => "ckbu_hist");

echo "<br><br><br><table class='sortable' border='1'>";

//Output the top row of the table (display names)
// echo "<td>" . $key . "</td>";
echo "<tr class=\"fields\">";
foreach($node_fields as $key => $value) {
    echo "<td align='center'>&nbsp;";
    	      echo $key;
    echo "&nbsp;</td>";
}
echo "</tr>";

while($row0 = mysql_fetch_array($result0, MYSQL_ASSOC)) {

$query = "SELECT client.*, node.name FROM client INNER JOIN node ON client.nodeid = node.id WHERE client.id='".$row0['clients_ids']."'";
$result = mysql_query($query, $conn);


//Output the rest of the table
$row = mysql_fetch_array($result, MYSQL_ASSOC);  // First, users online in last checkin

if($currentTime < strtotime($row["c_time"])+2592000) {
	if($currentTime < strtotime($row["c_time"])+330) {
	    $source = '<font color="#008000">';
	    foreach($node_fields as $key => $value) {
	        echo "<td align='center'>&nbsp;";
            $query1 = "SELECT SUM(ckbd_hist) AS tkbd, SUM(ckbu_hist) AS tkbu FROM client WHERE c_mac='".$row['c_mac']."' AND netid='".$_SESSION["netid"]."' AND c_time > '$time_window'";
			$result1 = mysql_query($query1, $conn);
            $row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
			if ( $value=="Status" ) {
				if( $currentTime < strtotime($row["c_time"])+330) {
					echo '<img src="../users/uonline.png" border=0 ALIGN=ABSMIDDLE>';
				}
	        } 
	        else if ( ($value=="name" ) && ( $row[$value] == "" ) )
	        {
	       		echo $source."Node Removed";
	        } 
	        else if ( $value == "Blacklist" )
	        {
	        	echo '<a href='."'".'block.php?macs='.$row["c_mac"].'?'.$macs_blocked."'".'><img src="../users/add.png" border=0></a>';
			} 
			else if ( $value=="Bypass" ) 
			{
				echo '<a href='."'".'bypass.php?macs='.$row["c_mac"].'?'.$macs_bypassed."'".'><img src="../users/add.png" border=0></a>';
	        } 
	        else if ( $value=="ckbd_hist" )
	        { 
	        	echo $source.number_format($row1['tkbd']/1024,2);
	        } 
	        else if ( $value == "ckbu_hist" )
	        {
	        	echo $source.number_format($row1['tkbu']/1024,2);
	        } 
	        else if ( $value == "name" )
	        {
	        	echo str_replace( "*", " ", $source.$row[$value] );
			}
	        else 
	        {
	        	echo $source.$row[$value];
	        }
	        echo "&nbsp;</td>";
	    }
	    echo "</tr>";
	} else {
	     $source = '<font color="#C0C0C0">';
	    foreach($node_fields as $key => $value) {
	        echo "<td align='center'>&nbsp;";
            $query1 = "SELECT SUM(ckbd_hist) AS tkbd, SUM(ckbu_hist) AS tkbu FROM client WHERE c_mac='".$row['c_mac']."' AND netid='".$_SESSION["netid"]."' AND c_time > '$time_window'";
			$result1 = mysql_query($query1, $conn);
            $row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
	        if ($value=="Status") 
	        {
	        	if($currentTime > strtotime($row["c_time"])+330) 
	        	{
	        		echo '<img src="../users/uoffline.png" border=0 ALIGN=ABSMIDDLE>';
	        	}
	        } 
	        else if ( ($value=="name" ) && ( $row[$value] == "" ) )
	        {
	       		echo $source."Node Removed";
	        } 
	        else if ( $value == "Blacklist" )
	        {
	        	echo '<a href='."'".'block.php?macs='.$row["c_mac"].'?'.$macs_blocked."'".'><img src="../users/add.png" border=0></a>';
			} 
			else if ( $value=="Bypass" ) 
			{
				echo '<a href='."'".'bypass.php?macs='.$row["c_mac"].'?'.$macs_bypassed."'".'><img src="../users/add.png" border=0></a>';
	        } 
	        else if ( $value=="ckbd_hist" )
	        { 
	        	echo $source.number_format($row1['tkbd']/1024,2);
	        } 
	        else if ( $value == "ckbu_hist" )
	        {
	        	echo $source.number_format($row1['tkbu']/1024,2);
	        } 
	        else if ( $value == "name" )
	        {
	        	echo str_replace( "*", " ", $source.$row[$value] );
			}
	        else 
	        {
	        	echo $source.$row[$value];
	        }
	        echo "&nbsp;</td>";
	    }
	    echo "</tr>";
	}
}

}
//echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><b><font color='#FF0000'>TOTAL: ".$kbt."&nbsp;</td><td align='center'><b><font color='#FF0000'>".$kbd."</td><td align='center'><b><font color='#FF0000'>".$kbu."</td>";
echo "</table>";

//Finish our HTML needed for NiftyCorners



?>

<br>
</td></tr></table>

</body>

</html>

