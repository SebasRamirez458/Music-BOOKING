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
    <script>
    function calcularFinYTotal() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const duracion = parseInt(document.getElementById('duracion_horas').value);
        const salaSelect = document.getElementById('sala_id');
        const precio = parseFloat(salaSelect.options[salaSelect.selectedIndex].getAttribute('data-precio'));
        if (fechaInicio && duracion && precio) {
            // Calcular fecha_fin
            const inicio = new Date(fechaInicio);
            inicio.setHours(inicio.getHours() + duracion);
            const yyyy = inicio.getFullYear();
            const mm = String(inicio.getMonth() + 1).padStart(2, '0');
            const dd = String(inicio.getDate()).padStart(2, '0');
            const hh = String(inicio.getHours()).padStart(2, '0');
            const min = String(inicio.getMinutes()).padStart(2, '0');
            document.getElementById('fecha_fin').value = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
            // Calcular total
            document.getElementById('total_reserva').value = (precio * duracion).toFixed(2);
        }
    }
    </script>
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
                    <select name="sala_id" id="sala_id" class="form-select" required onchange="calcularFinYTotal()">
                        <?php foreach ($salas as $sala): ?>
                            <option value="<?= $sala['sala_id'] ?>" data-precio="<?= $sala['precio_hora'] ?>">
                                <?= $sala['nombre_sala'] ?> - (<?= number_format($sala['precio_hora'], 0) ?> por hora)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" step="3600" name="fecha_inicio" id="fecha_inicio" class="form-control" required onchange="calcularFinYTotal()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Duración (horas)</label>
                    <select name="duracion_horas" id="duracion_horas" class="form-select" required onchange="calcularFinYTotal()">
                        <option value="1">1 hora</option>
                        <option value="2">2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="4">4 horas</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha y Hora de Fin</label>
                    <input type="datetime-local" name="fecha_fin" id="fecha_fin" class="form-control" readonly required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Total de la Reserva</label>
                    <input type="text" name="total_reserva" id="total_reserva" class="form-control" readonly required>
                </div>
                <button type="submit" class="btn btn-success w-100">Reservar</button>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    calcularFinYTotal();
});
</script>
</body>
</html>
