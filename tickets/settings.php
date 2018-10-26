<?php

include "access.php";
include "config.php";


function isDate($i_sDate)

{
  $blnValid = TRUE;
   if(!ereg ("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $i_sDate))
   {
    $blnValid = FALSE;
   }
   else //format is okay, check that days, months, years are okay
   {
      $arrDate = explode("/", $i_sDate); // break up date by slash
      $intDay = $arrDate[0];
      $intMonth = $arrDate[1];
      $intYear = $arrDate[2];
      $intIsDate = checkdate($intMonth, $intDay, $intYear);
   
     if(!$intIsDate)
     {
        $blnValid = FALSE;
     }
   }//end else

   return ($blnValid);
} //end function isDate

$sitelist=explode(',', $_SESSION['seclevel']); 
$sqlsitelist=implode(",",$sitelist); 

$result=mysql_query("SELECT * from sites where nref in (".$sqlsitelist.")");

if (mysql_num_rows($result)==1) {
	$ticklen=mysql_result($result,0,"ticketlen");
	} else {
        $ticklen=15;
    }

function genVoucher($length) {
      
    $characters = "123456789abcdefghijklmnpqrstuvwxyz";
$string="";
$unique=0;

while($unique==0) {

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    
$result=mysql_query("SELECT * from vouchers where voucherid='$string'");


if (mysql_num_rows($result)==1) {
	$unique=0;
    } else {
       	$unique=1;
    }

}

return strtoupper($string);
}

if (isset($_POST['submit'])) {
    
    //
    // Create x vouchers
    //
    
   
    
    if (is_numeric($_POST['novouchers'] )) {
	
$numvouchers=$_POST['novouchers'];

    }
    
    if (isDate ($_POST['expiry'] )) {
	  $todayDate = date("Y-m-d");// current date
$expdate=$_POST['expiry'];
    } else {
	
    $todayDate = date("Y-m-d");// current date
    $dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+1 month");
    $expdate=date("Y-m-d", $dateOneMonthAdded);
	
    }
    
      
    $l=0;
    while ($l<$numvouchers) {
     $vc= genVoucher($ticklen);
     $result=mysql_query("insert into vouchers set voucherid='$vc',voucherstate='0',vouchercreated='$todayDate',voucherexpires='$expdate'");
     $l=$l+1;
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CloudController | Voucher Control Panel</title>

<link rel="stylesheet" type="text/css" href="css/960.css" />
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/text.css" />
<link rel="stylesheet" type="text/css" href="css/blue.css" />
<link type="text/css" href="css/smoothness/ui.css" rel="stylesheet" />  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/blend/jquery.blend.js"></script>
	<script type="text/javascript" src="js/ui.core.js"></script>
	<script type="text/javascript" src="js/ui.sortable.js"></script>    
    <script type="text/javascript" src="js/ui.dialog.js"></script>

    <script type="text/javascript" src="js/effects.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.pack.js"></script>



    <!--[if IE]>
    <script language="javascript" type="text/javascript" src="js/flot/excanvas.pack.js"></script>
    <![endif]-->
	<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="css/iefix.css" />
	<script src="js/pngfix.js"></script>
    <script>
        DD_belatedPNG.fix('#menu ul li a span span');
    </script>        
    <![endif]-->

   
</head>

<body>
<!-- WRAPPER START -->
<div class="container_16" id="wrapper">	
<div class="grid_8" id="logo">Voucher Management Portal</div>
<div class="grid_8">
<div id="user_tools"><span><a href="#" class="mail">(1)</a> Welcome <a href="#">Admin Username</a>  |   |  <a href="logout.php">Logout</a></span></div></div>
<!-- USER TOOLS END -->    
<div class="grid_16" id="header">
<!-- MENU START -->
<div id="menu">
	<ul class="group" id="menu_group_main">
	<li class="item first" id="one"><a href="dashboard.php" class="main"><span class="outer"><span class="inner users">Active Vouchers</span></span></a></li>
	<li class="item second" id="two"><a href="unused.php" class="main"><span class="outer"><span class="inner users">Unused Vouchers</span></span></a></li>     
	<li class="item last" id="eight"><a href="settings.php" class="main current"><span class="outer"><span class="inner settings">Vouchers Settings</span></span></a></li>     
    </ul>
</div>
<!-- MENU END -->
</div>
<div class="grid_16">
<!-- TABS START -->
    <div id="tabs">
         <div class="container">
         
        </div>
    </div>
        </div>


<!-- CONTENT START -->
    <div class="grid_16" id="content">
    <!--  TITLE START  --> 
    <div class="grid_9">
    <h1 class="dashboard">Active Vouchers</h1>
    </div>
  
        <div class="clear"></div>
            <div id="portlets">
                <div class="portlet">
		    <div class="portlet-content nopadding">
			<p> Here you can create vouchers for assignment later.  Please note that vouchers are assigned an expiry date when they are created.</p>

			<form method="post" action="settings.php">
			 No of vouchers to create: <input type="text" name="novouchers">
			 Expirty Date of Vouchers: <input type="text" name="expiry">
			 <input type="submit" name="submit" value="Create Vouchers">
			</form>

	            </div>
	        </div>
	    </div>
    </div>

    
    <div class="clear"></div>

   


   
<!--  END #PORTLETS -->  
 
<div class="clear"> </div>
  
<div class="clear"> </div>

</div>

<!-- WRAPPER END -->
<!-- FOOTER START -->
<div class="container_16" id="footer">
 <a href="http://www.awdmesh.com">(C) 2011 Anaptyx Wireless Dynamics.</a></div>


<!-- FOOTER END -->
</body>
</html>
