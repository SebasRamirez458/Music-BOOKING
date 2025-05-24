<?php
require_once '../../config/db.php';
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    if ($password !== $confirmar) {
        die("Las contraseñas no coinciden.");
    }

    $usuario = new Usuario($conn);
    
    if ($usuario->existeEmail($email)) {
        die("Este correo ya está registrado.");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $registroExitoso = $usuario->registrar($nombre, $email, $hash);

    if ($registroExitoso) {
        header("Location: ../../views/auth/login.php");
        exit();
    } else {
        die("Error al registrar el usuario.");
    }
}
?>
