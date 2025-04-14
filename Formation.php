<?php

include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Formation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Add a formation
    public function addFormation($name, $description, $price, $duration, $type)
    {
        $query = "INSERT INTO formations (name, description, price, duration, type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $price);
        $stmt->bindParam(4, $duration);
        $stmt->bindParam(5, $type);
        return $stmt->execute();
    }

    // Get all formations
    public function getAllFormations()
    {
        $query = "SELECT * FROM formations";
        $stmt = $this->conn->query($query);
        return $stmt;
    }

    // Update a formation
    public function updateFormation($id, $name, $description, $price, $duration, $type)
    {
        $query = "UPDATE formations SET name=?, description=?, price=?, duration=?, type=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $price);
        $stmt->bindParam(4, $duration);
        $stmt->bindParam(5, $type);
        $stmt->bindParam(6, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete a formation
    public function deleteFormation($id)
    {
        $query = "DELETE FROM formations WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Reserve a formation
    public function reserveFormation($userId, $formationId)
    {
        // Start transaction to ensure both actions (mark formation reserved and insert into reservations) are atomic
        $this->conn->beginTransaction();

        try {
            // Mark the formation as reserved
            $query = "UPDATE formations SET reserved = TRUE WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $formationId, PDO::PARAM_INT);
            $stmt->execute();

            // Insert reservation into the reservations table
            $query = "INSERT INTO reservations (user_id, formation_id) VALUES (:user_id, :formation_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':formation_id', $formationId, PDO::PARAM_INT);
            $stmt->execute();

            // Commit the transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback in case of an error
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAllReservations()
    {
        $query = "
        SELECT 
            r.id,
            u.username,
            f.name AS formation_name,
            f.duration,
            r.reserved_at
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN formations f ON r.formation_id = f.id
        ORDER BY r.reserved_at DESC
    ";

        $stmt = $this->conn->query($query);
        return $stmt;
    }
}
