<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión.";
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Reserva.php';

$user_id = intval($_SESSION['user_id']);
$reserva_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$reservaModel = new Reserva($conn);

if ($reservaModel->eliminar($reserva_id, $user_id)) {
    $_SESSION['success'] = "Reserva eliminada correctamente.";
} else {
    $_SESSION['error'] = "No se pudo eliminar la reserva o no tienes permisos.";
}

header("Location: manage_bands.php");
exit();
