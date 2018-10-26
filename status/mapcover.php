<?php

//Setup session
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
<title>Coverage Map | <?php  echo $net_name; ?></title>
<?php
include "../lib/style.php";
include "../lib/mapkeys.php";
?>
<script type="text/javascript" src="../lib/map.js"></script>
<!--<script type="text/javascript" src="../lib/CircleOverlay.js"></script> -->
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
		//map = new GMap2(document.getElementById("map"));
		//map.addControl(new GLargeMapControl());
		//map.addControl(new GMapTypeControl());
		//map.addControl(new GOverviewMapControl());
		//geocoder = new GClientGeocoder();
		
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
		geocoder = new google.maps.Geocoder();

 	var circle = null;
		var point;
		var marker;

		window.onresize=setMapSizePos;

		//setup nifty corners
		Nifty("div.note");

<?php
require "../lib/connectDB.php";
include "../lib/toolbox.php";

// Get our markers from database and add to the map viewport
{

	$query="SELECT net_location FROM network WHERE id='$netid'";
	$result=mysql_query($query, $conn);
	if (mysql_num_rows($result)==1){
		$net_location = mysql_fetch_array($result, MYSQL_ASSOC);
		$net_location = $net_location["net_location"];
	}
	$netid = mysql_real_escape_string($netid, $conn);
	$query="SELECT *, UNIX_TIMESTAMP(TIME) as epoch_time FROM node WHERE netid='$netid'";
	$result=mysql_query($query, $conn);
	$num=mysql_num_rows($result);

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
					alert(address + " Hemos intentado localizar su red y esto es lo mas aproximado que hemos encontrado.");
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
		$cover1=$row["cover1"];
		$cover2=$row["cover2"];
		$cover3=$row["cover3"];

		// Calculate min, max latitude, longitude for center and zoom later
		if ($latitude < $minX) $minX = $latitude;
		if ($latitude > $maxX) $maxX = $latitude;
		if ($longitude < $minY) $minY = $longitude;
		if ($longitude > $maxY) $maxY = $longitude;

		if (!strlen($gw_qual)) $gw_qual = 0;

		//Get time since last checkin and prettify it
		$ctime = getdate();
		$ctime = $ctime[0];
		$up = $ctime-strtotime($time);
		$ulang = 'en';
		$LastCheckin = humantime($time);

        if($is_gateway=="1"){$hops="0";}
		$name=$row["name"]." - ".$users." users, ".round($kbdown/1000,1)." Kbs, ".(floor($gw_qual/255*100))."% quality, ".$hops." hops, ".$LastCheckin." checkin";
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
				'<td>Down/Up (Mb):</td>'.
				'<td>'.round($kbdown/1000,1).' / '.round($kbup/1000,1).'</td>'.
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
if($utype != "admin") {
    $blockcover="readonly";
} else {
    $buttom='<input type="submit" name="submit" value="Save Changes">';
}

$cover = addslashes('<form method="POST" action="../nodes/c_updatenode.php">'.
            '<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wireless Coverage Settings*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>'.
			'<table class="infoWindow">'.
			'<tr>'.
				'<td><font color="green">Excellent Coverage</font></td>'.
				'<td><font color="#008000"><b></b></font></td>'.
 				'<td><input type="text" size="1" name="cover1" value="'.$cover1.'" '.$blockcover.'> distance - meters (per radio)</td>'.
    			'</tr>'.
			'<tr>'.
				'<td><font color="yellow">Good Coverage</font></td>'.
				'<td><font color="#D7DF01"><b></b></font></td>'.
				'<td><input type="text" size="1" name="cover2" value="'.$cover2.'" '.$blockcover.'> distance - meters (per radio)</td>'.
    			'</tr>'.
			'<tr>'.
				'<td><font color="red">Coverage Limit</font></td>'.
				'<td><font color="#FF0000"><b></b></font></td>'.
				'<td><input type="text" size="1" name="cover3" value="'.$cover3.'" '.$blockcover.'> distance - meters (per radio)</td>'.
			'</tr>'.
   			'<tr> </tr><font color="red"><b>*</b></font>Omni-directional signal spread pattern.<tr> </tr><tr> </tr><tr> </tr><tr> </tr><tr> </tr><tr> </tr><tr> </tr>'.
            '<tr>'.
                '<input type="hidden" name="net_name" value="'.$net_name.'">'.
                '<input type="hidden" name="node_name" value="'.$name.'">'.
                '<input name="mac" type="hidden" value="'.$mac.'">'.
                '<input name="updates" type="hidden" value="Cover">'.
                '<td>'.$buttom.'</td>'.
  	    	'</tr>'.
			'</table></form>');

$neighbors = addslashes('<h3>Links to next nodes</h3><table class="infoWindow">'.
    '<td width=100><b>Name</b></td>'.
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
        if ($result1["rssi"] == "z") {$rssi="<font color='#008000'>n/a</font>";} //verde
        elseif ($result1["rssi"] < 10) {$rssi="<font color='#FF0000'>".$result1["rssi"]."</font>";}   //rojo
        elseif ($result1["rssi"] < 17) {$rssi="<font color='#FF9900'>".$result1["rssi"]."</font>";}   //naranja
        elseif ($result1["rssi"] >= 17) {$rssi="<font color='#008000'>".$result1["rssi"]."</font>";}  //verde
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
//map.addOverlay(polyline);
END;
    }
}
$neighbors .= ('</table>');






echo <<<END
	
    point = new google.maps.LatLng($latitude, $longitude);
	
	//var circle = new CircleOverlay(point, ($cover1*0.00062), "", 0, 1, "#006600", 0.3);  // Verde 30m.
	var circle1 = new google.maps.Circle(getCircleOptions(map, point, $cover1, "", 0, 1, "#006600", 0.3));
    
    //var circle = new CircleOverlay(point, ($cover2*0.00062), "", 0, 1, "#FFFF00", 0.3);  // Amarillo 90m.
	var circle2 = new google.maps.Circle(getCircleOptions(map, point, $cover2, "", 0, 1, "#FFFF00", 0.3));
    
	//var circle = new CircleOverlay(point, ($cover3*0.00062), "", 0, 1, "#CC0000", 0.1);  // Rojo 150m.
	var circle3 = new google.maps.Circle(getCircleOptions(map, point, $cover3, "", 0, 1, "#CC0000", 0.1));
	
	var marker = new nodeMarker(map, "$net_name", point, "$name", "$notes", "$mac", "$is_gateway", "$gw_qual", "$up", "$draggable", "$users", "$ip", "$gw_route");
	marker.addTab("Status", "$status");
    marker.addTab("Coverage", "$cover");
    marker.addTab("Neighbors", "$neighbors");
	marker.addListeners();
	//map.addOverlay(marker.get());

END;

	}
}

mysql_close($conn);

// We're done, so center and zoom the map
echo <<<END

		myCenterAndZoom(map, $minX, $maxX, $minY, $maxY, "$node_loc");
	}

END;

?>

//]]--->
</script>
</head>
<body bgcolor="#FFFFFF" align="center" onLoad="onLoad();" onResize="setMapSizePos()" >

<div align="center" id="top">

<?php  include '../lib/menu.php'; ?>





<table width=400><tr><td align='center' height=0>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#ff9900;"></font>
</td>
</tr></table>
<td align="left"><b><br>Key: </b><font class="style7"> Node up: <img src="greennode.png" ALIGN=ABSMIDDLE> Node down: <img src="graynode.png" ALIGN=ABSMIDDLE> Node issues (past hr): <img src="rednode.png" ALIGN=ABSMIDDLE> Node issues (past 24hrs): <img src="yellownode.png" ALIGN=ABSMIDDLE></font></a><td></td>

<div class="note" id="top">Double-click node icons to see detailed node information.
</div>

<td align="center"><a href="map.php">View Overview Map</a><br><br></td>



  <input name="net_name" id="net_name" type=hidden value=<?php  print $net_name?>>
</div>
<div id="map" style="width: 100%; height: 70%" text-align="center"></div>
</body>
</html>
