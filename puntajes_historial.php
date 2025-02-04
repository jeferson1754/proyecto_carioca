<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - Carioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .results-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
            position: relative;
            overflow: hidden;
        }

        .results-header {
            position: relative;
            margin-bottom: 3rem;
            text-align: center;
        }

        .results-header::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #4ecdc4, #45b7af);
            border-radius: 2px;
        }

        .player-card {
            background: #f8f9fa;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.5s ease forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .position {
            font-size: 1.5rem;
            font-weight: bold;
            width: 40px;
        }

        .player-info {
            flex-grow: 1;
            margin: 0 1rem;
        }

        .player-name {
            font-size: 1.2rem;
            margin: 0;
        }

        .score {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .trophy {
            font-size: 2rem;
            margin-left: 1rem;
        }

        .gold-card {
            background: linear-gradient(135deg, #ffd700 0%, #faf0be 100%);
            border-color: #ffd700;
        }

        .silver-card {
            background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
            border-color: #c0c0c0;
        }

        .bronze-card {
            background: linear-gradient(135deg, #cd7f32 0%, #e4bc9e 100%);
            border-color: #cd7f32;
        }

        .gold {
            color: #ffd700;
        }

        .silver {
            color: #c0c0c0;
        }

        .bronze {
            color: #cd7f32;
        }

        .btn-return {
            background: #4ecdc4;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-return:hover {
            background: #45b7af;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.3);
        }
    </style>
</head>

<body>
    <?php
    include 'bd.php';

    $id_partida = isset($_GET['id']) ? $_GET['id'] : null;
    // Obtener los datos de la partida 5
    $sql = "SELECT j.id AS jugador_id, j.nombre, SUM(dp.puntaje) AS total_puntaje 
        FROM jugadores j
        JOIN detalles_partida dp ON j.id = dp.jugador_id
        WHERE dp.partida_id = $id_partida
        GROUP BY j.id
        ORDER BY total_puntaje ASC";

    $result = $conn->query($sql);

    $jugadores = [];
    if ($result->num_rows > 0) {
        // Obtener los datos de los jugadores
        while ($row = $result->fetch_assoc()) {
            $jugadores[] = $row;
        }
    } else {
        echo "No se encontraron jugadores para la partida $id_partida.";
    }

    $conn->close();
    ?>

    <div class="container">
        <div class="results-container">
            <div class="results-header">
                <h2>Resultados Finales</h2>
                <p class="text-muted">Partida <?php echo htmlspecialchars($id_partida); ?></p>
            </div>

            <div class="results-list">
                <?php
                $position = 1;
                foreach ($jugadores as $jugador):
                    $nombre = htmlspecialchars($jugador['nombre']);
                    $total_puntaje = $jugador['total_puntaje'];

                    $cardClass = '';
                    $trophyIcon = '';

                    switch ($position) {
                        case 1:
                            $cardClass = 'gold-card';
                            $trophyIcon = '<i class="fas fa-trophy gold trophy"></i>';
                            break;
                        case 2:
                            $cardClass = 'silver-card';
                            $trophyIcon = '<i class="fas fa-trophy silver trophy"></i>';
                            break;
                        case 3:
                            $cardClass = 'bronze-card';
                            $trophyIcon = '<i class="fas fa-trophy bronze trophy"></i>';
                            break;
                        default:
                            $trophyIcon = '<i class="fas fa-medal trophy"></i>';
                    }
                ?>
                    <div class="player-card <?php echo $cardClass; ?>"
                        style="animation-delay: <?php echo ($position - 1) * 0.1; ?>s">
                        <div class="position"><?php echo $position; ?>°</div>
                        <div class="player-info">
                            <h3 class="player-name"><?php echo $nombre; ?></h3>
                        </div>
                        <div class="score"><?php echo number_format($total_puntaje); ?> pts</div>
                        <?php echo $trophyIcon; ?>
                    </div>
                <?php
                    $position++;
                endforeach;
                ?>
            </div>

            <div class="text-center mt-5">
                <a href="./historial.php" class="btn btn-return">
                    <i class="fas fa-history me-2"></i>
                    Volver al Historial
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>