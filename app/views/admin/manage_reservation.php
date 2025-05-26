<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../auth/login.php');
    exit();
}
require_once __DIR__ . '/../../../config/db.php';

$reserva_id = isset($_GET['reserva_id']) ? intval($_GET['reserva_id']) : null;
$reserva = null;
if ($reserva_id) {
    $stmt = $conn->prepare('SELECT r.*, b.nombre_banda, s.nombre_sala FROM reservas r JOIN bandas b ON r.band_id = b.band_id JOIN salas s ON r.sala_id = s.sala_id WHERE r.reserva_id = :reserva_id');
    $stmt->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
    $stmt->execute();
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$reserva) {
    echo '<div class="alert alert-danger">Reserva no encontrada.</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $reserva_id = intval($_POST['reserva_id']);
    // Eliminar conexiones en reserva_equipos
    $stmt = $conn->prepare('DELETE FROM reserva_equipos WHERE reserva_id = ?');
    $stmt->execute([$reserva_id]);
    // Eliminar la reserva
    $stmt = $conn->prepare('DELETE FROM reservas WHERE reserva_id = ?');
    $stmt->execute([$reserva_id]);
    // Redirigir al calendario
    header('Location: ../calendar.php?msg=Reserva eliminada');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Administrar Reserva</h2>
    <form method="POST" action="">
        <input type="hidden" name="reserva_id" value="<?php echo htmlspecialchars($reserva['reserva_id']); ?>">
        <div class="mb-3">
            <label class="form-label">Banda</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($reserva['nombre_banda']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Sala</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($reserva['nombre_sala']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de inicio</label>
            <input type="datetime-local" name="fecha_inicio" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($reserva['fecha_inicio'])); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Duración (horas)</label>
            <input type="number" name="duracion_horas" class="form-control" min="1" value="<?php echo htmlspecialchars($reserva['duracion_horas']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Estado de pago</label>
            <select name="estado_pago" class="form-select">
                <option value="Pendiente" <?php if ($reserva['estado_pago'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="Pagado" <?php if ($reserva['estado_pago'] == 'Pagado') echo 'selected'; ?>>Pagado</option>
            </select>
        </div>
        <!-- Equipos asociados a la reserva -->
        <div class="mb-3">
            <label class="form-label">Categoría de Equipo</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="">Selecciona una categoría</option>
                <option value="Instrumento">Instrumento</option>
                <option value="Micrófono">Micrófono</option>
                <option value="Clabeado/conexión">Clabeado/conexión</option>
                <option value="Amplificadores">Amplificadores</option>
                <option value="Modulos de efectos">Modulos de efectos</option>
                <option value="Otros">Otros</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Equipos disponibles</label>
            <div id="equipos-lista" class="list-group"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Equipos seleccionados (Carrito)</label>
            <ul id="carrito-equipos" class="list-group"></ul>
        </div>
        <input type="hidden" name="equipos_seleccionados" id="equipos_seleccionados">
        <div class="mb-3">
            <label class="form-label">Total de la reserva</label>
            <input type="number" name="total_reserva" class="form-control" value="<?php echo htmlspecialchars($reserva['total_reserva']); ?>">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" name="guardar" class="btn btn-success">Guardar cambios</button>
            <button type="submit" name="eliminar" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reserva?');">Eliminar reserva</button>
        </div>
    </form>
    <div class="mt-3">
        <a href="../calendar.php" class="btn btn-secondary">Volver al calendario</a>
    </div>
</div>
</body>
</html>
<script>
let carrito = [];
// DOM elements for equipment management
const equiposLista = document.getElementById('equipos-lista');
const carritoEquipos = document.getElementById('carrito-equipos');
const equiposSeleccionadosInput = document.getElementById('equipos_seleccionados');
// Pre-populate cart with equipos from Reserva_Equipos
<?php
$stmt = $conn->prepare('SELECT equipo_id FROM reserva_equipos WHERE reserva_id = ?');
$stmt->execute([$reserva['reserva_id']]);
$equipos_reserva = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
carrito = <?php echo json_encode(array_map('intval', $equipos_reserva)); ?>;
document.addEventListener('DOMContentLoaded', function() {
    actualizarCarrito();
});
document.getElementById('categoria').addEventListener('change', function() {
    const categoria = this.value;
    equiposLista.innerHTML = '';
    if (!categoria) return;
    fetch(`../../controllers/ajax/get_equipos_por_categoria.php?categoria=${encodeURIComponent(categoria)}`)
        .then(res => res.json())
        .then(data => {
            data.forEach(eq => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = eq.nombre_equipo + (eq.disponible_prestamo ? '' : ' (No disponible)');
                item.disabled = !eq.disponible_prestamo || carrito.includes(eq.equipo_id);
                item.onclick = function() {
                    if (!carrito.includes(eq.equipo_id)) {
                        carrito.push(eq.equipo_id);
                        actualizarCarrito();
                        item.disabled = true;
                    }
                };
                equiposLista.appendChild(item);
            });
        });
});
function actualizarCarrito() {
    carritoEquipos.innerHTML = '';
    carrito.forEach(id => {
        fetch(`../../controllers/ajax/get_equipo_nombre.php?id=${id}`)
            .then(res => res.json())
            .then(eq => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.textContent = eq.nombre_equipo;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm btn-danger';
                btn.textContent = 'Quitar';
                btn.onclick = function() {
                    carrito = carrito.filter(eid => eid !== id);
                    actualizarCarrito();
                    document.getElementById('categoria').dispatchEvent(new Event('change'));
                };
                li.appendChild(btn);
                carritoEquipos.appendChild(li);
            });
    });
    equiposSeleccionadosInput.value = JSON.stringify(carrito);
}
</script>