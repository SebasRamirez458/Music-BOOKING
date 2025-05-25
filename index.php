<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Music-BOOKING | Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="icon" href="favicon.ico" type="image/x-icon"> -->
</head>
<body>
<!-- Barra de navegación -->
<?php include 'app/views/layouts/navbar_out.php';?>

<!-- Banner principal -->
<div class="container-fluid p-0">
  <div class="banner d-flex align-items-center justify-content-center" style="height: 350px; background: #222 url('img/banner.jpg') center/cover no-repeat;">
    <div class="text-center text-white bg-dark bg-opacity-50 p-4 rounded">
      <h1 class="display-4">Reserva tu sala de ensamble</h1>
      <p class="lead">¡Haz música, nosotros te damos el espacio!</p>
      <a href="app/views/auth/Register.php" class="btn btn-primary btn-lg mt-3">Regístrate ahora</a>
    </div>
  </div>
</div>

<!-- Sección: ¿Qué es Music-BOOKING? -->
<section class="container my-5">
  <div class="row align-items-center">
    <div class="col-md-6">
      <h2>¿Qué es Music-BOOKING?</h2>
      <p>Music-BOOKING es una plataforma dedicada a músicos, bandas y productores que buscan reservar salas de ensayo profesionales de manera rápida y sencilla. Nuestro objetivo es facilitar el acceso a espacios equipados para que puedas concentrarte en lo más importante: ¡la música!</p>
    </div>
    <div class="col-md-6 text-center">
      <img src="img/sala_1.jpg" alt="Sala de ensayo" class="img-fluid rounded shadow">
    </div>
  </div>
</section>

<!-- Sección: ¿Necesitas equipos para un Toque? -->
<section class="container my-5">
  <div class="row align-items-center flex-row-reverse">
    <div class="col-md-6">
      <h2>¿Necesitas equipos para un Toque?</h2>
      <p>¿Tienes una presentación y te faltan equipos? En Music-BOOKING puedes reservar no solo la sala, sino también instrumentos y equipos de sonido de alta calidad. ¡Lleva tu música al siguiente nivel!</p>
    </div>
    <div class="col-md-6 text-center">
      <img src="img/sala_2.jpg" alt="Equipos para toque" class="img-fluid rounded shadow">
    </div>
  </div>
</section>

<!-- Footer -->
<?php include 'app/views/layouts/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>