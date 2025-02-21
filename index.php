<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Juego de Carioca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ff6b6b;
      --secondary-color: #4ecdc4;
      --third-color: #f1f1f1;
      --background-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    body {
      min-height: 100vh;
      background: var(--background-gradient);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .game-container {
      padding-top: 5vh;
      padding-bottom: 5vh;
    }

    .card {
      border: none;
      transition: all 0.3s ease;
      border-radius: 1rem;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-play {
      background: linear-gradient(45deg, var(--primary-color), #ff8e8e);
    }

    .card-history {
      background: linear-gradient(45deg, var(--secondary-color), #6fe7df);
    }

    .card-content {
      padding: 2.5rem 1.5rem;
      color: white;
    }

    .icon-container {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .card-text {
      opacity: 0.9;
      font-size: 1.1rem;
    }

    .btn-action {
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid white;
      color: white;
      padding: 0.75rem 2rem;
      border-radius: 2rem;
      transition: all 0.3s ease;
      margin-top: 1.5rem;
    }

    .btn-action:hover {
      background: white;
      color: var(--primary-color);
    }

    @media (max-width: 768px) {
      .game-container {
        padding-top: 3vh;
      }

      .card {
        margin-bottom: 2rem;
      }

      .card-content {
        padding: 2rem 1rem;
      }
    }

    .card-player-management {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .card-player-management:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .card-player-management .feature-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 70px;
      height: 70px;
      border-radius: 50%;
      color: white;
      margin-bottom: 1.25rem;
    }

    .bg-primary.bg-gradient {
      background: linear-gradient(135deg, #4361ee, #3a56d4);
    }

    .card-player-management .card-title {
      color: #212529;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    .card-player-management .card-text {
      color: #6c757d;
      font-size: 1rem;
    }

    .btn-primary {
      background: linear-gradient(to right, #4361ee, #3a56d4);
      border: none;
      border-radius: 30px;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(to right, #3a56d4, #324bc6);
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }
  </style>
</head>

<body>
  <div class="container game-container">
    <h1 class="text-center mb-5 text-dark">Carioca Game</h1>

    <div class="row justify-content-center g-4">
      <div class="col-12 col-md-6 col-lg-5">
        <div class="card card-play h-100">
          <div class="card-content text-center">
            <div class="icon-container">
              <i class="fas fa-gamepad fa-2x"></i>
            </div>
            <h2 class="card-title">Jugar Carioca</h2>
            <p class="card-text">Inicia una nueva partida y disfruta del juego con tus amigos</p>
            <a href="jugar.php" class="btn btn-action d-inline-block">
              Comenzar Juego
              <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-5">
        <div class="card card-history h-100">
          <div class="card-content text-center">
            <div class="icon-container">
              <i class="fas fa-history fa-2x"></i>
            </div>
            <h2 class="card-title">Historial</h2>
            <p class="card-text">Revisa el historial de partidas y estadísticas de juego</p>
            <a href="historial.php" class="btn btn-action d-inline-block">
              Ver Historial
              <i class="fas fa-chart-line ms-2"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-5">
        <div class="card card-player-management h-100">
          <div class="card-body text-center p-4">
            <div class="feature-icon bg-primary bg-gradient mb-4">
              <i class="fas fa-user-edit fa-lg"></i>
            </div>
            <h3 class="card-title fw-bold">Gestión de Jugadores</h3>
            <p class="card-text mb-4">Administra perfiles, visualiza estadísticas y configura datos del equipo</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
              <a href="gestion_jugadores.php" class="btn btn-primary px-4 py-2">
                Editar Jugadores
                <i class="fas fa-angle-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>