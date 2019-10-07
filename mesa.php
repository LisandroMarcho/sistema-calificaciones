<?php
session_start();
include_once("conx.php");

verifySession();

if(isset($_POST["nota"])){
	$nota = $_POST["nota"];
	$fecha = $_POST["fecha"];
	$idalumno = $_POST["alumno"];
	$idmateria = $_POST["materia"];

	$query = "INSERT INTO mesas (idalumno, idmateria, fecha, nota) VALUES ($idalumno, $idmateria, '$fecha', '$nota')";
	$query = mysqli_query($link, $query);
	echo "<script>window.location = 'vercurso.php?idmateria=$idmateria';</script>";
}

if(isset($_POST["guardar"])){
	$nota = $_POST["guardar"];
	$fecha = $_POST["fecha"];
	$idmateria = $_POST["materia"];
	$idmesa = $_POST["mesa"];

	$query = "UPDATE mesas SET fecha = '$fecha', nota = '$nota' WHERE idmesa = $idmesa";
	$query = mysqli_query($link, $query);
	echo "<script>window.location = 'vercurso.php?idmateria=$idmateria';</script>";
}


if(!isset($_GET["materia"]) && !isset($_GET["mesa"]))
	header("Location: cursos.php");
else {
	if(isset($_GET["materia"])) $idmateria = $_GET["materia"];
	if(isset($_GET["mesa"])) $idmesa = $_GET["mesa"];
}

$fechahoy = date("Y-m-d");

if(isset($idmateria)){
	$materia = "SELECT * FROM materias WHERE idmateria = $idmateria";
	$materia = mysqli_fetch_array(mysqli_query($link, $materia));

	$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
	$curso = mysqli_fetch_array(mysqli_query($link, $curso));

	$alumnos = "SELECT * FROM alumnos WHERE idcurso = $curso[0] ORDER BY ape";
	$alumnos = mysqli_query($link, $alumnos);
}

if(isset($idmesa)){
	$mesa = "SELECT * FROM mesas WHERE idmesa = $idmesa";
	$mesa = mysqli_fetch_array(mysqli_query($link, $mesa));

	$materia = "SELECT * FROM materias WHERE idmateria = $mesa[2]";
	$materia = mysqli_fetch_array(mysqli_query($link, $materia)); 

	$curso = "SELECT * FROM cursos WHERE idcurso = $materia[1]";
	$curso = mysqli_fetch_array(mysqli_query($link, $curso));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Mesa - <?php echo "$materia[2] - $curso[2]° $curso[3]ª $curso[4]"; ?></title>
</head>
<body>
	<h2>Mesa - <?php echo "$materia[2] - $curso[2]° $curso[3]ª $curso[4]"; ?></h2>

	<form method="POST">
		<input type="hidden" name="materia" value="<?php echo $materia[0]; ?>">
		<?php if(isset($mesa)) echo "<input type='hidden' name='mesa' value='$mesa[0]'>"?>
		<label for="alumno" style="font-weight: bold">Alumno</label><br>
			<?php
			if(isset($idmesa)){
				$alumno = "SELECT * FROM alumnos WHERE idalumno = $mesa[1]";
				$alumno = mysqli_fetch_array(mysqli_query($link, $alumno));
				echo "<input type='text' value='".strtoupper($alumno[3]).", $alumno[2]' readonly>";
			}else {
				echo "<select name='alumno'>";
				while($r = mysqli_fetch_array($alumnos)){
					echo "<option value='$r[0]'>".strtoupper($r[3]).", $r[2]</option>";
				}
				echo "<select>";
			}
			?><br>

		<label for="periodo" style="font-weight: bold">Fecha</label><br>
		<input type="date" name="fecha" value="<?php echo (isset($mesa) ? $mesa[3] : $fechahoy); ?>"><br><br>
		<input type="number" name="<?php echo (isset($mesa) ? "guardar" : "nota"); ?>" placeholder="Nota" <?php echo (isset($mesa) ? "value='$mesa[4]'" : "") ?> required><br><br>
		<input type="submit" value="Guardar">
	</form>
</body>
</html>