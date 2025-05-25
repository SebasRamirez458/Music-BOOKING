<?php
class Reserva {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear($band_id, $sala_id, $fecha_inicio, $duracion) {
        $sql = "INSERT INTO Reservas (band_id, sala_id, fecha_inicio, duracion_horas) 
                VALUES (:band_id, :sala_id, :fecha_inicio, :duracion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':band_id', $band_id);
        $stmt->bindParam(':sala_id', $sala_id);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':duracion', $duracion);
        return $stmt->execute();
    }

    public function obtenerSalasDisponibles($fecha_inicio, $duracion) {
        $sql = "SELECT * FROM Salas WHERE sala_id NOT IN (
                    SELECT sala_id FROM Reservas 
                    WHERE fecha_inicio < (:fecha_inicio + (:duracion || ' hours')::INTERVAL)
                    AND (fecha_inicio + (duracion_horas || ' hours')::INTERVAL) > :fecha_inicio
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function hayConflicto($sala_id, $fecha_inicio, $duracion) {
        $sql = "SELECT 1 FROM Reservas 
                WHERE sala_id = :sala_id
                AND fecha_inicio < (:fecha_inicio + (:duracion || ' hours')::INTERVAL)
                AND (fecha_inicio + (duracion_horas || ' hours')::INTERVAL) > :fecha_inicio";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':sala_id', $sala_id);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->execute();
        
        return $stmt->fetchColumn() !== false; // Devuelve true si hay conflicto
    }

    public function obtenerSalas() {
        $sql = "SELECT * FROM Salas";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
