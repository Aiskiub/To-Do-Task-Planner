<?php
session_start();
require_once dirname(dirname(__FILE__)) . '/config/conexion_db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'all';

$query = "SELECT DISTINCT a.*, c.nombre as categoria_nombre 
          FROM actividad a
          LEFT JOIN actividad_categoria ac ON a.id = ac.actividad_id
          LEFT JOIN categoria c ON ac.categoria_id = c.id
          WHERE a.usuario_id = ? ";

switch($filtro) {
    case 'today':
        $query .= "AND DATE(a.fecha_ejecucion) = CURDATE()";
        break;
    case 'next7':
        $query .= "AND DATE(a.fecha_ejecucion) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        break;
    case 'all':
        // No añadir filtro adicional
        break;
}

$query .= " ORDER BY a.fecha_ejecucion ASC";

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