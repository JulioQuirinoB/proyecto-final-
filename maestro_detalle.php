<?php
include 'conexion.php';

// Obtener todas las ventas
$sales_sql = "SELECT s.SaleID, s.CompanyID, s.CustomerID, s.SaleDate, s.TotalAmount 
              FROM Sales s";
$sales_stmt = sqlsrv_query($conn, $sales_sql);
if ($sales_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sales = array();
while ($row = sqlsrv_fetch_array($sales_stmt, SQLSRV_FETCH_ASSOC)) {
    $sales[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Ventas</h1>
    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>ID Compañía</th>
                <th>ID Cliente</th>
                <th>Fecha de Venta</th>
                <th>Monto Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['SaleID']); ?></td>
                    <td><?php echo htmlspecialchars($sale['CompanyID']); ?></td>
                    <td><?php echo htmlspecialchars($sale['CustomerID']); ?></td>
                    <td><?php echo htmlspecialchars($sale['SaleDate']->format('Y-m-d H:i:s')); ?></td>
                    <td><?php echo htmlspecialchars($sale['TotalAmount']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
