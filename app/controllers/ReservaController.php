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

    $reserva->crear($band_id, $sala_id, $fecha_inicio, $duracion);
    header("Location: ../../views/dashboard/reservas.php");
    exit();
}

if ($reserva->hayConflicto($sala_id, $fecha_inicio, $duracion)) {
    die("Error: ya existe una reserva para esa sala en ese horario.");
}