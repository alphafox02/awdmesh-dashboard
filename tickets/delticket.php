<?php

include 'config.php';


if (!isset($_GET[ticket])) {
    echo "-1";
}

else {
$tik=$_GET[ticket];
$i=$_GET[row];
$result=mysql_query("delete from vouchers where nref ='$tik'");

if (!$result) {
    echo mysql_error();
}
    echo $i;
}

?>