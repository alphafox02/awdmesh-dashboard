<?php
/* Name: view.php
 * Purpose: master view for network settings.

 */
  function signal_strength_color ($strength){
    if ($strength == "z") {return 'images/greengw.gif';} //solo gateway conectado
    if ($strength < 1) {return 'images/grey.gif';}
    if ($strength < 25) {return 'images/red.gif';}
    if ($strength < 100) {return 'images/yellow.gif';}
    return 'images/green.gif';
  }
  function reboot_reason($razon) {
    $razon=substr($razon,-2);
	switch($razon){
        case '00': return "Last cause for reboot: external reboot.";
        case '10': return "Last cause for reboot: channel drift.";
        case '12': return "Last cause for reboot: no internet.";
        case '13': return "Last cause for reboot: channel change.";
        case '14': return "Last cause for reboot: routing protocol switch.";
        case '15': return "Last cause for reboot: rescue failed.";
        case '22': return "Last cause for reboot: low memory.";
        case '23': return "Last cause for reboot: no wifi.";
        case '25': return "Last cause for reboot: bad lease.";
        case '30': return "Last cause for reboot: batmand failure.";
        case '32': return "Last cause for reboot: dnsmasq failure.";
        case '34': return "Last cause for reboot: udhcpc failure.";
        case '35': return "Last cause for reboot: captive portal failure.";
        case '36': return "Last cause for reboot: olsrd failure.";
        case '52': return "Last cause for reboot: restore gateway role.";
        case '90': return "Last cause for reboot: firmware upgrade.";
        case '91': return "Last cause for reboot: reboot needed after setting change.";
        case '92': return "Last cause for reboot: update chilli.";
        case '93': return "Last cause for reboot: scheduled reboot.";
        case '99': return "Last cause for reboot: firmware upgrade failed.";
		default: return "Last cause for reboot: ".$razon;
	};
}
//Setup session
session_start();

$net_name = $_SESSION['net_name'];

//Set how long a node can be down before it's name turns red (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid']))  {
	//header("Location: ../entry/select.php");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../entry/login.php");
        exit();
}

//Includes
include "../lib/style.php";
?>







<head>
  <title>Network Status | <?php echo $net_name; ?></title>

  <!--<META HTTP-EQUIV="Refresh" CONTENT="60">-->

  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script>
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<!-- Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column. -->
<script src='../lib/sorttable.js'></script>
<style type="text/css">
	table.padded td{
		padding:3px 5px;	
		font-size:14px;
	}
	tr.odd td{
		background-color:#eee;
	}
</style>
</head>
<body onload=Nifty("div.note");>
<table cellpadding="0" cellspacing="0" border=0 width=100%>
<tr><td style="padding:0px;" align=center>





	





<?php
include "../lib/menu.php";
require_once "../lib/connectDB.php";
setTable("node");
include '../lib/toolbox.php';

//Display the title of the page
$result = mysql_query("SELECT * FROM network WHERE id=".$_SESSION['netid'], $conn);
$resArray = mysql_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}

//$OK_DOWNTIME = $resArray['min_nodedown']*60;

if($ulang=='en') {
echo <<<TITLE
<table width=1040><tr><td align='right' height=0>
<!-- <font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><img src="anaptyxlogo.png" border=0 ALIGN=ABSMIDDLE>Network Status $display_name</font> -->
</td>

<td width=30> </td>

</tr></table><br>
<div class="note" id="tip">Nodes in <b>bold</b> are gateways. <font style="color:ff3300;">Red text</font> indicates issues. Click headers to sort. If you are connected to the panel within the mesh, click on the node's IP to access your web interface.<br><br>For information on last node reboot reasons, hover over the "Uptime" column for each node.
<a href="javascript:close()">hide tip</a></div>
<align="center"><div align="center"></div><br><a href="view.php">Fewer Node Details</a><br><br> 


TITLE;
//include("graficos.php");




//Get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>No nodes associated to this network</div>");

} else {
echo <<<TITLE
<table width=1040><tr><td align='right' height=80>
<font style="font-family:'Trebuchet MS',Arial,sans-serif; font-size:28px; color:#0477ad;"><b>&#32593;&#32476;&#36816;&#34892;&#29366;&#24577;</b> &nbsp; (&#32593;&#32476;&#21517;&#65306;$display_name)</font>
</td>
<td width=30> </td>
<td align='left' width=300><a href="view_adv.php">&#26356;&#22810;&#21442;&#25968;</a></td>
</tr></table>
<div class="note" id="tip">
<span style="color:ff3300;"><b>&#32418;&#33394;</b></span>&#30340;&#33410;&#28857;&#38656;&#35201;&#26816;&#26597;
<b>&#31895;&#20307;&#23383;</b>&#34920;&#31034;&#27492;&#33410;&#28857;&#26159;&#32593;&#20851; &nbsp; &nbsp;
<a href="javascript:close()">&#38544;&#21435;</a></div>
TITLE;

//Get all fields from "node" table that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysql_query($query, $conn);
if(mysql_num_rows($result)==0) die("<div class=error>&#30446;&#21069;&#32593;&#32476;&#19978;&#23578;&#26410;&#21152;&#20837;&#33410;&#28857;&#12290;&#33509;&#38656;&#22686;&#21152;&#33410;&#28857;&#65292;&#35831;<a href=\"../nodes/addnode.php\">&#28857;&#20987;&#27492;&#22788;</a>.</div>");

}

$source = "<font size='1'>";
//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
//-----
// Added "version" as value to the array, which is the index to node properties
// 
//-----
$node_fields = array("AP Status" => "status", "Node Name" => "name","IP<br>MAC address" => "ip", "Current<br>clients"=>"users", "Down kb" => "kbdown", "Uptime" => "uptime", "Firmware ver."=>"robin", "memfree"=>"memfree", "Gateway IP<br>Public IP" => "ip_public", "Hops" => "hops",
  "Mesh<br>latency"=>"RTT", "&nbsp; &nbsp; &nbsp; &nbsp; Mesh speed &nbsp; &nbsp; &nbsp; &nbsp;"=>"NTR", "Last Checkin" => "time");

$node_adv_fields = array("Status" => "status", "Node Name"=>"name", "Hops"=>"hops", "Network Rate"=>"NTR",
  "Ping RTT"=>"RTT", "RSSI"=>"rssi", "Number of Clients"=>"users");

$node_alignment = array("status" => "center", "name"=>"left", "users"=>"center", 
			"kbdown"=>"left", "gateway" =>"left", "hops" =>"center", 
			"RTT" => "center", "time"=> "center",
			"ip" => "left", "uptime" => "center", "robin" => "left",
			"memfree" => "center", "ip_public"=> "left", "NTR" => "center");

echo "<table class='sortable padded' border='1' bordercolor='999999' cellspacing='0'>";

//Output the top row of the table (display names)
// echo "<td>" . $key . "</td>";
echo "<tr class=\"fields\">";
foreach($node_fields as $key => $value) {
    echo "<td align='".$node_alignment[$value]."'>";
            if ($value=="name") {
              // node name
              if($ulang=='en')
              echo $source."Name<br>Notes";
              else
              echo "&#33410;&#28857;&#21517;/&#25551;&#36848;";
            } elseif ($value=="uptime") {
              // how long since the node first checked in
              if($ulang=='en')
              echo $source."Uptime";
              else
              echo "&#20837;&#32593;&#26102;&#38388;";
            } elseif ($value=="hops") {
              // number of hops to g/w
              if($ulang=='en')
              echo $source."Hops to<br>gateway";
              else
              echo "&#36339;&#25509;";
            } elseif ($value=="kbdown") {
              // download usage
              if($ulang=='en')
              echo $source."Usage (MB)<br>down / up";
              else
              echo "&#19979;&#36733;&#37327;";
            } elseif ($value=="time") {
              // Last checkin
              if($ulang=='en')
                echo $source."Connectivity";
              else
                echo "&#26368;&#36817;&#26356;&#26032;";
            } else {
    	      echo $source.$key;
            }
    echo "</td>";
}

echo "</tr>";

//Output the rest of the table
$nrw = 0;
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$nrw +=1;
	if($nrw%2 == 0){
		$classc = "odd";	
	}
	else{
		$classc = "even";
	}
    if ($row["approval_status"] == "A") {    //show only activated nodes
        if($currentTime - strtotime($row['time']) >= $OK_DOWNTIME) {
    	    echo "<tr class=\"down ".$classc."\">";
            switch($row["gateway_bit"]){
			case 1:
				$logostatus = '<img src="images/gatewayko.png" border=0 ALIGN=ABSMIDDLE>';
				break;
			case 0:
				$logostatus = '<img src="images/repeaterko.png" border=0 ALIGN=ABSMIDDLE>';
				break;
		}
            $calidadstatus = '<dl><ddoff>';
        }
        else {
    	    echo "<tr class=\"".$classc."\">";
         switch($row["gateway_bit"]){
			case 1:
				$logostatus = '<img src="images/gatewayok.png" border=0 ALIGN=ABSMIDDLE>';
				break;
			case 0:
				$logostatus = '<img src="images/repeaterok.png" border=0 ALIGN=ABSMIDDLE>';
				break;
		}
         $calidadstatus = '<dl><dd>';
        }
        foreach($node_fields as $key => $value) {
            echo "<td align='".$node_alignment[$value]."'>";
            if ($value=="status")
			echo $logostatus;
            if ($value=="name") {
              if ($row["gateway_bit"]==1 && $row["gw-qual"]>254)
                echo $source.'<p style="margin-top: 12; margin-bottom: 12"><b><a href="../nodes/node_info.php?mac='. $row["mac"] .'">'. str_replace("*"," ",$row[$value]) .'</a></b><br>'. $row["description"]. '';
              else
                echo $source.'<p style="margin-top: 12; margin-bottom: 12"><a href="../nodes/node_info.php?mac='. $row["mac"] .'">'. str_replace("*", " ",$row[$value]) .'</a><br>'. $row["description"]. '';
            }
            elseif ($value=="ip") {
            	echo $source."<a href='http://".$row[$value].":8080' target=_blank>".$row[$value]."</a><br>".$row['mac']."&nbsp;";
            }
            elseif ($value=="kbdown") {
            	echo $source.round($row['kbdown']/1000,1)."<br>";
            	echo $source.round($row['kbup']/1000,1);
            }
                elseif ($value=="ip_public") {
                echo $source."&nbsp;".$row['gateway']."<br>";
                echo $source."".$row[$value]."&nbsp;";
            }
            elseif ($value=="hops" && $row["gateway_bit"]==1 && $row["gw-qual"]>254) {
                echo $source."0";
            }
            elseif ($value=="gw-qual") {    //Convert rank from x {x | 0 < x < 255} to %
               // echo $source.floor(100 * ($row[$value] / 255)) . "%";
               echo $source.'<p style="margin-top: 0; margin-bottom: 0" class="center">' . floor (100 * ($row[$value] / 255)) . '%' . '</p>';
               echo $calidadstatus.'<div style="left:' . floor (100 * ($row[$value] / 255)-85) . 'px' . ';"><strong></strong></div></dd></dl>';

            }
            elseif ($value=="time"){
                        $width = 287;
                        $height = 13;
                    	echo $source.'<p style="margin-top: 0; margin-bottom: 0">'. humantime($row[$value]) . '</p>';
                       // echo '<p class="right">' . humantime ($row[$value]) . '</p>';
                       // $values = unserialize ($row['uptime_metric']);
                        $rssi = unserialize($row["rssi_hist"]);
                        $times = unserialize($row["usr_hist"]);
                        $string = '<table cellpadding=0 cellspacing=0 border=0>';
                        $string .= '' . '<tr><td colspan=3><table cellpadding=0 cellspacing=0 border=1 bordercolor=#000000><tr><td width=' . $width . ' height=' . $height . ' nowrap bgcolor=#cccccc>';
                        $index = intval(date ('H', time ()) * 12 + date ('i', time ()) / 5);

                        $index += 288 - $width;
                        $i = $width;

                        while (0 < $i)
                        {
                          if (287 < $index) {$index -= 288;}
                          //Check si rssi_hist es de mas de 24 horas (86400 secs.)
                          if($currentTime - strtotime($times[$index]) < 86400) {
                             if ($row["gateway_bit"]==1 && $row["gw-qual"]>254) {
                                $color = 'images/greengw.gif';
							 } else {
								$color = signal_strength_color ($rssi[$index]);
							 }
                            $string .= '' . '<image width=1 height=' . $height . ' src="' . $color . '">';
                          } else {
                            $string .= '' . '<image width=1 height=' . $height . ' src="images/grey.gif">';
                          }
                          ++$index;
                          --$i;
                        }


                    	$string .= '<table border="0" width="100%"><tr><td width="33%">'.$source.'-24h.</td><td><p align="center">'.$source.'-12h.</td><td width="33%"><p align="right">'.$source.'0h.</td></tr></table>';
                        $string .= '</td></tr></table></td></tr></table>';
                        echo $string;

            }
            elseif ($value=="uptime")  {
                echo "<div title='".reboot_reason($row[$value])."'".$source."&nbsp;".$row["uptime"]."&nbsp;</div>";
            }
            elseif ($value=="version")  {
                echo $source.$row["robin"]."<br>".$row["batman"];
            }
			elseif ($value == "NTR")  {
                              $string = substr ($row[$value], 0, 0 - 5);
                              if ($row["NTR"] == '999-KB/s')
                              {
                                echo $source.'N/A';
                              }
                              else
                              {
                                $string = substr ($row[$value], 0, 0 - 5);
                                $string2 = substr ($row[$value], 0 - 4);
                                if ($string2 == 'KB/s') {
                                    $Mbps = round ($string * 8 / 1000, 1);
                                } else {
                                    $Mbps = round ($string * 1000 * 8 / 1000, 1);
                                }
                                if ($Mbps < 0.1) {
                                    $Mbps = "";
                                    $MbpsImage = ""; //$MbpsImage = "n/a &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                } else if ($Mbps < 7) {
                                    $MbpsImage = '<p style="margin-top: 0; margin-bottom: 0" align="left"><img src="images/D'.$Mbps.'.png"> ';
                                    $Mbps .= '  Mbps';
                                } else {
                                    $MbpsImage = '<p style="margin-top: 0; margin-bottom: 0" align="left"><img src="images/D7.0.png"> ';
                                    $Mbps .= '  Mbps';
                                }
                                $ntr = unserialize($row["ntr_hist"]);
                                $times = unserialize($row["usr_hist"]);
                                $total = 0;
                                $checkins = 0;
                                $i = 288;
                                while (0 < $i)
                                {
                                    
                                    //Check si rssi_hist es de mas de 24 horas (86400 secs.)
                                    if($currentTime - strtotime($times[$i]) < 86400) {
                                        $valorntr = substr ($ntr[$i], 0 - 4);
                                        if ($valorntr == 'KB/s') {
                                            $valorntr = round (substr ($ntr[$i], 0, 0 - 5) * 8 / 1000, 1);
                                        } else {
                                            $valorntr = round (substr ($ntr[$i], 0, 0 - 5) * 1000 * 8 / 1000, 1);
                                        }
                                        if ($valorntr > 0) {
                                            $total += $valorntr;
                                            ++$checkins;
                                        }
                                    }
                                    --$i;
                                }
                                if ($checkins > 0) {
                                    $media = round($total/$checkins,1);
                                    if ($media > 7) {
                                        $mediaimage = '<img src="images/D7.0.png"> ';
                                        $media = $media . " Mbps";
                                    } else {
                                        $mediaimage = '<img src="images/D'.$media.'.png"> ';
                                        $media = $media . " Mbps";
                                    }
                                } else {
                                    $media = "";
                                    $mediaimage = "";
                                }
                                echo $source.$MbpsImage . $Mbps . '<p style="margin-top: 0; margin-bottom: 0" align="left">'.$mediaimage.$media;
                              }



            }
            elseif ($value=="robin")  {
                echo $source.$row["robin"]."<br>".$row["batman"];
            }
            else {
                echo $source.$row[$value];
            }
            echo "</td>";
        }
        echo "</tr>";
    }
}
echo "</table><p>&nbsp;</p>";

//Finish our HTML needed for NiftyCorners
?>


<br>
</td></tr></table>
</body>
