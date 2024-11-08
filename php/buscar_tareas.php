<?php
session_start();
include '../conexion_db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '';
$categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;

$query = "SELECT DISTINCT a.*, c.nombre as categoria_nombre 
          FROM actividad a
          LEFT JOIN actividad_categoria ac ON a.id = ac.actividad_id
          LEFT JOIN categoria c ON ac.categoria_id = c.id
          WHERE a.usuario_id = ? 
          AND (a.titulo LIKE ? OR a.descripcion LIKE ?)";

if ($categoria_id) {
    $query .= " AND ac.categoria_id = ?";
}

$query .= " ORDER BY a.fecha_ejecucion ASC";

$stmt = $conexion->prepare($query);

if ($categoria_id) {
    $stmt->bind_param("issi", $usuario_id, $searchTerm, $searchTerm, $categoria_id);
} else {
    $stmt->bind_param("iss", $usuario_id, $searchTerm, $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

$tareas = [];
while ($tarea = $result->fetch_assoc()) {
    $tareas[] = $tarea;
}

header('Content-Type: application/json');
echo json_encode($tareas);
?> 