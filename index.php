<?php
session_start();
include_once("conx.php");

if(isset($_SESSION["idprofesor"]))
	header('Location: cursos.php');
else if(isset($_POST["email"]) && isset($_POST["pass"])){
	$email = $_POST["email"];
	$pass = $_POST["pass"];

	$usuario = "SELECT * FROM profesores WHERE email = '$email' LIMIT 1";
	$usuario = mysqli_query($link, $usuario);

	if((mysqli_num_rows($usuario) == 1) && $usuario){
		$usuario = mysqli_fetch_assoc($usuario);
		if(password_verify($pass, $usuario["pass"])){
			$_SESSION["idprofesor"] = $usuario["idprofesor"];
			header('Location: cursos.php');
		}
	}
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="main.css">
	<title>Login</title>
</head>
<body class="login">
	<div class="login-div">
		<h2>Iniciar sesión</h2>
		<h3>¡Bienvenido, profesores!</h3>
		<form method="POST">
			<input type="email" class="login-text" name="email" placeholder="Correo Electrónico" required><br>
			<input type="password" class="login-text" name="pass" placeholder="Contraseña" required><br>
			<input type="submit" class="login-submit"  value="Entrar"><br>
		</form>
	</div>
</body>
</html>