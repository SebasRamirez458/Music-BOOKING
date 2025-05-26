<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Banda.php';

$bandaModel = new Banda($conn);
$user_id = intval($_SESSION['user_id']);

// --- GET: mostrar datos existentes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $band_id = intval($_GET['id']);
    $banda = $bandaModel->obtenerBandaDelUsuario($band_id, $user_id);

    if (!$banda) {
        $_SESSION['error'] = "No tienes acceso a esta banda.";
        header("Location: ../user/manage_bands.php");
        exit();
    }
}

// --- POST: procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $band_id = intval($_POST['band_id']);
    $nombre = trim($_POST['nombre_banda']);
    $integrantes = intval($_POST['num_integrantes']);

    $actualizado = $bandaModel->actualizar($band_id, $user_id, $nombre, $integrantes);

    if ($actualizado) {
        $_SESSION['success'] = "Banda actualizada correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar la banda.";
    }

    header("Location: ../user/manage_bands.php");
    exit();
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Banda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header"><h4>Editar Banda</h4></div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="band_id" value="<?= $banda['band_id'] ?>">
                <div class="mb-3">
                    <label for="nombre_banda" class="form-label">Nombre de la Banda</label>
                    <input type="text" name="nombre_banda" class="form-control" value="<?= htmlspecialchars($banda['nombre_banda']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="num_integrantes" class="form-label">Número de Integrantes</label>
                    <input type="number" name="num_integrantes" class="form-control" value="<?= htmlspecialchars($banda['num_integrantes']) ?>" required min="1">
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="../user/manage_bands.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
