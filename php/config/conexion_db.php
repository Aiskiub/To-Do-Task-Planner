<?php
require_once __DIR__ . '/config.php';

if (getenv('DATABASE_URL')) {
    require_once __DIR__ . '/render_db.php';
} else {
    $host = "localhost";
    $usuario = "root";
    $password = "";
    $database = "to_do_db";

    $conexion = mysqli_connect($host, $usuario, $password, $database);
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
}
?>