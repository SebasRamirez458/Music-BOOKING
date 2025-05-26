<?php
class Banda {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerBandaDelUsuario($band_id, $user_id) {
        $sql = "SELECT * FROM Bandas WHERE band_id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $band_id,
            ':uid' => $user_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function tieneRelacion($band_id) {
    $sql1 = "SELECT 1 FROM Reservas WHERE band_id = :id LIMIT 1";
    $sql2 = "SELECT 1 FROM Prestamos WHERE band_id = :id LIMIT 1";

    $stmt1 = $this->conn->prepare($sql1);
    $stmt2 = $this->conn->prepare($sql2);
    $stmt1->bindParam(':id', $band_id);
    $stmt2->bindParam(':id', $band_id);
    $stmt1->execute();
    $stmt2->execute();

    return $stmt1->fetchColumn() !== false || $stmt2->fetchColumn() !== false;
    }


    public function eliminar($band_id) {
        $sql = "DELETE FROM Bandas WHERE band_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $band_id);
        return $stmt->execute();
    }
}
?>
