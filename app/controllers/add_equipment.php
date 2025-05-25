<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Music-BOOKING/index.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_equipo = trim($_POST['nombre_equipo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio_dia = trim($_POST['precio_dia'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $disponible_prestamo = isset($_POST['disponible_prestamo']) ? true : false;

    if ($nombre_equipo === '' || ($precio_dia !== '' && (!is_numeric($precio_dia) || $precio_dia < 0))) {
        $_SESSION['add_equipment_error'] = 'Por favor, rellena los campos obligatorios correctamente.';
        header('Location: /Music-BOOKING/app/views/admin/add_equipment.php');
        exit();
    }

    try {
        $stmt = $conn->prepare('INSERT INTO equipos (nombre_equipo, descripcion, precio_dia, categoria, disponible_prestamo) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $nombre_equipo,
            $descripcion,
            $precio_dia !== '' ? $precio_dia : null,
            $categoria,
            $disponible_prestamo
        ]);
        $_SESSION['add_equipment_success'] = 'Equipo añadido correctamente.';
        header('Location: /Music-BOOKING/app/views/admin/admin_home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['add_equipment_error'] = 'Error al añadir el equipo: ' . $e->getMessage();
        header('Location: /Music-BOOKING/app/views/admin/add_equipment.php');
        exit();
    }
} else {
    header('Location: /Music-BOOKING/app/views/admin/add_equipment.php');
    exit();
}