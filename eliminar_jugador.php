<?php
include('bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!empty($id)) {
        $sql = "DELETE FROM jugadores WHERE id='$id'";
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