<?php
$database_url = getenv('DATABASE_URL');

if ($database_url) {
    $url = parse_url($database_url);
    
    $host = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = substr($url["path"], 1);
    
    $conexion = mysqli_connect($host, $username, $password, $database);
    
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
} else {
    // Fall back to local development settings
    require_once 'conexion_db.php';
}
?> 