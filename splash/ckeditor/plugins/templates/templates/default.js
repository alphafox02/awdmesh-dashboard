/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default',
{
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
//	imagesPath : CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),
imagesPath : 'plugins/templates/templates/',
	// The templates definitions.
	templates :
		[		
                     {
				title: 'Apartments',
				image: 'images/Apa1_template.jpg',
				html:
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
'<html xmlns="http://www.w3.org/1999/xhtml">' +
'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' +
'<meta name="keywords" content="awd, hotel, resort">' +
'<title>CloudController Splash Page</title>' +
'<style type="text/css">' +
'body,td,th {font-family: Tahoma;font-size: 12px;color: #666666;}body {background-color: #480A00;	margin-left: 1px;margin-top: 1px;margin-right: 1px;margin-bottom: 1px;background-image: url(ckeditor/plugins/templates/templates/images/bg2.gif);background-repeat: repeat-x;a:hover {color: #005080;text-decoration: none;}.style1 {font-size: 16px;font-weight: bold;color: #CCCCCC;}.style3 {font-size: 18px;color: #662626;}.style4 {font-size: 11px;color: #FFFFFF;font-family: Arial, Helvetica, sans-serif;}a:link {color: #00406A;	text-decoration: none;}.style5 {font-size: 9px}.style7 {font-size: 9px; color: #FFFFFF; }.style8 {color: #CCCCCC}a:visited {color: #003F69;	text-decoration: none;}.style9 {font-size: 18px;color: #61839E;}a:active {text-decoration: none;}.style13 {color: #FFFFFF;	font-family: Arial, Helvetica, sans-serif;font-size: 16px;font-weight: bold;}.style14 {font-size: 16px; font-weight: bold; color: #CCCCCC; font-family: Arial, Helvetica, sans-serif; }.style15 {color: #CCCCCC; font-family: Arial, Helvetica, sans-serif; }' +
'</style></head>' +
'<body>' +
'<center>' +
'<img src="ckeditor/plugins/templates/templates/images/Apartment-Header1A.jpg" alt="Welcome!" width="740" height="197" align="top"/>' +
'<table width="740" border="0" cellpadding="8" cellspacing="1" bgcolor="#333333">' +
'<tr>' +
'<td width="493" height="149"><p align="left" class="style1">&nbsp;</p>' +
'<p align="left" class="style14">Welcome to the Apartment Wi-Fi Network!</p>' +
'<p align="left" class="style15">This network access is provided to you on behalf of the management. We  hope you enjoy your experience. While you surf, please help us keep the network experience great for everyone. Save large uploads and downloads for a private connection.</p>' +
'<p align="left" class="style15">Thanks again,</p>' +
'<p align="left" class="style15">Apartment Management</p>' +
'<p align="left" class="style8">&nbsp;</p></td>' +
'<td width="5"><p align="left" class="style3"><img src="ckeditor/plugins/templates/templates/images/Apartment-Spacer.gif" width="5" height="100" /></p>' +
'</td>' +
'<td width="190"><p align="left" class="style9"><span class="style13">Begin browsing</span></p>' +
'<p align="left" class="style3"><a href="$authtarget"><img src="ckeditor/plugins/templates/templates/images/Button-Enter2.gif" alt="Begin browsing" width="70" height="20" border="0" /></a></p>' +
'<form method="get" action="$authaction"><input name="tok" value="$tok" type="hidden"/> <input name="redir" value="$redir" type="hidden"/> </form>' +
'<p align="left" class="style4">Click above to see your homepage</p></td>' +
'</tr>' +
'</table>' +
'<table width="740" border="0" cellpadding="0" cellspacing="0" bgcolor="#000000">' +
'<tr>' +
'<td><div align="center"><span class="style7">&copy; AWD</span></div></td>' +
'</tr>' +
'</table>' +
'<p class="style5">' +
'<p class="style5">' +
'<p class="style5"><br />' +
'</center>' +
'</body>' +
'</html>' 
            },
                     {      title: 'Campgrounds',
                            image: 'images/camp_template.jpg',
                            html:
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
'<html xmlns="http://www.w3.org/1999/xhtml">' +
'<head>' +
'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' +
'<meta name="keywords" content="awd, hotel, resort">' +
'<title>CloudController Splash Page</title>' +
'<style type="text/css">' +
'body,td,th {	font-family: Tahoma;	font-size: 12px;color: #FDFCFF;}body {background-color: #45311f;margin-left: 1px;margin-top: 1px;margin-right: 1px;margin-bottom: 1px;background-image: url(ckeditor/plugins/templates/templates/images/bg.gif);background-repeat: repeat-x;}a:hover {color: #005080;text-decoration: none;}.style1 {font-size: 16px;font-weight: bold;color: #CCCCCC;}.style3 {font-size: 18px;color: #662626;}.style4 {font-size: 11px;color: #000000;font-family: Arial, Helvetica, sans-serif;}a:link {	color: #00406A;text-decoration: none;}.style5 {	font-size: 9px}.style7 {font-size: 9px; color: #FFFFFF; }.style8 {color: #CCCCCC}a:visited {color: #003F69;text-decoration: none;}.style9 {font-size: 18px;color: #61839E;}a:active {text-decoration: none;}.style14 {color: #000000}.style15 {font-family: Arial, Helvetica, sans-serif}.style16 {color: #000000; font-family: Arial, Helvetica, sans-serif; }' +
'</style></head>' +
'<body>' +
'<center>' +
'<table width="740" height="206" border="0" cellpadding="0" cellspacing="0">' +
'<tr>' +
'<td><img src="ckeditor/plugins/templates/templates/images/Campground-Header.jpg" alt="Welcome!" width="740" height="206" border="0" align="top" />' +
'</td>' +
'</tr>' +
'</table>' +
'<table width="740" border="0" cellpadding="12" cellspacing="1" bgcolor="#FFFFFF">' +
'<tr>' +
'<td width="487" height="149"><p align="left" class="style1 style14"><br />' +
'<br />' +
'<span class="style15">Welcome to the Campground Wi-Fi Network!</span></p>' +
'<p align="left" class="style16">This network access is provided to you on behalf of the management. We  hope you enjoy your experience. While you surf, please help us keep the network experience great for everyone. Save large uploads and downloads for a private connection.</p>' +
'<p align="left" class="style16">Thanks again,</p>' +
'<p align="left" class="style14"><span class="style15">Campground Management<br />' +
'</span><br />' +
'<br />' +
'</p>' +
'<p align="left" class="style8">&nbsp;</p></td>' +
'<td width="9"><p align="left" class="style3"><img src="ckeditor/plugins/templates/templates/images/Camp-Spacer.gif" width="5" height="100" /></p>' +
'</td>' +
'<td width="168"><p align="left" class="style9"><span class="style16">Begin browsing</span></p>' +
'<p align="left" class="style3"><a href="$authtarget"><img src="ckeditor/plugins/templates/templates/images/Button-Enter3.gif" alt="Begin browsing" width="70" height="20" border="0" /></a></p>' +
'<p align="left" class="style4">Bike trails open year round!</p></td>' +
'</tr>' +
'</table>' +
'<table width="740" border="0" cellpadding="0" cellspacing="0" bgcolor="#2A7729">' +
'<tr>' +
'<td><div align="center"><span class="style7">&copy; AWD</span></div></td>' +
'</tr>' +
'</table>' +
'<p class="style5">' +
'<p class="style5">' +
'<p class="style5"><br />' +
'</p>' +
'</center>' +
'</body>' +
'</html>'
            },	
   			{
				title: 'Hotels',
				image: 'images/Hot2_template.jpg',
				html:
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
'<html xmlns="http://www.w3.org/1999/xhtml">' +
'<head>' +
'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' +
'<meta name="keywords" content="AWD, hotel, resort">' +
'<title>CloudController Splash Page</title>' +
'<style type="text/css">' +
'body,td,th {font-family: Tahoma;font-size: 12px;color: #666666;}body {margin-left: 1px;margin-top: 1px;margin-right: 1px;margin-bottom: 1px;background-image: url(ckeditor/plugins/templates/templates/images/BG.gif);background-repeat: repeat-x;background-color: #00355B;}a:hover {color: #005080;text-decoration: none;}.style1 {font-size: 16px;font-weight: bold;color: #CCCCCC;}.style3 {	font-size: 18px;color: #662626;}.style4 {	font-size: 12px;color: #1E4562;font-family: Arial, Helvetica, sans-serif;}a:link {	color: #00406A;	text-decoration: none;}.style5 {font-size: 9px}.style7 {font-size: 9px; color: #FFFFFF; }.style8 {color: #CCCCCC}a:visited {color: #003F69;	text-decoration: none;}.style9 {font-size: 18px;color: #61839E;}a:active {	text-decoration: none;}.style11 {font-size: 16px;font-weight: bold;	color: #1E4562;	font-family: Arial, Helvetica, sans-serif;}.style12 {color: #1E4562; font-family: Arial, Helvetica, sans-serif; }.style14 {font-size: 18px; color: #662626; font-family: Arial, Helvetica, sans-serif; }' +
'</style></head>' +
'<body>' +
'<center>' +
'<table width="740" border="0" cellspacing="0" cellpadding="0">' +
'<tr>' +
'<td width="160"><img src="ckeditor/plugins/templates/templates/images/Header-Hotel.jpg" width="580" height="160" /></td>' +
'<td><img src="ckeditor/plugins/templates/templates/images/Header-Hotel1.jpg" width="160" height="160" /></td>' +
'</tr>' +
'</table>' +
'<img src="ckeditor/plugins/templates/templates/images/Header2-Hotel.gif" width="740" height="37" />' +
'<table width="740" border="0" cellpadding="8" cellspacing="1" bgcolor="#ffffff">' +
'<tr>' +
'<td width="493" height="149"><p align="left" class="style1">&nbsp;</p>' +
'<p align="left" class="style11">Welcome to Hotels Wi-Fi Network!</p>' +
'<p align="left" class="style12">Thank you for visiting. We truly hope you enjoy your experience while you are here. While you surf or work, please help us keep the network experience great for everyone. Save large transfers until you are at a private location.</p>' +
'<p align="left" class="style12">Thanks again,</p>' +
'<p align="left" class="style12">Hotel Management</p>' +
'<p align="left" class="style8">&nbsp;</p></td>' +
'<td width="5"><p align="left" class="style3"><img src="ckeditor/plugins/templates/templates/images/Spacer.gif" width="5" height="100" /></p>' +
'</td>' +
'<td width="190"><p align="left" class="style9"><span class="style12">Begin browsing</span></p>' +
'<p align="left" class="style14"><a href="$authtarget"><img src="ckeditor/plugins/templates/templates/images/Button.gif" alt="Begin browsing" width="70" height="20" border="0" /></a></p>' +
'<p align="left" class="style4">Room Service: 7 a.m. - 7 p.m.</p></td>' +
'</tr>' +
'</table>' +
'<table width="740" border="0" cellpadding="0" cellspacing="0" bgcolor="#929B42">' +
'<tr>' +
'<td><div align="center"><span class="style7">&copy; AWD</span></div></td>' +
'</tr>' +
'</table>' +
'<p class="style5">' +
'<p class="style5">' +
'<p class="style5"><br />' +
'</center>' +
'</body>' +
'</html>'
            },	
   			{
				title: 'Municipality',
				image: 'images/muni-template.jpg',
				html:
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
'<html xmlns="http://www.w3.org/1999/xhtml">' +
'<head>' +
'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' +
'<title>CloudController Splash Page</title>' +
'<style type="text/css">' +
'body,td,th {font-family: Tahoma;font-size: 12px;color: #CCCCCC;}body {background-color: #FFFFFF;margin-left: 1px;margin-top: 5px;margin-right: 1px;margin-bottom: 1px;	background-image: url(BG.jpg);background-repeat: repeat;}a:hover {color: #005080;text-decoration: none;}.style1 {	font-size: 16px;font-weight: bold;	color: #CCCCCC;font-family: Arial, Helvetica, sans-serif;}.style3 {font-size: 18px;color: #662626;}.style4 {font-size: 11px;color: #000000;font-family: Arial, Helvetica, sans-serif;}a:link {color: #00406A;	text-decoration: none;}.style5 {font-size: 9px;	color: #000000;}a:visited {	color: #003F69;text-decoration: none;}.style9 {font-size: 18px;color: #61839E;}a:active {	text-decoration: none;}.style22 {color: #000000; }.style23 {font-size: 18px;color: #000000;font-family: Arial, Helvetica, sans-serif;}.style24 {font-size: 9px}.style25 {color: #000000; font-family: Arial, Helvetica, sans-serif; }' +
'</style></head>' +
'<body>' +
'<center>' +
'<table width="900" height="200" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">' +
'<tr>' +
'<td valign="top" background=""><img src="ckeditor/plugins/templates/templates/images/Municipal-Header.jpg" alt="Welcome!" width="900" height="200" /><br />' +
'</td>' +
'</tr>' +
'</table>' +
'<table width="900" border="0" cellpadding="8" cellspacing="1" bordercolor="#000000" bgcolor="#FFFFFF">' +
'<tr>' +
'<td width="493" height="235"><p align="left" class="style1 style22">Welcome!</p>' +
'<p align="left" class="style25">This network access is provided to you on behalf of the municipality. We  hope you enjoy your experience. While you surf, please help us keep the network experience great for everyone. Save large uploads and downloads for a private connection.</p>' +
'<p align="left" class="style25">Thanks!</p>' +
'<p align="left" class="style25">Your Town Representative</p>' +      
'</td>' +
'<td width="5"><p align="left" class="style3"><img src="ckeditor/plugins/templates/templates/images/Municipal-spacer.gif" width="5" height="100" /></p>' +
'</td>' +
'<td width="190"><p align="left" class="style9"><span class="style25">Begin browsing</span></p>' +
'<p align="left" class="style23"><a href="$authtarget"><img src="ckeditor/plugins/templates/templates/images/Button-Enter-Muni.gif" alt="Begin browsing" width="70" height="20" border="0" /></a></p>' +
'<p align="left" class="style4">Town Hall Meeting: 7-10-11, 7 p.m.</p></td>' +
'</tr>' +
'</table>' +
'<img src="ckeditor/plugins/templates/templates/images/Municipal-Footer.jpg" width="900" height="159" align="top" />' +
'<p class="style5"><span class="style24">&copy; AWD</span><br />' +
'</p>' +
'</center>' +
'</body>' +
'</html>'
           },
                     {
				title: 'Parks',
				image: 'images/Cam2_template.jpg',
				html:
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
'<html xmlns="http://www.w3.org/1999/xhtml">' +
'<head>' +
'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />' +
'<title>CloudController Splash Page</title>' +
'<style type="text/css">' +
'body {background-color: #FFFFFF;	margin:0px;	padding:0px;	text-align:center;	font-family: Georgia, "Times New Roman", Times, serif;	font-size:13px;	color:#252;	line-height:20px;	background-image: url();}#wrapper {text-align:left;margin:0 auto;width:850px;	margin-top:18px;	border:2px solid #015A9A;	background:white;}a:hover {	color: #005080;	text-decoration: none;}a:link {	color: #00406A;	text-decoration: none;}.style1 {	font-size: 18px;	font-weight: bold;	font-family: Arial, Helvetica, sans-serif;}.style2 {	color: #999999;	font-size: 10px;	font-family: Arial, Helvetica, sans-serif;}body,td,th {	color: #0065AD;}.style3 {font-size: 10px}.style4 {font-family: Arial, Helvetica, sans-serif}.style5 {	font-weight: bold;	font-size: 14px;}' +
'</style>' +
'</head>' +
'<body>' +
'<div id="wrapper"><img src="ckeditor/plugins/templates/templates/images/header.jpg" />' +
'<table width="850" background="#0065AD" border="0" cellpadding="0" cellspacing="0" bgcolor="#015A9A">' +
'<tr>' +
'<center>' +
'<td width="197"><img src="ckeditor/plugins/templates/templates/images/man_laptop.jpg" width="197" height="239" /></td>' +
'<td width="447"><img src="ckeditor/plugins/templates/templates/images/woods.jpg" width="447" height="239" /></td>' +
'<td width="198"><img src="ckeditor/plugins/templates/templates/images/dog.jpg" width="198" height="239" /></td>' +
'</center>' +
'</tr>' +
'</table>' +
'<img src="ckeditor/plugins/templates/templates/images/divider.gif" />' +
'<div style="background-image:url(ckeditor/plugins/templates/templates/images/bg.jpg); background-color:#FFFFFF; padding-left:190px; padding-right:40px;">' +
'<br />' +
'<span class="style1">Welcome to Your County  Park System!</span><br />' +
'<br />' +
'<div style="float:right; width:200px; margin-left:15px; padding-left:15px; margin-top:15px; border-left:2px dotted #99CC00">' +
'<p align="left" class="style9 style4 style5"><span class="style24">Begin browsing</span>:</p>' +
'<p align="left" class="style3"><span class="style18"><a href="$authtarget"><img src="ckeditor/plugins/templates/templates/images/enter_green.jpg" alt="Begin browsing" width="120" height="35" border="0" /></a></span></p>' +
'<p align="left" class="style4">Community Cookout - July 1 </p>' +
'</div>' +
'<p align="left" class="style4">This network access is provided to you on behalf of   the Parks Department. We hope you enjoy your experience. While you surf, please help   us keep the network experience great for everyone. Save large uploads and   downloads for a private connection.</p>' +
'<span class="style4">Thanks!<br />' +
'Parks Department</span><br />' +
'<br />' +
'</div>' +
'<img src="ckeditor/plugins/templates/templates/images/footer.jpg" width="850" height="20" /></div>' +
'<span class="style2">&copy; AWD</span><br />' +
'<br />' +
'</body>' +
'</html>'
           },
   			{
				title: 'Default',
				image: 'images/Bas1_template.jpg',
				html:
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' +
'<html><head>' +
'<title>CloudController Splash Page</title>' +
'<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">' +
'<meta content="MSHTML 6.00.6000.16735" name="GENERATOR">' +
'<style type="text/css">' +
'BODY {PADDING-RIGHT: 20px; PADDING-LEFT: 20px; BACKGROUND: #BDBDBD; PADDING-BOTTOM: 20px; FONT: 85% "Trebuchet MS",Arial,sans-serif; COLOR: #222; PADDING-TOP: 20px; TEXT-ALIGN: center}H1 {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}H2 {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}P {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}P {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 15px; PADDING-TOP: 0px}H1 {FONT-SIZE: 250%; COLOR: #fff; LETTER-SPACING: 1px}H2 {FONT-SIZE: 200%; COLOR: #002455; LINE-HEIGHT: 1}DIV#container {PADDING-RIGHT: 5px; PADDING-LEFT: 5px; BACKGROUND: #fff; PADDING-BOTTOM: 5px; MARGIN: 0px auto; WIDTH: 550px! important; PADDING-TOP: 5px; TEXT-ALIGN: left}DIV#header {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; BACKGROUND: #04B404; PADDING-BOTTOM: 10px; PADDING-TOP: 10px; TEXT-ALIGN: center}DIV#content {PADDING-RIGHT: 0px; PADDING-LEFT: 0px; BACKGROUND: #ffffff; FLOAT: left; PADDING-BOTTOM: 10px; MARGIN: 5px 0px; WIDTH: 400px; PADDING-TOP: 10px}DIV#nav {PADDING-RIGHT: 0px; PADDING-LEFT: 0px; BACKGROUND: #ffd154; FLOAT: right; PADDING-BOTTOM: 10px; MARGIN: 5px 0px; WIDTH: 145px; PADDING-TOP: 10px}DIV#nav H2 {FONT-SIZE: 120%; COLOR: #9e4a24}DIV#footer {CLEAR: both; PADDING-RIGHT: 0px; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 5px; WIDTH: 550px; ADDING-TOP: 5px; TEXT-ALIGN: center}' +
'</style>' +
'</head>' +
'<body style="color: rgb(0, 0, 0);" link="#000000">' +
'<div id="container"><div id="header">' +
'<h1>$gatewayname <br></h1></div>' +
'<div id="content">' +
'<p><font size="3">Welcome to our open WiFi network!</span></p>' +
'<h2><span style="font-weight: bold;">Free for $gatewayname Residents.</span></h2>' +
'<br><p><span style="font-size: 12pt;">Add your own message here.&nbsp; When you are done editing, click the save icon in the upper left corner of the editor.&nbsp; Make sure you do NOT delete the "Enter" link below or your page will revert to the standard template!&nbsp; Other than that, all aspects of this page can be edited.&nbsp; "$gatewayname" will be replaced by the name of your network.&nbsp;<br>' +
'</span></p></div><div id="nav">' +
'<h2>Please...</h2><p><br>' +
'We ask just a few things:&nbsp; Be respectful of others and please refrain from large uploads or downloads to keep the network fast for everyone!<br>' +
'</p></div><div id="footer" style="font-weight: bold;">' +
'<table border="0" cellpadding="0" cellspacing="0" width="100%">' +
'<tbody><tr><td bgcolor="ffffff"></td>' +
'<td bgcolor="c4e786" width="147"><a href="$authtarget"><span style="font-size: 18pt;">' +
'Enter</span></a>&nbsp;</td></tr></tbody></table>' +
'</div></div></body></html>'
            }
		]
});