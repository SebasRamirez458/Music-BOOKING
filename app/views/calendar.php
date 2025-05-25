<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

// Obtener salas para el selector
try {
    $stmt = $conn->query('SELECT sala_id, nombre_sala FROM salas');
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $rooms = [];
}

// Determinar la sala seleccionada y la semana actual
$sala_id = isset($_GET['sala_id']) ? intval($_GET['sala_id']) : (count($rooms) > 0 ? $rooms[0]['sala_id'] : null);
$start_of_week = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d'); 
$end_of_week = date('Y-m-d', strtotime($start_of_week . ' +6 days'));

$reservas = [];
if ($sala_id) {
    $sql = 'SELECT r.*, b.nombre_banda FROM reservas r JOIN bandas b ON r.band_id = b.band_id WHERE r.sala_id = :sala_id AND r.fecha_inicio >= :start AND r.fecha_inicio <= :end';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sala_id', $sala_id, PDO::PARAM_INT);
    $stmt->bindParam(':start', $start_of_week);
    $stmt->bindParam(':end', $end_of_week);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$dias = [];
for ($i = 0; $i < 7; $i++) {
    $dias[] = date('Y-m-d', strtotime($start_of_week . "+$i days"));
}

function getReservationsForDay($reservas, $dia) {
    $result = [];
    foreach ($reservas as $res) {
        if (strpos($res['fecha_inicio'], $dia) === 0) {
            $result[] = $res;
        }
    }
    return $result;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario Semanal de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'layouts/navbar_in.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Calendario Semanal de Reservas</h2>
    <form method="GET" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="sala_id" class="form-label">Selecciona una sala:</label>
                <select name="sala_id" id="sala_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['sala_id']; ?>" <?php if ($room['sala_id'] == $sala_id) echo 'selected'; ?>><?php echo htmlspecialchars($room['nombre_sala']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="start" class="form-label">Semana que comienza en:</label>
                <input type="date" name="start" id="start" class="form-control" value="<?php echo $start_of_week; ?>" onchange="this.form.submit()">
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <?php foreach ($dias as $dia): ?>
                    <th><?php echo date('D d/m', strtotime($dia)); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach ($dias as $dia): ?>
                    <td>
                        <?php $res_dia = getReservationsForDay($reservas, $dia); ?>
                        <?php if (count($res_dia) > 0): ?>
                            <?php foreach ($res_dia as $res): ?>
                                <div class="alert alert-info p-1 mb-1">
                                    <strong><?php echo htmlspecialchars($res['nombre_banda']); ?></strong><br>
                                    <?php echo date('H:i', strtotime($res['fecha_inicio'])); ?> - <?php echo date('H:i', strtotime($res['fecha_inicio'] . ' + ' . $res['duracion_horas'] . ' hours')); ?><br>
                                    Estado: <?php echo htmlspecialchars($res['estado_pago']); ?>
                                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                        <form method="POST" class="mt-1 select-reserva-form">
                                            <input type="hidden" name="selected_reserva_id" value="<?php echo $res['reserva_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">Seleccionar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">Sin reservas</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin/admin_home.php" class="btn btn-secondary mt-3">Volver al menú principal</a>
        <?php else: ?>
            <a href="user/user_home.php" class="btn btn-secondary mt-3">Volver al menú principal</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] && isset($_POST['selected_reserva_id'])): ?>
                <form method="GET" action="admin/manage_reservation.php" class="mt-3">
                    <input type="hidden" name="reserva_id" value="<?php echo htmlspecialchars($_POST['selected_reserva_id']); ?>">
                    <button type="submit" class="btn btn-warning">Administrar reserva seleccionada</button>
                </form>
            <?php endif; ?>
</div>
</body>
</html>