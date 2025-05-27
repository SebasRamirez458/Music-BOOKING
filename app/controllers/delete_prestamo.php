<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Inicia sesión para continuar.";
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Prestamo.php';


$user_id = intval($_SESSION['user_id']);
$prestamo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$prestamoModel = new Prestamo($conn);

if ($prestamoModel->eliminar($prestamo_id, $user_id)) {
    $_SESSION['success'] = "Préstamo eliminado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo eliminar el préstamo o no tienes permisos.";
}

header("Location: ../views/user/manage_bands.php");
exit();
