<?php
require_once __DIR__ . '/config.php';

$host = getenv('MYSQL_HOST') ?: 'bklh49kulsoolhk6uayw-mysql.services.clever-cloud.com';
$user = getenv('MYSQL_USER') ?: 'u4j93xe2z4ovjypf';
$password = getenv('MYSQL_PASSWORD') ?: 'OhMPlDSPOQRaYaWVqE3s';
$database = getenv('MYSQL_DATABASE') ?: 'bklh49kulsoolhk6uayw';
$port = getenv('MYSQL_PORT') ?: '3306';

$conexion = mysqli_connect($host, $user, $password, $database, $port);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>