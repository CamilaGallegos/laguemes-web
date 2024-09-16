<?php

require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/EventController.php';
require_once __DIR__ . '/controllers/GalleryController.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Event.php';
require_once __DIR__ . '/models/Gallery.php';
require_once __DIR__ . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$eventModel = new Event($db);
$galleryModel = new Gallery($db);

$userController = new UserController($userModel);
$eventController = new EventController($eventModel);
$galleryController = new GalleryController($galleryModel);

$router->get('/api/events', 'EventController@index');
$router->get('/api/events/{id}', 'EventController@show');
$router->post('/api/events', 'EventController@store');
$router->put('/api/events/{id}', 'EventController@update');
$router->delete('/api/events/{id}', 'EventController@destroy');

$action = $_GET['action'] ?? '';

//rutas para usuarios
switch ($action) {
    case 'login':
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($userController->login($username, $password)) {
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
        }
        break;

    case 'register':
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($userController->register($username, $password)) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
        }
        break;

    case 'logout':
        $userController->logout();
        echo json_encode(['success' => true, 'message' => 'Sesión cerrada exitosamente']);
        break;

        //rutas para eventos
    case 'getAllEvents':
        echo json_encode($eventController->getAllEvents());
        break;

    case 'createEvent':
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        if ($eventController->createEvent($name, $description, $date)) {
            echo json_encode(['success' => true, 'message' => 'Evento creado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el evento']);
        }
        break;

    case 'deleteEvent':
        $id = $_POST['id'] ?? 0;
        if ($eventController->deleteEvent($id)) {
            echo json_encode(['success' => true, 'message' => 'Evento eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el evento']);
        }
        break;

    case 'updateEvent':
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        if ($eventController->updateEvent($id, $name, $description, $date)) {
            echo json_encode(['success' => true, 'message' => 'Evento actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el evento']);
        }
        break;

        //rutas para galeria
    case 'getAllImages':
        echo json_encode($galleryController->getAllImages());
        break;

    case 'addImage':
        $imagePath = $_POST['image_path'] ?? '';
        $description = $_POST['description'] ?? '';
        if ($galleryController->addImage($imagePath, $description)) {
            echo json_encode(['success' => true, 'message' => 'Imagen añadida exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al añadir la imagen']);
        }
        break;

    case 'deleteImage':
        $id = $_POST['id'] ?? 0;
        if ($galleryController->deleteImage($id)) {
            echo json_encode(['success' => true, 'message' => 'Imagen eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la imagen']);
        }
        break;

    default:
        echo json_encode(['message' => 'Acción no válida']);
        break;
}
