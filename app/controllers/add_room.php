<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Music-BOOKING/index.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sala = trim($_POST['nombre_sala'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio_hora = trim($_POST['precio_hora'] ?? '');

    
    if ($nombre_sala === '' || $descripcion === '' || $precio_hora === '' || !is_numeric($precio_hora) || $precio_hora < 0) {
        $_SESSION['add_room_error'] = 'Por favor, rellena todos los campos correctamente.';
        header('Location: /Music-BOOKING/app/views/admin/add_room.php');
        exit();
    }

    try {
        $stmt = $conn->prepare('INSERT INTO salas (nombre_sala, descripcion, precio_hora) VALUES (?, ?, ?)');
        $stmt->execute([$nombre_sala, $descripcion, $precio_hora]);
        $_SESSION['add_room_success'] = 'Sala añadida correctamente.';
        header('Location: /Music-BOOKING/app/views/admin/admin_home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['add_room_error'] = 'Error al añadir la sala: ' . $e->getMessage();
        header('Location: /Music-BOOKING/app/views/admin/add_room.php');
        exit();
    }
} else {
    header('Location: /Music-BOOKING/app/views/admin/add_room.php');
    exit();
}