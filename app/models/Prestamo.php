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
        return $this->conn->lastInsertId(); 
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
    public function obtenerPorUsuario($user_id) {
    $sql = "SELECT p.prestamo_id, p.fecha_inicio_prestamo, p.fecha_fin_prestamo,
                   p.total_prestamo, b.nombre_banda
            FROM Prestamos p
            JOIN Bandas b ON p.band_id = b.band_id
            WHERE b.user_id = :user_id
            ORDER BY p.fecha_inicio_prestamo DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function eliminar($prestamo_id, $user_id) {

    $sql = "SELECT p.prestamo_id
            FROM Prestamos p
            JOIN Bandas b ON p.band_id = b.band_id
            WHERE p.prestamo_id = :id AND b.user_id = :user_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $prestamo_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if (!$stmt->fetch()) {
        return false;
    }


    $sqlEquipos = "DELETE FROM Prestamo_Equipos WHERE prestamo_id = :id";
    $stmt = $this->conn->prepare($sqlEquipos);
    $stmt->bindParam(':id', $prestamo_id);
    $stmt->execute();


    $sqlPrestamo = "DELETE FROM Prestamos WHERE prestamo_id = :id";
    $stmt = $this->conn->prepare($sqlPrestamo);
    $stmt->bindParam(':id', $prestamo_id);
    return $stmt->execute();
    }   


}
?>
