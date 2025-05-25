<?php
require_once 'config/db.php';

$stmt = $conn->query("SELECT 1;");
$result = $stmt->fetch();
echo "Conexión verificada. Resultado: " . $result[0];
?>