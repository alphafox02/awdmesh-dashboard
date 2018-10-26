<?php

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


$time_window = date('Y-m-d H:i:s', strtotime('-6 hours'));
$query = "SELECT COUNT(DISTINCT(c_mac)) as coun, SUM(IF(ckbd_hist>0, ckbd_hist, 0))/1024 as down, SUM(IF(ckbu_hist>0, ckbu_hist, 0))/1024 as up, DATE(c_time) as d, HOUR(c_time) as h, FLOOR(MINUTE(c_time)/5) as m, c_time FROM client WHERE netid='".$_SESSION["netid"]."' AND c_time > '$time_window' GROUP BY d,h,m ORDER BY c_time DESC";

$macq = "SELECT COUNT(DISTINCT(c_mac)) as macs FROM client WHERE netid='".$_SESSION["netid"]."' AND c_time > '$time_window'";
$macr = mysql_query($macq, $conn);
$macrow = mysql_fetch_row($macr);


$result = mysql_query($query, $conn);
if(mysql_num_rows($result)<1) {$grafico="<div id='chart_div'></div>";$emptygraph = true;} else {$grafico="<div id='chart_div'></div>";}




?>





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
	echo "['".date("H:i:s", strtotime($row['c_time']))."',".$row['down'].",".$row['up'].",".$row['coun']."]";
	$down+= $row['down'];
	$up += $row['up'];
	$tu += $row['macs'];
}


if($emptygraph){
	for($i=0;$i<100;$i++){
		if($first) {$first = false;} else {echo ",";}
		echo "['',0,0,0]";
	}
}
?>
        ]);
	if(document.getElementById('chart_div')){
		var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
		chart.draw(data, {width: 1000, height: 100, title: 'Usage: 6 Hours | <?php echo " Download: ".round($down/1024,2)."GB | Upload: ".round($up/1024,2)."GB | Clients: ".$macrow[0];?>', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 1}, pointSize: '0',lineWidth: '1',colors:['#0084ff','#2f8319','#f3b006'], legend: 'bottom',

		                  hAxis: {title: '', showTextEvery:'2', direction:'-1', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 10}}
		                 });

	      }
	}
    </script>

<?php
echo $grafico;
?>



