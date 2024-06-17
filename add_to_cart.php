<?php
session_start();
if (!isset($_SESSION["customerid"])) {
    header("Location: login_usuario.html");
    exit();
}

include 'conexion.php';

// Verifica si la conexión se realizó correctamente
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];
    $quantity = 1; // Aquí asumo que siempre se agrega una unidad del producto. Puedes cambiarlo según tu necesidad.

    // Obtener el stock actual del producto
    $sql = "SELECT ProductName, Description, Price, Stock FROM Products WHERE ProductID = ?";
    $params = array($productId);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
        $currentStock = $row['Stock'];

        if ($currentStock >= $quantity) {
            // Actualizar el stock en la base de datos
            $newStock = $currentStock - $quantity;
            $sql = "UPDATE Products SET Stock = ? WHERE ProductID = ?";
            $params = array($newStock, $productId);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            // Agregar el producto al carrito (puedes personalizar esto según tu lógica de carrito)
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = [
                    'product_id' => $productId,
                    'product_name' => $row['ProductName'],
                    'description' => $row['Description'],
                    'price' => $row['Price'],
                    'quantity' => $quantity
                ];
            }

            echo "Producto agregado al carrito.";
        } else {
            echo "No hay suficiente stock disponible.";
        }
    } else {
        echo "Producto no encontrado.";
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
header("Location: cart.php");
exit();
?>
