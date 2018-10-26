<?php

session_start();

if ($_SESSION['user_type']!='admin') 
 header("Location: ../entry/login.php");

// Setup Database Connection
require_once '../lib/connectDB.php';
setTable('network');

// Cambiar para cada servidor/dashboard
$dashboard = "";

$netid = $_SESSION["netid"];
$query = "SELECT * FROM ".$dbTable." WHERE id='".$netid."'";

$result = mysql_query($query, $conn);
$resArray = mysql_fetch_array($result, MYSQL_ASSOC);

//$htmlsource = $_POST['FCKEditor1'];
$htmlsource = $_POST['CKEditor1'];
$displayName = $resArray['net_name'];

$a=chr(92).'"';
$b='"';
$htmlsource = str_replace($a,$b,$htmlsource);

$userpath = "../users_splash/".$displayName;
$splashfile = $userpath . "/splash.txt";
$editorfile = $userpath . "/editor.html";

// Check to see if the user path exists, if not create it, it should exist as it should have been created by index.php

if (!file_exists($userpath))
	{		
		$rs = mkdir($userpath,0777);		
	}

// Store copy just for the editor to use.

$fh = fopen($editorfile,'wb') or die("Can't open file ");
fwrite($fh,$htmlsource);
fclose($fh);

// Now we need to convert image URLS by removing the user path so that the node will process it correctly.
//$stringtofind = "/users_splash/" . $resArray['display_name'];
$stringtofind = "ckeditor/plugins/templates/templates/";
$tmp_htmlsource = str_replace($stringtofind,"",$htmlsource);
$stringtofind =$dashboard ."/users_splash/".$displayName."/";
$tmp_htmlsource = str_replace($stringtofind,"",$tmp_htmlsource);

// Store copy for the node to download

$fh = fopen($splashfile,'w') or die("Can't open file");
fwrite($fh,$tmp_htmlsource);
fclose($fh);

// Preprocess html for inclusion into the node config file
// The splash file is easy, what we have to do is search the html for any and all image tags

// Regex match all img src tags.
//preg_match_all('/<img.*src=\"([^\"]+)\"[^>]+>/i',$htmlsource,$rs);   CORREGIDO por Valentin con la linea siguiente
preg_match_all('/src=\"([^\"]+)\"[^>]+>/i',$htmlsource,$rs);
// Set protocol to use
if ($_SERVER['HTTPS'] != '')
	$protocol = "https";
else
	$protocol = "http";

//remove next line when you obtain valid SSL cert.
$protocol = "http";

// Iterate through all the entries from above
foreach ($rs[1] as $srctag)
{

    // If the user has supplied an external image, make sure we dont' prepend the URL for this site.
    if (substr_count($srctag,'http') == 1)
	   $imagelink = "image " . str_replace(" ","%20",$srctag) . "\n";
    else
        // Añadido el texto meshcontroller/splash que antes no estaba
	   $imagelink = "image " . $protocol . "://" . $_SERVER['SERVER_NAME'] .$dashboard ."/splash/" . str_replace(" ","%20",$srctag) . "\n";

    $imagelink = str_replace("splash//". $dashboard ,"",$imagelink);  //corrige ruta en imagenes subidas, propias
    $nodeincstring .= $imagelink;

}

// Construct the include file and save to the users folder. He agregado el texto meshcontroller que antes no estaba
$last_update_time = date("Y-m-d H:i:s");
$net_ID = $resArray["id"];
mysql_query("UPDATE network SET last_dash_update='$last_update_time' WHERE id='$net_ID'", $conn);
$nodeincstring = "#@#config splash-HTML\r\n" . "page " . $protocol . "://" . $_SERVER['SERVER_NAME'] .$dashboard ."/users_splash/" . str_replace(" ","%20",$displayName) ."/splash.txt\r\n" . $nodeincstring;
$fh = fopen($userpath . "/nodeinc.txt",'wb') or die("Can't open nodeinc.txt file");
//$fh = fopen($userpath . "/nodeinc.txt",'w') or die("Can't open nodeinc.txt file");
fwrite($fh,$nodeincstring);
fclose($fh);

// Redirect back to the HTML editor
echo '<HTML><HEAD><META HTTP-EQUIV="refresh" CONTENT="0; URL=index.php?saved=1"></HEAD></HTML>';

?>
