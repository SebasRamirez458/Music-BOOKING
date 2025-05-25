<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
$rol = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 'Admin' : 'Usuario';

// Hora inicial del lado servidor
$dt = new DateTime('now', new DateTimeZone('America/Bogota'));
$hora_actual = $dt->format('H:i:s');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <span class="navbar-brand">
      <?php echo htmlspecialchars($rol); ?> | <?php echo htmlspecialchars($nombre); ?>
    </span>
    <div class="d-flex ms-auto align-items-center">
      <span class="text-white me-3">Hora actual: <span id="hora-actual"><?php echo $hora_actual; ?></span></span>
      <form action="/Music-BOOKING/app/controllers/logout.php" method="POST" class="d-inline">
        <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
      </form>
    </div>
  </div>
</nav>
<br>
<br>

<script>
// Función que actualiza la hora cada segundo
function actualizarHora() {
  const horaSpan = document.getElementById('hora-actual');
  const ahora = new Date();

  // Opcional: ajusta a UTC-5 (Bogotá) si el navegador no está en esa zona
  const opciones = {
    timeZone: 'America/Bogota',
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  };

  const horaFormateada = new Intl.DateTimeFormat('es-CO', opciones).format(ahora);
  horaSpan.textContent = horaFormateada;
}

// Actualizar cada segundo
setInterval(actualizarHora, 1000);
actualizarHora(); // Llamada inicial
</script>
