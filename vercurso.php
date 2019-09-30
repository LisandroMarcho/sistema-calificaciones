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
	<a href=<?php echo "asistencia.php?materia=$idmateria";?>>Asistencia</a>
	<a href=<?php echo "calificacion.php?materia=$idmateria";?>>Calificaciones</a>
	<h3>Alumnos</h3>
	<ul>
		<?php while($r = mysqli_fetch_array($alumnos)) {
			echo "<li>".strtoupper($r[3]).", $r[2]</li>";
		}?>
	</ul>
</body>
</html>