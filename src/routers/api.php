<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../controllers/EventController.php';
require_once __DIR__ . '/../controllers/GalleryController.php';
require_once __DIR__ . '/../controllers/ContactController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Gallery.php';
require_once __DIR__ . '/../models/Contact.php';
require_once __DIR__ . '/../models/User.php';

session_start();

class Router
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function handleRequest()
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->handleGet($path);
                break;
            case 'POST':
                $this->handlePost($path);
                break;
            case 'PUT':
                $this->handlePut($path);
                break;
            case 'DELETE':
                $this->handleDelete($path);
                break;
            default:
                echo json_encode(['message' => 'Método no permitido']);
                break;
        }
    }

    private function handleGet($path)
    {
        if (strpos($path, '/laguemes-web/index.php/api/eventos') !== false) {
            $eventModel = new Event($this->pdo);
            $controller = new EventController($eventModel);
            echo json_encode($controller->getAllEvents());
        } elseif (strpos($path, '/laguemes-web/index.php/api/galeria') !== false) {
            $galleryModel = new Gallery($this->pdo);
            $controller = new GalleryController($galleryModel);
            echo json_encode($controller->getAllPhotos());
        } elseif (strpos($path, '/laguemes-web/index.php/api/contacto') !== false) {
            if (isset($_SESSION['user_id'])) {
                $contactModel = new Contact($this->pdo);
                $controller = new ContactController($contactModel);
                $controller->getAllContacts();
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/login') !== false) {
            if (isset($_SESSION['user_id'])) {
                $userModel = new User($this->pdo);
                $controller = new UserController($userModel);
                echo json_encode($controller->getAllUsers());
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } else {
            echo json_encode(['message' => 'Ruta no encontrada']);
        }
    }

    private function handlePost($path)
    {
        if (strpos($path, '/laguemes-web/index.php/api/eventos') !== false) {
            if (isset($_SESSION['user_id'])) {
                // Solo permitir a usuarios autenticados
                $eventModel = new Event($this->pdo);
                $controller = new EventController($eventModel);
                $data = json_decode(file_get_contents('php://input'), true);

                if (isset($data['name'], $data['description'], $data['date'])) {
                    $response = $controller->createEvent($data['name'], $data['description'], $data['date']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['error' => 'Invalid data']);
                }
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/galeria') !== false) {
            if (isset($_SESSION['user_id'])) {
                $galleryModel = new Gallery($this->pdo);
                $controller = new GalleryController($galleryModel);

                if (isset($_FILES['image']['tmp_name']) && !empty($_POST['description'])) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $imagePath = '/uploads/' . basename($_FILES['image']['name']);
                        $description = $_POST['description'];

                        $result = $controller->addPhoto($imagePath, $description);
                        echo json_encode($result);
                    } else {
                        echo json_encode(['error' => 'Error al mover el archivo.']);
                    }
                } else {
                    echo json_encode(['error' => 'Todos los campos son obligatorios']);
                }
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/contacto') !== false) {
            $contactModel = new Contact($this->pdo);
            $controller = new ContactController($contactModel);
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->addContact($data['name'], $data['email'], $data['message']); // Manejar solicitud POST
        } elseif (strpos($path, '/laguemes-web/index.php/api/login') !== false) {
            $userModel = new User($this->pdo);
            $controller = new UserController($userModel);
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['username'], $data['password'])) {
                $response = $controller->login($data['username'], $data['password']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/usuarios') !== false) {
            $userModel = new User($this->pdo);
            $controller = new UserController($userModel);
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['username'], $data['password'])) {
                $response = $controller->register($data['username'], $data['password']);
                echo json_encode($response);
            } else {
                echo json_encode(['error' => 'Datos inválidos']);
            }
        } else {
            echo json_encode(['message' => 'Ruta no encontrada']);
        }
    }

    private function handlePut($path)
    {
        if (strpos($path, '/laguemes-web/index.php/api/eventos') !== false) {
            if (isset($_SESSION['user_id'])) {
                $eventModel = new Event($this->pdo);
                $controller = new EventController($eventModel);
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $this->getIdFromPath($path);
                echo json_encode($controller->updateEvent($id, $data));
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/galeria') !== false) {
            if (isset($_SESSION['user_id'])) {
                $galleryModel = new Gallery($this->pdo);
                $controller = new GalleryController($galleryModel);
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $this->getIdFromPath($path);
                echo json_encode($controller->updatePhoto($id, $data['image_path'], $data['description']));
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/login') !== false) {
            if (isset($_SESSION['user_id'])) {
                $userModel = new User($this->pdo);
                $controller = new UserController($userModel);
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $this->getIdFromPath($path);
                echo json_encode($controller->updateUser($id, $data));
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } else {
            echo json_encode(['message' => 'Ruta no encontrada']);
        }
    }

    private function handleDelete($path)
    {
        if (strpos($path, '/laguemes-web/index.php/api/eventos') !== false) {
            if (isset($_SESSION['user_id'])) {
                $eventModel = new Event($this->pdo);
                $controller = new EventController($eventModel);
                $id = $this->getIdFromPath($path);
                echo json_encode($controller->deleteEvent($id));
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/galeria') !== false) {
            if (isset($_SESSION['user_id'])) {
                $galleryModel = new Gallery($this->pdo);
                $controller = new GalleryController($galleryModel);
                $id = $this->getIdFromPath($path);
                echo json_encode($controller->deletePhoto($id));
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } elseif (strpos($path, '/laguemes-web/index.php/api/login') !== false) {
            if (isset($_SESSION['user_id'])) {
                $userModel = new User($this->pdo);
                $controller = new UserController($userModel);
                $id = $this->getIdFromPath($path);
                echo json_encode($controller->deleteUser($id));
            } else {
                echo json_encode(['error' => 'No autorizado']);
            }
        } else {
            echo json_encode(['message' => 'Ruta no encontrada']);
        }
    }

    private function getIdFromPath($path)
    {
        $parts = explode('/', $path);
        return end($parts);
    }
}
