<?php
/* Name: c_password_lost.php
 * Purpose: process password change.


 */
session_start();
require_once '../lib/connectDB.php';
include "../lib/style.php";
include '../lib/toolbox.php';
?>
<html><head>
 	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
 	<title>AWD Mesh</title>
</head>
<body>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
	<tr><td height=10></td></tr>
	<tr><td style="padding:0px;" align=center>
<?php
include "../lib/menu.php";



echo "<br><br><br><br><br><br>";


if ((($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg"))
&& ($_FILES["file"]["size"] < (150*1024))) {          // LIMITAR A 150Kb EL TAMAÑO DE LA IMAGEN

$net_name = $_POST["net_name"];


$directorio = "../users_splash/".$net_name;
if (!file_exists("../users_splash/".$net_name)) {$rs = mkdir("../users_splash/".$net_name,0777);}
if (!file_exists("../users_splash/".$net_name."/images")) {$rs = mkdir("../users_splash/".$net_name."/images",0777);}


$raiz = "floor-";


	if ($_FILES["file"]["error"] > 0) {
    	echo "Error: " . $_FILES["file"]["error"] . "<br>";
    } else {
        if ($_SERVER['HTTPS'] != '') $protocol = "https"; else $protocol = "http";
		$url = $protocol."://".$_SERVER['SERVER_NAME']."/".$_SESSION["dashboard"]."users_splash/".$net_name."/images/".$raiz.$_FILES["file"]["name"];
        if ($_POST["logo"] == '1') { // Graba imagen logo_tickets en tabla network
			$query = "UPDATE network SET logo_tickets='".$url."' WHERE id='".$_SESSION["netid"]."'";
        } else if ($_POST["mac"] != '') {  // Graba imagen floor_plan en tabla custom
			$query = "UPDATE custom SET floor_plan='".$url."' WHERE mac='".$_POST["mac"]."'";
		} else {    // Graba imagen floor_plan en tabla network
			$query = "UPDATE network SET floor_plan='".$url."' WHERE id='".$_SESSION["netid"]."'";
		}
		$result = mysql_query($query, $conn) or die("Error in sql: ".mysql_error($conn));

		echo "<br><br>Uploaded: " . $_FILES["file"]["name"] . "<br>";
    	echo "<br>Type: " . $_FILES["file"]["type"] . "<br />";
    	echo "<br>Size: " . round(($_FILES["file"]["size"] / 1024),1) . " Kb<br />";

    	move_uploaded_file($_FILES["file"]["tmp_name"], "../users_splash/".$net_name."/images/".$raiz.$_FILES["file"]["name"]);
    	echo "<br>Saved as: ".$url;
    }
} else   {
	echo "Error uploading " . $_FILES["file"]["name"] . " with size " . round(($_FILES["file"]["size"] / 1024), 1) . " Kb.";
}


echo "<br><br><br><a href='edit.php'>Back</a>";

?>
</body>
</html>
