<?php 
/* Name: login.php
 * Purpose: creates a user session and logs the user into the config or status page.


 */

$rd_flag = $_GET['rd'];
unset($_GET['rd']);
session_start();


if(isset($_POST["submit"])){
	//unset variable
	unset($_POST["submit"]);
	//setup connection
	require_once '../lib/connectDB.php';
	include '../lib/toolbox.php';
	setTable('network');
	sanitizeAll();
	
	//generate query
	$net_name = $_POST["net_name"];
	$password = md5($_POST["password"]);
	
	$query = "SELECT * FROM ".$dbTable." WHERE net_name='".$net_name."' AND password='".$password."'";
	$result = mysql_query($query, $conn);
	
	$num = mysql_num_rows($result);

	if (($num < 1) && ($_POST["password"] == $_SESSION["password"])) {
		$query = "SELECT * FROM ".$dbTable." WHERE net_name='".$net_name."'";
		$result = mysql_query($query, $conn);
		$num = mysql_num_rows($result);
	}
	
	if ( $num >= 1 )
	{   
    	// A matching row was found - the user is authenticated as 'admin'. 
    	$resArray = mysql_fetch_array($result, MYSQL_ASSOC);
    	
    	$_SESSION[ 'netid'        ] = $resArray[ 'id' ];
   		$_SESSION[ 'user_type'    ] = 'admin';
   		$_SESSION[ 'net_name'     ] = $net_name;
   		$_SESSION[ 'masterlogin'  ] = $resArray[ 'master_login' ];
   		
   		if ( $_SESSION[ 'masterlogin' ] == "9" ) 
   		{ 
   			// It is a master login 
   			//
   			$_SESSION[ 'bMasterLogin' ] = true;
   			
   			// Store the master network id
   			//
   			$_SESSION[ 'masternetid'  ] = $resArray[ 'master_netid' ];
   		} 
   		else 
   		{
   			// We are not loged in on a master network
   			//
   			$_SESSION[ 'bMasterLogin' ] = false;
   			
   			// Unset the masternetid
   			//
   			unset( $_SESSION[ 'masternetid' ] );
   		}
   		
   		// Redirt to the startup page
   		//
    	//echo "<meta http-equiv=\"Refresh\" content=\"0;url=../status/map.php\">"; 
    	//redirect to view.php (not to use Google map) -- MeshConnect 1/10/09
    	echo "<meta http-equiv=\"Refresh\" content=\"0;url=../status/map.php\">"; 
    	
	} else {
		//there was no match found, so the login failed
    	  unset($_SESSION['user_type']);
    	  $_SESSION['error'] = true;
    	  echo "<meta http-equiv=\"Refresh\" content=\"0;url=login.php?rd=$net_name\">"; 
  	}  
}
//else if (isset($_SESSION['authenticated']) && ($rd_flag != "edit")) {
//    // gets here if already logged in and coming from another page.
//    echo "<meta http-equiv=\"Refresh\" content=\"0;url=$rd_flag.php\">"; 
	

//}
 else {
?>
<!DOCTYPE html>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="en">
    <head>
        <title>Login</title>
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script type="text/javascript" src="js/cufon-yui.js"></script>
        <script type="text/javascript" src="js/Mr_Jones_400.font.js"></script>
        <script type="text/javascript">
            Cufon.replace('h1')('h2')('h3')('h4')('h5')('h6'); 
        </script>
        <style type="text/css">
<!--
.style1 {font-size: 10px}
.style4 {color: #5C8B38}
-->
        </style>
</head>
    <body>
        
        <div id="rail_top">
            <p>
                <strong><font color="#ffffff">Don't have an account?</font></strong> <a style="color:#8AD355" href="createmaster.php">Sign up today!</a>            </p>
    </div>
        
        <div id="wrap">
            
            <div id="page">
                
                <h1 id="logo">Anaptyx Mesh</h1>
                
                <form name="form" form method="post" action="<?php  echo "login.php?rd=$rd_flag"; ?>" id="login_box" onSubmit="if (this.checker.checked) toMem(this)" >
                    <?php  if (isset($_SESSION['error'])) if($ulang=='en') echo "<font style='color:#ff3311;'><b>Invalid Network Name/Password</b></font>"; else echo "<font style='color:#ff3311;'><b>Invalid Network Name/Password</b></font>";?>
              <h2>
                        <font color="#5D8D39">Sign in to</font><br>
                        <span class="brand_cloud">Cloud</span><span class="brand_controller">Controller</span>
                  </h2>
                    
                  <div align="center">
                      <input name="net_name" type="text" id="net_name"
                        onfocus="if(this.value=='Network Name'){this.value='';}"
                        onblur="if(this.value==''){this.value='Network Name';}" value="Network Name">
                      <br>
                     <script type="text/javascript" language="javascript">  
			function toPassword(oInput) {     
			var newEl = document.createElement('input');    
			newEl.setAttribute('type', 'password');   
			newEl.setAttribute('name', 'password');   
			newEl.setAttribute('className', 'text');   
			oInput.parentNode.replaceChild(newEl,oInput);     
			toPassword.el = newEl;     
			setTimeout('toPassword.el.focus()',100);     
			return true; 
}  
			</script>   
			<input type="text" name="password" value="Password"  
			class="text" id="password"  
			onfocus="if(this.value==this.defaultValue)return toPassword(this)"  /> 
                      <br>
                     <input type="submit" name="submit" value="Sign in"> 
			 <br>
                  <a style="color:#202020" href='../net_settings/password_lost.php' class="style1">forgot password</a>                        </div>
              </form>
                
        <!-- Typography Test -->
                <h1>&nbsp;</h1>
          </div>
            
        </div>
        
        <div id="rail_bottom">
    <div align="center"><font color="#5C8B38">Copyright © 2010 AWD. All rights reserved.</font></div>
        
    <script type="text/javascript">Cufon.now();</script>
    </body>
</html>
<?php 
} 