<?php
session_start();
include_once("conx.php");

verifySession();

$idprofesor = $_SESSION["idprofesor"];

$idmaterias = "SELECT idmateria FROM materiasprofesores WHERE idprofesor = $idprofesor";
$idmaterias = mysqli_query($link, $idmaterias);

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cursos</title>
</head>
<body>
	<ul>
	<?php
		while($r = mysqli_fetch_array($idmaterias)[0]){
			$materia = "SELECT * FROM materias WHERE idmateria = $r";
			$materia = mysqli_query($link, $materia);
			$materia = mysqli_fetch_array($materia);

			$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
			$curso = mysqli_query($link, $curso);
			$curso = mysqli_fetch_array($curso);

			$escuela = "SELECT * FROM escuelas WHERE idescuela = $curso[1]";
			$escuela = mysqli_query($link, $escuela);
			$escuela = mysqli_fetch_array($escuela);

			echo "<li><a href='vercurso.php?idcurso=$curso[0]&idmateria=$materia[0]'>$materia[2] - $curso[2]° $curso[3]ª $curso[4]</a> - $escuela[1]</li>";
		}
	?>
	</ul>
</body>
</html>