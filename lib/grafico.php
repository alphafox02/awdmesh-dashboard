<?php
require_once '../lib/connectDB.php'; //Establish database connection....

//Set up session, get session variables
session_start();

$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];



setTable("client");
$time_window = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
$result = mysql_query("SELECT c_time,ckbd_hist,ckbu_hist FROM client WHERE c_time > '$time_window' ORDER BY c_time");
$resArray = mysql_fetch_assoc($result);


?>




<html>
  <head>
  
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
     	<?
	while($row = mysql_fetch_array($result)){
    echo "[";
    echo "'".$row["c_time"]."'". " , ". $row["ckbd_hist"]." , ".$row["ckbu_hist"];
    echo "],";
	//This above spits out the result in a format that the Goolge visualization API can read. The format is  
	//['2004', 1000, 400],
     //['2005', 1170, 460],
	 // and so on. The "2005 could be replaced with a time , then data for downloading , then for uploading.
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