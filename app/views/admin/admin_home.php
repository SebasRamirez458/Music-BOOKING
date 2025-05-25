<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}
$nombre = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Bienvenido, Admin <?php echo htmlspecialchars($nombre); ?>!</h4>
                </div>
                <div class="card-body text-center">
                    <p class="lead">Has iniciado sesión como administrador.</p>
                    <a href="/Music-BOOKING/app/views/admin/manage_rooms.php" class="btn btn-outline-success m-2">Administrar Salas</a>
                    <a href="/Music-BOOKING/app/views/admin/manage_equipment.php" class="btn btn-outline-primary m-2">Administrar Equipos</a>
                    <a href="/Music-BOOKING/app/views/admin/manage_reservations.php" class="btn btn-outline-warning m-2">Administrar reservas</a>
                </div>
                <div class="card-body text-center">
                    <a href="/Music-BOOKING/app/views/calendar.php" class="btn btn-outline-info m-2">Mostrar calendario</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>