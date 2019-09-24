<?php
session_start();
include_once("conx.php");

verifySession();

$fechahoy = date("Y-m-d");

$idmateria = $_GET["materia"];
$idhorario = $_GET["horario"];

$materia = "SELECT * FROM materias WHERE idmateria = $idmateria";
$materia = mysqli_fetch_array(mysqli_query($link, $materia));

$horarios = "SELECT * FROM horarios WHERE idmateria = $idmateria";
$horarios = mysqli_query($link, $horarios);

$alumnos = "SELECT * FROM alumnos WHERE idcurso = $materia[1]";
$alumnos = mysqli_query($link, $alumnos);

$query = "SELECT * FROM asistencias WHERE idmateria = $idmateria AND idhorario = $idhorario AND fecha = '$fechahoy'";
$query = mysqli_query($link, $query);

if(isset($_POST["presentes"])) {
	$fecha = $_POST["fecha"];
	$idmateria = $_POST["materia"];
	$idhorario = $_POST["horario"];
	
	foreach ($_POST["presentes"] as $key => $estado) {
		$idalumno = $_POST["alumnos"][$key];
		$observacion = $_POST["observaciones"][$key];
		$query = "INSERT INTO asistencias (idalumno, idmateria, idhorario, fecha, estado, observaciones) VALUES ($idalumno, $idmateria, $idhorario,'$fecha', '$estado', '$observacion')";	
		$consulta = mysqli_query($link, $query);
		//if($consulta) echo "<script>console.log(`$query`);</script>";
	}
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Asistencia</title>
	<style>
		th, td {
			height: 30px;
			min-width: 120px;
		}
		td, label { padding: 0 5px 0 5px; }
	</style>
</head>
<body>
	<h2><?php echo $materia["nom"];	 ?></h2>
	<form method="POST">
		<input type="hidden" name="materia" value=<?php echo $_GET["materia"]; ?>>
		<input type="date" name="fecha" style="margin-left: 10px;"
		value="<?php echo $fechahoy; ?>">
		<select name="horario" value=<?php echo $_GET["horario"] ?>>
			<?php
				while($r = mysqli_fetch_array($horarios)) 
					echo "<option value=$r[0]>$r[2] - $r[3]</option>";
			?>
		</select>
		<table border="1" style="margin-top: 10px;">
			<tr>
				<th>Presente</th>
				<th>Alumno</th>
				<th>Observaciones</th>
			</tr>
			<?php

				while($r = mysqli_fetch_array($alumnos))
					echo "<tr>
							<td style='background: red' onclick='cambiarPresente(this);'>
								<input type=hidden name=presentes[] value='a'>
								<label>Ausente</label>
							</td> 
							<td>
								".strtoupper($r[3]).", $r[2]
								<input type='hidden' value=$r[0] name=alumnos[]>
							</td>
							<td>
								<input type='text' placeholder='Observaciones...' name='observaciones[]' value=' '>
							</td> 
						  </tr>";
			?>
		</table>
		<input type="submit" value="Cargar asistencia">
	</form>

	<script>
		//window.onload = comprobarPresentes();

		function comprobarPresentes(){
			let inputs = document.querySelectorAll('input[name="presentes[]"]');
			inputs.forEach(input => {
				if (input.value == "p") {
					input.parentElement.style.background = "green";
					input.nextElementSibling.innerText = "Presente";
				} else if(input.value == "t"){
					input.parentElement.style.background = "blue";
					input.nextElementSibling.innerText = "Tarde";
				}
			})
		}

		function cambiarPresente(td){
			let input = td.children[0];
			let label = td.children[1];

			if(input.value == "a"){
				td.style.background = "green";
				input.value = "p";
				label.innerText = "Presente";
			}else if(input.value == "p"){
				td.style.background = "blue";
				input.value = "t";
				label.innerText = "Tarde";
			}else if(input.value == "t"){
				td.style.background = "red";
				input.value = "a";
				label.innerText = "Ausente";
			}
		}
	</script>
</body>
</html>