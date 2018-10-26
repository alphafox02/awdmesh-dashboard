<?php 
/* Name: mailalerts.php

 */

//Set how long a node can be down before it's alerted (in seconds)
//$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Setup db connection
require_once 'connectDB.php';
include 'toolbox.php';

//Select all the networks from the database WITH email for alerts
$query = "SELECT * FROM network WHERE email2 <> ''";
$network_result = mysql_query($query, $conn);
if(mysql_num_rows($network_result)==0) die("No networks with alerts, mailing halted.");

//For every network in the dashboard
while($network = mysql_fetch_assoc($network_result)) {
    $body2 = "";
    
    $netname = $network['net_name'];
    $email = $network['email2'];
    //Get the nodes associated with this network
    $query = "SELECT * FROM node WHERE netid='".$network['id']."' ORDER BY time DESC";
    $node_result = mysql_query($query, $conn);

    if(mysql_num_rows($node_result)==0)
        continue;
	
    //For every node that is in the network
    while($node = mysql_fetch_assoc($node_result)){
        if($node['alerts'] == 1){
            //if the node is down, add a line to email
            $down = $currentTime - strtotime($node['time']);
	    $high_time = ($network['min_nodedown']+30)*60;
            if($down > ($network['min_nodedown']*60) && $down < $high_time){
                $body2 .= "<font style='color:#FF0000;'>".$node['name']." Last Check-in: ". humantime($node['time']).".</font><br>";
            } // else {$body .= "<font style='color:#007900;'>".$node['name']." funciona correctamente (".(int)($down /(60))." minutos).</font><br>";}
        }
    }


    if ($body2 != "") {
        //Generate email
        $body = '<br><br><a href="http://dashboard.awdmesh.com/"><img src="http://dashboard.awdmesh.com/status/anaptyxlogo.png"></a>';
        $body .= "<br><br><font style='color:#000000;'>The following equipment on the <b><i>".$netname."</i></b> network has become unreachable from the AWD CloudController.<br><br>";
        $body .= $body2;
        $body .= "<br><br><font style='color:#000000;'>This is an automated alert. You will only receive this once per node that is alerting. If you receive it more often, then the node came back up before going down again. <br><br>Please don't reply to this address. <br><br>You may view your network status at <br><br></font>http://dashboard.awdmesh.com/<br><br>";
        $recipients = $email;
        $subject = "Alert for ".$netname." - Nodes went down";
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        //$headers .= 'To:  <>' . "\r\n";
        //$headers .= 'Sender:  <>' . "\r\n";
        //$headers .= 'From: <alerts@awdmesh.com>' . "\r\n";
        $from = "alerts@awdmesh.com";
        $headers .= "From: $from";
        //$headers .= "X-Mailer: PHP\n"; // mailer  // Makes this look less like spam
        //$headers .= "Return-Path: <" . $sender . ">\n"; // Return path for errors
        mail($recipients, $subject, $body, $headers);
        //mail($recipients, $subject, $body, $headers, '-falerts@awdmesh.com');
    }
}
?>
