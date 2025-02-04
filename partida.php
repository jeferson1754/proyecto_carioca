<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Puntuación - Carioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .game-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .player-card {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .probability-badge {
            background: #4ecdc4;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
        }

        .score-input {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 0.5rem;
            text-align: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .score-input:focus {
            border-color: #4ecdc4;
            box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
        }

        .score-high {
            color: #dc3545 !important;
            font-weight: bold;
        }

        .score-low {
            color: #198754 !important;
            font-weight: bold;
        }

        .score-mid {
            color: #6c757d !important;
        }

        .game-controls {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php
    include 'bd.php'; // Conexión a la base de datos

    // Obtener la última partida
    $sql = "SELECT * FROM partidas ORDER BY fecha DESC LIMIT 1";
    $result = $conn->query($sql);
    $partida = $result->fetch_assoc();
    $partida_id = $partida['id'];

    // Obtener detalles de la partida con los jugadores y sus puntajes
    $sql = "SELECT u.id as id_jugador, u.nombre, dp.puntaje, tj.juego, tj.ID as id_juego
        FROM detalles_partida dp
        JOIN jugadores u ON dp.jugador_id = u.id
        JOIN tipo_juego tj ON dp.tipo_juego_id = tj.ID
        WHERE dp.partida_id = $partida_id";
    $result = $conn->query($sql);

    $jugadores = [];
    $juegos = [];

    while ($row = $result->fetch_assoc()) {
        // Agrupar los puntajes por jugador y juego
        $jugadores[$row['id_jugador']]['nombre'] = $row['nombre'];
        $jugadores[$row['id_jugador']]['puntajes'][$row['id_juego']] = $row['puntaje'];

        // Agregar el tipo de juego solo una vez
        if (!in_array($row['juego'], $juegos)) {
            $juegos[] = $row['juego'];
            $juego = $row['juego'];
        }
    }

    // Obtener la cantidad de partidas jugadas y ganadas por cada jugador
    $sql = "SELECT * FROM jugadores;";
    $result = $conn->query($sql);

    // Calcular las probabilidades
    $probabilidades = [];
    while ($row = $result->fetch_assoc()) {
        $jugador_id = $row['id'];
        $partidas_jugadas = $row['partidas_jugadas'];
        $partidas_ganadas = $row['partidas_ganadas'];

        // Calcular la probabilidad de ganar (si se han jugado partidas)
        if ($partidas_jugadas > 0) {
            $probabilidades[$jugador_id] = $partidas_ganadas / $partidas_jugadas;
        } else {
            $probabilidades[$jugador_id] = 0;  // Si no ha jugado partidas, la probabilidad es 0
        }
    }

    // Determinar colores según el puntaje
    $puntajes = array_column($jugadores, 'puntajes');
    $max_puntaje = max(array_map('max', $puntajes));
    $min_puntaje = min(array_map('min', $puntajes));

    $cantidad_juegos = count($juegos);
    ?>

    <div class="container">
        <div class="game-container">
            <h1 class="text-center mb-4">
                <i class="fas fa-dice me-2"></i>
                <?php echo ucfirst(htmlspecialchars($juego)); ?>
            </h1>

            <div class="row mb-4">
                <?php foreach ($jugadores as $id_jugador => $jugador): ?>
                    <div class="col-md-4">
                        <div class="player-card text-center">
                            <h4><?php echo htmlspecialchars($jugador['nombre']); ?></h4>
                            <div class="probability-badge">
                                <i class="fas fa-chart-line me-2"></i>
                                <?php
                                $probabilidad = isset($probabilidades[$id_jugador]) ? $probabilidades[$id_jugador] : 0;
                                echo number_format($probabilidad * 100, 1) . '%';
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="POST" action="guardar_puntajes.php" id="scoreForm">
                <input type="hidden" name="id_partida" value="<?php echo htmlspecialchars($partida_id); ?>">
                <input type="hidden" name="juego" value="<?php echo htmlspecialchars($juego); ?>">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Juego</th>
                                <?php foreach ($jugadores as $jugador): ?>
                                    <th class="text-center"><?php echo htmlspecialchars($jugador['nombre']); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($juegos as $index => $nombre_juego): ?>
                                <tr>
                                    <td class="align-middle"><?php echo htmlspecialchars($nombre_juego); ?></td>
                                    <?php foreach ($jugadores as $id_jugador => $jugador): ?>
                                        <?php
                                        $puntaje = isset($jugador['puntajes'][$index + 1]) ? intval($jugador['puntajes'][$index + 1]) : 0;
                                        ?>
                                        <td>
                                            <input type="number"
                                                class="form-control score-input"
                                                name="puntajes[<?php echo $id_jugador; ?>][<?php echo $index; ?>]"
                                                value="<?php echo $puntaje; ?>"
                                                <?php echo ($index !== count($juegos) - 1) ? 'disabled' : ''; ?>
                                                min="0"
                                                required>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>

                            <tr class="table-light">
                                <td><strong>Total</strong></td>
                                <?php
                                $totales = [];
                                foreach ($jugadores as $id_jugador => $jugador) {
                                    $totales[$id_jugador] = array_sum($jugador['puntajes']);
                                }

                                // Encontrar el puntaje máximo y mínimo
                                $max_puntaje = max($totales);
                                $min_puntaje = min($totales);

                                foreach ($jugadores as $id_jugador => $jugador): ?>
                                    <?php
                                    $total_puntajes = $totales[$id_jugador];
                                    $class = ($total_puntajes == $max_puntaje) ? 'score-high' : ($total_puntajes == $min_puntaje ? 'score-low' : 'score-mid');
                                    ?>
                                    <td class="text-center <?php echo $class; ?>">
                                        <strong id="total-<?php echo $id_jugador; ?>">
                                            ???
                                        </strong>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="game-controls">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-outline-secondary" name="omitir">
                            <i class="fas fa-forward me-2"></i>
                            Omitir
                        </button>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-2"></i>
                                Siguiente Juego
                            </button>
                            <?php if ($cantidad_juegos >= 5): ?>
                                <a href="finalizar_partida.php?id=<?php echo urlencode($partida_id); ?>"
                                    class="btn btn-success">
                                    <i class="fas fa-flag-checkered me-2"></i>
                                    Finalizar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('scoreForm');
            const inputs = form.querySelectorAll('input[type="number"]');


            form.addEventListener('submit', function(e) {
                const enabledInputs = Array.from(inputs).filter(input => !input.disabled);
                const allFilled = enabledInputs.every(input => input.value !== '');

                if (!allFilled && !e.submitter?.name === 'omitir') {
                    e.preventDefault();
                    alert('Por favor completa todos los puntajes antes de continuar.');
                }
            });
        });
    </script>
</body>

</html>