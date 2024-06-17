<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz de Administrador</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
        }
        .navbar a {
            color: #f2f2f2;
            text-align: center;
            text-decoration: none;
            font-size: 16px; /* Reducir el tamaño de la fuente del navbar */
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .content {
            margin-top: 30px;
            padding: 20px;
            text-align: center;
        }
        .main {
            padding: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: left;
        }
        .form-control {
            margin-bottom: 10px;
        }
        /* Reducir tamaño de fuente en los formularios */
        .form-control,
        label,
        h2,
        button {
            font-size: 14px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Interfaz de Administrador</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#agregar-producto"></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#modificar-producto"></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#reporte-ventas"></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#eliminar-producto"></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="content">
    <div id="agregar-producto" class="main">
        <h2>Agregar Producto</h2>
        <form action="procesar_producto.php" method="POST">
            <div class="form-group">
                <label for="productname">Nombre del Producto:</label>
                <input type="text" id="productname" name="productname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="text" id="price" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="company_id">Compañía:</label>
                <select id="company_id" name="company_id" class="form-control" required>
                    <?php
                    include 'conexion.php'; // Incluir archivo de conexión

                    // Consulta SQL para obtener las compañías
                    $sql = "SELECT CompanyID, CompanyName FROM Companies";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    // Mostrar opciones de compañías
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='" . $row['CompanyID'] . "'>" . $row['CompanyName'] . "</option>";
                    }

                    sqlsrv_free_stmt($stmt);
                    sqlsrv_close($conn);
                    ?>
                </select>
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn btn-primary">Agregar Producto</button>
        </form>
    </div>

    <div id="modificar-producto" class="main">
        <h2>Modificar Producto</h2>
        <form action="procesar_producto.php" method="POST">
            <div class="form-group">
                <label for="product_id_mod">ID del Producto:</label>
                <input type="number" id="product_id_mod" name="product_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="productname_mod">Nombre del Producto:</label>
                <input type="text" id="productname_mod" name="productname" class="form-control">
            </div>
            <div class="form-group">
                <label for="description_mod">Descripción:</label>
                <textarea id="description_mod" name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="price_mod">Precio:</label>
                <input type="text" id="price_mod" name="price" class="form-control">
            </div>
            <div class="form-group">
                <label for="stock_mod">Stock:</label>
                <input type="number" id="stock_mod" name="stock" class="form-control">
            </div>
            <div class="form-group">
                <label for="company_id_mod">Compañía:</label>
                <select id="company_id_mod" name="company_id" class="form-control">
                    <?php
                    include 'conexion.php'; // Incluir archivo de conexión

                    // Consulta SQL para obtener las compañías
                    $sql = "SELECT CompanyID, CompanyName FROM Companies";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    // Mostrar opciones de compañías
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='" . $row['CompanyID'] . "'>" . $row['CompanyName'] . "</option>";
                    }

                    sqlsrv_free_stmt($stmt);
                    sqlsrv_close($conn);
                    ?>
                </select>
            </div>
            <input type="hidden" name="action" value="modify">
            <button type="submit" class="btn btn-primary">Modificar Producto</button>
        </form>
    </div>

    <div id="eliminar-producto" class="main">
    <h2>Eliminar Producto</h2>
    <form action="procesar_producto.php" method="POST">
        <div class="form-group">
            <label for="product_id_del">ID del Producto:</label>
            <input type="number" id="product_id_del" name="product_id" class="form-control" required>
        </div>
        <input type="hidden" name="action" value="delete">
        <button type="submit" class="btn btn-danger">Eliminar Producto</button>
    </form>
</div>


    <div id="reporte-ventas" class="main">
        <h2>Reporte de Ventas</h2>
        <form action="reporte_ventas.php" method="POST">
            <div class="form-group">
                <label for="reporte_compania">Compañía:</label>
                <select id="reporte_compania" name="reporte_compania" class="form-control" required>
                    <?php
                    include 'conexion.php'; // Incluir archivo de conexión

                    // Consulta SQL para obtener las compañías
                    $sql = "SELECT CompanyID, CompanyName FROM Companies";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    // Mostrar opciones de compañías
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='" . $row['CompanyID'] . "'>" . $row['CompanyName'] . "</option>";
                    }

                    sqlsrv_free_stmt($stmt);
                    sqlsrv_close($conn);
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="reporte_anio">Año del Reporte:</label>
                <input type="text" id="reporte_anio" name="reporte_anio" placeholder="YYYY" pattern="[0-9]{4}" title="Ingrese un año válido (YYYY)" required>
            </div>
            <button type="submit">Generar Reporte</button>
        </form>
    </div>

</div>

</body>
</html>