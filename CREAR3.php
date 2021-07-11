<?php
require('fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
   
   
    // Logo
    $this->Image('logo.JPG' , 0 ,-6, 55 , 38,'JPG');
    // Arial bold 15
    $this->SetFont('Arial','B',18);
    // Movernos a la derecha
    $this->Cell(60);
    // Título
    $this->Cell(70,10,'Detalle de venta ',0,0,'C');
   

    // Salto de línea
    $this->Ln(20);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
 
//Base conex
require 'CONEXIONES.php';
$consulta="SELECT dp.c as cod,
nomusu as nombreU,
e.nombre as nombre ,  
 MOnth(dp.FechaEnvio) as mes , 
pr.nompro as nombreProducto ,
 unidadventa as unidad, 
 prepro as precio ,
  cantidad as cantidad 
FROM pedido p 
inner join usuario u on p.codusu=u.codusu
inner join detallespedidos  dp on p.codped=dp.codped 
inner join empleados e on dp.idEmpleado=e.idEmpleado
inner join producto pr on p.codpro=pr.codpro ORDER BY dp.c    " ;

$resultado=$mysqli->query($consulta);
//se modifico para sacar sub total de las ventas 

$consulta1="SELECT 
 sum(p.cantidad*pr.prepro) as subTotal ,
 sum((p.cantidad*pr.prepro)*0.18)  as IGV ,
  sum(p.cantidad*pr.prepro+(p.cantidad*pr.prepro)*0.18)  as Total
FROM pedido p 
inner join detallespedidos  dp on p.codped=dp.codped 
inner join empleados e on dp.idEmpleado=e.idEmpleado
inner join producto pr on p.codpro=pr.codpro  " ;

$resultado1=$mysqli->query($consulta1);



// Creación del objeto de la clase heredada
  //$pdf = new PDF();
  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->SetFont('Times','',12);

//Cabe
$pdf->Cell(10,10,'cod',1,0,'C',0);
$pdf->Cell(20,10,'nombre',1,0,'C',0);
$pdf->Cell(20,10,'Cliente',1,0,'C',0);
$pdf->Cell(25,10,'mes',1,0,'C',0);
$pdf->Cell(35,10,'Producto',1,0,'C',0);
$pdf->Cell(35,10,'Tipo de venta',1,0,'C',0);
$pdf->Cell(25,10,'Precio',1,0,'C',0);
$pdf->Cell(20,10,'cantidad',1,1,'C',0);
 
 



// datos captura

while($row=$resultado->fetch_assoc()){
   
    $pdf->Cell(10,10,$row['cod'],1,0,'C',0);
    $pdf->Cell(20,10,$row['nombre'],1,0,'C',0);
    $pdf->Cell(20,10,$row['nombreU'],1,0,'C',0);
    $pdf->Cell(25,10,$row['mes'],1,0,'C',0);
    $pdf->Cell(35,10,$row['nombreProducto'],1,0,'C',0);
    $pdf->Cell(35,10,$row['unidad'],1,0,'C',0);
    $pdf->Cell(25,10,$row['precio'],1,0,'C',0);
    $pdf->Cell(20,10,$row['cantidad'],1,1,'C',0);

    
    $pdf->Ln(0);
}

$pdf->Ln(10);
// cabeza 2 
$pdf->Cell(25,10,'subtotal',1,0,'C',0);
$pdf->Cell(25,10,'IGV',1,0,'C',0);
$pdf->Cell(35,10,'Total',1,1,'C',0);

while($row1=$resultado1->fetch_assoc()){
   
    $pdf->Cell(25,10,$row1['subTotal'],1,0,'C',0);
    $pdf->Cell(25,10,$row1['IGV'],1,0,'C',0);
    $pdf->Cell(35,10,$row1['Total'],1,1,'C',0);
 

    
    $pdf->Ln(0);
     
    
}


 

 
 
$pdf->Output('D','crear3.pdf');
 

$pdf->Output();
$pdf->Output('ficha3.pdf','F');

 
 
?>