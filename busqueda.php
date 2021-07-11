<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bodega</title>
	<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
 
</head>
<body>
	<?php include("index.php"); ?>
	<div class="main-content">
		<div class="content-page">
			 
			<div class="products-list" id="space-list">
			</div>
		</div>
	</div>
	<div class="main-content">
			<div class="content-page">
			 
				<div class="products-list" id="space-list">
				</div>
			</div>
		<script type="text/javascript" src="js/main-scripts.js"></script>
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
						html+=
						'<div class="product-box">'+
							'<a href="producto.php?p='+data.datos[i].codpro+'">'+
								'<div class="product">'+
									'<img src="assets/products/'+data.datos[i].rutimapro+'">'+
									'<p class="detalle-ropa">'+data.datos[i].nompro+'</p>'+
									'<p class="precio-ropa">'+formato_precio(data.datos[i].prepro)+'</p>'+
								'</div>'+
							'</a>'+
						'</div>';
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
	</script>
	</div>

		
	</div>

	<script type="text/javascript" src="js/main-scripts.js"></script>
	<script type="text/javascript">
		var text="<?php echo $_GET['text']; ?>";
		$(document).ready(function(){
			$.ajax({
				url:'servicios/producto/get_all_results.php',
				type:'POST',
				data:{
					text:text
				},
				success:function(data){
					console.log(data);
					let html='';
					for (var i = 0; i < data.datos.length; i++) {
						html+=
						'<div class="product-box">'+
							'<a href="producto.php?p='+data.datos[i].codpro+'">'+
								'<div class="product">'+
									'<img src="assets/products/'+data.datos[i].rutimapro+'">'+
									'<div class="detail-title">'+data.datos[i].nompro+'</div>'+
									'<div class="detail-price">'+formato_precio(data.datos[i].prepro)+'</div>'+
								'</div>'+
							'</a>'+
						'</div>';
					}
					if (html=='') {
						document.getElementById("space-list").innerHTML="No hay resultados";
					}else{
						document.getElementById("space-list").innerHTML=html;
					}
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
	</script>
 
</body>
</html>