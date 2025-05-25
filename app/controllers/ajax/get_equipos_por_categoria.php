<?php
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json');
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
if (!$categoria) {
    echo json_encode([]);
    exit;
}
$stmt = $conn->prepare('SELECT equipo_id, nombre_equipo, disponible_prestamo FROM Equipos WHERE categoria = ?');
$stmt->execute([$categoria]);
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($equipos);