<?php



session_start();

session_unset("nref");
session_destroy();





setcookie("nref","", "0", "/", "", "");


$url2 = 'login.html';

?>



<html>

<head>

<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?php echo$url2?>">

</head>

</html>
