<?php
session_start();
include_once("conx.php");

verifySession();

$idprofesor = $_SESSION["idprofesor"];

$escuelas = "SELECT e.* 
FROM escuelas e, profesores p, materiasprofesores mp, materias m, cursos c
WHERE p.idprofesor = 1
AND p.idprofesor = mp.idprofesor
AND mp.idmateria = m.idmateria
AND c.idcurso = m.idcurso
AND e.idescuela = c.idescuela
GROUP BY idescuela";
$escuelas = mysqli_query($link, $escuelas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cursos</title>
	<link rel="stylesheet" href="main.css">
</head>
<body>
	<nav></nav>
	
	<div class="cursos">
		<h2>Cursos</h2>
		<?php
			while($r = mysqli_fetch_array($escuelas)){
				echo "<table>";
				echo "<tr class='cursos-nombre-escuela'><th>$r[1]</th></tr>";
				
				$materias = "SELECT m.* FROM materias m, cursos c 
							WHERE m.idcurso = c.idcurso AND c.idescuela = $r[0]";
				$materias = mysqli_query($link, $materias);
				while($materia = mysqli_fetch_array($materias)){
					$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
					$curso = mysqli_query($link, $curso);
					$curso = mysqli_fetch_array($curso);
					echo "<tr><td><a href='vercurso.php?idmateria=$materia[0]'>$curso[2]° $curso[3]ª $curso[4] - $materia[2]</a></td></tr>";
				}
				echo "</table>";
			}
		?>
	</div>
</body>
</html>