<?php
session_start();
include_once("conx.php");

verifySession();

if (!isset($_GET["idmateria"]))
	header("Location: cursos.php");
else $idmateria = $_GET["idmateria"];

$sesionValida = "SELECT count(*) FROM materiasprofesores WHERE idmateria = $idmateria AND idprofesor = " . $_SESSION["idprofesor"];
$sesionValida = mysqli_query($link, $sesionValida);
if(mysqli_fetch_array($sesionValida)[0] == 0) header('Location: cursos.php');

$idprofesor = $_SESSION["idprofesor"];

$profemateria = "SELECT count(*) FROM materiasprofesores WHERE idmateria = $idmateria AND idprofesor = $idprofesor";
$profemateria = mysqli_query($link, $profemateria);

if ($profemateria) {
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
	<link rel="stylesheet" href="main.css">
	<title><?php echo $titulo ?></title>
</head>

<body>
	<nav></nav>
	<div class="vercurso">
		<a href="./cursos.php">Volver...</a>
		<h3><?php echo $escuela; ?></h3>
		<h2><?php echo $materia[2]; ?></h2>
		<h3><?php echo "$curso[2]° $curso[3]ª $curso[4]"; ?></h3>
		<br>
		<table>
			<tr class="acciones">
				<td>
					<a class="button" href='<?php echo "asistencia.php?materia=$idmateria"; ?>'>Asistencia</a>
				</td>
				<td>
					<a class="button" href='<?php echo "calificaciones.php?materia=$idmateria"; ?>'>Calificaciones</a>
				</td>
			</tr>
		</table>
		<br><hr>
		<table>
			<!-- Alumnos -->
			<tr>
				<th><h3>Alumnos<h3></th>
			</tr>		
			<?php while ($r = mysqli_fetch_array($alumnos)) {
				echo "<tr><td> - " . strtoupper($r[3]) . ", $r[2]</td><td></td></tr>";
			} ?>
		</table>
	</div>
</body>

</html>