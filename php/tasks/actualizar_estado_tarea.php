<?php
session_start();
require_once dirname(dirname(__FILE__)) . '/config/conexion_db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['id']) || !isset($_POST['estado'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$tarea_id = $_POST['id'];
$estado_id = $_POST['estado'];
$usuario_id = $_SESSION['usuario_id'];

$query = "UPDATE actividad SET estado_id = ? WHERE id = ? AND usuario_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iis", $estado_id, $tarea_id, $usuario_id);

header('Content-Type: application/json');
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error al actualizar el estado']);
}
?> 