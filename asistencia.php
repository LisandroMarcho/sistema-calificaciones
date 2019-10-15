<?php
session_start();
include_once("conx.php");

verifySession();

if (isset($_GET["materia"]))
	$idmateria = $_GET["materia"];
else header('Location: cursos.php');

$sesionValida = "SELECT count(*) FROM materiasprofesores WHERE idmateria = $idmateria AND idprofesor = " . $_SESSION["idprofesor"];
$sesionValida = mysqli_query($link, $sesionValida);
if(mysqli_fetch_array($sesionValida)[0] == 0) header('Location: cursos.php');

if (isset($_POST["presentes"])) {
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

if (isset($_POST["cambiar"])) {
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

if (isset($_GET["fecha"]))
	$fechahoy = $_GET["fecha"];
else $fechahoy = date("Y-m-d");

$materia = "SELECT * FROM materias WHERE idmateria = $idmateria";
$materia = mysqli_fetch_array(mysqli_query($link, $materia));

$horarios = "SELECT * FROM horarios WHERE idmateria = $idmateria";
$horarios = mysqli_query($link, $horarios);

if (isset($_GET["horario"]))
	$idhorario = $_GET["horario"];
else $idhorario = mysqli_fetch_array(mysqli_query($link, "SELECT idhorario FROM horarios WHERE idmateria = $idmateria LIMIT 1"))[0];

$alumnos = "SELECT * FROM alumnos WHERE idcurso = $materia[1] ORDER BY ape ASC";
$alumnos = mysqli_query($link, $alumnos);

$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
$curso = mysqli_fetch_array(mysqli_query($link, $curso));

$escuela = "SELECT nom FROM escuelas WHERE idescuela = $curso[1]";
$escuela = mysqli_fetch_array(mysqli_query($link, $escuela))[0];

$query = "SELECT * FROM asistencias WHERE idmateria = $idmateria AND idhorario = $idhorario AND fecha = '$fechahoy' ORDER BY idasistencia";
$query = mysqli_query($link, $query);

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Asistencia</title>
	<link rel="stylesheet" href="main.css">
	<style>
		th,
		td {
			height: 30px;
			min-width: 120px;
		}

		td,
		label {
			padding: 0 5px 0 5px;
		}
	</style>
</head>

<body>
	<nav></nav>
	<div class="formulario">
		<a href="./vercurso.php?idmateria=<?php echo $materia[0]; ?>">Volver...</a>
		<h3><?php echo $escuela; ?></h3>
		<h2><?php echo $materia[2]; ?></h2>
		<h3><?php echo "$curso[2]° $curso[3]ª $curso[4]"; ?></h3>
		<form method="GET">
			<input type="hidden" name="materia" value=<?php echo $idmateria; ?>>
			<input type="date" name="fecha" value="<?php echo $fechahoy; ?>" onchange="this.form.submit()">
			<select name="horario" onchange="this.form.submit();">
				<?php
				while ($r = mysqli_fetch_array($horarios))
					echo "<option value='$r[0]'" . ($idhorario == $r[0] ? " selected" : "") . ">$r[2] - $r[3]</option>";
				?>
			</select>
		</form>
		<button onclick="sinClases();">Sin clases</button>
		<button onclick="invertir();">Invertir</button>
		<form method="POST">
			<input type="hidden" name="materia" value=<?php echo $idmateria; ?>>
			<input type="hidden" name="fecha" value="<?php echo $fechahoy; ?>">
			<input type="hidden" name="horario" value=<?php echo $idhorario; ?>>
			<table border="1">
				<tr>
					<th>Presente</th>
					<th>Alumno</th>
					<th>Observaciones</th>
				</tr>
				<?php

				if (mysqli_num_rows($query) > 0) {
					while ($r = mysqli_fetch_array($query)) {
						$alumno = "SELECT * FROM alumnos WHERE idalumno = $r[1]";
						$alumno = mysqli_fetch_array(mysqli_query($link, $alumno));
						echo "<tr>
								<td class='no-sec' style='background: red' onclick='cambiarEstado(this);'>
									<input type=hidden name=cambiar[] value='$r[5]'>
									<label>Ausente</label>
								</td> 
								<td>
									" . strtoupper($alumno[3]) . ", $alumno[2]
									<input type='hidden' value=$alumno[0] name=alumnos[]>
									<input type='hidden' value=$r[0] name=asistencias[]>
								</td>
								<td style='text-aling: center'>
									<svg class='svg' viewBox='0 0 20 20' onclick='abrirObservacion($alumno[0]);'>
										<path d='M17.657,2.982H2.342c-0.234,0-0.425,0.191-0.425,0.426v10.21c0,0.234,0.191,0.426,0.425,0.426h3.404v2.553c0,0.397,0.48,0.547,0.725,0.302l2.889-2.854h8.298c0.234,0,0.426-0.191,0.426-0.426V3.408C18.083,3.174,17.892,2.982,17.657,2.982M17.232,13.192H9.185c-0.113,0-0.219,0.045-0.3,0.124l-2.289,2.262v-1.96c0-0.233-0.191-0.426-0.425-0.426H2.767V3.833h14.465V13.192z M10,7.237c-0.821,0-1.489,0.668-1.489,1.489c0,0.821,0.668,1.489,1.489,1.489c0.821,0,1.488-0.668,1.488-1.489C11.488,7.905,10.821,7.237,10,7.237 M10,9.364c-0.352,0-0.638-0.288-0.638-0.638c0-0.351,0.287-0.638,0.638-0.638c0.351,0,0.638,0.287,0.638,0.638C10.638,9.077,10.351,9.364,10,9.364 M14.254,7.237c-0.821,0-1.489,0.668-1.489,1.489c0,0.821,0.668,1.489,1.489,1.489s1.489-0.668,1.489-1.489C15.743,7.905,15.075,7.237,14.254,7.237 M14.254,9.364c-0.351,0-0.638-0.288-0.638-0.638c0-0.351,0.287-0.638,0.638-0.638c0.352,0,0.639,0.287,0.639,0.638C14.893,9.077,14.605,9.364,14.254,9.364 M5.746,7.237c-0.821,0-1.489,0.668-1.489,1.489c0,0.821,0.668,1.489,1.489,1.489c0.821,0,1.489-0.668,1.489-1.489C7.234,7.905,6.566,7.237,5.746,7.237 M5.746,9.364c-0.351,0-0.638-0.288-0.638-0.638c0-0.351,0.287-0.638,0.638-0.638c0.351,0,0.638,0.287,0.638,0.638C6.384,9.077,6.096,9.364,5.746,9.364'></path>
									</svg>
								</td>
							</tr>
							<div class='comment' id='ob$alumno[0]'>
								<input type='text' placeholder='Observaciones...' name='observaciones[]' value='$r[6]'>
								<button class='button' onclick='cerrarObservacion(this);'>Cerrar</button>
							</div>
							";
					}
				} else while ($r = mysqli_fetch_array($alumnos))
					echo "<tr>
								<td class='no-sec' style='background: red' onclick='cambiarEstado(this);'>
									<input type=hidden name=presentes[] value='a'>
									<label>Ausente</label>
								</td> 
								<td>
									" . strtoupper($r[3]) . ", $r[2]
									<input type='hidden' value=$r[0] name=alumnos[]>
								</td>
								<td style='text-aling: center'>
									<svg class='svg' viewBox='0 0 20 20' onclick='abrirObservacion($r[0]);'>
										<path d='M17.657,2.982H2.342c-0.234,0-0.425,0.191-0.425,0.426v10.21c0,0.234,0.191,0.426,0.425,0.426h3.404v2.553c0,0.397,0.48,0.547,0.725,0.302l2.889-2.854h8.298c0.234,0,0.426-0.191,0.426-0.426V3.408C18.083,3.174,17.892,2.982,17.657,2.982M17.232,13.192H9.185c-0.113,0-0.219,0.045-0.3,0.124l-2.289,2.262v-1.96c0-0.233-0.191-0.426-0.425-0.426H2.767V3.833h14.465V13.192z M10,7.237c-0.821,0-1.489,0.668-1.489,1.489c0,0.821,0.668,1.489,1.489,1.489c0.821,0,1.488-0.668,1.488-1.489C11.488,7.905,10.821,7.237,10,7.237 M10,9.364c-0.352,0-0.638-0.288-0.638-0.638c0-0.351,0.287-0.638,0.638-0.638c0.351,0,0.638,0.287,0.638,0.638C10.638,9.077,10.351,9.364,10,9.364 M14.254,7.237c-0.821,0-1.489,0.668-1.489,1.489c0,0.821,0.668,1.489,1.489,1.489s1.489-0.668,1.489-1.489C15.743,7.905,15.075,7.237,14.254,7.237 M14.254,9.364c-0.351,0-0.638-0.288-0.638-0.638c0-0.351,0.287-0.638,0.638-0.638c0.352,0,0.639,0.287,0.639,0.638C14.893,9.077,14.605,9.364,14.254,9.364 M5.746,7.237c-0.821,0-1.489,0.668-1.489,1.489c0,0.821,0.668,1.489,1.489,1.489c0.821,0,1.489-0.668,1.489-1.489C7.234,7.905,6.566,7.237,5.746,7.237 M5.746,9.364c-0.351,0-0.638-0.288-0.638-0.638c0-0.351,0.287-0.638,0.638-0.638c0.351,0,0.638,0.287,0.638,0.638C6.384,9.077,6.096,9.364,5.746,9.364'></path>
									</svg>
								</td>
							</tr>
							<div class='comment' id='ob$r[0]'>
								<input type='text' placeholder='Observaciones...' name='observaciones[]' value=' '>
								<button class='button' onclick='cerrarObservacion(this);'>Cerrar</button>
							</div>";
				?>
			</table>
			<input type="submit" value="Guardar asistencia">
		</form>
	</div>
	
	

	<script>
		window.onload = pintarEstados();

		function sinClases() {
			let inputs = document.querySelectorAll('input[name="presentes[]"]');
			if (inputs.length < 1)
				inputs = document.querySelectorAll('input[name="cambiar[]"]');
			inputs.forEach(input => {
				input.value = "s";
				input.parentElement.style.background = "grey";
				input.nextElementSibling.innerText = "Sin clases";
			});
		}

		function invertir() {
			let inputs = document.querySelectorAll('input[name="presentes[]"]');
			if (inputs.length < 1)
				inputs = document.querySelectorAll('input[name="cambiar[]"]');
			inputs.forEach(input => {
				if(input.value == "s" || input.value == "a"){
					input.value = "p";
					input.parentElement.style.background = "green";
					input.nextElementSibling.innerText = "Presente";
				}else if(input.value == "p"){
					input.value = "a";
					input.parentElement.style.background = "red";
					input.nextElementSibling.innerText = "Ausente";
				}
			});
		}

		function pintarEstados() {
			let inputs = document.querySelectorAll('input[name="cambiar[]"]');
			inputs.forEach(input => {
				if (input.value == "p") {
					input.parentElement.style.background = "green";
					input.nextElementSibling.innerText = "Presente";
				} else if (input.value == "t") {
					input.parentElement.style.background = "blue";
					input.nextElementSibling.innerText = "Tarde";
				} else if (input.value == "s") {
					input.parentElement.style.background = "grey";
					input.nextElementSibling.innerText = "Sin Clases";
				}
			});
		}

		function cambiarEstado(td) {
			let input = td.children[0];
			let label = td.children[1];

			if (input.value == "a") {
				td.style.background = "green";
				input.value = "p";
				label.innerText = "Presente";
			} else if (input.value == "p") {
				td.style.background = "blue";
				input.value = "t";
				label.innerText = "Tarde";
			} else if (input.value == "t") {
				td.style.background = "red";
				input.value = "a";
				label.innerText = "Ausente";
			}
		}

		function abrirObservacion(ob) {
			let div = document.getElementById(`ob${ob}`);
			div.style.display = "block";
		}

		function cerrarObservacion(btn) {
			event.preventDefault();
			console.log(btn.parentElement);
			btn.parentElement.style.display = "none";
		}
	</script>
</body>

</html>