<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../views/auth/login.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sala_id = $_POST['sala_id'] ?? '';
    $nombre_sala = trim($_POST['nombre_sala'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio_hora = trim($_POST['precio_hora'] ?? '');

    if ($nombre_sala === '' || $descripcion === '' || $precio_hora === '' || !is_numeric($precio_hora) || $precio_hora < 0) {
        $_SESSION['edit_room_error'] = 'Por favor, rellena todos los campos correctamente.';
        header('Location: ../views/admin/edit_room.php?id=' . urlencode($sala_id));
        exit();
    }

    try {
        $stmt = $conn->prepare('UPDATE salas SET nombre_sala = ?, descripcion = ?, precio_hora = ? WHERE sala_id = ?');
        $stmt->execute([$nombre_sala, $descripcion, $precio_hora, $sala_id]);
        $_SESSION['edit_room_success'] = 'Sala actualizada correctamente.';
        header('Location: ../views/admin/manage_rooms.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['edit_room_error'] = 'Error al actualizar la sala: ' . $e->getMessage();
        header('Location: ../views/admin/edit_room.php?id=' . urlencode($sala_id));
        exit();
    }
} else {
    header('Location: ../views/admin/manage_rooms.php');
    exit();
}