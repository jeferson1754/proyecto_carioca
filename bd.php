<?php

$usuario  = "root";
$password = "";
$servidor = "localhost";
$basededatos = "carioca_game";
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
mysqli_query($conexion, "SET SESSION collation_connection ='utf8_unicode_ci'");
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Error en conectar a la Base de Datos");

try {
    $connect = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


$conn = new mysqli($servidor, $usuario, $password, $basededatos);

//Linea para los caracteres �

if (!mysqli_set_charset($conexion, "utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
    exit();
}

if (mysqli_connect_errno()) {
    die("No se pudo conectar a la base de datos: " . mysqli_connect_error());
}
