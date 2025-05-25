<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../views/auth/login.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo_id = isset($_POST['equipo_id']) ? intval($_POST['equipo_id']) : 0;
    $nombre_equipo = trim($_POST['nombre_equipo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio_dia = floatval($_POST['precio_dia'] ?? 0);
    $categoria = trim($_POST['categoria'] ?? '');
    $disponible_prestamo = isset($_POST['disponible_prestamo']) ? intval($_POST['disponible_prestamo']) : 0;

    if ($equipo_id <= 0 || $nombre_equipo === '' || $descripcion === '' || $precio_dia <= 0 || $categoria === '') {
        $_SESSION['error'] = 'Por favor, complete todos los campos correctamente.';
        header('Location: ../views/admin/edit_equipment.php?id=' . urlencode($equipo_id));
        exit();
    }

    try {
        $stmt = $conn->prepare('UPDATE equipos SET nombre_equipo = ?, descripcion = ?, precio_dia = ?, categoria = ?, disponible_prestamo = ? WHERE equipo_id = ?');
        $stmt->execute([$nombre_equipo, $descripcion, $precio_dia, $categoria, $disponible_prestamo, $equipo_id]);
        $_SESSION['success'] = 'Equipo actualizado correctamente.';
        header('Location: ../views/admin/manage_equipment.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al actualizar el equipo: ' . $e->getMessage();
        header('Location: ../views/admin/edit_equipment.php?id=' . urlencode($equipo_id));
        exit();
    }
} else {
    $_SESSION['error'] = 'Acceso no válido.';
    header('Location: ../views/admin/manage_equipment.php');
    exit();
}