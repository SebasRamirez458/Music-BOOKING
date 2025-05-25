<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../../config/db.php';

$rooms = [];
try {
    $stmt = $conn->query('SELECT sala_id, nombre_sala, descripcion, precio_hora FROM salas');
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error al obtener las salas: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Administrar Salas</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <a href="/Music-BOOKING/app/views/admin/add_room.php" class="btn btn-success m-2">Agregar Sala</a>
    <br><br>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio/Hora ($)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?php echo htmlspecialchars($room['sala_id']); ?></td>
                <td><?php echo htmlspecialchars($room['nombre_sala']); ?></td>
                <td><?php echo htmlspecialchars($room['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($room['precio_hora']); ?></td>
                <td>
                    <a href="edit_room.php?id=<?php echo urlencode($room['sala_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="../../controllers/delete_room.php?id=<?php echo urlencode($room['sala_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta sala?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_home.php" class="btn btn-secondary mt-3">Volver al menú principal</a>
</div>
</body>
</html>