<?php
session_start();
require_once dirname(dirname(__FILE__)) . '/config/conexion_db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$tarea_id = $_POST['id'];
$usuario_id = $_SESSION['usuario_id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$fecha_ejecucion = $_POST['fecha_ejecucion'];
$hora_ejecucion = $_POST['hora_ejecucion'];
$prioridad_id = $_POST['prioridad_id'];
$categoria_id = $_POST['categoria_id'] ?? null;

// Verificar que la tarea pertenece al usuario
$query = "UPDATE actividad 
          SET titulo = ?,
              descripcion = ?,
              fecha_ejecucion = ?,
              hora_ejecucion = ?,
              prioridad_id = ?
          WHERE id = ? AND usuario_id = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ssssiis", $titulo, $descripcion, $fecha_ejecucion, $hora_ejecucion, $prioridad_id, $tarea_id, $usuario_id);

if ($stmt->execute()) {
    // Actualizar la categoría si existe
    if ($categoria_id) {
        // Primero eliminar las categorías existentes
        $delete_cat = "DELETE FROM actividad_categoria WHERE actividad_id = ?";
        $stmt_del = $conexion->prepare($delete_cat);
        $stmt_del->bind_param("i", $tarea_id);
        $stmt_del->execute();
        
        // Insertar la nueva categoría
        $insert_cat = "INSERT INTO actividad_categoria (actividad_id, categoria_id) VALUES (?, ?)";
        $stmt_ins = $conexion->prepare($insert_cat);
        $stmt_ins->bind_param("ii", $tarea_id, $categoria_id);
        $stmt_ins->execute();
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al actualizar la tarea']);
}
?> 