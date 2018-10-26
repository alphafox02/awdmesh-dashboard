<?php
session_start();
if ($_SESSION['user_type']!='admin' && $_SESSION['user_type']!='user')
	header("Location: ../entry/login.php");
 ?>
<html><head>
 	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
 	<title>Upload Overlay | <?php echo $_GET["net_name"] ?></title>
</head>
<body>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
	<tr><td height=10></td></tr>
	<tr><td style="padding:0px;" align=center>
<?php
include "../lib/style.php";
include "../lib/menu.php";
?>

</html>
<br><br><br><br>
<table>
<tr>
	<td>&nbsp;</td><td><form action="c_upload_file.php" method="POST" enctype="multipart/form-data"><input type="file" name="file" id="file" value="Browse"></td>
</tr>
<tr height="20px">&nbsp;</tr>
<tr>
   <input type="hidden" name="net_name" value="<?php echo $_GET["net_name"] ?>">
   <input type="hidden" name="logo" value="<?php echo $_GET["logo"] ?>">
   <input type="hidden" name="mac" value="<?php echo $_GET["mac"] ?>">
   <td></td><td><input type="submit" name="submit" value="Submit image"></td>
</tr>
	</form>
</table>
</html>
