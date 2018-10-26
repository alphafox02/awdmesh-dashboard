<?php  
/* Name: view.php
 * Purpose: master view for network settings.


 */
//require '../lib/connectDB.php'; //Establish database connection....
//Setup session

$net_name = $_SESSION['net_name'];
if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");
require_once '../lib/connectDB.php';
//Set how long a node can be down before it's name turns red (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	//header("Location: ../entry/login.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}


$time_window = date('Y-m-d H:i:s', strtotime('-12 hours'));
$query = "SELECT * FROM client  WHERE netid='".$_SESSION["netid"]."' AND c_time > '$time_window' ORDER BY c_time DESC";

$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) echo("No activity data");




?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Network Usage</title>



<!-- load Google visualization api , from http://code.google.com/apis/visualization/documentation/gallery/areachart.html */ -->

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Time');
        data.addColumn('number', 'Download');
        data.addColumn('number', 'Upload');
        data.addRows([

<?php
$i=0;
$valores=array();
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $valores[$i][0]=substr($row["c_time"],11);
    $valores[$i][1]=$row["ckbd_hist"];
    $valores[$i][2]=$row["ckbu_hist"];
	$i++;
}

$i=0;
while ($i < count($valores)) {
	$i++;
	if(!$valores[$i][0] || !$valores[$i][1] || !$valores[$i][2]) {continue;}
	echo "['".$valores[$i][0]."'". ",".$valores[$i][1].",".$valores[$i][2]."],";
}
?>

        ]);

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 950, height: 200, title: 'Bandwidth in the network in the last 24 hours', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 1}, pointSize: '0',lineWidth: '1',colors:['#0084ff','#2f8319'], legend: 'bottom',

                          hAxis: {title: '', showTextEvery:'50', direction:'-1', textStyle: {color: '#000000', fontName: 'Arial', fontSize: 9}}
                         


                         });

      }
    </script>

  </head>
  <body>
    <div id="chart_div"></div>
  </body>

</html>
