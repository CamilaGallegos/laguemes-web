<?php

class Gallery
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllPhotos()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM gallery");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function addPhoto($imagePath, $description)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO gallery (image_path, description) VALUES (:image_path, :description)");
            $stmt->bindParam(':image_path', $imagePath);
            $stmt->bindParam(':description', $description);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Imagen añadida exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al añadir la imagen'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deletePhoto($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM gallery WHERE id = :id");
            if ($stmt->execute(['id' => $id])) {
                return ['success' => true];
            } else {
                return ['error' => 'Error al eliminar la imagen'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updatePhoto($id, $imagePath, $description)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE gallery SET image_path = :image_path, description = :description WHERE id = :id");
            if ($stmt->execute(['id' => $id, 'image_path' => $imagePath, 'description' => $description])) {
                return ['success' => true];
            } else {
                return ['error' => 'Error al actualizar la imagen'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
