<?php
session_start();
include '../conexion_db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['id']) || !isset($_POST['estado'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$tarea_id = $_POST['id'];
$nuevo_estado = $_POST['estado'];
$usuario_id = $_SESSION['usuario_id'];

// Verificar que la tarea pertenece al usuario
$query = "UPDATE actividad 
          SET estado_id = ? 
          WHERE id = ? AND usuario_id = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iii", $nuevo_estado, $tarea_id, $usuario_id);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al actualizar el estado']);
}
?> 