<?php
session_start();

if (!isset($_SESSION["customerid"])) {
    header("Location: login_usuario.html");
    exit();
}

include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['checkout'])) {
        // Recoger datos del formulario
        $paymentMethod = $_POST['payment_method'];
        $companyID = $_POST['company_id'];
        $totalPrice = $_POST['total_price']; // Asegúrate de incluir este campo hidden en tu formulario de cart.php

        // Insertar la venta en la tabla Sales
        $customerID = $_SESSION["customerid"];
        $saleDate = date('Y-m-d H:i:s'); // Fecha y hora actual del servidor

        // Ajusta la consulta SQL según la estructura de tu procedimiento almacenado o función
        $sql = "EXECUTE InsertSaleAndGetID ?,?,?,?";
        
        $params = array($companyID, $customerID, $saleDate, $totalPrice);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Obtener el SaleID
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $saleID = $row['SaleID']; // Ajusta 'SaleID' según el nombre real de la columna en tu resultado

        // Si la inserción fue exitosa, limpiar el carrito y redireccionar
        unset($_SESSION['cart']);
        header("Location: products.php?saleID=" . $saleID); // Redireccionar a página de éxito con el SaleID
        exit();
    }
}
?>
