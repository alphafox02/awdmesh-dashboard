<?php 
/* Name: clean_logs.php
 * Purpose: cron for clean clients history.
 *
 */

//Número de segundos de antigüedad a borrar (200 dias = 17.280.000 segundos / 10 dias = 864000)
$OK_DOWNTIME = 3456000;

$fecha = time() - $OK_DOWNTIME;
$fecha = date('Y-m-d h:m:s', $fecha);

//Setup db connection
require_once 'connectDB.php';

//Selecciona todos los registros antiguos a borrar
$query = "SELECT * FROM client WHERE c_time < '". $fecha ."'";
$client_result = mysql_query($query, $conn);
if(mysql_num_rows($client_result)==0) die("Nada que borrar.");

//Borra cada registro
while($client = mysql_fetch_assoc($client_result)) {

    $query = "DELETE FROM client WHERE id = ". $client['id'];
    $delete_result = mysql_query($query, $conn);

}

?>
