<?php
session_start();
require_once '../../config/db.php';
require_once '../models/Reserva.php';

$reserva = new Reserva($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $band_id = $_POST['band_id'];
    $sala_id = $_POST['sala_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $duracion = $_POST['duracion_horas'];
    $total_reserva = isset($_POST['total_reserva']) ? floatval($_POST['total_reserva']) : 0;
    $equipos = isset($_POST['equipos_seleccionados']) ? json_decode($_POST['equipos_seleccionados'], true) : [];

    if ($reserva->hayConflicto($sala_id, $fecha_inicio, $duracion)) {
        die("Error: ya existe una reserva para esa sala en ese horario.");
    }

    $reserva_id = $reserva->crear($band_id, $sala_id, $fecha_inicio, $duracion, $total_reserva);

    if ($reserva_id && is_array($equipos)) {
        $stmt = $conn->prepare('INSERT INTO Reserva_Equipos (reserva_id, equipo_id) VALUES (?, ?)');
        foreach ($equipos as $equipo_id) {
            $stmt->execute([$reserva_id, $equipo_id]);
        }
    }
    header("Location: ../views/user/manage_bands.php");
    exit();
}