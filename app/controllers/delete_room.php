<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../views/auth/login.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if (isset($_GET['id'])) {
    $sala_id = intval($_GET['id']);
    try {
        $stmt = $conn->prepare('DELETE FROM salas WHERE sala_id = ?');
        $stmt->execute([$sala_id]);
        $_SESSION['success'] = 'Sala eliminada correctamente.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al eliminar la sala: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'ID de sala no proporcionado.';
}
header('Location: ../views/admin/manage_rooms.php');
exit();