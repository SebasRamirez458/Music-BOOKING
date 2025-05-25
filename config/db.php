<?php

// Cambié el modo de conección

$host = 'aws-0-us-east-2.pooler.supabase.com';
$dbname = 'postgres';
$user = 'postgres.eeuajwnkjuufegwuzmmz';
$password = 'tbatqT3AxSzzNWxi';
$port = '6543'; 
$pool_mode = 'transaction';

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Puedes descomentar esto para pruebas
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
