<?php
include 'conexion.php';
session_start();
$sql = "SELECT nombre FROM Usuarios WHERE usuarioid = ?";
if (!isset($_SESSION['usuarioid']) || $_SESSION['rol_id'] != 1) {
  header('Location: login.php');
  exit();
}
$nombre = $_SESSION['usuarioid'];
$params = array($nombre);
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); 
}
if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $nombreUsuario = $row['nombre']; 
}
?>