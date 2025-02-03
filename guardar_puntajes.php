<?php
include 'bd.php'; // Asegúrate de incluir tu conexión ($conn)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['puntajes'], $_POST['id_partida'], $_POST['id_juego'])) {
    $id_partida = $_POST['id_partida'];
    $id_juego = $_POST['id_juego'];

    $id_juego_new = $id_juego + 1;

    try {
        // Verificar si el id_partida existe en la tabla partidas
        $sql_verificar_partida = "SELECT COUNT(*) FROM partidas WHERE id = :id_partida";
        $stmt_verificar = $connect->prepare($sql_verificar_partida);
        $stmt_verificar->execute(['id_partida' => $id_partida]);
        $count = $stmt_verificar->fetchColumn();

        if ($count == 0) {
            // Si el id_partida no existe, muestra un error y detén la ejecución
            throw new Exception("El id_partida proporcionado no existe en la tabla partidas.");
        }

        // Preparar la consulta para insertar los puntajes en detalles_partida
        $sql = "INSERT INTO detalles_partida (partida_id, jugador_id, tipo_juego_id, puntaje) 
                VALUES (:id_partida, :id_jugador, :tipo_juego, :puntaje)";
        $stmt = $connect->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error en la preparación de la consulta: " . implode(", ", $connect->errorInfo()));
        }

        // Insertar los puntajes
        foreach ($_POST['puntajes'] as $id_jugador => $nuevo_puntaje) {
            $stmt->execute([
                'id_partida' => $id_partida,
                'id_jugador' => $id_jugador,
                'tipo_juego' => $id_juego,
                'puntaje' => $nuevo_puntaje
            ]);
        }

        // Preparar la consulta para insertar los puntajes en detalles_partida
        $sql = "INSERT INTO detalles_partida (partida_id, jugador_id, tipo_juego_id, puntaje) 
            VALUES (:id_partida, :id_jugador, :tipo_juego, :puntaje)";
        $stmt = $connect->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error en la preparación de la consulta: " . implode(", ", $connect->errorInfo()));
        }

        // Insertar los puntajes
        foreach ($_POST['puntajes'] as $id_jugador => $nuevo_puntaje) {
            $stmt->execute([
                'id_partida' => $id_partida,
                'id_jugador' => $id_jugador,
                'tipo_juego' => $id_juego_new,
                'puntaje' => 0
            ]);
        }
    } catch (Exception $e) {
        // Captura el error y lo muestra
        echo "Error: " . $e->getMessage();
        exit();
    }
}

header("Location: partida.php"); // Redirige de vuelta
exit();
