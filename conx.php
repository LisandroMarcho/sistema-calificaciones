<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sistema";

if($link = mysqli_connect($host, $user, $pass, $db))
	mysqli_set_charset($link, 'utf8');
else http_response_code(500);

date_default_timezone_set("America/Argentina/Buenos_Aires");
?>