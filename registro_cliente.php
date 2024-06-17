<?php

include 'conexion.php';

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST["customername"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encriptar contraseña

    $sql = "INSERT INTO Customers (CustomerName, Address, Phone, Email, Password) VALUES (?, ?, ?, ?, ?)";
    $params = array($customerName, $address, $phone, $email, $password); // Ajusta el orden de los parámetros

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Registro exitoso. <a href='login_cliente.html'>Ingresa aquí</a>";
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
?>
