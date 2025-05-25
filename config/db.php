<?php
// Parámetros de conexión (debes obtenerlos desde tu cuenta de Supabase)
$host = 'db.eeuajwnkjuufegwuzmmz.supabase.co';
$dbname = 'postgres';
$user = 'postgres';
$password = 'tbatqT3AxSzzNWxi';
$port = '5432'; // Supabase usa el puerto 5432 para PostgreSQL

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Puedes descomentar esto para pruebas
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
