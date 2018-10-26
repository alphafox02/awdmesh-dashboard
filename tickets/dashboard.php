<?php
include "access.php";
include "config.php";

$sitelist=explode(',', $_SESSION['seclevel']); 
$sqlsitelist=implode(",",$sitelist); 
$tickets = mysql_query("SELECT * from vouchers where voucherstate='1'");
    
    while($node = mysql_fetch_array($tickets)){
    
    $nodeTAB .= "<tr>
                <td>$node[voucherid]</td>
                <td>$node[voucherowner]</td>
                <td>$node[voucherusername]</td>
                <td>$node[voucherpassword]</td>
                <td>$node[voucherexpires]</td>
		<td><input type='button' value='Rev' name='rev$node[nref]' onclick='javascript:revticket($node[nref],this.parentNode.parentNode.rowIndex);'></td>
                </tr>";

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
    
    <script type="text/javascript">

function createObject() {
var request_type;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer"){
request_type = new ActiveXObject("Microsoft.XMLHTTP");
}else{
request_type = new XMLHttpRequest();
}
return request_type;
}

var http = createObject();
// Revoke Ticket Functions
function revticket(tik,i) {

var checker=confirm('Revoking this ticket will stop internet access for the ticket owner. Continue?');

if (checker) {
	 nocache = Math.random();
	 http.open('get', 'revticket.php?ticket='+tik+'&nocache='+nocache+'&row='+i);
	 http.onreadystatechange = loginReply;
	 http.send(null);
	 }
}
function loginReply() {
    if(http.readyState == 4){
	var response = http.responseText;
	if(parseInt(response) == -1){
	    alert('Failed Deleted');
	    } else {
	    alert('Voucher Revoked.  Find it in unused tickets');
	    document.getElementById('box-table-a').deleteRow(parseInt(response));
	}
    }
}

</script>



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

<div class="container_16" id="wrapper">	
<div class="grid_8" id="logo">Ticket Management Portal</div>
<div class="grid_8">
<div id="user_tools"><span><a href="#" class="mail">(1)</a> Welcome <a href="#">Admin Username</a>  |   |  <a href="logout.php">Logout</a></span></div></div>
<!-- USER TOOLS END -->    
<div class="grid_16" id="header">
<!-- MENU START -->
<div id="menu">
	<ul class="group" id="menu_group_main">
	<li class="item first" id="one"><a href="dashboard.php" class="main current"><span class="outer"><span class="inner users">Active Vouchers</span></span></a></li>
	<li class="item second" id="two"><a href="unused.php" class="main"><span class="outer"><span class="inner users">Unused Vouchers</span></span></a></li>     
	<li class="item last" id="eight"><a href="settings.php" class="main"><span class="outer"><span class="inner settings">Vouchers Settings</span></span></a></li>     
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
  
    <div class="clear">
    </div>
    <!--  TITLE END  -->    
    <!-- #PORTLETS START -->
    <div id="portlets">
    <div class="portlet">
    <div class="portlet-header fixed"><img src="images/icons/sites.png" width="16" height="16" alt="" /> Sites:</div>
    <div class="portlet-content nopadding">
<!-- here is the jquery Table -->

        <table width="100%" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">
            <thead>
              <tr>
                <th width="200" scope="col">VoucherID</th>
                <th width="160" scope="col">VoucherOwner</th>
                <th width="159" scope="col">VoucherUsername</th>
                <th width="100" scope="col">VoucherPassword</th>
                <th width="150" scope="col">VoucherExpires</th>
                <th width="100" scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
	
	<?php echo $nodeTAB; ?>
       </table> 
<tr class="footer">
               
                
              </tr>
            </tbody>
          </table>

<!-- End jquery Table -->


	<!--<div id="pager"></div> -->
		
</div>
    </div>
    </div>
     </div>
  
    
<!--  Second Table -->

 <div class="clear"></div>

   


   
<!--  END #PORTLETS -->  
 
    <div class="clear"> </div>
<!-- END CONTENT-->    
  </div>
<div class="clear"> </div>

		
<!-- WRAPPER END -->
<!-- FOOTER START -->
<div class="container_16" id="footer">
 <a href="http://www.awdmesh.com">(C) 2011 Anaptyx Wireless Dynamics.</a></div>


<!-- FOOTER END -->
</body>
</html>
