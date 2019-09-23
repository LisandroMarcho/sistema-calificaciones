<?php
session_start();
include_once("conx.php");
verifySession();

$idmateria = $_GET["materia"];

$materia = "SELECT * FROM materias WHERE idmateria = $idmateria";
$materia = mysqli_fetch_array(mysqli_query($link, $materia));

$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
$curso = mysqli_fetch_array(mysqli_query($link, $curso));

$alumnos = "SELECT * FROM alumnos WHERE idcurso = $curso[0] ORDER BY ape ASC";
$alumnos = mysqli_query($link, $alumnos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cargar calificaciones</title>
	<style>
		.nom {
			width: 200px;
			word-wrap: break-word;
		}
	</style>
</head>
<body>
	<h2><?php echo $materia["nom"]; ?></h2>	
	<h2><?php echo "$curso[2]° $curso[3]ª $curso[4]"; ?></h2>

	<table border="1">
		<tr>
			<th>Alumno</th>
			<th>Nota 1</th>
			<th>Nota 2</th>
			<th>Nota 3</th>
			<th>Promedio</th>
			<th>Asistencia</th>
		</tr>
		<?php
		while ($alumno = mysqli_fetch_array($alumnos)) {
			$asistencia = "SELECT * FROM asistencias WHERE idalumno = $alumno[0] AND fecha BETWEEN '2019-9-7' AND '2019-11-30'";
			$asistencia = mysqli_query($link, $asistencia);

			$presente = 0;
			$numAsistencias = mysqli_num_rows($asistencia);
			while($r = mysqli_fetch_array($asistencia)){
				if($r[5] == 'p') $presente++;
				else if($r[5]=='t') $presente += 0.5;
			}

			$promedioAsistencia = number_format(($presente / $numAsistencias)*100, 2);

			echo "<tr>
					<td class='nom'><label>".strtoupper($alumno[3]).", $alumno[2]</label></td>
					<td><input type='number' name='notauno[]' style='width: 50px' onclick='calcularpromedio(this, 1)'></td>
					<td><input type='number' name='notados[]' style='width: 50px' onclick='calcularpromedio(this, 2)'></td>
					<td><input type='number' name='notatres[]' style='width: 50px' onclick='calcularpromedio(this, 3)'></td>
					<td><input type='text' name='promedio[]' readonly style='width: 70px'></td>
					<td><input type='text' name='asistencia[]' style='width: 70px' readonly value='$promedioAsistencia%'></td>
			      </tr>";
		}		
		?>
	</table>

	<script>
		function calcularpromedio(input, ubi){
			nota = 0;
			switch(ubi){
			case 1:
				nota += input.value;
				nota += input.parentElement.nextElementSibling.children[0].value;
				nota += input.parentElement.nextElementSibling.nextElementSibling.children[0].value;
				input.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0].value; = nota / 3;
				break;
			}
		}
	</script>
</body>
</html>