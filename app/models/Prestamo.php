<?php
class Prestamo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear($band_id, $fecha_inicio, $fecha_fin, $total_prestamo) {
        $sql = "INSERT INTO Prestamos (band_id, fecha_inicio_prestamo, fecha_fin_prestamo, total_prestamo)
                VALUES (:band_id, :inicio, :fin, :total)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':band_id', $band_id);
        $stmt->bindParam(':inicio', $fecha_inicio);
        $stmt->bindParam(':fin', $fecha_fin);
        $stmt->bindParam(':total', $total_prestamo);
        $stmt->execute();
        return $this->conn->lastInsertId(); // ID del préstamo creado
    }

    public function obtenerEquiposDisponibles($fecha_inicio, $fecha_fin) {
        $sql = "SELECT * FROM Equipos 
                WHERE disponible_prestamo = TRUE
                AND equipo_id NOT IN (
                    SELECT equipo_id FROM Prestamo_Equipos pe
                    INNER JOIN Prestamos p ON p.prestamo_id = pe.prestamo_id
                    WHERE :inicio < p.fecha_fin_prestamo AND :fin > p.fecha_inicio_prestamo
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':inicio', $fecha_inicio);
        $stmt->bindParam(':fin', $fecha_fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function vincularEquipo($prestamo_id, $equipo_id, $precio_unitario) {
        $sql = "INSERT INTO Prestamo_Equipos (prestamo_id, equipo_id, precio_unitario_prestamo)
                VALUES (:prestamo_id, :equipo_id, :precio)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':prestamo_id', $prestamo_id);
        $stmt->bindParam(':equipo_id', $equipo_id);
        $stmt->bindParam(':precio', $precio_unitario);
        return $stmt->execute();
    }
}
?>
