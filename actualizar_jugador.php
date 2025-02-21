<?php
include('bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    if (!empty($id)) {
        $sql = "UPDATE `jugadores` SET nombre='$nombre' WHERE id='$id'";
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