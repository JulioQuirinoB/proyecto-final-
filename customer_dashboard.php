<?php
session_start();
if (!isset($_SESSION["customerid"])) {
    header("Location: login_cliente.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente</title>
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
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-left">
                <a href="products.php">Comprar</a>
            </div>
            <div class="navbar-right">
                <span class="text-white me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION["customername"]); ?></span>
                <a href="login_cliente.html" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Panel de Cliente</h2>
        <p>Selecciona una opción en la barra de navegación para comenzar.</p>
    </div>

    <!-- Bootstrap JS (opcional, si necesitas funcionalidades como el despliegue del menú hamburguesa) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
