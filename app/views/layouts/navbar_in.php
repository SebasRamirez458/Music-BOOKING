<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
$rol = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 'Admin' : 'Usuario';
// Hora actual en zona horaria -05
$dt = new DateTime('now', new DateTimeZone('America/Bogota'));
$hora_actual = $dt->format('H:i:s');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">
      <?php echo htmlspecialchars($rol); ?> | <?php echo htmlspecialchars($nombre); ?>
    </span>
    <div class="d-flex ms-auto align-items-center">
      <span class="text-white me-3">Hora actual: <?php echo $hora_actual; ?> </span>
      <form action="/Music-BOOKING/app/controllers/logout.php" method="POST" class="d-inline">
        <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
      </form>
    </div>
  </div>
</nav>