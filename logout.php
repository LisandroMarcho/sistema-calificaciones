<?php
session_start();
if(isset($_SESSION["idprofesor"])) session_destroy();
header('Location: index.php');