<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Jugadores - Carioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .selection-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .player-card {
            padding: 1rem;
            margin: 0.5rem 0;
            border: 2px solid #eee;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .player-card:hover {
            border-color: #4ecdc4;
            background: #f8f9fa;
        }

        .player-card.selected {
            border-color: #4ecdc4;
            background: #e8f8f7;
        }

        .counter-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4ecdc4;
            color: white;
            padding: 1rem;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php include('bd.php');
    $sql = "SELECT id, nombre FROM jugadores";
    $result = $conn->query($sql);
    ?>

    <div class="container">

        <!-- Botón para abrir el modal -->
        <div class="mt-4">
            <button class="btn btn-success btn-m" data-bs-toggle="modal" data-bs-target="#modalRegistrarJugador">
                <i class="fas fa-user-plus me-2"></i>
                Registrar Nuevo Jugador
            </button>
        </div>
        <div class="selection-container">
            <h2 class="text-center mb-4">
                <i class="fas fa-users me-2"></i>
                Seleccionar Jugadores
            </h2>

            <form id="playerForm" action="iniciar_partida.php" method="POST">
                <?php if ($result->num_rows > 0): ?>
                    <div class="player-list">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="player-card">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input class="form-check-input player-checkbox"
                                            type="checkbox"
                                            name="jugadores[]"
                                            value="<?= $row['id'] ?>"
                                            id="usuario<?= $row['id'] ?>">
                                        <label class="form-check-label ms-2" for="usuario<?= $row['id'] ?>">
                                            <?= htmlspecialchars($row['nombre']) ?>
                                        </label>
                                    </div>
                                    <i class="fas fa-check text-success opacity-0"></i>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No hay jugadores registrados.
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                        <i class="fas fa-play me-2"></i>
                        Iniciar Partida
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contador de jugadores seleccionados -->
    <div class="counter-badge" id="playerCounter">0</div>

    <!-- Modal para registrar nuevo jugador -->
    <div class="modal fade" id="modalRegistrarJugador" tabindex="-1" aria-labelledby="modalRegistrarJugadorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistrarJugadorLabel">Registrar Nuevo Jugador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="registrar_jugador.php" method="POST">
                        <div class="mb-3">
                            <label for="nombreJugador" class="form-label">Nombre del Jugador</label>
                            <input type="text" class="form-control" id="nombreJugador" name="nombre" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Registrar Jugador</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('playerForm');
            const checkboxes = document.querySelectorAll('.player-checkbox');
            const submitBtn = document.getElementById('submitBtn');
            const counter = document.getElementById('playerCounter');

            function updateUI() {
                const selectedCount = document.querySelectorAll('.player-checkbox:checked').length;
                counter.textContent = selectedCount;

                checkboxes.forEach(checkbox => {
                    const card = checkbox.closest('.player-card');
                    const checkIcon = card.querySelector('.fa-check');

                    if (checkbox.checked) {
                        card.classList.add('selected');
                        checkIcon.classList.remove('opacity-0');
                    } else {
                        card.classList.remove('selected');
                        checkIcon.classList.add('opacity-0');
                    }
                });

                submitBtn.disabled = selectedCount < 2;
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateUI);
            });

            form.addEventListener('submit', function(e) {
                const selectedCount = document.querySelectorAll('.player-checkbox:checked').length;

                if (selectedCount < 2) {
                    e.preventDefault();
                    alert('Selecciona al menos 2 jugadores.');
                }
            });

            updateUI();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>