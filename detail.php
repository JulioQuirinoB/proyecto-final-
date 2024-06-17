<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Ventas</title>
</head>
<body>
    <h1>Detalles de Ventas</h1>
    <?php
    // Incluir el archivo de conexión
    include 'conexion.php';

    // Ejecutar el procedimiento almacenado
    $sql = "{CALL GetAllSaleDetails}";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Generar la tabla HTML con los resultados
    echo "<table border='1'>";
    echo "<tr>
            <th>SaleDetailID</th>
            <th>SaleID</th>
            <th>ProductID</th>
            <th>Quantity</th>
            <th>UnitPrice</th>
            <th>Total</th>
          </tr>";

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['SaleDetailID'] . "</td>";
        echo "<td>" . $row['SaleID'] . "</td>";
        echo "<td>" . $row['ProductID'] . "</td>";
        echo "<td>" . $row['Quantity'] . "</td>";
        echo "<td>" . $row['UnitPrice'] . "</td>";
        echo "<td>" . $row['Total'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Liberar recursos y cerrar la conexión
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    ?>
</body>
</html>
