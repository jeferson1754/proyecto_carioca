<?php
include('bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    if (!empty($nombre)) {
        $sql = "INSERT INTO jugadores (nombre) VALUES ('$nombre')";
        if ($conn->query($sql) === TRUE) {
            echo "Nuevo jugador registrado exitosamente.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "El nombre del jugador no puede estar vacío.";
    }
}

header("Location: gestion_jugadores.php");