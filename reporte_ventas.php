<?php
// Verificar que se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validar y obtener los datos del formulario
    $compania = $_POST['reporte_compania'];
    $anio = $_POST['reporte_anio'];

    // Incluir archivo de conexión a la base de datos
    include 'conexion.php';  // Asegúrate de que este archivo tenga la conexión a SQL Server

    // Consulta SQL para obtener las ventas filtradas por compañía y año
    $sql = "SELECT SaleID, CustomerID, TotalAmount, SaleDate FROM Sales WHERE CompanyID = ? AND YEAR(SaleDate) = ?";
    
    // Preparar la consulta
    $params = array($compania, $anio);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    // Verificar si la consulta fue exitosa
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Crear instancia de TCPDF
    require_once('C:/xampp/htdocs/multicompany/TCPDF-main/tcpdf.php');  // Ajusta la ruta según tu instalación
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Establecer información del documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Nombre');
    $pdf->SetTitle('Reporte de Ventas');
    $pdf->SetSubject('Reporte de Ventas');
    $pdf->SetKeywords('ventas, reporte, pdf');

    // Agregar página
    $pdf->AddPage();
    
    // Título del reporte
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Ventas para ' . obtenerNombreCompania($conn, $compania) . ' en el año ' . $anio, 0, 1, 'C');
    $pdf->Ln(10);

    // Tabla de ventas
    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.1);
    
    // Encabezados de la tabla
    $pdf->Cell(30, 10, 'ID Venta', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'ID Cliente', 1, 0, 'C', 1);
    $pdf->Cell(50, 10, 'Venta Total', 1, 0, 'C', 1);
    $pdf->Cell(50, 10, 'Fecha de Venta', 1, 1, 'C', 1);
    
    // Datos de ventas
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Verificar si las claves del array están definidas antes de usarlas
        $saleID = isset($row['SaleID']) ? $row['SaleID'] : '';
        $customerID = isset($row['CustomerID']) ? $row['CustomerID'] : '';
        $totalAmount = isset($row['TotalAmount']) ? $row['TotalAmount'] : '';
        $saleDate = isset($row['SaleDate']) ? $row['SaleDate']->format('Y-m-d') : '';

        $pdf->Cell(30, 10, $saleID, 1, 0, 'C');
        $pdf->Cell(30, 10, $customerID, 1, 0, 'C');
        $pdf->Cell(50, 10, $totalAmount, 1, 0, 'C');
        $pdf->Cell(50, 10, $saleDate, 1, 1, 'C');
    }
    
    // Cerrar conexión y liberar recursos
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    
    // Salida del documento PDF
    $pdf->Output('reporte_ventas.pdf', 'I');

} else {
    // Redireccionar si se intenta acceder directamente sin enviar el formulario
    header("Location: index.html");
    exit();
}

// Función para obtener el nombre de la compañía
function obtenerNombreCompania($conn, $companyID) {
    $sql = "SELECT CompanyName FROM Companies WHERE CompanyID = ?";
    $params = array($companyID);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['CompanyName'];
}
?>
