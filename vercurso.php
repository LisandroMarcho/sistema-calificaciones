<?php
session_start();
include_once("conx.php");

verifySession();

if(!isset($_GET["idmateria"]))
	header("Location: cursos.php");

$idmateria = $_GET["idmateria"];
$idprofesor = $_SESSION["idprofesor"];

$profemateria = "SELECT count(*) FROM materiasprofesores WHERE idmateria = $idmateria AND idprofesor = $idprofesor";
$profemateria = mysqli_query($link, $profemateria);

if($profemateria){
	$materia = "SELECT * from materias WHERE idmateria = $idmateria";
	$materia = mysqli_query($link, $materia);
	$materia = mysqli_fetch_array($materia);
	
	$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
	$curso = mysqli_fetch_array(mysqli_query($link, $curso));

	$escuela = "SELECT nom FROM escuelas WHERE idescuela = $curso[1]";
	$escuela = mysqli_fetch_array(mysqli_query($link, $escuela))[0];

	$alumnos = "SELECT * FROM alumnos WHERE idcurso = $materia[1] ORDER BY ape";
	$alumnos = mysqli_query($link, $alumnos);

	$mesas = "SELECT * FROM mesas WHERE idmateria = $materia[0] ORDER BY idalumno";
	$mesas = mysqli_query($link, $mesas);

	$titulo = "$materia[2] - $curso[2]° $curso[3]ª $curso[4] - $escuela";
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $titulo ?></title>
</head>
<body>
	<h2><?php echo $titulo ?></h2>
	<a href='<?php echo "asistencia.php?materia=$idmateria";?>'>Asistencia</a>
	<a href='<?php echo "calificaciones.php?materia=$idmateria";?>'>Calificaciones</a>
	<a href='<?php echo "mesa.php?materia=$idmateria"?>'>Nueva mesa</a>
	<h3>Alumnos</h3>
	<ul>
		<?php while($r = mysqli_fetch_array($alumnos)) {
			echo "<li>".strtoupper($r[3]).", $r[2]</li>";
		}?>
	</ul>

	<h3>Mesas</h3>

	<table border=1>
		<tr>
			<th>Alumno</th>
			<th>Fecha</th>
			<th>Nota</th>
		</tr>
		<?php 
		if(mysqli_num_rows($mesas) < 1)
			echo "<tr><td colspan=3>No hubo mesas en esta materia</td></tr>";
		else while($r = mysqli_fetch_array($mesas)){
			$alumno = "SELECT * FROM alumnos WHERE idalumno = $r[1]";
			$alumno = mysqli_fetch_array(mysqli_query($link, $alumno));
			echo "<tr>
					<td>$alumno[3], $alumno[2]</td>
					<td><input type='date' value='$r[3]' readonly></td>
					<td>$r[4]</td>
					<td><a href='mesa.php?mesa=$r[0]'>Editar</a></td>
		          </tr>";

		}
		 ?>
	</table>
</body>
</html>