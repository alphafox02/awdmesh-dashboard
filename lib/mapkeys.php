<?php 
/* Name: mapkeys.php
 * Purpose: selects the proper google map api key for the host server.

 */


//Set up google maps
//$myAddress = "awdmesh.com";    //Your server's URL, without the http://
//$myKey = "ABQIAAAAc4wQlMSg-_8v5xPpds7N-xT2ZxlvTVgIbgVDppTC-5-vnBu6SRRLxcAxv7xzM27IaxT76QW14kXmzg";    //Go to http://code.google.com/apis/maps/signup.html for a key
$myAddress = "dashboard.awdmesh.com";    //Your server's URL, without the http://
//$myKey = "ABQIAAAAVKmcMcPpPFk7hi51hLIDNhT2ZxlvTVgIbgVDppTC-5-vnBu6SRScNDDQwJODAZwizpkD6xNxwVcCIw";
$myKey = "AIzaSyCVe5DXzALEWmZTvcI6B2gjmPNegoOEY68";
//Set host
$host = $_SERVER['HTTP_HOST'];

//Output map script from Google
if ($host == "localhost")
	echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$myKey.'&sensor=false" type="text/javascript"></script>'."\n" ;  
else if ($host == "omnis.hopto.org")
	echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$myKey.'&sensor=false" type="text/javascript"></script>'."\n" ;  
else if ($host == "open-mesh.com")
	echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$myKey.'&sensor=false" type="text/javascript"></script>'."\n" ;  
else if ($host == $myAddress)
	echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$myKey.'&sensor=false" type="text/javascript"></script>'."\n" ;  
else{
	echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$myKey.'&sensor=false" type="text/javascript"></script>'."\n" ;  
	//include '../lib/menu.php';
	//die('<div class=error>This OrangeMesh server does not have a Google Map key, which is needed to display the maps on this page. Contact the administrator, or see the owner\'s manual for information on how to get this key.</div>');
}
?>
