<?php  
/* Name: view.php
 * Purpose: master view for network settings.


 */

//Setup session
session_start();
if ($_SESSION['user_type']!='admin')
	header("Location: ../entry/login.php");

//Set how long a node can be down before it's name turns red (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/select.php");
        exit();
}

require "../lib/connectDB.php";
setTable("node");
include '../lib/toolbox.php';

$query = "SELECT * FROM client  WHERE netid='".$_SESSION["netid"]."' AND c_time > '2010-09-28 00:00:22'";
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("No hay registros que coincidan con su seleccion. <a href=\"../users/users.php\">Back</a>");




?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Nodalis Meshcontroller | Estado de la red</title>



<!-- load Google visualization api , from http://code.google.com/apis/visualization/documentation/gallery/areachart.html */ -->

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Time');
        data.addColumn('number', 'Uploaded Bandwidth');
        data.addColumn('number', 'Downloaded Bandwidth');
        data.addRows([

<?php
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {


    echo "[";
    echo "'".$row["c_time"]."'". " , ". $row["ckbd_hist"]." , ".$row["ckbu_hist"];
    echo "],";
}
?>

        ]);

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 700, height: 125, title: 'Bandwidth Usage Over Time', titleTextStyle: {color: '#037F03', fontName: 'Arial'}, pointSize: '0',lineWidth: '1',colors:['#0084ff','#2f8319'], legend: 'bottom',

                          hAxis: {title: 'Overall Bandwidth Usage', showTextEvery:'10', direction:'1', titleTextStyle: {color: '#037F03', fontName: 'Arial'}}


                         });

      }
    </script>

  </head>
  <body>
    <div id="chart_div"></div>
  </body>

</html>
