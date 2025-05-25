<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Prestamo.php';

$band_id = isset($_GET['band_id']) ? intval($_GET['band_id']) : null;

if (!$band_id) {
    die("Error: No se especificó la banda.");
}

$equipos_disponibles = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $inicio = $_POST['fecha_inicio'];
    $fin = $_POST['fecha_fin'];

    $prestamo = new Prestamo($conn);
    $equipos_disponibles = $prestamo->obtenerEquiposDisponibles($inicio, $fin);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Préstamo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <h3>Solicitar Préstamo de Equipos</h3>
    <form method="POST">
        <input type="hidden" name="band_id" value="<?= $band_id ?>">
        <div class="row mb-3">
            <div class="col">
                <label>Inicio</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control" required>
            </div>
            <div class="col">
                <label>Fin</label>
                <input type="datetime-local" name="fecha_fin" class="form-control" required>
            </div>
        </div>
        <button name="buscar" class="btn btn-primary">Buscar Equipos Disponibles</button>
    </form>

    <?php if (!empty($equipos_disponibles)): ?>
        <form method="POST" action="../../controllers/PrestamoController.php" class="mt-4">
            <input type="hidden" name="band_id" value="<?= $band_id ?>">
            <input type="hidden" name="fecha_inicio" value="<?= $_POST['fecha_inicio'] ?>">
            <input type="hidden" name="fecha_fin" value="<?= $_POST['fecha_fin'] ?>">
            <h5>Selecciona Equipos</h5>
            <?php foreach ($equipos_disponibles as $eq): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="equipos[]" value="<?= $eq['equipo_id'] ?>" id="eq<?= $eq['equipo_id'] ?>">
                    <label class="form-check-label" for="eq<?= $eq['equipo_id'] ?>">
                        <?= htmlspecialchars($eq['nombre_equipo']) ?> - $<?= number_format($eq['precio_dia'], 0) ?>/día
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-success mt-3">Confirmar Préstamo</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
