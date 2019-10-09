<?php
session_start();
include_once("conx.php");
verifySession();

$fechasPeriodo = array(
	"'2019-03-14' AND '2019-05-30'",
	"'2019-06-01' AND '2019-08-30'",
	"'2019-09-01' AND '2019-11-30'");

if(isset($_POST["promedio"])){
	$idmateria = $_POST["materia"];
	$periodo = $_POST["periodo"];

	foreach ($_POST["promedio"] as $key => $promedio) {
		$asistencia = $_POST["asistencia"][$key];
		$notauno = $_POST["notauno"][$key];
		$notados = $_POST["notados"][$key];
		$notatres = $_POST["notatres"][$key];
		$idalumno = $_POST["alumno"][$key];

		$calificacion = "INSERT INTO calificaciones (idalumno, idmateria, periodo, notauno, notados, notatres, promedio, asistencia) VALUES ($idalumno, $idmateria, '$periodo', '$notauno', '$notados', '$notatres', '$promedio', '$asistencia')";
		$calificacion = mysqli_query($link, $calificacion);
	}
}

if(isset($_POST["cambiar"])){
	$idmateria = $_POST["materia"];
	$periodo = $_POST["periodo"];

	foreach ($_POST["cambiar"] as $key => $promedio) {
		$asistencia = $_POST["asistencia"][$key];
		$notauno = $_POST["notauno"][$key];
		$notados = $_POST["notados"][$key];
		$notatres = $_POST["notatres"][$key];
		$idalumno = $_POST["alumno"][$key];

		$calificacion = "UPDATE calificaciones SET notauno = '$notauno', notados = '$notados', notatres = '$notatres', promedio = '$promedio', asistencia = '$asistencia' WHERE idalumno = $idalumno";
		$calificacion = mysqli_query($link, $calificacion);
	}
}

$idmateria = $_GET["materia"];

if(isset($_GET["periodo"])) $periodo = $_GET["periodo"];
else $periodo = 1;

$materia = "SELECT * FROM materias WHERE idmateria = $idmateria";
$materia = mysqli_fetch_array(mysqli_query($link, $materia));

$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
$curso = mysqli_fetch_array(mysqli_query($link, $curso));

$alumnos = "SELECT * FROM alumnos WHERE idcurso = $curso[0] ORDER BY ape ASC";
$alumnos = mysqli_query($link, $alumnos);

$query = "SELECT * FROM calificaciones WHERE idmateria = $materia[0] AND periodo = '$periodo'";
$query = mysqli_query($link, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cargar calificaciones</title>
	<link rel="stylesheet" type="text/css" href="main.css">
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
	
	<form method="GET">
		<input type="hidden" name="materia" value="<?php echo $materia[0] ?>">
		<select name="periodo" onchange="this.form.submit();">
			<option value="1" <?php if($periodo == 1) echo "selected"?>>
				Primer Trimestre
			</option>
			<option value="2" <?php if($periodo == 2) echo "selected"?>>
				Segundo Trimestre
			</option>
			<option value="3" <?php if($periodo == 3) echo "selected"?>>
				Tercer Trimestre
			</option>
		</select>
	</form>
	<form method="POST">
		<input type="hidden" name="materia" value="<?php echo $materia[0] ?>">
		<input type="hidden" name="periodo" value="<?php echo $periodo; ?>">
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
			if(mysqli_num_rows($query) > 0){
				while($r = mysqli_fetch_array($query)){
					$alumno = "SELECT * FROM alumnos WHERE idalumno = $r[1]";
					$alumno = mysqli_fetch_array(mysqli_query($link, $alumno));
					echo "<tr>
						<input type='hidden' name='alumno[]' value='$alumno[0]'>
						<td class='nom'><label>".strtoupper($alumno[3]).", $alumno[2]</label></td>
						<td><input type='number' name='notauno[]' style='width: 50px' onchange='calcularpromedio(this)' value='$r[4]' required></td>
						<td><input type='number' name='notados[]' style='width: 50px' onchange='calcularpromedio(this)' value='$r[5]' required></td>
						<td><input type='number' name='notatres[]' style='width: 50px' onchange='calcularpromedio(this)' value='$r[6]' required></td>
						<td><input type='text' name='cambiar[]' value='$r[7]' readonly style='width: 70px'></td>
						<td><input type='text' name='asistencia[]' style='width: 70px' readonly value='$r[8]%'></td>
				      </tr>";
				}
			}
			else while ($alumno = mysqli_fetch_array($alumnos)) {
				$indiceAsistencia = $periodo - 1;
				$asistencia = "SELECT * FROM asistencias WHERE idalumno = $alumno[0] AND idmateria = $materia[0] AND estado != 's' AND fecha BETWEEN " . $fechasPeriodo[$indiceAsistencia];
				echo "<script>console.log(`$asistencia`)</script>";
				$asistencia = mysqli_query($link, $asistencia);

				$presente = 0;
				$numAsistencias = mysqli_num_rows($asistencia);
				while($r = mysqli_fetch_array($asistencia)){
					if($r[5] == 'p') $presente++;
					else if($r[5]=='t') $presente += 0.5;
				}
				if($numAsistencias > 0)
					$promedioAsistencia = number_format(($presente / $numAsistencias)*100, 2);
				else $promedioAsistencia = 0;

				echo "<tr>
						<input type='hidden' name='alumno[]' value='$alumno[0]'>
						<td class='nom'><label>".strtoupper($alumno[3]).", $alumno[2]</label></td>
						<td><input type='number' name='notauno[]' style='width: 50px' onchange='calcularpromedio(this)' value='0' required></td>
						<td><input type='number' name='notados[]' style='width: 50px' onchange='calcularpromedio(this)' value='0' required></td>
						<td><input type='number' name='notatres[]' style='width: 50px' onchange='calcularpromedio(this)' value='0' required></td>
						<td><input type='text' name='promedio[]' readonly style='width: 70px'></td>
						<td><input type='text' name='asistencia[]' style='width: 70px' readonly value='$promedioAsistencia%'></td>
				      </tr>";
			}		
			?>
		</table>
		<input type="submit" value="Guardar calificaciones">
	</form>

	<script>
		function calcularpromedio(input){
			let nota = 0;
			switch(input.name){
			case 'notauno[]':
				nota += parseInt(input.value);
				let n = input.parentElement.nextElementSibling.children[0];
				nota += parseInt(n.value);
				n = n.parentElement.nextElementSibling.children[0];
				nota += parseInt(n.value);
				nota = nota / 3;
				n.parentElement.nextElementSibling.children[0].value = nota.toFixed(2);
				break;
			case 'notados[]':
				nota += parseInt(input.value);
				let ne = input.parentElement.previousElementSibling.children[0];
				nota += parseInt(ne.value);
				ne = ne.parentElement.nextElementSibling.nextElementSibling.children[0];
				nota += parseInt(ne.value);
				nota = nota / 3;
				ne.parentElement.nextElementSibling.children[0].value = nota.toFixed(2);
				break;
			case 'notatres[]':
				nota += parseInt(input.value);
				let nex = input.parentElement.previousElementSibling.children[0];
				nota += parseInt(nex.value);
				nex = nex.parentElement.previousElementSibling.children[0];
				nota += parseInt(nex.value);
				nota = nota / 3;
				nex.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0].value = nota.toFixed(2);
				break;
			}
		}
	</script>
</body>
</html>