<?php

//Setup session
include "../lib/toolbox.php";
require_once "../lib/connectDB.php";
session_start();
if (!isset($_SESSION['user_type']))
	header("Location: ../entry/login.php");
$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

// A lot of the following is from Mike; forgive me if the code is unclear.
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Network Overview | <?php  echo $net_name; ?></title>



<?php
include "../lib/style.php";
include "../lib/mapkeys.php";

?>
<style type="text/css">
.announcement{
    margin-top:20px;
}

.announcement span{
    border:1px solid #76d941;
    color: #76A741;
    padding:5px 10px;
    font-size:15px;
}
</style>
<script type="text/javascript" src="../lib/infobubble.js"></script>  
<script type="text/javascript" src="../lib/map.js"></script>
<script type="text/javascript">

<!--[CDATA[

	function close(){
		document.getElementById("tip").style.display="none";
	}

	var map = null;
	var geocoder = null;
	function onLoad()
	{
		// Display Info Windows Above Markers
		//
		// Show a custom info window above each marker by listening
		// to the click event for each marker. We take advantage of function
		// closures to customize the info window content for each marker.

		// Center the map is done later after we read data points
		// MAPA ANTERIOR map = new GMap2(document.getElementById("map"));
		// map = new GMap2(document.getElementById("map"));
		
		map = new google.maps.Map(document.getElementById('map'), {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                //zoom: 3,
				mapTypeControl: true,
				draggable: true,
				scaleControl: false,
				scrollwheel: true,
				panControl:true,
				streetViewControl: false,
				overviewMapControl : true,
				zoomControl: true
            });
		
		// map.addControl(new GLargeMapControl());
		// map.addControl(new GMapTypeControl());
		// map.addControl(new GOverviewMapControl());
		// geocoder = new GClientGeocoder();
		geocoder = new google.maps.Geocoder();

		var point;
		var marker;

		window.onresize=setMapSizePos;

		//setup nifty corners
		Nifty("div.note");

<?php

// Get our markers from database and add to the map viewport
{

	$query="SELECT net_location, floor_plan, min_nodedown FROM network WHERE id='$netid'";
	$result=mysql_query($query, $conn);

	if (mysql_num_rows($result)==1){
		$net = mysql_fetch_array($result, MYSQL_ASSOC);
		$net_location = $net["net_location"];
		$floor_plan = $net["floor_plan"];
		$min_nodedown = $net["min_nodedown"];
	}
	$netid = mysql_real_escape_string($netid, $conn);
	$query="SELECT *, UNIX_TIMESTAMP(TIME) as epoch_time FROM node WHERE netid='$netid'";
	
	$num_down = 0;
	$currentTime = getdate();
    $currentTime = $currentTime['0'];
    $OK_DOWNTIME = 1800;
	
	$result=mysql_query($query, $conn);
	$num=mysql_num_rows($result);
	$nodenum = mysql_num_rows($result);
	if ($num)
	{
		$resArray = mysql_fetch_array($result, MYSQL_ASSOC);
		$longitude = $resArray["longitude"];
		$latitude=$resArray["latitude"];
		mysql_data_seek($result,0);
echo <<<NODES
	map.setCenter(new google.maps.LatLng($latitude, $longitude));
	map.setZoom(17);
	map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
NODES;
	} else {
echo <<<NO_NODES
		address = "$net_location";
		geocoder.geocode({address: address}, function(results) {
				if (results && results.length > 0) {
					var pos = results[0].geometry.location;
					map.setCenter(pos);
					map.setZoom(13);
				} else {
					alert(address + "Please indicate your network location. You can change your default location on the configure page.");
				}			
			}
  		);
NO_NODES;
	}

	$i=0;
	$minX=90;
	$maxX=-90;
	$minY=360;
	$maxY=-360;

	// Plot our nodes
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
       if($currentTime - strtotime($row['time']) >= $OK_DOWNTIME)
            $num_down += 1;
		$approval_status=$row["approval_status"];
		if($approval_status == "D" || $approval_status == "R"){continue;}

		$description=$row["description"];
		$ip=$row["ip"];
		$mac=$row["mac"];
		$longitude=$row["longitude"];
		$latitude=$row["latitude"];
		if(!$owner_name=$row["owner_name"])
			$owner_name="(none)";
		if(!$owner_email=$row["owner_email"])
			$owner_email="(none)";
		if(!$owner_phone=$row["owner_phone"])
			$owner_phone="(none)";
		if(!$owner_address=$row["owner_address"])
			$owner_address="(none)";
		$gateway=$row["gateway"];
		$gw_qual=$row["gw-qual"];
        $gw_route=$row["routes"];
		$users=$row["users"];
		$time=$row["time"];
		$kbdown=$row["kbdown"];
		$kbup=$row["kbup"];
		$hops=$row["hops"];
		$robin=$row["robin"];
		$batman=$row["batman"];
		$latitude=$row["latitude"];
		$longitude=$row["longitude"];
		$is_gateway=$row["gateway_bit"];
		$webcamurl=$row["webcamurl"];
		$twitterid=$row["twitterid"];
              $nodes=$row["nodes"].";";
		$rssi=$row["rssi"].";";

              // Extrae en $nodes_rssi[mac] el rssi de cada neighboor
		$h=0;
		$list_node = explode(";", $nodes);
		$list_rssi = explode(";", $rssi);
		foreach ($list_node as &$value) {
			$nodes_rssi[$value]=$list_rssi[$h];
			++$h;
		}

		// Calculate min, max latitude, longitude for center and zoom later
		if ($latitude < $minX) $minX = $latitude;
		if ($latitude > $maxX) $maxX = $latitude;
		if ($longitude < $minY) $minY = $longitude;
		if ($longitude > $maxY) $maxY = $longitude;

		if (!strlen($gw_qual)) $gw_qual = 0;

		//Get time since last checkin and prettify it
		$ctime = getdate();
		$ctime = $ctime[0];
		$up = ($ctime-strtotime($time))-($min_nodedown*60)+1800;
		$ulang = 'en';
		$LastCheckin = humantime($time);

        if($is_gateway=="1"){$hops="0";}
		$name=str_replace("*"," ",$row["name"])." - ".$users." users, ".round($kbdown/1024,1)." MB, ".(floor($gw_qual/255*100))."% quality, ".$hops." hops, ".$LastCheckin." checkin";
        $nbs=$row["nbs"];
		$draggable = false;


// Create the Marker
$html_string = '<h3>'.str_replace("*"," ",$row["name"]).'</h3>'.'<table class="infoWindow">';
if($utype=="admin"){
	$html_string .='<tr>'.'<td>Description:</td>'.'<td>'.$description.'</td>'.'</tr>';
	$html_string .='<tr>'.'<td>MAC/IP:</td>'.'<td>'.$mac.' / '.$ip.'</td>'.'</tr>';
}
if($is_gateway=="1"){$hops="0 (gateway)";}

$html_string .='<tr>'.
				'<td>Check-in:</td>'.
				'<td>'.$LastCheckin.'</a></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Users:</td>'.
				'<td>'.$users.'</a></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Down/Up (MB):</td>'.
				'<td>'.round($kbdown/1024,1).' / '.round($kbup/1024,1).'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Hops:</td>'.
				'<td>'.$hops.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Quality:</td>'.
				'<td>'.(floor($gw_qual/255*100)).'%</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Firmware:</td>'.
				'<td>'.$robin.' / '.$batman.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Edit node:</td>'.
				'<td><a href="../nodes/node_info.php?mac='.$mac.'">Click</a></td>'.
			'</tr>'.
			'</table>';
$status = addslashes($html_string);

if($utype=="masteradmin"){
$owner = addslashes('<h3>Additional information</h3>'.
			'<table class="infoWindow">'.
			'<tr>'.
				'<td>Owner:</td>'.
				'<td>'.$owner_name.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Email:</td>'.
				'<td><a href="mailto:'.$owner_email.'">'.$owner_email.'</a></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Telephone:</td>'.
				'<td>'.$owner_phone.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Address:</td>'.
				'<td>'.$owner_address.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Latitude:</td>'.
				'<td>'.$latitude.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Longitude:</td>'.
				'<td>'.$longitude.'</td>'.
			'</tr>'.
			'</table>');
			}

$neighbors = addslashes('<h3>'.str_replace("*"," ",$row["name"]).'</h3>'.'<table class="infoWindow">'.
    '<td width=100><b>Neighbor</b></td>'.
    '<td width=140><b>MAC</b></td>'.
    '<td width=50><b>Meters</b></td>'.
    '<td width=70 align=center><b>Quality</b></td>'.
    '<td><b>RSSI</b></td>');




$neigh = explode(";", $nbs);
foreach ($neigh as &$value) {
    $query1 = "SELECT * FROM node WHERE ip='".$value."' AND netid='$netid'";
    $node_result = mysql_query($query1, $conn);
    if(mysql_num_rows($node_result)!=0) { //"neighbors."
        $result1 = mysql_fetch_assoc($node_result);
        $rssi=$nodes_rssi[$result1["ip"]];
        if ($rssi == "z") {$rssi="<font color='#008000'>n/a</font>";} //verde
		elseif ($rssi < 10) {$rssi="<font color='#FF0000'>".$rssi."</font>";}   //rojo
		elseif ($rssi < 17) {$rssi="<font color='#FF9900'>".$rssi."</font>";}   //naranja
		elseif ($rssi >= 17) {$rssi="<font color='#008000'>".$rssi."</font>";}  //verde
        $ips = str_replace(".", "0", $result1["ip"]);
		if ($result1["latitude"] != 0 && $result1["longitude"] != 0) {$distancia = calculadistancia($latitude, $longitude, $result1["latitude"], $result1["longitude"]);}
        if ($value!="") {
		$neighbors .= ('<tr>'.
				    '<td width=100><a href='."'".'javascript:'."'".' onclick='."'".'myClick('.$ips.');'."'".'>'.str_replace("*"," ",$result1["name"]).'</a></td>'.
				    '<td width=140>'.$result1["mac"].'</td>'.
				    '<td align=center width=50>'.$distancia.'</td>'.
				    '<td align=center>'.floor($result1["gw-qual"]/255*100).'%</td>'.
				    '<td align=center>'.$rssi.'</td>'.
                    '</tr>');
		}
    }
$lat=$result1["latitude"];
$long=$result1["longitude"];
$rss=array_sum(explode(";", $result1["rssi"]));

if ($lat != 0 && $long != 0)
{
echo <<<END
var polyline = new drawRoutePolyline($latitude, $longitude, $lat, $long, $rss, map);
END;
    }
}
$neighbors .= ('</table>');



if($utype=="masteradmin"){
$twitter = addslashes('<h3>Node messages '.$description.'</h3>'.


			'<table class="infoWindow">'.

			'<font style="font-family:Arial,sans-serif; font-size:11px; color:#000000;">'.

			'<div id="twitter_div">'.
            '<h2 style="display: none;">Twitter Updates</h2>'.
            '<ul id="twitter_update_list"></ul>'.
            '<a href="http://twitter.com/'.$twitterid.'" id="twitter-link" target="_blank" style="display:block;text-align:right;">follow me on Twitter</a>'.
            '</div>'.
            '<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>'.
            '<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$twitterid.'.json?callback=twitterCallback2&amp;count=3"></script>'.

			'</font>'.

			'</table>');

}


echo <<<END
	point = new google.maps.LatLng($latitude, $longitude);
	var marker = new nodeMarker(map, "$net_name", point, "$name", "$notes", "$mac", "$is_gateway", "$gw_qual", "$up", "$draggable", "$users", "$ip", "$gw_route", "$nbs");
	marker.addTab("Status","$status");
//	marker.addTab("Info","$owner");
    marker.addTab("Neighbors", "$neighbors");
//	marker.addTab("Messages", "$twitter"); 

	marker.addListeners();
END;

	}
}

mysql_close($conn);

// We're done, so center and zoom the map
echo <<<END
		myCenterAndZoom(map, $minX, $maxX, $minY, $maxY, "$node_loc");
		if("$floor_plan" != ""){
			var imagen = new google.maps.GroundOverlay("$floor_plan", map.getBounds());
			imagen.setMap(map);
		}
	}

END;

?>

//]]--->#76A741
</script>
</head>

<body style="width:100%; height:100%" bgcolor="#FFFFFF" align="center" onLoad="onLoad();" onResize="setMapSizePos()" >
<?php  require '../lib/menu.php'; ?>

<!-- <td align="left"><b>Key:</b><font class="style7"> Node is down:<img src="graynode.png" ALIGN=ABSMIDDLE> Node is up:<img src="greennode.png" ALIGN=ABSMIDDLE> Problems 1h:<img src="rednode.png" ALIGN=ABSMIDDLE> Problems 24h<img src="yellownode.png" ALIGN=ABSMIDDLE></font><a href="mapcover.php" ALIGN=ABSMIDDLE><img src="cobertura.png" border=0 ALIGN=ABSMIDDLE></a><a href="mapcam.php"><img src="mapawebcams.png" border=0 ALIGN=ABSMIDDLE></a><a href="mapusers.php"><img src="mapausuarios.png" border=0 ALIGN=ABSMIDDLE></a></td> -->

<div style="margin-top:10px;">
	<?php include 'graph_front_page.php';?>
</div>
<div>
<?php 
    try
    {   
        $announcement = file_get_contents ("../announcement.txt");
        if($announcement){
            echo '<p class="announcement"><span>'.$announcement.'</span></p>';
        }
    }
    catch(Exception $e)
    {
        
    }
?>
</div> 

<div id="top" style="width:94%; margin:0px auto 0px auto;text-align:left;zoom:1;clear:both;overflow:hidden;">
	<h3 style="font-size:13px;float:left;margin-bottom:2px;"><?php echo $nodenum; if($nodenum == 1):?> Node <?php else:?> Nodes <?php endif;?> in this Network&nbsp;&nbsp;
	    <?php if($num_down != 0):?><span style="color:#d00;"><?php echo $num_down; if($num_down == 1):?> Node <?php else:?> Nodes <?php endif;?> Alerting</span><?php endif;?>
	    <br/><b>Map of Users per Node</b></h3>
	<h3 style="font-size:12px;float:right;margin-bottom:2px;">&nbsp;<br/>Move the mouse over, or click/doubleclick any node for more information</h3>
</div>

<div id="map" style="width:94%; height:500px; margin:0px auto 0px auto;" text-align="center"></div>
<div style="width:80%; margin:5px auto 0px auto; text-align:left;"><b><br>Key: </b><font class="style7"> Node up: <img src="greennode.png" ALIGN=ABSMIDDLE> Node down: <img src="graynode.png" ALIGN=ABSMIDDLE> Node issues (past hr): <img src="rednode.png" ALIGN=ABSMIDDLE> Node issues (past 24hrs): <img src="yellownode.png" ALIGN=ABSMIDDLE></font></a></div>
</body>
</html>
