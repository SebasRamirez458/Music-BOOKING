<?php
require_once '../../config/db.php';
require_once '../models/Prestamo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestamo = new Prestamo($conn);

    $band_id = $_POST['band_id'];
    $inicio = $_POST['fecha_inicio'];
    $fin = $_POST['fecha_fin'];
    $equipos = $_POST['equipos']; 
    $total = 0;

 
    $in = str_repeat('?,', count($equipos) - 1) . '?';
    $stmt = $conn->prepare("SELECT equipo_id, precio_dia FROM Equipos WHERE equipo_id IN ($in)");
    $stmt->execute($equipos);
    $equipos_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dias = ceil((strtotime($fin) - strtotime($inicio)) / (60 * 60 * 24));
    foreach ($equipos_data as $eq) {
        $total += $eq['precio_dia'] * $dias;
    }

    $prestamo_id = $prestamo->crear($band_id, $inicio, $fin, $total);


    foreach ($equipos_data as $eq) {
        $prestamo->vincularEquipo($prestamo_id, $eq['equipo_id'], $eq['precio_dia']);
    }

    header("Location: ../views/user/manage_bands.php");

    exit();
}
