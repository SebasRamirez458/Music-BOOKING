<?php
require_once '../../config/db.php';
require_once '../models/Usuario.php';

session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    if ($password !== $confirmar) {
        $_SESSION['register_error'] = 'Las contraseñas no coinciden.';
        header('Location: ../views/auth/Register.php');
        exit();
    }

    $usuario = new Usuario($conn);
    if ($usuario->existeEmail($email)) {
        $_SESSION['register_error'] = 'Este correo ya está registrado.';
        header('Location: ../views/auth/Register.php');
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $registroExitoso = $usuario->registrar($nombre, $email, $hash);

    if ($registroExitoso) {
        unset($_SESSION['register_error']);
        header('Location: ../views/auth/login.php');
        exit();
    } else {
        $_SESSION['register_error'] = 'Error al registrar el usuario.';
        header('Location: ../views/auth/Register.php');
        exit();
    }
}
?>
