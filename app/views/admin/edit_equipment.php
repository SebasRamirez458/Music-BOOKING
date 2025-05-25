<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../../config/db.php';

$equipo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($equipo_id <= 0) {
    $_SESSION['error'] = 'ID de equipo no válido.';
    header('Location: manage_equipment.php');
    exit();
}

try {
    $stmt = $conn->prepare('SELECT * FROM equipos WHERE equipo_id = ?');
    $stmt->execute([$equipo_id]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$equipo) {
        $_SESSION['error'] = 'Equipo no encontrado.';
        header('Location: manage_equipment.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error al obtener el equipo: ' . $e->getMessage();
    header('Location: manage_equipment.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Editar Equipo</h2>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form action="../../controllers/edit_equipment.php" method="POST">
        <input type="hidden" name="equipo_id" value="<?php echo htmlspecialchars($equipo['equipo_id']); ?>">
        <div class="mb-3">
            <label for="nombre_equipo" class="form-label">Nombre del Equipo</label>
            <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" value="<?php echo htmlspecialchars($equipo['nombre_equipo']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo htmlspecialchars($equipo['descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio_dia" class="form-label">Precio por Día (€)</label>
            <input type="number" step="0.01" class="form-control" id="precio_dia" name="precio_dia" value="<?php echo htmlspecialchars($equipo['precio_dia']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo htmlspecialchars($equipo['categoria']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="disponible_prestamo" class="form-label">Disponible para Préstamo</label>
            <select class="form-select" id="disponible_prestamo" name="disponible_prestamo" required>
                <option value="1" <?php if ($equipo['disponible_prestamo']) echo 'selected'; ?>>Sí</option>
                <option value="0" <?php if (!$equipo['disponible_prestamo']) echo 'selected'; ?>>No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="manage_equipment.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>