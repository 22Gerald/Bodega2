<?php
	session_start();
	if (!isset($_SESSION['codusu'])) {
		header('location: index.php');
	}
?>

<title>Bodega Lorena</title>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pedidos</title>
	<link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/estilo.css">
	<link href = "https://fonts.googleapis.com/css2? family = Poppins & display = swap" rel = "stylesheet">
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<header>
		<nav>
			<div class="logo">Lorena</div>
			<label for="btn" class="icon">
				<span class="fa fa-bars"></span>
			</label>
			<input type="checkbox" name="btn" id="btn">
			<ul>
				<li><a href="index.php">Inicio</a></li>
		    </ul>
			<ul>
				<li><a href="Historial.php">Ver pedidos</a></li>
		    </ul>

			 

		</nav>
    </header>	





  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/m.css">
</head>
<body>
<form>
<input type="text" name="buscada" id="busqueda" placeholder="Buscar...">
</form>

 
	<div class="main-content">
		<div class="content-page">
			<h3>Mis pedidos</h3>
			<div class="item-pedido" id="space-list">
			</div>
			<h3 style="color:#FF0000">Comprbante</h3>
			<div class="p-line"><div>MONTO TOTAL:</div>S/.&nbsp;<span id="montototal"></span></div>
			<div class="p-line"><div>Banco:</div>BCP</div>
			<div class="p-line"><div>N° de Cuenta:</div>191-45678945-006</div>
		 
			<p><b>NOTA:</b> Ya se Descargo el comprobante de pago </p>

		 
 
		</div>
	</div>
	<script type="text/javascript" src="js/main-scripts.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$.ajax({
				url:'servicios/pedido/get_procesados.php',
				type:'POST',
				data:{},
				success:function(data){
					console.log(data);
					let html='';
					let monto=0;
					for (var i = 0; i < data.datos.length; i++) {
						html+=
						'<div class="item-pedido">'+
							'<div class="pedido-img">'+
								'<img src="assets/products/'+data.datos[i].rutimapro+'">'+
							'</div>'+
							'<div class="pedido-detalle">'+
								'<h3>'+data.datos[i].nompro+'</h3>'+
								'<p><b>Precio:</b> S/.'+data.datos[i].prepro+'</p>'+
								'<p><b>Fecha:</b> '+data.datos[i].fecped+'</p>'+
								'<p><b>Estado:</b> '+data.datos[i].estadotext+'</p>'+
								'<p><b>Dirección:</b> '+data.datos[i].dirusuped+'</p>'+
								'<p><b>Celular:</b> '+data.datos[i].telusuped+'</p>'+
							'</div>'+
						'</div>';
						if (data.datos[i].estado=="2") {
							monto+=parseFloat(data.datos[i].prepro);
						}
					}
					document.getElementById("montototal").innerHTML=monto;
					document.getElementById("space-list").innerHTML=html;
				},
				error:function(err){
					console.error(err);
				}
			});
		});
	</script>
</body>
</html>