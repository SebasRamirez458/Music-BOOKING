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

$banda = new Banda($conn);

$banda_valida = $banda->obtenerBandaDelUsuario($band_id, $user_id);

if (!$banda_valida) {
    $_SESSION['error'] = "No se puede eliminar esta banda.";
    header("Location: manage_bands.php");
    exit();
}

if ($banda->eliminar($band_id)) {
    $_SESSION['success'] = "Banda eliminada exitosamente.";
} else {
    $_SESSION['error'] = "Error al eliminar la banda.";
}

header("Location: manage_bands.php");
exit();
