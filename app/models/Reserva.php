<?php
class Reserva {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear($band_id, $sala_id, $fecha_inicio, $duracion) {
        $inicio = new DateTime($fecha_inicio);
        $fin = clone $inicio;
        $fin->modify("+{$duracion} hours");
        $fecha_fin = $fin->format('Y-m-d H:i:s');
        $fecha_creacion = (new DateTime('now', new DateTimeZone('America/Bogota')))->format('Y-m-d H:i:s');
        $sql = "INSERT INTO Reservas (band_id, sala_id, fecha_inicio, fecha_fin, duracion_horas, fecha_creacion) 
                VALUES (:band_id, :sala_id, :fecha_inicio, :fecha_fin, :duracion, :fecha_creacion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':band_id', $band_id);
        $stmt->bindParam(':sala_id', $sala_id);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->bindParam(':fecha_creacion', $fecha_creacion);
        $stmt->execute();
        return $this->conn->lastInsertId();
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
        $inicio = new DateTime($fecha_inicio);
        $fin = clone $inicio;
        $fin->modify("+{$duracion} hours");
        $fecha_fin = $fin->format('Y-m-d H:i:s');
        $sql = "SELECT 1 FROM Reservas 
                WHERE sala_id = :sala_id
                AND (
                    (fecha_inicio < :fecha_fin AND fecha_fin > :fecha_inicio)
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':sala_id', $sala_id);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();
        return $stmt->fetchColumn() !== false;
    }

    public function obtenerSalas() {
        $sql = "SELECT * FROM Salas";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
