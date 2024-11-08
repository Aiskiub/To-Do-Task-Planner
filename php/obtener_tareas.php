<?php
include dirname(__FILE__) . '/../conexion_db.php';

function obtenerTareas($usuario_id) {
    global $conexion;
    
    $query = "SELECT a.*, p.nivel as prioridad, e.nombre as estado 
              FROM actividad a
              LEFT JOIN prioridad p ON a.prioridad_id = p.id 
              LEFT JOIN estado e ON a.estado_id = e.id
              WHERE a.usuario_id = ?
              ORDER BY a.fecha_ejecucion ASC";
              
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $tareas = [];
    
    while($tarea = $resultado->fetch_assoc()) {
        $tareas[] = $tarea;
    }
    
    return $tareas;
}
?> 