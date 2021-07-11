<?php
 session_start();
   
 $varsession = $_SESSION['emausu'];
require('fpdf.php');
 

$nombre =$_SESSION['nomusu'];
$apellido =$_SESSION['apeusu'];
	
$hoy = getdate();
$ndia = $hoy["mday"]-1; 
$mes = $hoy["mon"];   
$año = $hoy["year"]; 

require 'CONEXIONES.php';
$consulta="SELECT  p.nompro as nombreproducto , p.prepro as precio FROM pedido pe
inner join producto p  on pe.codpro=p.codpro 
inner join usuario u on pe.codusu=u.codusu where u.emausu='$varsession' and pe.estado=1" ;
$resultado=$mysqli->query($consulta);


$consulta1="SELECT   sum(p.prepro) as sub ,sum(p.prepro*0.18) as igv , sum(p.prepro+p.prepro*0.18) as total FROM pedido pe
inner join producto p  on pe.codpro=p.codpro
inner join usuario u on pe.codusu=u.codusu
 where u.emausu='$varsession' and pe.estado=1 " ;
$resultado1=$mysqli->query($consulta1);

// Creación del objeto de la clase heredada
$pdf = new FPDF('P','mm',array(80,150));
$pdf->AddPage();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(60,4,'Bodega Lorena',0,1,'C'); // titulo
$pdf->Cell(60,4,'Los Olivos-Lima',0,1,'C'); // Direccion
 

 //
$pdf->Cell(60,4,'contacto@BodegaLorena.com',0,1,'C');
$pdf->Cell(60,4,'Telefono 999999',0,1,'C');
 
// DATOS FACTURA        
$pdf->Ln(5);
$pdf->Cell(60,4,utf8_decode('Ruc.N°200000'),0,1); // Ruc
$pdf->Cell(60,4,utf8_decode('N° Pedido'),0,1); // titulo
$pdf->Cell(60,4,"Fecha: ".$ndia."/".$mes."/".$año,0,1,'');
$pdf->Cell(60,4,'Metodo de pago: Trasferencia',0,1,'');
$pdf->Cell(60,4, "Cliente : ".$nombre." ".$apellido ,0,1,'');
 
 
 // COLUMNAS
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(30, 10, 'Articulo', 0);
$pdf->Cell(5, 10, 'Unidades',0,0,'R');
$pdf->Cell(10, 10, 'Precio',0,0,'R');
$pdf->Cell(15, 10, 'Total',0,0,'R');
$pdf->Ln(8);
$pdf->Cell(60,0,'','T');
$pdf->Ln(0);

//
 
//Producto y precio
while($row=$resultado->fetch_assoc()){
    $pdf->MultiCell(30,4,$row['nombreproducto'],0,'L');
    $pdf->Cell(35, -5, '1',0,0,'R');//Unidades
    $pdf->Cell(10, -5, number_format(round($row['precio'],2), 2, ',', ' '),0,0,'R');//PrecioUnitario
    $pdf->Cell(15, -5, number_format(round(1*$row['precio'],2), 2, ',', ' '),0,0,'R');//Unidades*PrecioUnitario
    $pdf->Ln(1);

   
}


while($row1=$resultado1->fetch_assoc()){
  
 // Salto de línea
 $pdf->Ln(20);
 // SUMATORIO DE LOS PRODUCTOS Y EL IVA
 $pdf->Ln(6);
 $pdf->Cell(60,0,'','T');
 $pdf->Ln(2);    
 $pdf->Cell(25, 10, 'Sub total', 0);    
 $pdf->Cell(20, 10, '', 0);
 $pdf->Cell(15, 10, number_format(round($row1['sub'],2), 2, ',', ' '),0,0,'R');
 $pdf->Ln(3);    
 $pdf->Cell(25, 10, 'IGV', 0);    
 $pdf->Cell(20, 10, '', 0);
 $pdf->Cell(15, 10, number_format(round($row1['igv'],2), 2, ',', ' '),0,0,'R');
 $pdf->Ln(3);    
 $pdf->Cell(25, 10, 'TOTAL', 0);    
 $pdf->Cell(20, 10, '', 0);
 $pdf->Cell(15, 10, number_format(round($row1['total'],2), 2, ',', ' '),0,0,'R');
 // PIE DE PAGINA $pdf->Ln(10); 
 $pdf->Ln(20);
 
}


  
 
$pdf->Cell(60,0,'Gracias por su compra',0,1,'C'); 
$pdf->Output('D','crear.pdf');
 

$pdf->Output();
$pdf->Output('ficha.pdf','F');


?>