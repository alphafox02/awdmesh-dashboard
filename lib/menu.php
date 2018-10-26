<?php

// Anaptyx menu
//
// Johan van Zoomeren
// Anaptyx, Oktober 2010


// Apply user access rights to determine which menu items the
// user may view
//
function anGetAllowedMenuItems( $aMenuItems, $nUserTypes )
{
	$aMenu = array();
	
	foreach( $aMenuItems as $strItemTitle => $aMenuOptions )
	{
		$nItemUserTypes = $aMenuOptions[ "usertypes" ];
		
		// Add item it the user is allowed to view it
		//
		if ( $nUserTypes & $nItemUserTypes )
		{
		    $aMenu[ $strItemTitle] = $aMenuOptions;	
		}
	}
	
	return $aMenu;
}

// User types
//
define ( USERTYPE_MASTER, 1  );
define ( USERTYPE_ADMIN,  2  );
define ( USERTYPE_USER,   4  );
define ( USERTYPE_OTHER,  8  );
define ( USERTYPE_ALL,    15 );

// Globals
//
global $ulang;

// Variables
//
$bMasterAccount = false;
$ulang 			= 'en';
$utype 			= 'unknown';
$nUserTypes     = 0;
$strNetworkName = "";
$strDisplayName = "";
$urlPrefix      = "";
$bDebug         = false;

// Top left menu
//
$aTopLeftMenu   = array( "Bird's Eye View (BETA)" => array ( "url"       => "status/noc.php",
				  		   	                     "usertypes" => USERTYPE_MASTER   ) 
);

// Top right menu
//
$aTopRightMenu   = array( "Add/Delete Node" => array ( "url"       => "nodes/addnode.php",
                                                "usertypes" => USERTYPE_ADMIN     ), 
                          "Sign out" => array ( "url"       => "entry/logout.php",
                                                "usertypes" => USERTYPE_ADMIN     ),
                          "Sign in" => array (  "url"       => "entry/login.php",
                                                "usertypes" => USERTYPE_OTHER     ), 
                          "Help"     => array ( "url"       => "help/help.php",
                                                "usertypes" => USERTYPE_ALL,        
                                                "class"     => "help_link"        )
);

// Top right menu
//
$aMainMenu   = array( "Overview"   => array ( "url"       => "status/map.php",
                                              "usertypes" => USERTYPE_ADMIN + USERTYPE_USER    ), 
                      "Usage"      => array ( "url"       => "status/viewg.php",
                                              "usertypes" => USERTYPE_ADMIN     ), 
                      "Status"     => array ( "url"       => "status/view.php",
                                              "usertypes" => USERTYPE_ADMIN  + USERTYPE_USER   ),        
                      "Configure"  => array ( "url"       => "net_settings/edit.php",
                                              "usertypes" => USERTYPE_ADMIN,    )
);

// Start PHP session
//
session_start();

// Include database functions
//
require 'connectDB.php';

// Get the user type
//
if ( isset( $_SESSION[ 'user_type' ] ) )
{
	$utype = $_SESSION[ 'user_type' ];
}

// Convert usertype to numeric usertype
//
switch( $utype )
{
	case 'admin':
		$nUserTypes = USERTYPE_ADMIN;
		break;
	case 'user':
		$nUserTypes = USERTYPE_USER;
		break;
	default:
		$nUserTypes = USERTYPE_OTHER;
		break;
}

// Set the language
//

if( isset( $_SESSION[ 'lang_selc' ] ) )
{
	$ulang = $_SESSION['lang_selc'];
}

// Determine if we're on the index page - used to determine file paths.
//
$strUrlPrexix = ( false === strpos( $_SERVER[ 'PHP_SELF' ], 'index.php' ) ) ? "../" : "";

// note: there are quite a number of issues with the master network switch
//       code below, and is is my advice to fix it! Old code, not written
//       by me(Johan)!
//
$netid = $_SESSION['netid'];
$masternetid = $_SESSION['masternetid'];
//$query0="SELECT * FROM network WHERE master_netid='$masternetid' AND master_login='9'";
$query0="SELECT * FROM network WHERE master_netid=$masternetid AND master_login=9";
if ( true == $bDebug ) echo "query0: $query0<br />";
$result0=mysql_query($query0, $conn);
if( false !== $result && mysql_num_rows($result0)>0){
	// This is a master account
	//
	$bMasterAccount = true;

	//$query="SELECT * FROM network WHERE master_netid='$masternetid' AND (master_login='9' OR master_login='1')";
	$query="SELECT * FROM network WHERE master_netid=$masternetid AND (master_login=9 OR master_login=1)";
	if ( true == $bDebug ) echo "query1: $query <br />";
	$result=mysql_query($query, $conn);
	if(mysql_num_rows($result)>1){
		$strMasterSelectBox ='Select Network:&nbsp;<select name="networks" id="networks" onchange="cambiared()">';
		while($result1 = mysql_fetch_assoc($result)) {
			if($_SESSION['netid']==$result1["id"]) {
				$strMasterSelectBox .='<option selected value="'.$result1["id"].'">'.$result1["net_name"].'</option>';
			} else {
				$strMasterSelectBox .='<option value="'.$result1["id"].'">'.$result1["net_name"].'</option>';
			}
		}
		$strMasterSelectBox .='<option value="create">Create network</option>';
		$strMasterSelectBox .='<option value="birdseye">Bird\'s Eye View (BETA)</option>';
		$strMasterSelectBox .= '</select>';
		// don't store this in the session!!!
		//
		$_SESSION['html']=$strMasterSelectBox;
	} elseif(mysql_num_rows($result)==1){
		// Why doing this? This is an exact copy of the if block above!!!
		//
		$strMasterSelectBox ='Select Network:&nbsp;<select name="networks" id="networks" onchange="cambiared()">';
		while($result1 = mysql_fetch_assoc($result)) {
			if($_SESSION['netid']==$result1["id"]) {
				$strMasterSelectBox .='<option selected value="'.$result1["id"].'">'.$result1["net_name"].'</option>';
			} else {
				$strMasterSelectBox .='<option value="'.$result1["id"].'">'.$result1["net_name"].'</option>';
			}
		}
		$strMasterSelectBox .='<option value="create">Create network</option>';
		$strMasterSelectBox .='<option value="birdseye">Bird\'s Eye View (BETA)</option>';
		$strMasterSelectBox .= '</select>';
		// don't store this in the session!!!
		//
		$_SESSION['html']=$strMasterSelectBox;
	}
}

if ( true == $bMasterAccount )
{
	$nUserTypes += USERTYPE_MASTER;
}

// apply small hack to put networks in the drop-down
//
if ( "" != $strMasterSelectBox )
{
	//$strMasterSelectBox = "<li> |</li><li> $strMasterSelectBox </li>";
}

// Retrieve the network name and display name
//
$nNetworkId = ( true == array_key_exists( "netid", $_SESSION) ) ? $_SESSION[ 'netid' ] : 0;
$strQuery = "SELECT net_name, display_name FROM network WHERE id='$nNetworkId'";
$resResult = mysql_query( $strQuery, $conn );
if ( false !== $resResult && mysql_num_rows( $resResult ) > 0 )
{
	if ( $aRow = mysql_fetch_assoc( $resResult ) )
	{
		$strNetworkName = $aRow[ "net_name"     ];
		$strDisplayName = $aRow[ "display_name" ];
	}
}

// Dispay debug information
//
if ( true == $bDebug )
{
	echo "strUserType: $utype <br />";
	echo "nUserType: $nUserTypes <br />";
	echo "bOnIndex: $on_index <br />";
	echo "bMasterAccount: $bMasterAccount <br />";
	echo "url: " . $_SERVER['PHP_SELF'] . "<br />";
	echo "netid: $nNetworkId <br />";
	echo "master netid: $masternetid <br />";
	echo "net_name: $strNetworkName <br />";
	echo "display_name: $strDisplayName <br />";
	echo "url prefix: $strUrlPrexix <br />";
	echo "<br />Session params: <br />";
	echo "bMasterLogin: " . $_SESSION[ 'bMasterLogin' ] . "<br />";
    echo "netid: "        .	$_SESSION[ 'netid'        ] . "<br />";
    echo "net name: "     . $_SESSION[ 'net_name'     ] . "<br />";	
	echo "masternetid: "  . $_SESSION[ 'masternetid'  ] . "<br />";
    echo "user_type: "    .	$_SESSION[ 'user_type'    ] . "<br />";
}

// Build list of HTML items for top left menu
//
$aTopLeftMenuAccess  = anGetAllowedMenuItems( $aTopLeftMenu, $nUserTypes );
$strTopLeftMenuItems = "";
$nCountItems         = count ( $aTopLeftMenuAccess );
$nIndex              = 1;

foreach ( $aTopLeftMenuAccess as $strItemTitle => $aMenuOptions )
{
	$strItemUrl     = $aMenuOptions[ "url"       ];
	$nItemUserTypes = $aMenuOptions[ "usertypes" ];
	$strItem		= "<li><a href=\"" . $strUrlPrexix . $strItemUrl . "\">" . $strItemTitle . "</a></li>";

	// Add item it the user is allowed to view it
	//
	if ( $nUserTypes & $nItemUserTypes )
	{
		if ( $nIndex++ < $nCountItems )
		{
			// Add separator bar
			//
			$strItem .= "<li> | </li>";
		}

		$strTopLeftMenuItems .= $strItem;
	}
}


// Build list of HTML items for the top right menu
//
$aTopRightMenuAccess  = anGetAllowedMenuItems( $aTopRightMenu, $nUserTypes );
$strTopRightMenuItems = "";
$nCountItems          = count ( $aTopRightMenuAccess );
$nIndex               = 1;

foreach ( $aTopRightMenuAccess as $strItemTitle => $aMenuOptions )
{
	$strItemUrl     = $aMenuOptions[ "url"       ];
	$nItemUserTypes = $aMenuOptions[ "usertypes" ];
	$strCSSClass    = ( true == array_key_exists( "class", $aMenuOptions ) ) ? $aMenuOptions[ "class" ] : "";
	$strItem		= "<li class=\"" . $strCSSClass . "\"><a href=\"" . $strUrlPrexix . $strItemUrl . "\">" . $strItemTitle . "</a></li>";

	// Add item it the user is allowed to view it
	//
	if ( $nUserTypes & $nItemUserTypes )
	{
		if ( $nIndex++ < $nCountItems )
		{
			// Add separator bar
			//
			$strItem .= "<li> |</li>";
		}

		$strTopRightMenuItems .= $strItem;
	}
}

// Detect current selected menu item
//
$strMainMenuCurrent = ( true == array_key_exists( "nav_main_current", $_SESSION) ) ? $_SESSION[ 'nav_main_current' ] : "";
foreach ( $aMainMenu as $strItemTitle => $aMenuOptions )
{
	$strItemUrl = $aMenuOptions[ "url" ];

	if ( preg_match( '!' . $strItemUrl .'!', $_SERVER[ 'PHP_SELF' ] ) > 0 )
	{
		$strMainMenuCurrent             = $strItemTitle;
		$_SESSION[ 'nav_main_current' ] = $strMainMenuCurrent;

		break;
	}
}

// Build list of HTML items for the main menu
//
$aMainMenuAccess  = anGetAllowedMenuItems( $aMainMenu, $nUserTypes );
$strMainMenuItems = "";
$nCountItems      = count ( $aMainMenuAccess );
$nIndex           = 1;

foreach ( $aMainMenuAccess as $strItemTitle => $aMenuOptions )
{
	$strItemUrl     = $aMenuOptions[ "url"       ];
	$nItemUserTypes = $aMenuOptions[ "usertypes" ];
	$strCSSClass    = ( true == array_key_exists( "class", $aMenuOptions ) ) ? $aMenuOptions[ "class" ] : "";

	// create list item
	//
	if ( $strItemTitle == $strMainMenuCurrent )
	{
		$strItem = "<li class=\"other\"><a class=\"current\" href=\"" . $strUrlPrexix . $strItemUrl . "\">" . $strItemTitle . "</a></li>";
	}
	else
	{
		$strItem = "<li class=\"other\"><a href=\"" . $strUrlPrexix . $strItemUrl . "\">" . $strItemTitle . "</a></li>";
	}

	// Add item it the user is allowed to view it
	//
	if ( $nUserTypes & $nItemUserTypes )
	{
		$strMainMenuItems .= $strItem;
	}
}

// Generate the header, including the new anaptyx menus
//
$strOutput = <<<EOT
<link rel="stylesheet" type="text/css" href="../lib/anaptyx.css" />
<div id="html">
<div class="wrap">
<div id="header_new" style="zoom:1;clear:both;height:105px;overflow:hidden;">
    <div id="top_nav">
	<!--ul id="top_left_nav">
	$strTopLeftMenuItems

	    
	</ul--> <!-- top_left_nav -->
	
	<ul id="top_right_nav">
	$strTopRightMenuItems
	    <!--<li><a href="#">Network Info</a></li>
	    <li> | </li>
	    <li><a href="#">Sign Out</a></li>
	    <li> | </li>
	    <li class="help_link"><a href="#">Help</a></li>
	</ul> <!-- top_right_nav> -->
	
    </div> <!-- top_nav -->
    
    <h1 id="logo" style="margin-top:0px;">
	<span class="network_name">$strNetworkName&nbsp;</span>
	<span class="network_owner">$strDisplayName&nbsp;</span>
    </h1>
    
    <!-- note: class="other" and class="last" is done to display menus correctly
	 in IE. This is a hack -->
    <ul id="main_nav" style="position: relative;">
    $strMainMenuItems
	<!--<li class="other"><a href="#" class="current">Overview</a></li>
	<li class="other"><a href="#">Usage</a></li>
	<li class="other"><a href="#">Status</a></li>
	<li class="other"><a href="#">Configure</a></li> -->
	<li class="last" style="float:right;color:#fff;">$strMasterSelectBox</li>
    </ul> <!-- main_nav -->
    
</div> <!-- header_new -->

</div> <!-- wrap -->
</div> <!-- html -->
EOT;

// echo the menu
//
echo $strOutput;

?>
