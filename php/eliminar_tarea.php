<?php
session_start();
include '../conexion_db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$tarea_id = $_POST['id'];
$usuario_id = $_SESSION['usuario_id'];

// Primero eliminar las relaciones en actividad_categoria
$query1 = "DELETE FROM actividad_categoria WHERE actividad_id = ? AND 
           EXISTS (SELECT 1 FROM actividad WHERE id = ? AND usuario_id = ?)";
$stmt1 = $conexion->prepare($query1);
$stmt1->bind_param("iii", $tarea_id, $tarea_id, $usuario_id);
$stmt1->execute();

// Luego eliminar la actividad
$query2 = "DELETE FROM actividad WHERE id = ? AND usuario_id = ?";
$stmt2 = $conexion->prepare($query2);
$stmt2->bind_param("ii", $tarea_id, $usuario_id);

if ($stmt2->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al eliminar la tarea']);
}
?> 