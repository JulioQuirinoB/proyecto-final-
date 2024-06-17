<?php
session_start();
if (!isset($_SESSION["customerid"])) {
    header("Location: login_usuario.html");
    exit();
}
include 'conexion.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_quantity'])) {
        $productName = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        if ($quantity > 0 && isset($_SESSION['cart'][$productName])) {
            $_SESSION['cart'][$productName]['quantity'] = $quantity;
        }
    } elseif (isset($_POST['remove_from_cart'])) {
        $productName = $_POST['product_name'];
        if (isset($_SESSION['cart'][$productName])) {
            unset($_SESSION['cart'][$productName]);
        }
    }
    header("Location: cart.php");
    exit();
}

// Obtener la lista de compañías
$companies = [];
$sql = "SELECT CompanyID, CompanyName FROM Companies";
$result = sqlsrv_query($conn, $sql);

if ($result !== false) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $companies[] = $row;
    }
}

$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
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
        .cart-item {
            padding: 15px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-bottom: 10px;
        }
        .cart-item h3 {
            margin-bottom: 10px;
        }
        .cart-item p {
            margin-bottom: 5px;
        }
        .cart-item form {
            margin-top: 10px;
        }
        .cart-item button {
            margin-right: 5px;
        }
        .payment {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .payment h3 {
            margin-bottom: 10px;
        }
        .payment form {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Carrito de Compra</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="cart-items">
                    <?php foreach ($_SESSION['cart'] as $productName => $item): ?>
                        <div class="cart-item">
                            <h3><?php echo isset($item['product_name']) ? htmlspecialchars($item['product_name']) : 'N/A'; ?></h3>
                            <p><?php echo isset($item['description']) ? htmlspecialchars($item['description']) : 'N/A'; ?></p>
                            <p>Precio: $<?php echo isset($item['price']) ? htmlspecialchars($item['price']) : 'N/A'; ?></p>
                            <form method="post" action="cart.php">
                                <div class="mb-3">
                                    <label for="quantity_<?php echo htmlspecialchars($productName); ?>" class="form-label">Cantidad:</label>
                                    <input type="number" id="quantity_<?php echo htmlspecialchars($productName); ?>" name="quantity" class="form-control" value="<?php echo isset($item['quantity']) ? htmlspecialchars($item['quantity']) : 1; ?>" min="1">
                                </div>
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($productName); ?>">
                                <button type="submit" name="update_quantity" class="btn btn-primary">Actualizar</button>
                                <button type="submit" name="remove_from_cart" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="payment">
                    <h3>Método de Pago</h3>
                    <form method="post" action="checkout.php">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_efectivo" value="efectivo" checked>
                                <label class="form-check-label" for="payment_efectivo">
                                    Efectivo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_debito" value="debito">
                                <label class="form-check-label" for="payment_debito">
                                    Tarjeta de Débito
                                </label>
                            </div>
                        </div>
                        <p>Total: $<?php echo number_format($totalPrice, 2); ?></p>
                        <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($totalPrice); ?>">
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Selecciona Compañía:</label>
                            <select id="company_id" name="company_id" class="form-select" required>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?php echo htmlspecialchars($company['CompanyID']); ?>">
                                        <?php echo htmlspecialchars($company['CompanyName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="checkout" class="btn btn-success">Finalizar Compra</button>
                    </form>
                    <form method="get" action="products.php">
                        <button type="submit" class="btn btn-primary">Seguir Comprando</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
