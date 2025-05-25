<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../../config/db.php';

$equipment = [];
try {
    $stmt = $conn->query('SELECT equipo_id, nombre_equipo, descripcion, precio_dia, categoria, disponible_prestamo FROM equipos');
    $equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error al obtener los equipos: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Administrar Equipos</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <a href="/Music-BOOKING/app/views/admin/add_equipment.php" class="btn btn-primary m-2">Agregar Equipo</a>
    <br><br>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio/Día ($)</th>
                <th>Categoría</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($equipment as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['equipo_id']); ?></td>
                <td><?php echo htmlspecialchars($item['nombre_equipo']); ?></td>
                <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($item['precio_dia']); ?></td>
                <td><?php echo htmlspecialchars($item['categoria']); ?></td>
                <td><?php echo $item['disponible_prestamo'] ? 'Sí' : 'No'; ?></td>
                <td>
                    <a href="edit_equipment.php?id=<?php echo urlencode($item['equipo_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="../../controllers/delete_equipment.php?id=<?php echo urlencode($item['equipo_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_home.php" class="btn btn-secondary mt-3">Volver al menú principal</a>
</div>
</body>
</html>