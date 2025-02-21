<?php
include 'bd.php';

// Consulta para obtener los datos de las partidas con la cantidad de juegos distintos
$sql = "SELECT *, (partidas_ganadas/partidas_jugadas) as probabilidad FROM `jugadores` LIMIT 30;";
$result = $conn->query($sql);

$sql_conteo = "SELECT 
                (SELECT COUNT(*) FROM partidas) AS total_partidas, 
                (SELECT COUNT(*) FROM jugadores) AS total_jugadores;";

// Ejecutar la consulta
$resultado = $conexion->query($sql_conteo);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
  $fila = $resultado->fetch_assoc(); // Obtener los datos
  $total_partidas = $fila['total_partidas']; // Asignar a una variable
  $total_jugadores = $fila['total_jugadores']; // Asignar a otra variable
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Jugadores - Juegos de Cartas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #1e88e5;
      --secondary-color: #26a69a;
      --accent-color: #ff9800;
      --dark-color: #263238;
      --light-color: #f5f5f5;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --primary: #6c5ce7;
      --secondary: #00cec9;
      --dark: #2d3436;
      --light: #f7f7f7;
      --danger: #d63031;
      --success: #00b894;
      --warning: #fdcb6e;
      --info: #0984e3;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--light-color);
      margin: 0;
      padding: 0;
      color: var(--dark-color);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    header {
      background-color: var(--primary-color);
      color: white;
      padding: 20px;
      box-shadow: var(--shadow);
      border-radius: 0 0 10px 10px;
      margin-bottom: 30px;
    }

    header h1 {
      margin: 0;
      display: flex;
      align-items: center;
      font-size: 28px;
    }

    header h1 i {
      margin-right: 15px;
      font-size: 32px;
    }

    .game-selector {
      background-color: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .game-button {
      background-color: white;
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      padding: 10px 20px;
      border-radius: 20px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.2s;
    }

    .game-button.active {
      background-color: var(--primary-color);
      color: white;
    }

    .game-button:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .card {
      background-color: white;
      border-radius: 10px;
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: transform 0.3s;
      position: relative;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      background-color: var(--secondary-color);
      color: white;
      padding: 15px;
      position: relative;
    }

    .card-header h3 {
      margin: 0;
      font-size: 18px;
    }

    .player-stats {
      display: flex;
      padding: 25px;
      border-bottom: 1px solid #eee;
    }

    .stat {
      flex: 1;
      text-align: center;
      padding: 0 10px;
    }

    .stat:not(:last-child) {
      border-right: 1px solid #eee;
    }

    .stat-number {
      font-size: 22px;
      font-weight: bold;
      color: var(--primary-color);
    }

    .stat-label {
      font-size: 12px;
      color: #777;
    }

    .card-body {
      padding: 15px;
    }

    .player-info {
      display: flex;
      align-items: center;
    }

    .player-avatar {
      width: 50px;
      height: 50px;
      background-color: #e0e0e0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 20px;
      color: var(--dark-color);
    }

    .player-data {
      flex-grow: 1;
    }

    .player-email {
      color: #777;
      font-size: 14px;
    }

    .card-actions {
      display: flex;
      justify-content: space-between;
      padding: 25px;
      background-color: #f9f9f9;
      margin-top: -50px;
    }

    .btn1 {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.2s;
      display: flex;
      align-items: center;
    }

    .btn1 i {
      margin-right: 8px;
    }

    .btn-primary {
      background-color: var(--primary-color);
      color: white;
      height: 40px;
      margin-top: 35px;
    }

    .btn-save {
      background-color: var(--primary-color);
      color: white;
      height: 40px;
      margin-top: 35px;
    }

    .btn-danger {
      background-color: #f44336;
      color: white;
      height: 40px;
      margin-top: 35px;
    }

    .btn:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    .badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: var(--accent-color);
      color: white;
      padding: 3px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: bold;
    }

    .form-container {
      background-color: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: var(--shadow);
    }

    .form-title {
      margin-top: 0;
      color: var(--dark-color);
      font-size: 20px;
      display: flex;
      align-items: center;
    }

    .form-title i {
      margin-right: 10px;
      color: var(--primary-color);
    }


    .form-row {
      display: flex;
      flex-wrap: wrap;
      margin: -10px;
    }

    .form-col {
      flex: 1;
      padding: 10px;
      min-width: 200px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }

    input,
    select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
      font-family: inherit;
      transition: border-color 0.2s;
    }

    input:focus,
    select:focus {
      border-color: var(--primary-color);
      outline: none;
    }


    .empty-state {
      text-align: center;
      padding: 50px 0;
      color: #777;
    }

    .empty-state i {
      font-size: 50px;
      color: #ddd;
    }

    .btn-cancel {
      height: auto;
      margin-top: auto;
    }

    @media (max-width: 768px) {
      .cards-container {
        grid-template-columns: 1fr;
      }

      .form-col {
        flex: 100%;
      }

      .game-selector {
        flex-direction: column;
        align-items: stretch;
      }

      .game-button {
        margin-bottom: 10px;
      }

      .dashboard-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .dashboard-stats {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 10px;
      }

      .player-grid {
        grid-template-columns: 1fr;
      }

      .btn-save {
        height: auto;
        margin: 0 auto;
      }
    }

    .dashboard-header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      padding: 25px 30px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .dashboard-title {
      margin: 0;
      font-size: 24px;
      font-weight: 600;
    }

    .dashboard-stats {
      display: flex;
      gap: 20px;
    }

    .stat-box {
      background-color: rgba(255, 255, 255, 0.2);
      padding: 8px 16px;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .stat-value {
      font-size: 18px;
      font-weight: bold;
    }

    .stat-label {
      font-size: 12px;
      opacity: 0.8;
    }

    /* Efecto hover para el botón de eliminación */
    .btn-danger:hover {
      background: linear-gradient(to right, #c0392b, #e74a3b) !important;
      box-shadow: 0 0.125rem 0.25rem rgba(220, 53, 69, 0.4) !important;
    }

    /* Animación suave para el modal */
    .modal.fade .modal-dialog {
      transition: transform 0.3s ease-out;
    }

    /* Estilos para el checkbox de confirmación */
    .form-check-input:checked {
      background-color: #e74a3b;
      border-color: #e74a3b;
    }

    /* Efecto hover para el botón principal */
    .btn-primary:hover {
      background: linear-gradient(to right, #224abe, #4e73df) !important;
    }

    /* Sombras mejoradas */
    .modal-content {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Efecto de transición suave */
    .modal.fade .modal-dialog {
      transition: transform 0.3s ease-out;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="text-start mt-0 mb-2">
      <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Volver al Inicio
      </a>
    </div>
    <div class="dashboard-header">
      <h2 class="dashboard-title"><i class="fas fa-cards"></i>Gestión de Jugadores</h2>
      <div class="dashboard-stats">
        <div class="stat-box">
          <span class="stat-value"><?php echo $total_jugadores; ?></span>
          <span class="stat-label">Jugadores</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><?php echo $total_partidas ?></span>
          <span class="stat-label">Partidas</span>
        </div>
      </div>
    </div>

    <div class="form-container">
      <h3 class="form-title"><i class="fas fa-user-plus"></i> Añadir nuevo jugador</h3>
      <form action="registrar_jugador_gestion.php" method="post">
        <div class="form-row">
          <div class="form-col">
            <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" id="nombre" name="nombre" placeholder="Nombre del jugador" required>
            </div>
          </div>
          <button type="submit" class="btn1 btn-save">
            <i class="fas fa-save"></i> Guardar jugador
          </button>
        </div>
      </form>
    </div>


    <div class="cards-container">

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()):
          $probabilidad = $row['probabilidad'] * 100;
        ?>
          <div class="card">
            <div class="card-header">
              <h3> <?php echo $row['nombre']; ?></h3>
            </div>
            <div class="player-stats">
              <div class="stat">
                <div class="stat-number"><?php echo $row['partidas_jugadas']; ?></div>
                <div class="stat-label">Partidas</div>
              </div>
              <div class="stat">
                <div class="stat-number"><?php echo $row['partidas_ganadas']; ?></div>
                <div class="stat-label">Victorias</div>
              </div>
              <div class="stat">
                <div class="stat-number"><?php echo $probabilidad ?>%</div>
                <div class="stat-label">Prob.</div>
              </div>
            </div>
            <!--
                  <div class="card-body">
                    <div class="player-info">
                      <div class="player-avatar">JP</div>
                      <div class="player-data">
                        <div class="player-email">juan@example.com</div>
                        <div>Tel: (+34) 600 123 456</div>
                      </div>
                    </div>
                  </div>
            -->
            <div class="card-actions">
              <button class="btn1 btn-primary" data-bs-toggle="modal" data-bs-target="#ModalEditar<?php echo $row['id']; ?>">
                <i class="fas fa-edit"></i> Editar
              </button>
              <button class="btn1 btn-danger" data-bs-toggle="modal" data-bs-target="#ModalEliminar<?php echo $row['id']; ?>">
                <i class="fas fa-trash"></i> Eliminar
              </button>
            </div>
          </div>

          <div class="modal fade" id="ModalEliminar<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="eliminarJugadorLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content shadow border-0">
                <!-- Encabezado del modal con fondo rojo para indicar acción destructiva -->
                <div class="modal-header bg-danger text-white" style="background: linear-gradient(to right, #e74a3b, #c0392b);">
                  <h5 class="modal-title" id="eliminarJugadorLabel">
                    <i class="fas fa-user-times me-2"></i>Eliminar Jugador
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal con mensaje de advertencia -->
                <div class="modal-body">
                  <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡Atención!</strong> Esta acción no se puede deshacer.
                  </div>

                  <form class="form" method="POST" action="eliminar_jugador.php">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div class="mb-3">
                      <label for="nombre_eliminar<?php echo $row['id']; ?>" class="form-label fw-bold">
                        Jugador a eliminar:
                      </label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-danger">
                          <i class="fas fa-user-slash"></i>
                        </span>
                        <input
                          type="text"
                          class="form-control form-control-lg bg-light"
                          id="nombre_eliminar<?php echo $row['id']; ?>"
                          name="nombre"
                          value="<?php echo $row['nombre']; ?>"
                          readonly>
                      </div>
                      <small class="text-muted fst-italic mt-1 d-block">El registro será eliminado permanentemente de la base de datos.</small>
                    </div>

                    <!-- Confirmación explícita -->
                    <div class="form-check mb-4 mt-3">
                      <input class="form-check-input" type="checkbox" id="confirmarEliminacion<?php echo $row['id']; ?>" required>
                      <label class="form-check-label" style="margin-left: 10px;" for="confirmarEliminacion<?php echo $row['id']; ?>">
                        Confirmo que deseo eliminar este jugador
                      </label>
                    </div>
                </div>

                <!-- Footer con botones claramente diferenciados -->
                <div class="modal-footer bg-light">
                  <button type="button" class="btn btn-outline-secondary px-4 btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                  </button>
                  <button type="submit" name="eliminar" class="btn btn-danger px-4" style="background: linear-gradient(to right, #e74a3b, #c0392b); border: none;">
                    <i class="fas fa-trash-alt me-1"></i>Eliminar Jugador
                  </button>
                </div>
                </form>
              </div>
            </div>
          </div>


          <div class="modal fade" id="ModalEditar<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editarJugadorLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content shadow">
                <!-- Encabezado del modal con degradado correcto -->
                <div class="modal-header bg-primary text-white" style="background: linear-gradient(to right, #4e73df, #224abe);">
                  <h5 class="modal-title" id="editarJugadorLabel">
                    <i class="fas fa-user-edit me-2"></i>Editar Jugador
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal con padding adicional -->
                <div class="modal-body p-4">
                  <form class="form needs-validation" method="POST" action="actualizar_jugador.php" novalidate>
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div class="mb-3">
                      <label for="nombre<?php echo $row['id']; ?>" class="form-label fw-bold">
                        <i class="fas fa-signature me-1"></i>Nombre del Jugador
                      </label>
                      <div class="input-group">
                        <span class="input-group-text bg-light">
                          <i class="fas fa-user"></i>
                        </span>
                        <input
                          type="text"
                          class="form-control form-control-lg border-0 shadow-sm"
                          id="nombre<?php echo $row['id']; ?>"
                          name="nombre"
                          value="<?php echo $row['nombre']; ?>"
                          required
                          placeholder="Ingrese el nombre completo">
                        <div class="invalid-feedback">
                          Por favor ingrese un nombre válido.
                        </div>
                      </div>
                    </div>

                    <!-- Botones del modal con degradado -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                      <button type="button" class="btn btn-outline-secondary px-4 btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                      </button>
                      <button type="submit" name="actualizar" class="btn btn-save px-4 shadow-sm" style="background: linear-gradient(to right, #4e73df, #224abe); border: none;">
                        <i class="fas fa-save me-1"></i>Guardar Cambios
                      </button>
                    </div>
                  </form>
                  <script>
                    document.addEventListener("DOMContentLoaded", function() {
                      const form = document.querySelector(".needs-validation");

                      form.addEventListener("submit", function(event) {
                        let isValid = true;
                        const nombreInput = form.querySelector("input[name='nombre']");
                        const nombreValue = nombreInput.value.trim();
                        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios

                        // Validar si está vacío
                        if (nombreValue === "") {
                          nombreInput.classList.add("is-invalid");
                          nombreInput.nextElementSibling.textContent = "El nombre no puede estar vacío.";
                          isValid = false;
                        }
                        // Validar caracteres especiales
                        else if (!regex.test(nombreValue)) {
                          nombreInput.classList.add("is-invalid");
                          nombreInput.nextElementSibling.textContent = "El nombre solo puede contener letras y espacios.";
                          isValid = false;
                        }
                        // Si es válido, quitar mensaje de error
                        else {
                          nombreInput.classList.remove("is-invalid");
                          nombreInput.classList.add("is-valid");
                        }

                        if (!isValid) {
                          event.preventDefault(); // Evita el envío del formulario si hay errores
                        }
                      });

                      // Eliminar mensaje de error cuando el usuario escriba
                      document.querySelector("input[name='nombre']").addEventListener("input", function() {
                        this.classList.remove("is-invalid");
                      });
                    });
                  </script>

                </div>
              </div>
            </div>
          </div>


        <?php endwhile; ?>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-users fa-3x mb-3"></i>
          <h4>No hay jugadores aún</h4>
          <p>Registra jugadores para que aparezcan en la lista.</p>

        </div>
      <?php endif; ?>

    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>