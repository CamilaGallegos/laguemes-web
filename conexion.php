<?php
require 'vendor/autoload.php';
require 'src/config/database.php';

$db = new Database();
$connection = $db->getConnection();

if ($connection) {
    echo "Conexión a la base de datos exitosa.";
} else {
    echo "Error al conectar con la base de datos.";
}
