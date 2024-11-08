<?php
session_start();
include '../conexion_db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !isset($_GET['id'])) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$task_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

$query = "SELECT a.*, c.nombre as categoria_nombre, ac.categoria_id,
          p.nivel as prioridad_nivel, e.nombre as estado_nombre
          FROM actividad a
          LEFT JOIN actividad_categoria ac ON a.id = ac.actividad_id
          LEFT JOIN categoria c ON ac.categoria_id = c.id
          LEFT JOIN prioridad p ON a.prioridad_id = p.id
          LEFT JOIN estado e ON a.estado_id = e.id
          WHERE a.id = ? AND a.usuario_id = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $task_id, $usuario_id);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
}

$result = $stmt->get_result();
$tarea = $result->fetch_assoc();

if (!$tarea) {
    echo json_encode(['error' => 'Tarea no encontrada']);
    exit;
}

echo json_encode($tarea);
?> 