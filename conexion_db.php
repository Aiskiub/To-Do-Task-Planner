<?php
    $host = "localhost";
    $usuario = "root";
    $password = "";
    $database = "to_do_db";

    $conexion = mysqli_connect($host, $usuario, $password, $database);
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>