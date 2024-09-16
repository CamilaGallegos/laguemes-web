<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'src/config/database.php';
require_once 'src/routers/api.php';

// index.php o api.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Conectar a la base de datos
$database = new Database();
$pdo = $database->getConnection();

// Crear un enrutador
$router = new Router($pdo);

// Procesar la solicitud según la ruta y el método
$router->handleRequest();
