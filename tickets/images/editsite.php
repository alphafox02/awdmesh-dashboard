<?php

include "access.php";
include "db_conf.php";

$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

if (isset($_POST[nref])) {
    $siteid=$_POST[nref];
    
    $siteredirect= "siteredirect".$siteid;
    $siteredirect=$_POST["$siteredirect"];
    
    if (!filter_var($siteredirect, FILTER_VALIDATE_URL,FILTER_FLAG_HOST_REQUIRED)) {
    
    echo "Please enter a valid URL";
    
    }
    
    
    
   
    
    
    $sitepassword= "sitepassword".$siteid;
    $sitepassword=$_POST["$sitepassword"];
    
    $kbsup= "kbsup".$siteid;
    $kbsup= $_POST["$kbsup"];
    
    $kbsdown= "kbsdown".$siteid;
    $kbsdown= $_POST["$kbsdown"];
    
    $sitelist=explode(',', $_SESSION['seclevel']); 
    $sqlsitelist=implode(",",$sitelist);

        if (in_array($siteid, $sitelist )) {
            
            $update_sql = "update sites set siteredirect='$siteredirect',sitepassword='$sitepassword',kbsup='$kbsup',kbsdown='$kbsdown' WHERE nref=$siteid";
            //$getUser = mysql_query($getUser_sql);
            //$getUser_result = mysql_fetch_assoc($getUser);
            //$getUser_RecordCount = mysql_num_rows($getUser);
              echo $update_sql;     
                }  else {
            echo "POO:".$siteid;
            
        }
}
?>
