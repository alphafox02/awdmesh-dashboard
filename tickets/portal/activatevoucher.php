<?php
$vo=strtoupper($_GET[voucher]."");
session_start();
include 'config.php';
$_SESSION['login']="authorise.php";
$result=mysql_query(sprintf("select * from vouchers where voucherid='%s'",$vo));

			if (mysql_num_rows($result)==1) {

					$vs=mysql_result($result,0,"voucherstate");
					$ve=mysql_result($result,0,"voucherexpires");

					if ($vs<>"0" ) {
							echo "Voucher no longer valid";
							die();
						}

						$result=mysql_query(sprintf("update vouchers set voucherstate='1' where voucherid='%s'",$vo));
$						$_SESSION['vouchergood']=1;
						$_SESSION['login']=$_SESSION['token'];
						echo "-1";

					
			} else {
				echo "Please enter a valid voucher code";
}

?>
