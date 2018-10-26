<?php  
/* Name: nets_list.php
 * Purpose: view network list



 */

//Start session
session_start();

?>
<head>
<script language="javascript" type="text/javascript">
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<script src='../lib/sorttable.js'></script>
<?php 

//Includes
include "../lib/style.php";
?>
<title>CloudController | Network List</title></head>
<body onload=Nifty("div.comment#tip");>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>
<?php 
include "../lib/menu.php";

//Setup database connection
require_once "../lib/connectDB.php";
setTable("node");


?>
  <table width="900"  border="0" cellpadding="0" cellspacing="0" >
  <tr><td align='center'>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#ff9900;"><?php if($ulang=='en') echo '<b>Global List by Network Name</b><br>'; else echo '<b>&#24050;&#24314;&#32593;&#32476;</b><br>'; ?></font></font>
  </td></tr>
  <tr><td height=20></td></tr>
  </table>


<?php 
// define the mySQL query
$query = "SELECT * FROM network" ;
$result = mysql_query($query, $conn);

if($ulang=='en') {
  if(mysql_num_rows($result)==0) die("<div class=error>No hay redes en la BD.");
} else {
  if(mysql_num_rows($result)==0) die("<div class=error>No hay redes en la BD.");
}

//Table columns, in format Display Name => DB field name.
// Build a reference hash  ("Display field" => "SQL field reference")
$ref_fields = array("Network Name" => "net_name","Channel" => "radio_channel","Network Admin" => "email1","SQL ID" => "id","Users" => "users");

//Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column.
echo "";
echo "<table class='sortable' border='1'>";

// Write the top row of the table (display names)
echo "<tr class=\"fields\">";
foreach($ref_fields as $key => $value) {
//    echo "<td>" . $key . "</td>";
      echo "<td align='center'>";
            if ($value=="net_name") echo $key;
            if ($value=="radio_channel") echo $key;
            if ($value=="email1") echo $key;
			if ($value=="users") echo $key;
            if ($value=="id") echo $key;
      echo "</td>";
}
echo "</tr>";
echo "<tr><td height=10></td></tr>";

//Output the content of the table
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo "<tr>\n"; 
        foreach($ref_fields as $key => $value) {
            echo "<td align='left' height=20>\n";
            if ($value=="net_name") echo "<a href='javascript:document.net_select.net_name.value = \"" .$row[$value] ."\";document.net_select.submit()'>" . $row[$value] . "</a>";
            if ($value=="radio_channel") echo $row[$value];
            if ($value=="email1") echo $row[$value];
			   if ($value=="users") echo $row[$value];
            if ($value=="id") echo $row[$value];
        }
        echo "</tr>\n";
}
echo "</table>";

//Display NiftyCorners effects
?>
<br>
</td></tr></table>

<form method="POST" action='../entry/c_select.php' name="net_select">
  <input name="net_name" type="hidden">
</form>

</body>
</html>
