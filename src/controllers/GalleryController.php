<?php

class GalleryController
{
    private $galleryModel;

    public function __construct($galleryModel)
    {
        $this->galleryModel = $galleryModel;
    }

    public function getAllPhotos()
    {
        return $this->galleryModel->getAllPhotos();
    }

    public function addPhoto($imagePath, $description)
    {
        if (empty($imagePath) || empty($description)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }
        $result = $this->galleryModel->addPhoto($imagePath, $description);
        if ($result) {
            return ['success' => true, 'message' => 'Imagen añadida exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al añadir la imagen'];
        }
    }



    public function deletePhoto($id)
    {
        return $this->galleryModel->deletePhoto($id);
    }

    public function updatePhoto($id, $imagePath, $description)
    {
        return $this->galleryModel->updatePhoto($id, $imagePath, $description);
    }
}
