<?php
require_once dirname(dirname(__FILE__)) . '/config/conexion_db.php';

function obtenerTareas($usuario_id = null) {
    global $conexion;
    
    $query = "SELECT a.*, p.nivel as prioridad, e.nombre as estado 
              FROM actividad a
              LEFT JOIN prioridad p ON a.prioridad_id = p.id 
              LEFT JOIN estado e ON a.estado_id = e.id";
    
    if ($usuario_id) {
        $query .= " WHERE a.usuario_id = ?";
    }
    
    $query .= " ORDER BY a.fecha_ejecucion ASC";
              
    $stmt = $conexion->prepare($query);
    
    if ($usuario_id) {
        $stmt->bind_param("i", $usuario_id);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    $tareas = [];
    
    while($tarea = $resultado->fetch_assoc()) {
        $tareas[] = $tarea;
    }
    
    return $tareas;
}
?> 