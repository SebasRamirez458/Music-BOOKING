<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../views/auth/login.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if (isset($_GET['id'])) {
    $equipo_id = intval($_GET['id']);
    try {
        $stmt = $conn->prepare('DELETE FROM equipos WHERE equipo_id = ?');
        $stmt->execute([$equipo_id]);
        $_SESSION['success'] = 'Equipo eliminado correctamente.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al eliminar el equipo: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'ID de equipo no proporcionado.';
}
header('Location: ../views/admin/manage_equipment.php');
exit();