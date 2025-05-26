<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Inicia sesión para continuar.";
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Banda.php';

$user_id = intval($_SESSION['user_id']);
$band_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


$bandaModel = new Banda($conn);

$banda = $bandaModel->obtenerBandaDelUsuario($band_id, $user_id);

if (!$banda) {
    $_SESSION['error'] = "La banda no existe o no te pertenece.";
    header("Location: ../user/manage_bands.php");
    exit();
}


if ($bandaModel->tieneRelacion($band_id)) {
    $_SESSION['error'] = "No se puede eliminar la banda porque tiene reservas o préstamos asociados.";
    header("Location: ../user/manage_bands.php");
    exit();
}


if ($bandaModel->eliminar($band_id)) {
    $_SESSION['success'] = "Banda eliminada correctamente.";
} else {
    $_SESSION['error'] = "Error al eliminar la banda.";
}

header("Location: ../user/manage_bands.php");
exit();
