<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Music-BOOKING/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Añadir Nueva Sala</div>
                <div class="card-body">
                    <form action="/Music-BOOKING/app/controllers/add_room.php" method="POST">
                        <div class="mb-3">
                            <label for="nombre_sala" class="form-label">Nombre de la Sala</label>
                            <input type="text" class="form-control" id="nombre_sala" name="nombre_sala" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio_hora" class="form-label">Precio por Hora (€)</label>
                            <input type="number" class="form-control" id="precio_hora" name="precio_hora" step="0.01" min="0" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="/Music-BOOKING/app/views/auth/admin_home.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Añadir Sala</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>