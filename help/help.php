PHP: 
<?
header( "HTTP/1.1 301 Moved Permanently" );
header( "Status: 301 Moved Permanently" );
header( "Location: http://support.awdmesh.com/" );
exit(0); // This is Optional but suggested, to avoid any accidental output
?> 
