<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "La sesión ha expirado. Por favor, inicie sesión nuevamente.";
    header('Location: ../auth/login.php');
    exit();
}
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Reserva.php';
require_once __DIR__ . '/../../models/Prestamo.php';


$user_id = intval($_SESSION['user_id']);
$prestamoModel = new Prestamo($conn);
$prestamos = $prestamoModel->obtenerPorUsuario($user_id);
$reservaModel = new Reserva($conn);
$reservas = $reservaModel->obtenerPorUsuario($user_id);

$bands = [];
$error = '';
try {
    $stmt = $conn->prepare('SELECT band_id, nombre_banda, num_integrantes FROM bandas WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $bands = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error al obtener las bandas: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Bandas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <h2 class="mb-4">Tus Bandas</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <a href="add_band.php" class="btn btn-success mb-3">Crear Nueva Banda</a>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Número de Integrantes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bands as $band): ?>
            <tr>
                <td><?php echo htmlspecialchars($band['band_id']); ?></td>
                <td><?php echo htmlspecialchars($band['nombre_banda']); ?></td>
                <td><?php echo htmlspecialchars($band['num_integrantes']); ?></td>
                <td>
                    <a href="../bandas/edit_band.php?id=<?= urlencode($band['band_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="../bandas/delete_band.php?id=<?= urlencode($band['band_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta banda?');">Eliminar</a>
                    <a href="add_reservation.php?band_id=<?php echo urlencode($band['band_id']); ?>" class="btn btn-primary btn-sm">Reservar Sala</a>
                    <a href="add_checkout.php?band_id=<?= $band['band_id'] ?>" class="btn btn-info btn-sm">Pedir Equipo</a>       
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h3 class="mt-5">Reservas Activas</h3>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Banda</th>
                <th>Sala</th>
                <th>Fecha de Inicio</th>
                <th>Duración (horas)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($reservas) > 0): ?>
            <?php foreach ($reservas as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['nombre_banda']) ?></td>
                    <td><?= htmlspecialchars($res['nombre_sala']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($res['fecha_inicio'])) ?></td>
                    <td><?= $res['duracion_horas'] ?></td>
                    <td>
                        <a href="../../controllers/delete_reserva.php?id=<?= $res['reserva_id'] ?>" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('¿Seguro que deseas eliminar esta reserva?');">
                        Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No hay reservas activas.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
        <h3 class="mt-5">Préstamos Activos</h3>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Banda</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($prestamos) > 0): ?>
            <?php foreach ($prestamos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre_banda']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['fecha_inicio_prestamo'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['fecha_fin_prestamo'])) ?></td>
                    <td>$<?= number_format($p['total_prestamo'], 0) ?></td>
                    <td>
                        <a href="../../controllers/delete_prestamo.php?id=<?= $p['prestamo_id'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('¿Deseas eliminar este préstamo?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No hay préstamos activos.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>


    <a href="user_home.php" class="btn btn-secondary mt-3">Volver al menú principal</a>
</div>
</body>
</html>