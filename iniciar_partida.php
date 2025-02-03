<?php
include 'bd.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jugadores'])) {
    $jugadores = $_POST['jugadores']; // Array con IDs de jugadores seleccionados

    try {
        $connect->beginTransaction(); // Iniciar transacción

        // Insertar nueva partida
        $stmt = $connect->prepare("INSERT INTO partidas () VALUES ()");
        $stmt->execute();
        $id_partida = $connect->lastInsertId(); // Obtener el ID de la partida creada

        $id_juego = 1;

        // Insertar jugadores en detalles_partidas
        $stmt = $connect->prepare("INSERT INTO detalles_partida (partida_id, jugador_id, tipo_juego_id) VALUES (?, ?, ?)");
        foreach ($jugadores as $id_jugador) {
            $stmt->execute([$id_partida, $id_jugador, $id_juego]);
        }

        $connect->commit(); // Confirmar transacción
        echo "Partida creada exitosamente con los jugadores seleccionados.";
    } catch (Exception $e) {
        $connect->rollBack(); // Revertir cambios en caso de error
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se seleccionaron jugadores.";
}
