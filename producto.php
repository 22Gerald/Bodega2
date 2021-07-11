	 
<?php
  
  session_start();

?>
<title>Bodega Lorena </title>
 
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/m.css">
</head>
<body>
   
	<div class="main-content">
		<div class="content-page">
			<section>
				<div class="part1">
					<img id="idimg" src="assets/products/crepe.jpg">
				</div>
				<div class="part2">
				 <h1 align="center" id="idtitle">NOMBRE PRINCIPAL</h1> <br>
				 
					<h3 id="iddescription">Descripcion del producto</h3><br>
					<h3>Stock:</h3><h2 id="idstock">stock</h2><br>
				 
					 


					<h3>Precio unitario </h3>  
					<h2 id="idprice">S/. 35.<span>99</span></h2>
					<h4 id="idunidadventa">kg</h4> 
					<br>
					<center>	 <button  onclick="iniciar_compra();">Agregar Producto</button></center>
				</div>
			</section>
			<div class="title-section"></div>
			<div class="products-list" id="space-list">
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/main-scripts.js"></script>
	<script type="text/javascript">
		var p='<?php echo $_GET["p"]; ?>';
	</script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
	<script type="text/javascript">
		$(document).ready(function(){
			$.ajax({
				url:'servicios/producto/get_all_products.php',
				type:'POST',
				data:{},
				success:function(data){
					console.log(data);
					let html='';
					for (var i = 0; i < data.datos.length; i++) {
						if (data.datos[i].codpro==p) {
							document.getElementById("idimg").src="assets/products/"+data.datos[i].rutimapro;
							document.getElementById("idtitle").innerHTML=data.datos[i].nompro;
							document.getElementById("idprice").innerHTML=formato_precio(data.datos[i].prepro);
							document.getElementById("iddescription").innerHTML=data.datos[i].despro;
							document.getElementById("idstock").innerHTML=data.datos[i].stock;
                            document.getElementById("idunidadventa").innerHTML=data.datos[i].unidadventa;
							 
						}
					}
					document.getElementById("space-list").innerHTML=html;
				},
				error:function(err){
					console.error(err);
				}
			});
		});
		function formato_precio(valor){
			//10.99
			let svalor=valor.toString();
			let array=svalor.split(".");
			return "S/. "+array[0]+".<span>"+array[1]+"</span>";
		}
		function iniciar_compra(){
						swal({
								title: "Desea realizar la compra ?",
								text: "",
								icon: "warning",
								buttons: true,
								dangerMode: true,
								}) 


								.then((willDelete) => {
								if (willDelete) {
									swal("se agrego el producto ", {
										 
									icon: "success",
									});
									
									window.location.href="carrito.php";
			$.ajax({ 
				url:'servicios/compra/validar_inicio_compra.php',
				type:'POST',
				data:{
					codpro:p
				},
				success:function(data){
					console.log(data);
					if (data.state) {
						swal(data.detail);
					}else{
					 
						alert(data.detail);
						if (data.open_login) {
							open_login();
						}
					}
				},
				error:function(err){
					console.error(err);
				}
			});
		 
		
	} else {
	 	swal("No se agrego al carrito ");
					}
					});

	 
		}
		 

		function open_login(){
			window.location.href="Clientes/ingreso.php";
		}

		 
	</script>
</body>
</html>