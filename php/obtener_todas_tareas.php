<?php
session_start();
include '../conexion_db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$query = "SELECT DISTINCT a.*, c.nombre as categoria_nombre 
          FROM actividad a
          LEFT JOIN actividad_categoria ac ON a.id = ac.actividad_id
          LEFT JOIN categoria c ON ac.categoria_id = c.id
          WHERE a.usuario_id = ?
          ORDER BY a.fecha_ejecucion ASC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$tareas = [];
while ($tarea = $result->fetch_assoc()) {
    $tareas[] = $tarea;
}

header('Content-Type: application/json');
echo json_encode($tareas);
?> 