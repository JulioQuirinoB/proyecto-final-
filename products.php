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

// Obtener productos de la base de datos
$sql = "SELECT ProductID, ProductName, Description, Price, Stock FROM Products WHERE product_status = 1";
$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}

$products = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $products[] = $row;
}

sqlsrv_free_stmt($result);
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Informáticos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
            padding: 10px 0;
            color: #ffffff;
        }
        .navbar-left {
            float: left;
        }
        .navbar-right {
            float: right;
        }
        .navbar a {
            color: #000000;
            text-decoration: none;
            padding: 10px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .product {
            padding: 15px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .product h3 {
            margin-bottom: 10px;
        }
        .product p {
            margin-bottom: 5px;
        }
        .product form {
            margin-top: 10px;
        }
        .product button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 8px 12px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .product button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-left">
                <a href="customer_dashboard.php">Volver al Panel</a>
            </div>
            <div class="navbar-right">
                <a href="cart.php" class="btn btn-primary">Ver Carrito</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Productos Informáticos</h2>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h3><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                    <p><?php echo htmlspecialchars($product['Description']); ?></p>
                    <p>Precio: $<?php echo htmlspecialchars($product['Price']); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($product['Stock']); ?></p>
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['ProductID']); ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['ProductName']); ?>">
                        <input type="hidden" name="description" value="<?php echo htmlspecialchars($product['Description']); ?>">
                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['Price']); ?>">
                        <button type="submit" class="btn btn-success">Agregar al Carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, si necesitas funcionalidades como el despliegue del menú hamburguesa) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
