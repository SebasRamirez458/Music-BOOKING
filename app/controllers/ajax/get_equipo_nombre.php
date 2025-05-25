<?php
//un ajax es una peticion asincrona que se hace al servidor y se actualiza la pagina sin recargar
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo json_encode(['nombre_equipo' => 'Equipo desconocido']);
    exit;
}
$stmt = $conn->prepare('SELECT nombre_equipo FROM Equipos WHERE equipo_id = ?');
$stmt->execute([$id]);
$eq = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($eq ? $eq : ['nombre_equipo' => 'Equipo desconocido']);