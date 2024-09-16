<?php

class Contact
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addContact($name, $email, $message)
    {
        try {
            $query = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Mensaje enviado exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al enviar el mensaje'];
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getAllContacts()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
