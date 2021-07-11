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
    $this->Cell(70,10,'Ventas  Por Mes de los producto',0,0,'C');
   

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
$consulta="SELECT  nompro as Producto ,COUNT(*) as cantidad ,  prepro as precio, sum(cantidad*prepro) as subTotal ,month(fecped) as mes ,YEAR(fecped) as año FROM pedido p  INNER JOIN producto pr on p.codpro=pr.codpro  
 group by pr.nompro,cantidad,prepro,month(p.fecped),year(p.fecped) Order by  month(fecped) " ;
$resultado=$mysqli->query($consulta);

$consulta1="SELECT month(fecped) as mes ,YEAR(fecped) as año ,sum(cantidad*prepro) as sub ,sum((cantidad*prepro)*0.18) as IGV, sum(cantidad*prepro+cantidad*prepro*0.18 ) as total  FROM pedido p  INNER JOIN producto pr on p.codpro=pr.codpro  
  group by  month(p.fecped)" ;

$resultado1=$mysqli->query($consulta1);


// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//Cabe
$pdf->Cell(35,10,utf8_decode('Mes,Año'),1,0,'C',0);
$pdf->Cell(35,10,'Cantidad Pedido',1,0,'C',0);
$pdf->Cell(50,10,'Producto',1,0,'C',0);
$pdf->Cell(35,10,'Precio',1,0,'C',0);
$pdf->Cell(35,10,'Sub Total',1,1,'C',0);
 
 



// datos captura

while($row=$resultado->fetch_assoc()){
   
    $pdf->Cell(35,10,$row['mes']."/".$row['año'],1,0,'C',0);
    $pdf->Cell(35,10,$row['cantidad'],1,0,'C',0);
    $pdf->Cell(50,10,$row['Producto'],1,0,'C',0);
    $pdf->Cell(35,10,$row['precio'],1,0,'C',0);
    $pdf->Cell(35,10,$row['subTotal'],1,1,'C',0);
    $pdf->Ln(0);
}
$pdf->Ln(10);

while($row1=$resultado1->fetch_assoc()){
$pdf->Cell(40,10,'MES ',1,0,'C');
$pdf->Cell(40,10,$row1['mes']."/".$row1['año'],1,1,'C',0);
 
$pdf->Cell(40,10,'SubTotal ',1,0,'C');
$pdf->Cell(40,10,$row1['sub'],1,1,'C',0);
 
$pdf->Cell(40,10,'IGV ',1,0,'C');
$pdf->Cell(40,10,$row1['IGV'],1,1,'C',0);


$pdf->Cell(40,10,'Total',1,0,'C',0);
$pdf->Cell(40,10,$row1['total'],1,1,'C',0);
$pdf->Ln(10);    
}
$pdf->Output('D','crear1.pdf');
 

$pdf->Output();
$pdf->Output('ficha1.pdf','F');
?>