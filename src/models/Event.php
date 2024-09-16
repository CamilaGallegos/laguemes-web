<?php

class Event
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function createEvent($name, $description, $date)
    {
        try {
            $query = "INSERT INTO events (name, description, date) VALUES (:name, :description, :date)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Evento creado exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al crear el evento'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteEvent($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = :id");
            if ($stmt->execute(['id' => $id])) {
                return ['success' => true];
            } else {
                return ['error' => 'Error al eliminar el evento'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateEvent($id, $name, $description, $date)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE events SET name = :name, description = :description, date = :date WHERE id = :id");
            if ($stmt->execute(['id' => $id, 'name' => $name, 'description' => $description, 'date' => $date])) {
                return ['success' => true];
            } else {
                return ['error' => 'Error al actualizar el evento'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
