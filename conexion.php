<?php 
$serverName = "DESKTOP-FAJKSMF"; // Cambia esto a la dirección de tu servidor SQL Server
$connectionInfo = array("Database" => "POSMultiCompany", "UID" => "myuser", "PWD" => "Lob0@2000");
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>