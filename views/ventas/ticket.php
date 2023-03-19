<?php
$id_venta = (empty($_GET['sale'])) ? null : $_GET['sale'];
if ($id_venta != null) {
    require_once '../../config.php';
    require_once '../../models/reporte.php';
    $ventas = new Reporte();
    $datos = $ventas->getConfiguracion();
    $result = $ventas->getSale($id_venta);
    $products = $ventas->getProductsVenta($id_venta);

    require('../fpdf/fpdf.php');

    $pdf = new FPDF('P', 'mm', array(80, 200));
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 10, $datos['nombre'], 0, 1, 'C');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(60, 5, utf8_decode('Telefono: ' . $datos['telefono']), 0, 1, 'C');
    $pdf->Cell(60, 5, 'Correo: ' . $datos['email'], 0, 1, 'C');
    $pdf->Cell(60, 5, utf8_decode('Dirección: ' . $datos['direccion']), 0, 1, 'C');
    $pdf->Cell(60, 5, utf8_decode('Nro. Ticket: ' . $datos['id']), 0, 1, 'C');
    // $pdf->Cell(60, 5, '===============================', 0, 1, 'C');
    // //########## Datos del cliente
    // $pdf->Cell(60, 5, utf8_decode('Nombre: ' . $result['nombre']), 0, 1, 'C');
    // $pdf->Cell(60, 5, utf8_decode('Telefono: ' . $result['telefono']), 0, 1, 'C');
    // $pdf->Cell(60, 5, utf8_decode('Dirección: ' . $result['direccion']), 0, 1, 'C');

    $pdf->Ln(2);
    $pdf->Cell(60, 5, '','T');
    $pdf->Ln(2);
    
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(60, 5, utf8_decode('Datos del producto/servicio'), 0, 1, 'C');
    $pdf->Ln(2);
    $pdf->Cell(60, 5, '','T');
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 4, utf8_decode('Cant.  -  P/U '), 0, 0, 'L');
    $pdf->Cell(30, 4, utf8_decode('Producto '), 0, 0 , 'L');
    $pdf->Cell(20, 4, utf8_decode('Total '), 0, 1, 'L');
    $pdf->Cell(60, 5, '','T');
    $pdf->Ln(2);
    // $pdf->Cell(60, 4, '-------------------------------------------------------------------', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 8); 
    $total = 0;
  
    foreach ($products as $product) {
        $total += $product['cantidad'] * $product['precio'];
        
        $pdf->Cell(20, 5, $product['cantidad'] . ' x ' . $product['precio'], 0, 0, 'L');
        $pdf->Cell(30, 5, $product['descripcion'], 0, 0, 'L');
        $pdf->Cell(20, 5, number_format($product['cantidad'] * $product['precio'], 2), 0, 1, 'L');
        // $pdf->Cell(60, 5, '------------------------------------------------------', 0, 1, 'C');
    }
    $pdf->Ln(3);
    $pdf->Cell(60, 5, '','T');
    $pdf->Ln(3);
    // $pdf->Cell(60, 5, '****************************************************', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 9); 
    $pdf->Cell(60, 5, 'Total ' . number_format($total, 2), 0, 1, 'R');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Ln(2);
    $pdf->SetFont('Arial', '', 8); 

    // $pdf->Cell(60, 5, 'MONTO PAGADO: ' . number_format($mdado, 2), 0, 1, 'R');
   
    $pdf->Ln(4);
    $pdf->Cell(40, 5, 'METODO DE PAGO:', 0, 1, 'R');
    
    $pdf->Cell(40, 5, $result['metodo'], 0, 1, 'R');
    // $pdf->Cell(60, 5, 'Monto pa1gado'. $result['mpagado'], 0, 1, 'C');
    $pdf->Ln(2);
    $pdf->Cell(40, 5, 'Monto Recibido: '. $result['mdado'], 0, 1, 'R');
    $vuelto = $result['mdado'] - $total;
    $pdf->Cell(40, 5, 'VUELTO: '. number_format($vuelto, 2), 0, 1, 'R');

    $pdf->Cell(60, 5,  utf8_decode('¡GRACIAS POR SU COMPRA!'), 0, 1, 'C');
    $pdf->Output();
} else {
    echo 'PAGINA NO ENCONTRADA';
}