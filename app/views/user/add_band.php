<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Banda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Crear Nueva Banda</h2>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form action="../../controllers/add_band.php" method="POST">
        <div class="mb-3">
            <label for="nombre_banda" class="form-label">Nombre de la Banda</label>
            <input type="text" class="form-control" id="nombre_banda" name="nombre_banda" maxlength="100" required>
        </div>
        <div class="mb-3">
            <label for="num_integrantes" class="form-label">Número de Integrantes</label>
            <input type="number" class="form-control" id="num_integrantes" name="num_integrantes" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Crear Banda</button>
        <a href="manage_bands.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>