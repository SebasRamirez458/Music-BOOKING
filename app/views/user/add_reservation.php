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
<?php include '../layouts/navbar_in.php';?>
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
                <!-- Selección de Equipos -->
                <div class="mb-3">
                    <label class="form-label">Categoría de Equipo</label>
                    <select class="form-select" id="categoria_equipo" name="categoria_equipo">
                        <option value="">Selecciona una categoría</option>
                        <option value="Instrumento">Instrumento</option>
                        <option value="Micrófono">Micrófono</option>
                        <option value="Cableado/conexión">Clabeado/conexión</option>
                        <option value="Amplificadores">Amplificadores</option>
                        <option value="Módulos de efectos">Módulos de efectos</option>
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
                <button type="submit" class="btn btn-success w-100">Reservar</button>
            </form>
            <br>
            <a href="user_home.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    calcularFinYTotal();
});
const equiposLista = document.getElementById('equipos-lista');
const carritoEquipos = document.getElementById('carrito-equipos');
const equiposSeleccionadosInput = document.getElementById('equipos_seleccionados');
let carrito = [];

document.getElementById('categoria_equipo').addEventListener('change', function() {
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
                    document.getElementById('categoria_equipo').dispatchEvent(new Event('change'));
                };
                li.appendChild(btn);
                carritoEquipos.appendChild(li);
            });
    });
    equiposSeleccionadosInput.value = JSON.stringify(carrito);
}
</script>
</body>
</html>
