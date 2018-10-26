<?php 
/* Name: toolbox.php
 * Purpose: general utility functions for the dashboard.
 *
 
 *

 */

//insert values corresponding to fields into the table
function insert($table,$fields,$values){
	global $conn;	//the db connection
	
	//convert the fields array into a comma-seperated list
	$fields = implode(",",$fields);
	
	//seperate the values array into a comma/' seperated list
	$values = implode("','",$values);
	
	//generate sql query
	$query = "INSERT INTO ".$table;
	$query .= " (".$fields.") ";
	$query .= "VALUES('".$values."')";
	
	//echo $result."<br>";
	
	//execute the query
	mysql_query($query, $conn) or die("Error executing query: ".mysql_error($conn));
}

//Get the values we ask for from post
function getValuesFromPOST($fields){
	foreach ($fields as $f){
		$val = $_POST[$f];
		$values[]=$_POST[$f];
	}
	return $values;
}

//Sanitize a string
//I think this is best used in controllers, to check their own input. We don't
//want someone to be able to mess stuff up by calling a controller script.
function sanitize($string){
	global $conn;
	return mysql_real_escape_string(htmlspecialchars($string), $conn);

}
function sanitizeAll(){
	foreach($_POST as $key=>$value){
		$_POST[$key] = sanitize($value);
	}
	foreach($_GET as $key=>$value){
		$_GET[$key] = sanitize($value);
	}
}
function is_mac($mac){
	if(preg_match("/^([0-9A-F][0-9A-F]:){5}[0-9A-F][0-9A-F]$/i",$mac)){
//	echo "valid!";
		return true;
	} else {
//	echo "invalid. :(";
		return false;
	}
}
function humantime($time){
	$ctime = getdate();
	$ctime = $ctime[0];
	$up = $ctime-strtotime($time);

	$days  = (int)($up / 86400);
	$hours = (int)(($up - ($days * 86400)) / 3600);
	$mins  = (int)(($up - ($days * 86400) - ($hours * 3600)) / 60);
	$secs  = (int)(($up - ($days * 86400) - ($hours * 3600) - ($mins * 60)));


	if($days>=14000)
		return "never checked in";
	if($days>50)
		return "Over 50 days";

	if ($days)
		$humantime = "$days days, $hours hours, $mins mins";
	else if ($hours)
		$humantime = "$hours hour, $mins mins";
	else if ($mins)
		$humantime = "$mins mins, $secs secs";
	else
		$humantime = "$secs secs";

	return $humantime;

}

function calculadistancia($lat1, $long1, $lat2, $long2) {
	$earth = 6371000; //meters
	//$earth = 3960; //miles

	//Point 1 cords
	$lat1 = deg2rad($lat1);
	$long1= deg2rad($long1);

	//Point 2 cords
	$lat2 = deg2rad($lat2);
	$long2= deg2rad($long2);

	//Haversine Formula
	$dlong=$long2-$long1;
	$dlat=$lat2-$lat1;

	$sinlat=sin($dlat/2);
	$sinlong=sin($dlong/2);

	$a=($sinlat*$sinlat)+cos($lat1)*cos($lat2)*($sinlong*$sinlong);

	$c=2*asin(min(1,sqrt($a)));

	$d=round($earth*$c,1);

return $d;
}

?>
