
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
/*Logica con if anidado del boton buscar al momento de enviar algo, en caso de envio del metodo post
VACIO: Muestra todos los registros de la tabla
xdepartamento: realiza el filtro de que departamento se elegio y busca los registros
xnombre: Busca con el LIKE comparaciones en los campos de la tabla definididos
*/

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
/*$empleados:realiza la consulta en la tabla empleados y toma como condición lo que se envió en 
la variable where, ya sea el xnombre,xdepartamento o ambos
$resdepto: realiza la consulta para el SELECT y agrupa por departamentos los resultados que de la tabla */
$empleados_x_pagina=10;

$iniciar=($_GET['pagina']-1)*$empleados_x_pagina;
$total_paginas=ceil()


$empleados="SELECT * FROM empleados $where limit $iniciar,$empleados_x_pagina ";
$resdepto="SELECT * FROM empleados $where group by depto ";
$resEmpleados=$conexion->query($empleados);
$resDepartamentos=$conexion->query($resdepto);



/*Verifica si lo que se envio en la consulta, en caso de no haber datos que coincidan no muestra registros 
y muestra el mensaje de error, pero si los hay muestra la tabla $empleados con la condion enviada y encontrada
en el where*/
if(mysqli_num_rows($resEmpleados)==0)
{
	echo "<div class=mensaje <b><h1>sin registros</h1></b><div>" ;
}

////////////////CONTADOR DE FILA/////////////////////////////



$totalFilas=mysqli_num_rows($resEmpleados);
$paginas = $totalFilas/8;
$paginas =ceil($paginas);
echo $paginas;



////////////CONSULTA PARA MOSTRAR LOS EMPLEADOS EN LA PAGINACION


?>


<!--Empieza el html para la estructura de la pagina-->
<html lang="es">

	<head>
<!--FORZAMOS PARA QUE LA PAGINACION EMPIECE A PARTIR DE LA PAGINA 1-->
	<?php
	if(!$_GET){
		header('Location:pagina2.php?pagina=1');
	}
	if($_GET['pagina']>$paginas || $_GET['pagina']<=0){
		header('Location:pagina2.php?pagina=1');
	}
	
	?>

		
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
                <li><a class="active" href="ext_empleados.php">Extensiones</a></li>
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
			<table class="table">
				<tr>
					<th>Nombre</th>
					<th>Extension</th>
					<th>Departamento</th>
					<th>Comentarios</th>
					
				</tr>

				<?php

				while ($registroEmpleados = $resEmpleados->fetch_array(MYSQLI_BOTH))
				{

					echo'<tr>
						 <td>'.$registroEmpleados['nombre'].'</td>
						 <td>'.$registroEmpleados['ext'].'</td>
						 <td>'.$registroEmpleados['depto'].'</td>
						 <td>'.$registroEmpleados['comentarios'].'</td>
						 </tr>';
				}

				?>

				
			</table>


			
  <ul class="pagination">
    <li class="page-item <?php echo $_GET['pagina']<=1? 'disabled':''?>">
	<a class="page-link" href="pagina2.php?pagina=<?php echo $_GET['pagina']-1?>">
	Anterior
	</a>
	</li>

<?php for($i=0; $i<$paginas;$i++): ?>
    <li class="page-item 
	<?php echo $_GET['pagina']==$i+1 ? 'active':''?>">
	<a class="page-link"
	href="pagina2.php?pagina=<?php echo $i+1?>">
	<?php echo $i+1?></a>
	</li>
<?php endfor ?>
	
    <li class="page-item
	<?php echo $_GET['pagina']>=$paginas? 'disabled':''?>
		">
	<a class="page-link" href="pagina2.php?pagina=<?php echo $_GET['pagina']+1?>">
	Siguiente
	</a>
	</li>
  </ul>
</nav>

			
		</section>
		
	</body>
	
</html>

