<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Prestamo.php';

$user_id = intval($_SESSION['user_id']);
$prestamoModel = new Prestamo($conn);
$prestamos = $prestamoModel->obtenerPorUsuario($user_id); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Préstamos Activos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Préstamos Activos</h3>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Banda</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($prestamos)): ?>
            <?php foreach ($prestamos as $prestamo): ?>
                <tr>
                    <td><?= htmlspecialchars($prestamo['nombre_banda']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($prestamo['fecha_inicio_prestamo'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($prestamo['fecha_fin_prestamo'])) ?></td>
                    <td>$<?= number_format($prestamo['total_prestamo'], 0) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay préstamos activos.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
