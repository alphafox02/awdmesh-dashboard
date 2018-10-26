<?php 
/* Name: select.php
 * Purpose: select network page.


 */

$cls_flag = $_GET['cls'];

session_start();

?>
<html>
<head>
<title>CloudController | View Network Status</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<?php include "../lib/style.php"; ?>
</head>
<body>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
  <tr><td style="padding:0px;" align=center>
  <?php  include "../lib/menu.php"; ?>
<!--
Select the network you'd like to see.
-->
  <table cellpadding="0" cellspacing="0" border=0 width=1040>
  <tr><td width=100% align=center>
    <h1><b><?php if($ulang=='en') echo 'View Network Status'; else echo '&#36873;&#25321;&#24744;&#35201;&#26597;&#30475;&#30340;&#32593;&#32476;'; ?></b></h1>
   </td></tr>
  </table>
<form method="POST" action='c_select.php' name="select">
<?php  
if($cls_flag==0) {
  if (isset($_SESSION['error'])) {
    if ($ulang=='en') echo "<div class='error'>This network does not exist: try again.</div><br>";
    else echo "<div class='error'>&#32593;&#32476;&#19981;&#23384;&#22312;</div><br>";
    unset($_SESSION['error']);
  }
} else {
  unset($_SESSION['error']);
}
?>
	<?php if($ulang=='en') echo 'Network Name:'; else echo '&#32593;&#32476;&#21517;'; ?> <input name="net_name" select><br>
<br>
  	<input name="select" value="<?php if($ulang=='en') echo 'View Status'; else echo ' &#26597;&#30475;&#32593;&#32476; '; ?>" type="submit">
</form>
  </td>
  </tr>
  <tr> <td height=20> </td> </tr>
  <tr>
  <td align='center'>
  </td>
  </tr>
</table>
</body>
</html>
