$sliderval=intval($_GET['sliderval']); //get the value from ajax function
$link = mysql_connect('localhost', 'root', ''); //change the onfiguration in required
if (!$link) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db('db_slider'); //change the name of the database if required
$query="UPDATE tbl_slider SET slider_val='$sliderval' WHERE id='1'";
$result=mysql_query($query);