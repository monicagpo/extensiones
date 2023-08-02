
<?php

////////////////// CONEXION A LA BASE DE DATOS ////////////////////////////////////

$host="localhost";
$usuario="root";
$contraseña="";
$base="extensiones";

$conexion= new mysqli($host, $usuario, $contraseña, $base);
if ($conexion -> connect_errno)
{
	die("Fallo la conexion:(".$conexion -> mysqli_connect_errno().")".$conexion-> mysqli_connect_error());
}

////////////////// VARIABLES DE CONSULTA////////////////////////////////////



////////////////////// BOTON BUSCAR //////////////////////////////////////
$where="";





if (isset($_POST['buscar']))
{

	
	if (empty($_POST['xsubgrupo']))
	{
		$where="where nombre_documento like '".$_POST['xnombre_documento']."%' or nombre_tipo like '".$_POST['xnombre_documento']."%' or nombre_subgrupo like '".$_POST['xnombre_documento']."%'";
	}

	else if (empty($_POST['xnombre_documento']))
	{
		$where="where nombre_subgrupo='".$_POST['xsubgrupo']."'";
	}

	else
	{
		$where="where nombre like '".$_POST['xnombre']."%' and nombre_subgrupo='".$_POST['xsubgrupo']."' ";
	}
}
/////////////////////// CONSULTA A LA BASE DE DATOS ////////////////////////


$documento="SELECT nombre_documento,nombre_tipo,nombre_subgrupo,descripcion FROM tipo_documentos 
			INNER JOIN documentos ON tipo_documentos.id_tipo=documentos.id_tipo 
			INNER JOIN subgrupo_documentos ON documentos.id_subgrupo=subgrupo_documentos.id_subgrupo
			$where AND tipo_documentos.id_tipo=1 ";
$resSubgrupo="SELECT nombre_documento,nombre_tipo,nombre_subgrupo,descripcion FROM tipo_documentos 
			INNER JOIN documentos ON tipo_documentos.id_tipo=documentos.id_tipo 
			INNER JOIN subgrupo_documentos ON documentos.id_subgrupo=subgrupo_documentos.id_subgrupo
			$where group by nombre_subgrupo ";
$resDocumentos=$conexion->query($documento);
$resSubgrupos=$conexion->query($resSubgrupo);

if(mysqli_num_rows($resDocumentos)==0)
{
	echo "<div class=mensaje <b><h1>sin registros</h1></b><div>" ;
}
?>
<html lang="es">

	<head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
       
		<link href="css/estilos_tabla.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        
		
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Menú Responsive</title>
		<link rel="stylesheet" href="css/estilos.css">
    <!-- CDN font awesome -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
		
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Menú Responsive</title>
		
    <!-- CDN font awesome -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- DATATABLES -->
    <!--  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <!--INICIO DEL MENU DE NAVEGACION-->
    <title>Paginacion</title>
    </head>
	<body>
	<header>
        <nav>
            <a href="#" class="enlace">
                <img src="img/Logo_Canteras_small.png" alt="" class="logo">
            </a>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <i class="fas fa-bars"></i>
            </label>
            <ul>
                <li><a class="active" href="extensiones.php">Extensiones</a></li>
                <li><a href="ma_administrativos.php">Manuales administrativos</a></li>
                <li><a href="#">Manuales TI</a></li>
                <li><a href="#">Procedimientos</a></li>
                <li><a href="#">Formatos</a></li>
				<li><a href="#">Politicas</a></li>
				<li><a href="#">Tutoriales</a></li>
				<li><a href="#">Sugerencias</a></li>
            </ul>
        </nav>
    </header>
    <header>
			<div class="alert alert-info">
			<h2>Lista de extensiones</h2>
			</div>
		</header>
		
	<!--FIN DEL MENÚ DE NAVEGACIÓN-->
		
		<section >
		<form method="post">
				<input type="text" placeholder="Nombre" name="xnombre_documento"/>
				<select name="xsubgrupo">
					<option value="">Todos </option>
					<?php

						while ($registroSubgrupo = $resSubgrupos->fetch_array(MYSQLI_BOTH))
						{
							echo '<option value="'.$registroSubgrupo['nombre_subgrupo'].'">'.$registroSubgrupo['nombre_subgrupo'].'</option>';
						}
					?>

				</select>
		
				
				<button name="buscar" type="submit">Buscar</button>
			</form>
		
<body>
    <div class="container" style="margin-top:0px;padding: 5px">
        <table id="tablax" class="table table-striped table-bordered" style="width:100%"  >

			
				<tr>
					<th>Nombre</th>
					<th>Tipo</th>
					<th>Subgrupo</th>
					<th>Descripcion</th>
					
				</tr>

				<?php

				while ($registroDocumentos = $resDocumentos->fetch_array(MYSQLI_BOTH))
				{

					echo'<tr>
						 <td>'.$registroDocumentos['nombre_documento'].'</td>
						 <td>'.$registroDocumentos['nombre_tipo'].'</td>
						 <td>'.$registroDocumentos['nombre_subgrupo'].'</td>
						 <td>'.$registroDocumentos['descripcion'].'</td>
						 </tr>';
				}
				
				?>
				
			</table>

			
		</section>
		
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous">
    </script>
    <!-- DATATABLES -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
    </script>
    <!-- BOOTSTRAP -->
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js">
    </script>
    <script>
    ////////////////CAMBIO DE IDIOMA DataTable y DESABILITACION DE INPUT SEARCH////////////////////////////
        $(document).ready(function () {
            $('#tablax').DataTable({
                 
                language: {
                    processing: "Tratamiento en curso...",
                    search: "Buscar&nbsp;:",
                    lengthMenu: "Agrupar de _MENU_ items" ,
                    info: "Mostrando del item _START_ al _END_ de un total de _TOTAL_ items",
                    infoEmpty: "No existen datos.",
                    infoFiltered: "(filtrado de _MAX_ elementos en total)",
                    infoPostFix: "",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron datos con tu busqueda",
                    emptyTable: "No hay datos disponibles en la tabla.",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Ultimo"
                    },
                    aria: {
                        sortAscending: ": active para ordenar la columna en orden ascendente",
                        sortDescending: ": active para ordenar la columna en orden descendente"
                    }
                },
                scrollY: 400,
                lengthMenu: [ [10, 20, -1], [10, 20, "All"] ],
                "searching": false,
                
                
               
            });
        });
    </script>
</body>
</html>
		
	

