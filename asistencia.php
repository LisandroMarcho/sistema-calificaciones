<?php
session_start();
include_once("conx.php");

verifySession();

if(isset($_POST["presentes"])) {
	$fecha = $_POST["fecha"];
	$idmateria = $_POST["materia"];
	$idhorario = $_POST["horario"];
	
	foreach ($_POST["presentes"] as $key => $estado) {
		$idalumno = $_POST["alumnos"][$key];
		$observacion = $_POST["observaciones"][$key];
		$carga = "INSERT INTO asistencias (idalumno, idmateria, idhorario, fecha, estado, observaciones) VALUES ($idalumno, $idmateria, $idhorario,'$fecha', '$estado', '$observacion')";	
		$consulta = mysqli_query($link, $carga);
	}
}

if(isset($_POST["cambiar"])){
	$fecha = $_POST["fecha"];
	$idmateria = $_POST["materia"];
	$idhorario = $_POST["horario"];
	
	foreach ($_POST["cambiar"] as $key => $estado) {
		$idasistencia = $_POST["asistencias"][$key];
		$idalumno = $_POST["alumnos"][$key];
		$observacion = $_POST["observaciones"][$key];
		$cambio = "UPDATE asistencias SET estado = '$estado', observaciones = '$observacion' WHERE idasistencia = $idasistencia";	
		$consulta = mysqli_query($link, $cambio);
		//if($consulta) echo "<script>console.log(`$query`);</script>";
	}
}

if(isset($_GET["fecha"]))
	$fechahoy = $_GET["fecha"];
else $fechahoy = date("Y-m-d");

if(isset($_GET["materia"]))
	$idmateria = $_GET["materia"];
else header('Location: cursos.php');

$materia = "SELECT * FROM materias WHERE idmateria = $idmateria";
$materia = mysqli_fetch_array(mysqli_query($link, $materia));

$horarios = "SELECT * FROM horarios WHERE idmateria = $idmateria";
$horarios = mysqli_query($link, $horarios);

if(isset($_GET["horario"]))
	$idhorario = $_GET["horario"];
else $idhorario = mysqli_fetch_array(mysqli_query($link, "SELECT idhorario FROM horarios WHERE idmateria = $idmateria LIMIT 1"))[0];

$alumnos = "SELECT * FROM alumnos WHERE idcurso = $materia[1] ORDER BY ape ASC";
$alumnos = mysqli_query($link, $alumnos);


$query = "SELECT * FROM asistencias WHERE idmateria = $idmateria AND idhorario = $idhorario AND fecha = '$fechahoy'";
$query = mysqli_query($link, $query);

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
	<h2 style="display: inline-block; margin-right: 10px;"><?php echo $materia["nom"];	 ?></h2>
	<a href="./vercurso.php?idmateria=<?php echo $materia[0]; ?>">Volver...</a>
	<form method="GET">
		<input type="hidden" name="materia" value=<?php echo $idmateria; ?>>
		<input type="date" name="fecha" style="margin-left: 10px;"
		value="<?php echo $fechahoy; ?>" onchange="this.form.submit()">
		<select name="horario" onchange="this.form.submit();">
			<?php
				while($r = mysqli_fetch_array($horarios))
					echo "<option value='$r[0]'". ($idhorario == $r[0] ? " selected" : "" ) .">$r[2] - $r[3]</option>";
			?>
		</select>
	</form>
	<button onclick="sinClases();" style="margin-top: 10px">Sin clases</button>
	<form method="POST">
		<input type="hidden" name="materia" value=<?php echo $idmateria; ?>>
		<input type="hidden" name="fecha" value="<?php echo $fechahoy; ?>">
		<input type="hidden" name="horario" value=<?php echo $idhorario; ?>>
		<table border="1" style="margin-top: 10px;">
			<tr>
				<th>Presente</th>
				<th>Alumno</th>
				<th>Observaciones</th>
			</tr>
			<?php

				if(mysqli_num_rows($query) > 0){
					while($r = mysqli_fetch_array($query)){
						$alumno = "SELECT * FROM alumnos WHERE idalumno = $r[1]";
						$alumno = mysqli_fetch_array(mysqli_query($link, $alumno));
						echo "<tr>
							<td style='background: red' onclick='cambiarEstado(this);'>
								<input type=hidden name=cambiar[] value='$r[5]'>
								<label>Ausente</label>
							</td> 
							<td>
								".strtoupper($alumno[3]).", $alumno[2]
								<input type='hidden' value=$alumno[0] name=alumnos[]>
								<input type='hidden' value=$r[0] name=asistencias[]>
							</td>
							<td>
								<input type='text' placeholder='Observaciones...' name='observaciones[]' value='$r[6]'>
							</td> 
						  </tr>";
					}
				}
				else while($r = mysqli_fetch_array($alumnos))
					echo "<tr>
							<td style='background: red' onclick='cambiarEstado(this);'>
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
		<input type="submit" value="Guardar asistencia" style="margin-top: 10px">
	</form>

	<script>
		window.onload = pintarEstados();

		function sinClases(){
			let inputs = document.querySelectorAll('input[name="presentes[]"]');
			if(inputs.length < 1) 
				inputs = document.querySelectorAll('input[name="cambiar[]"]');
			inputs.forEach(input => {
				input.value = "s";
				input.parentElement.style.background = "grey";
				input.nextElementSibling.innerText = "Sin clases";
			});
		}

		function pintarEstados(){
			let inputs = document.querySelectorAll('input[name="cambiar[]"]');
			inputs.forEach(input => {
				if (input.value == "p") {
					input.parentElement.style.background = "green";
					input.nextElementSibling.innerText = "Presente";
				} else if(input.value == "t"){
					input.parentElement.style.background = "blue";
					input.nextElementSibling.innerText = "Tarde";
				} else if(input.value == "s"){
					input.parentElement.style.background = "grey";
					input.nextElementSibling.innerText = "Sin Clases";
				}
			});
		}

		function cambiarEstado(td){
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