<?php
require_once __DIR__ . '/../../../config/db.php';
require_once '../../models/Reserva.php';

$reserva = new Reserva($conn);
$salas = $reserva->obtenerSalas();


session_start();

$band_id = isset($_GET['band_id']) ? intval($_GET['band_id']) : null;
if (!$band_id) {
    die("No se ha especificado ninguna banda para hacer la reserva.");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <h4>Reservar Sala de Ensayo</h4>
        </div>
        <div class="card-body">
            <form action="../../controllers/ReservaController.php" method="POST">
                <input type="hidden" name="band_id" value="<?= $band_id ?>">

                <div class="mb-3">
                    <label class="form-label">Sala</label>
                    <select name="sala_id" class="form-select" required>
                        <?php foreach ($salas as $sala): ?>
                            <option value="<?= $sala['sala_id'] ?>">
                                <?= $sala['nombre_sala'] ?> - <?= $sala['tipo_sala'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Duración (horas)</label>
                    <select name="duracion_horas" class="form-select" required>
                        <option value="1">1 hora</option>
                        <option value="2">2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="4">4 horas</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Reservar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
