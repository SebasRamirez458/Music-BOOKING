<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['user_id'])) {
    header('Location: ../views/auth/login.php');
    exit();
}
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_SESSION['user_id']);
    $nombre_banda = trim($_POST['nombre_banda'] ?? '');
    $num_integrantes = intval($_POST['num_integrantes'] ?? 0);

    if ($nombre_banda === '' || $num_integrantes <= 0) {
        $_SESSION['error'] = 'Por favor, complete todos los campos correctamente.';
        header('Location: ../views/user/add_band.php');
        exit();
    }

    try {
        $stmt = $conn->prepare('INSERT INTO bandas (user_id, nombre_banda, num_integrantes) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $nombre_banda, $num_integrantes]);
        $_SESSION['success'] = 'Banda creada correctamente.';
        header('Location: ../views/user/manage_bands.php');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = 'El nombre de la banda ya existe. Por favor, elija otro.';
        } else {
            $_SESSION['error'] = 'Error al crear la banda: ' . $e->getMessage();
        }
        header('Location: ../views/user/add_band.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Acceso no válido.';
    header('Location: ../views/user/manage_bands.php');
    exit();
}