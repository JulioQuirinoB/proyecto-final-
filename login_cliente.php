<?php
include 'conexion.php';

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT CustomerID, Password, CustomerName FROM Customers WHERE Email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $customer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($customer && password_verify($password, $customer["Password"])) {
        session_start();
        $_SESSION["customerid"] = $customer["CustomerID"];
        $_SESSION["customername"] = $customer["CustomerName"];
        $_SESSION["email"] = $email;
        
        header ('Location: customer_dashboard.php');
    } else {
        header ('Location: login_cliente.html');
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
?>
