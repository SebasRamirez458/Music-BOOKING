<?php
session_start();
require_once '../../config/db.php';
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuario = new Usuario($conn);
    $userData = $usuario->autenticar($email, $password);

    if ($userData) {
        $_SESSION['usuario'] = $userData['nombre'];
        $_SESSION['is_admin'] = !empty($userData['is_admin']) && $userData['is_admin'];
        unset($_SESSION['login_error']);
        if ($_SESSION['is_admin']) {
            // se muy cuidadoso con estas direcciones, tipo, si las cambias verifica que los archivos existan
            header('Location: ../views/admin/admin_home.php');
        } else {
            header('Location: ../viewsuser/user_home.php');
        }
        exit();
    } else {
        $_SESSION['login_error'] = 'Correo o contraseña incorrectos.';
        header('Location: ../views/auth/login.php');
        exit();
    }
}
?>