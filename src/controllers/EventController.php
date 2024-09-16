<?php

class EventController
{
    private $eventModel;

    public function __construct($eventModel)
    {
        $this->eventModel = $eventModel;
    }

    public function getAllEvents()
    {
        return $this->eventModel->getAllEvents();
    }

    public function createEvent($name, $description, $date)
    {
        if (empty($name) || empty($description) || empty($date)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        $result = $this->eventModel->createEvent($name, $description, $date);
        return $result;
    }


    public function deleteEvent($id)
    {
        if ($this->eventModel->deleteEvent($id)) {
            return ['success' => true];
        } else {
            return ['error' => 'error al eliminar el evento'];
        }
    }

    public function updateEvent($id, $data)
    {
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;
        $date = $data['date'] ?? null;

        if ($name && $description && $date) {
            if ($this->eventModel->updateEvent($id, $name, $description, $date)) {
                return ['success' => true];
            } else {
                return ['error' => 'error al actualizar el evento'];
            }
        } else {
            return ['error' => 'Invalid data'];
        }
    }
}
