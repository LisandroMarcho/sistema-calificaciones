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

	$titulo = "$materia[2] - $curso[2]° $curso[3]ª $curso[4]";
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
	<h3>Alumnos</h3>
	
</body>
</html>