<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: admin_home.php');
    exit();
}
$sala_id = $_GET['id'];

try {
    $stmt = $conn->prepare('SELECT * FROM salas WHERE sala_id = ?');
    $stmt->execute([$sala_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$room) {
        $_SESSION['edit_room_error'] = 'Sala no encontrada.';
        header('Location: manage_rooms.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['edit_room_error'] = 'Error al obtener la sala: ' . $e->getMessage();
    header('Location: manage_rooms.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Editar Sala</div>
                <div class="card-body">
                    <?php if (isset($_SESSION['edit_room_error'])): ?>
                        <div class="alert alert-danger"> <?php echo $_SESSION['edit_room_error']; unset($_SESSION['edit_room_error']); ?> </div>
                    <?php endif; ?>
                    <form action="../../controllers/edit_room.php" method="POST">
                        <input type="hidden" name="sala_id" value="<?php echo htmlspecialchars($room['sala_id']); ?>">
                        <div class="mb-3">
                            <label for="nombre_sala" class="form-label">Nombre de la Sala</label>
                            <input type="text" class="form-control" id="nombre_sala" name="nombre_sala" maxlength="100" required value="<?php echo htmlspecialchars($room['nombre_sala']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($room['descripcion']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio_hora" class="form-label">Precio por Hora ($)</label>
                            <input type="number" class="form-control" id="precio_hora" name="precio_hora" step="0.01" min="0" required value="<?php echo htmlspecialchars($room['precio_hora']); ?>">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="manage_rooms.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>