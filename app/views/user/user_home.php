<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$nombre = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h4>
                </div>
                <div class="card-body text-center">
                    <p class="lead">Has iniciado sesión correctamente.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>