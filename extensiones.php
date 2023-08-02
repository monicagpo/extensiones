
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


////////////////////// BOTON BUSCAR //////////////////////////////////////
$where="";

if (isset($_POST['buscar']))
{

	
	if (empty($_POST['xdepartamento']))
	{
		$where="where nombre like '".$_POST['xnombre']."%' or ext like '".$_POST['xnombre']."%' or depto like '".$_POST['xnombre']."%'";
	}

	else if (empty($_POST['xnombre']))
	{
		$where="where depto='".$_POST['xdepartamento']."'";
	}

	else
	{
		$where="where nombre like '".$_POST['xnombre']."%' and depto='".$_POST['xdepartamento']."'";
	}
}
/////////////////////// CONSULTA A LA BASE DE DATOS ////////////////////////


$empleados="SELECT * FROM empleados $where ";
$resdepto="SELECT * FROM empleados $where group by depto ";
$resEmpleados=$conexion->query($empleados);
$resDepartamentos=$conexion->query($resdepto);

if(mysqli_num_rows($resEmpleados)==0)
{
	echo "<div class=mensaje <b><h1>sin registros</h1></b><div>" ;
}
?>
<html lang="en">
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
                <li><a href="administrativos.php">Manuales administrativos</a></li>
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
		<section>
			<form method="post">
				<input type="text" placeholder="Nombre" name="xnombre"/>
				<select name="xdepartamento">
					<option value="">Todos </option>
					<?php

						while ($registroDepartamento = $resDepartamentos->fetch_array(MYSQLI_BOTH))
						{
							echo '<option value="'.$registroDepartamento['depto'].'">'.$registroDepartamento['depto'].'</option>';
						}
					?>

				</select>

				
				<button name="buscar" type="submit">Buscar</button>
			</form>
            </section>
</head>
<body>
    <div class="container" style="margin-top:0px;padding: 5px">
        <table id="tablax" class="table table-striped table-bordered" style="width:100%"  >
      
            <thead>
                    <th>Nombre</th>
					<th>Extension</th>
					<th>Departamento</th>
					<th>Comentarios</th>
           
            </thead>
            <tbody>
                <?php

				while ($registroAlumnos = $resEmpleados->fetch_array(MYSQLI_BOTH))
				{

					echo'<tr>
						 <td>'.$registroAlumnos['nombre'].'</td>
						 <td>'.$registroAlumnos['ext'].'</td>
						 <td>'.$registroAlumnos['depto'].'</td>
						 <td>'.$registroAlumnos['comentarios'].'</td>
						 </tr>';
				}
				
				?>
            </tbody>
        </table>
    </div>


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

