<?php
/* Name: addnode.php
 * Purpose: map to add node to network

 */

//Set up session, get session variables


session_start();
$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

// A lot of the following is from Mike; forgive me if the code is unclear.

//Make sure person is logged in
session_start();

if ($_SESSION['user_type']!='admin') 
	header("Location: ../entry/login.php");



//Select the network from the database and get the values

//


?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Add/Delete Node | <?php  echo $net_name; ?></title>
<?php
include "../lib/style.php";
include "../lib/mapkeys.php";
?>
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
		map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GOverviewMapControl());
		geocoder = new GClientGeocoder();

		// Create a marker whenever the user clicks the map   
		GEvent.addListener(map, 'click', function(overlay, point) 
		{
			if (point)
			{
				var html = 	'<form name="addnode" method="POST">' +
				'<B>Add Node</B>' +
				'<table width="310"  border="0" cellpadding="0" cellspacing="0" id="node">' +
				'<tr>' +
				  '<td class="style1">Name:</span><span class="style2">&nbsp;&#42;&nbsp;</span></td>' +
				  '<td><input type="text" size="32" name="node_name" required="1"></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">MAC:</span><span class="style2">&nbsp;&#42;&nbsp;</span></td>' +
				  '<td><input type="text" size="32" name="mac"></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Description:</td>' +
				  '<td><input type="text" size="32"  name="description"></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Latitude:</span></td>' +
				  '<td><input type="text" size="32"  name="latitude" value="' + point.y + '" readonly></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Longitude:&nbsp;</span></td>' +
				  '<td><input type="text" size="32"  name="longitude" value="' + point.x + '" readonly></td>' +
				'</tr><tr>' +
				  '<td><input type="hidden" name="user_type" value="<?php echo $utype;?>"><input type="hidden" name="form_name" value="addNode"><input type="hidden" name="net_name" value="' + document.getElementById("net_name").value + '"></td>' +
				  '<td align="right"><input type="button" name="Add" value="Add" onClick="addNode(this.form)"></td></tr>' +
            '<tr><td colspan=2>*Node name cannot begin with #<br>*Enter the MAC format as 11:22:33:44:55:66</td></tr></table></form>';
                			map.openInfoWindowHtml(point, html);
			}
		});

	var point;
	var marker;
	
	window.onresize=setMapSizePos;
	
	Nifty("div.note");

<?php
include_once "../lib/connectDB.php";
include "../lib/toolbox.php";

// Get our markers from database and add to the map viewport
{

	$query="SELECT net_location, floor_plan FROM network WHERE id='$netid'";
	$result=mysql_query($query, $conn);
	if (mysql_num_rows($result)==1){
		$net = mysql_fetch_array($result,MYSQLI_ASSOC);
		$floor_plan = $net["floor_plan"];
		$net_location = $net["net_location"];
	}

//	$netid = mysql_real_escape_string($conn,$netid);
	$query="SELECT *, UNIX_TIMESTAMP(TIME) as epoch_time FROM node WHERE netid='$netid'";
	$result=mysql_query($query, $conn);
	$num=$result->num_rows;

	
    if(mysql_num_rows($result)>0)
	{	
		$resArray = mysql_fetch_array($result,MYSQLI_ASSOC);
		$longitude = $resArray["longitude"];
		$latitude=$resArray["latitude"];
		mysql_data_seek($result,0);
echo <<<NODES
	map.setCenter(new GLatLng($latitude, $longitude), 17);
	map.setMapType(G_NORMAL_MAP);
NODES;
	} else {
echo <<<NO_NODES
		address = "$net_location";
		geocoder.getLatLng(address,function(point) {
      		if (!point) {
        		alert(address + "We tried to find your network and this is the closest thing we have found. If incorrect, move the map to the proper position.");
      		} else {
        		map.setCenter(point, 13);
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
	while ($row = mysql_fetch_array($result, MYSQLI_ASSOC))
	{
		$approval_status=$row["approval_status"];
		if($approval_status == "D" || $approval_status == "R"){continue;}
		
		$name=str_replace("*"," ",$row["name"]);
		$description=$row["description"];
		$ip=$row["ip"];
		$id=$row["id"];
		$mac=$row["mac"];
		$longitude=$row["longitude"];
		$latitude=$row["latitude"];
		$owner_name=$row["owner_name"];
		$owner_email=$row["owner_email"];
		$owner_phone=$row["owner_phone"];
		$owner_address=$row["owner_address"];
		$gateway=$row["gateway"];
		$gw_metric=$row["gw-qual"];
		$users=$row["users"];
		$time=$row["time"];
		
		// Calculate min, max latitude, longitude for center and zoom later
		if ($latitude < $minX) $minX = $latitude;
		if ($latitude > $maxX) $maxX = $latitude;
		if ($longitude < $minY) $minY = $longitude;
		if ($longitude > $maxY) $maxY = $longitude;
		
		if (!strlen($gw_metric)) $gw_metric = 0;
		
		$ctime = getdate();
		$ctime = $ctime[0];
		$up = $ctime-strtotime($time);
		$LastCheckin = humantime($time);
	
		switch($utype){
		case 'admin':
			$draggable = true;
			break;
		case 'user':
			$draggable = false;
			break;
		default:
			$draggable = false;
			break;
		}
		
// Create the Marker
$html = addslashes('<form name="basicEdit" method="POST">'.
			'<h3>Basic Node Info</h3>'.
				'<table id="node">'.
				'<tr>'.
				  '<td class="style1">Node Name:</td>'.
				  '<td><input type="text" size="32" name="node_name" value="'.$name.'"></td>'.
				'</tr>'.
				'<tr>'.
				  '<td><span class="style1">MAC:</span><span class="style2">&nbsp;&nbsp;</span></td>'.
				  '<td><input type="text" size="32" name="mac" value="'.$mac.'" readonly></td>'.
				'</tr>'.
				'<tr>' .
				  '<td><span class="style1">Description:</td>' .
				  '<td><input type="text" size="32"  name="description" value="' . $description . '"></td>' .
				'</tr>'.
				'<tr>' .
				  '<td><span class="style1">Latitude:</span></td>' .
				  '<td><input type="text" size="32"  name="latitude" value="' . $latitude . '" readonly></td>' .
				'</tr>'.
				'<tr>' .
				  '<td><span class="style1">Longitude:&nbsp;</span></td>' .
				  '<td><input type="text" size="32"  name="longitude" value="' . $longitude . '" readonly></td>' .
				'</tr>'.
				'<tr>' .
				  '<td><input type="hidden" name="net_name" value="' . $net_name . '"></td>' .
				  '<td><input type="hidden" name="id" value="' . $id . '"></td>' .
				  '<td><input type="hidden" name="user_type" value="' . $utype . '"></td>' .
				  '<td><input type="hidden" name="form_name" value="basicEdit"></td>'.
				'</tr>' .
		      	'<tr>' .
					'<td>'.
						'<input type="submit" name="submit" value="Update" onClick="addNode(this.form)">' .
	  	    			'<input type="button" name="Delete" value="Delete" onClick="deleteNode(this.form)">'.
					'</td>' .
				'</tr>' .
				'</table></form>');

$owner = addslashes('<form method="POST" action="../nodes/c_updatenode.php">'.
			'<h3>Information</h3>'.
			'<table class="infoWindow">'.
			'<tr>'.
				'<td>Owner Name:</td>'.
				'<td><input type="text" size="32" name="owner_name" value="'.$owner_name.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td><a href="mailto:'.$owner_email.'">Owner Email:</a></td>'.
				'<td><input type="text" size="32" name="owner_email" value="'.$owner_email.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Phone:</td>'.
				'<td><input type="text" size="32" name="owner_phone" value="'.$owner_phone.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Address:</td>'.
				'<td><input type="text" size="32" name="owner_address" value="'.$owner_address.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td><input type="hidden" name="net_name" value="' . $net_name . '">' .
				'<input type="hidden" name="mac" value="' . $mac . '">' .
				'<td><input type="hidden" name="user_type" value="' . $utype . '"></td>' .
                '<input name="updates" type="hidden" value="Owner">'.
			'</tr>'.
                '<td><input type="submit" name="submit" value="Reload">'.
  	    		'<input type="button" name="Delete" value="Delete" onClick="deleteNode(this.form)"></td>'.
  	    	'</tr>' .
			'</table></form>');

$html_string = '<h3>Datos del nodo '.$name.'</h3>'.'<table class="infoWindow">'.
			'<tr>'.
				'<td>Owner Node:</td>'.
				'<td>'.$owner_name.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Email:</td>'.
				'<td>'.$owner_email.'</td>'.
			'</tr>'.
			'<tr>' .
				'<td colspan=2><i>Telephone and address of the owner</i></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Last Checkin:</td>'.
				'<td>'.$LastCheckin.'</td>'.
			'</tr>'.
			'</table>';
$status = addslashes($html_string);

echo <<<END
 		
	point = new GPoint($longitude, $latitude);
	var marker = new nodeMarker(map, "$net_name", point, "$name", "$notes", "$mac", "$gateway", "$gw_metric", "$up", "$draggable", "$users");	
END;
	if($utype=='admin'){
		echo 'marker.addTab("General","'.$html.'");';
	} else {
		echo 'marker.addTab("Status","'.$status.'");';
	}
		
echo <<<END
	marker.addListeners();
	map.addOverlay(marker.get());
END;
	
	}
}

mysql_close($conn);

// We're done, so center and zoom the map
echo <<<END
	
		myCenterAndZoom(map, $minX, $maxX, $minY, $maxY, "$node_loc");
		var imagen = new GGroundOverlay("$floor_plan", map.getBounds());
		map.addOverlay(imagen);
	}  

END;

?>

//]]--->
</script>
</head>
<body style="width: 100%; height: 100%" bgcolor="#FFFFFF" align="center" onLoad="onLoad()" onResize="setMapSizePos()" onUnload="GUnload()" >
<?php include '../lib/menu.php'; ?>

<div align="center" id="top">
  <input name="net_name" id="net_name" type=hidden value=<?php print $net_name?>>
</div>
<!-- <tr>
  <td align='center'>
	<a href=""><img src="" border="" ALIGN=ABSMIDDLE></a> <font style="font-size: 13px; color:acacac;">|</font>
      <a href=""><img src="" border="" ALIGN=ABSMIDDLE></a> <font style="font-size: 13px; color:acacac;">|</font>
      <a href=""><img src="" border="" ALIGN=ABSMIDDLE></a><br>
  </td></tr> -->
<br>
<div class=note id=tip>Click on map to place a node, drag the nodes to control placement, double click to edit. <a href=javascript:close()>Hide tip</a>
</div>
<div id="map" style="width: 100%; height: 100%" text-align="center"></div>
</body>
</html>
