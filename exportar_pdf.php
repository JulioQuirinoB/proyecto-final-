<?php
require('fpdf.php');
include 'conexion.php';

// Obtener el ID de la venta
$sale_id = isset($_POST['sale_id']) ? intval($_POST['sale_id']) : null;

if ($sale_id) {
    // Obtener detalles de la venta
    $details = getSaleDetails($sale_id, $conn);
    $sale_master = $details['sale_master'];
    $sale_details = $details['sale_details'];

    // Crear un nuevo PDF
    class PDF extends FPDF
    {
        function Header()
        {
            // Logo
            $this->Image('logo.png',10,6,30);
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Movernos a la derecha
            $this->Cell(80);
            // Título
            $this->Cell(30,10,'Detalle de Venta',0,0,'C');
            // Salto de línea
            $this->Ln(20);
        }

        function Footer()
        {
            // Posición a 1.5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);

    // Datos de la Venta
    $pdf->Cell(0,10,'Datos de la Venta',0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,10,'ID Venta: ' . $sale_master['SaleID'],0,1);
    $pdf->Cell(0,10,'ID Compañía: ' . $sale_master['CompanyID'],0,1);
    $pdf->Cell(0,10,'ID Cliente: ' . $sale_master['CustomerID'],0,1);
    $pdf->Cell(0,10,'Fecha de Venta: ' . ($sale_master['SaleDate'] ? $sale_master['SaleDate']->format('Y-m-d H:i:s') : ''),0,1);
    $pdf->Cell(0,10,'Monto Total: $' . $sale_master['TotalAmount'],0,1);

    // Salto de línea
    $pdf->Ln(10);

    // Detalles de los Productos
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Detalles de los Productos',0,1,'C');
    $pdf->SetFont('Arial','',10);

    foreach ($sale_details as $detail) {
        $pdf->Cell(0,10,'ID Detalle de Venta: ' . $detail['SaleDetailID'],0,1);
        $pdf->Cell(0,10,'ID Producto: ' . $detail['ProductID'],0,1);
        $pdf->Cell(0,10,'Cantidad: ' . $detail['Quantity'],0,1);
        $pdf->Cell(0,10,'Precio Unitario: $' . $detail['UnitPrice'],0,1);
        $pdf->Cell(0,10,'Total: $' . $detail['Total'],0,1);
        // Salto de línea
        $pdf->Ln(5);
    }

    // Exportar PDF
    $pdf->Output('D', 'Venta_'.$sale_id.'.pdf');
} else {
    echo "No se ha proporcionado un ID de venta válido.";
}
?>
