<?php
require_once __DIR__ . '/../models/Contact.php';

class ContactController
{
    private $contactModel;

    public function __construct($contactModel)
    {
        $this->contactModel = $contactModel;
    }

    public function addContact($name, $email, $message)
    {
        if (empty($name) || empty($email) || empty($message)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        $result = $this->contactModel->addContact($name, $email, $message);
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function getAllContacts()
    {
        $contacts = $this->contactModel->getAllContacts();
        header('Content-Type: application/json');
        echo json_encode($contacts);
    }
}
