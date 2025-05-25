<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Registro de Usuario</h4>
                </div>
                <div class="card-body">
                    <form action="../../controllers/AuthController.php" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmar" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" name="confirmar" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>
                    <?php include __DIR__ . '/../layouts/volver.php'; ?>
                </div>
                <div class="card-footer text-center">
                    <small>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
session_start();
$error = isset($_SESSION['register_error']) ? $_SESSION['register_error'] : '';
if ($error) {
    echo '<div class="alert alert-danger text-center">' . htmlspecialchars($error) . '</div>';
    unset($_SESSION['register_error']);
}
?>
