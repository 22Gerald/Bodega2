<?php
	session_start();
	if (!isset($_SESSION['codusu'])) {
		header('location: index.php');
	}
?>
 
<title>Clan</title>
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
		</nav>
    </header>	

	
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/m.css">
  <link rel="stylesheet" type="text/css" href="diseño1.css">
</head>
<body>
	 
	 
		<div class="options-place">
			<?php
			if (isset($_SESSION['codusu'])) {
				echo
				'<div class="item-option"><i class="fa fa-user-circle-o" aria-hidden="true"></i><p>'.$_SESSION['nomusu'].'</p></div>';
			}else{
			?>
			<div class="item-option" title="Registrate"><i class="fa fa-user-circle-o" aria-hidden="true"></i></div>
			<div class="item-option" title="Ingresar"><i class="fa fa-sign-in" aria-hidden="true"></i></div>
			<?php
			}
			?>
			 
		</div>
 
	<div class="main-content">
		<div class="content-page">
			<h3>Mi carrito</h3>
			<div class="body-pedidos" id="space-list">
			</div>
			<input class="ipt-procom" type="text" id="dirusu" placeholder="Domicilio">
			<br>
			<input class="ipt-procom" type="text" id="telusu" placeholder="Celular">
			<br>
			<h4>Tipos de pago</h4>
			<div class="metodo-pago">
				<input type="radio" name="tipopago" value="1" id="tipo1">
				<label for="tipo1">Pago por transferencia</label>
			</div>
			<div class="metodo-pago">
				<input type="radio" name="tipopago" value="2" id="tipo2">
				<label for="tipo2" readonly >Paypal</label>
			</div>
			<button onclick=" " style="margin-top: 5px;"> <a href="https://localhost/bodega/CREAR.php"  FONT COLOR="red" >Descargar Comprobante</a>  </button> 
			<button onclick="procesar_compra()" style="margin-top: 5px;">Pagar </button>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$.ajax({
				url:'servicios/pedido/get_porprocesar.php',
				type:'POST',
				data:{},
				success:function(data){
					console.log(data);
					let html='';
					let sumaMonto=0;
					for (var i = 0; i < data.datos.length; i++) {
						html+=
						'<div class="table">'+
							'<div class="pedido-img">'+
								'<img src="assets/products/'+data.datos[i].rutimapro+'">'+
							'</div>'+
							'<div class="pedido-detalle">'+
								'<h3>'+data.datos[i].nompro+'</h3>'+
								'<p><b>Precio:</b> S/ '+data.datos[i].prepro+'</p>'+
								'<p><b>Fecha:</b> '+data.datos[i].fecped+'</p>'+
								'<p><b>Estado:</b> '+data.datos[i].estado+'</p>'+
								'<p><b>Dirección:</b> '+data.datos[i].dirusuped+'</p>'+
								'<p><b>Celular:</b> '+data.datos[i].telusuped+'</p>'+
								'<button onclick="e();" style="margin-top: 5px;">Remover compra</button>' +
							 
							'</div>'+
							 
						'</div>';
						 
						sumaMonto+=(parseInt(data.datos[i].prepro))*100;

					}
				    Culqi.settings({
				        title: 'Paypal',
				        currency: 'Soles',
				        description: 'Productos',
				        amount: sumaMonto
				    });
					document.getElementById("space-list").innerHTML=html;
				},
				error:function(err){
					console.error(err);
				}
			});
		});
		function e(){
			window.location.href="edi_PRO/Lis_prod.php";
		}
		function procesar_compra(){
			let dirusu=document.getElementById("dirusu").value;
			let telusu=$("#telusu").val();
			let tipopago=1;
			if (document.getElementById("tipo2").checked) {
				tipopago=2;
			}
			if (dirusu=="" || telusu=="") {
				alert("Complete los campos");
			}else{
				if (!document.getElementById("tipo1").checked &&
					!document.getElementById("tipo2").checked) {
					alert("Seleccione un método de pago!");
				}else{
					if (tipopago==2) {
						Culqi.open();
					}else{
						$.ajax({
							url:'servicios/pedido/confirm.php',
							type:'POST',
							data:{
								dirusu:dirusu,
								telusu:telusu,
								tipopago:tipopago,
								token:''
							},
							success:function(data){
								console.log(data);
								if (data.state) {
									window.location.href="pedido.php";
								}else{
									alert(data.detail);
								}
							},
							error:function(err){
								console.error(err);
							}
						});
					}
				}
			}
		}
		function culqi() {
			if (Culqi.token) { 
		      	var token = Culqi.token.id;
		      	$.ajax({
					url:'servicios/pedido/confirm.php',
					type:'POST',
					data:{
						dirusu:document.getElementById("dirusu").value,
						telusu:$("#telusu").val(),
						tipopago:2,
						token:token
					},
					success:function(data){
						console.log(data);
						if (data.state) {
							window.location.href="pedido.php";
						}else{
							alert(data.detail);
						}
					},
					error:function(err){
						console.error(err);
					}
				});
		  	} else {
		      	console.log(Culqi.error);
		      	alert(Culqi.error.user_message);
		  	}
		};
	</script>
	<script src="https://checkout.culqi.com/js/v3"></script>
	<script>
	    Culqi.publicKey = 'pk_test_3adf22bd8acf4efc';
	</script>
</body>
</html>