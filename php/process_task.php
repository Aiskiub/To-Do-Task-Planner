<?php
session_start();
include '../conexion_db.php';

try {
    error_log("Iniciando proceso de nueva actividad para usuario ID: " . $_SESSION['usuario_id']);

    // Recoge y sanitiza los datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']) ?? '';
    $fecha_ejecucion = $_POST['fecha_ejecucion'];
    $prioridad_id = (int)$_POST['prioridad_id'];
    $importante = isset($_POST['importante']) ? 1 : 0;
    $categoria_id = (int)$_POST['categoria_id'];
    $usuario_id = $_SESSION['usuario_id'];
    $estado_id = 1; // Estado inicial (pendiente)

    error_log("Datos recopilados: " . print_r($_POST, true));

    // Inserta en la tabla actividad
    $query = "INSERT INTO actividad (titulo, descripcion, fecha_ejecucion, prioridad_id, importante, estado_id, usuario_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    error_log("Query: " . $query);
    $stmt = $conexion->prepare($query);
    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $conexion->error);
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }

    error_log("Bind parameters: " );
    $stmt->bind_param("sssiiii", 
        $titulo, 
        $descripcion, 
        $fecha_ejecucion,
        $prioridad_id,
        $importante,
        $estado_id,
        $usuario_id
    );

    error_log("Execute: " );
    if (!$stmt->execute()) {
        error_log("Error al ejecutar la consulta: " . $stmt->error);
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    error_log("Actividad insertada con éxito. ID: " . $stmt->insert_id);
    
    // Obtener el ID de la actividad recién insertada
    $actividad_id = $stmt->insert_id;

    // Si se seleccionó una categoría, insertar en la tabla actividad_categoria
    if ($categoria_id > 0) {
        $query_categoria = "INSERT INTO actividad_categoria (actividad_id, categoria_id) VALUES (?, ?)";
        $stmt_categoria = $conexion->prepare($query_categoria);
        if (!$stmt_categoria) { 
            error_log("Error en la preparación de la consulta de categoría: " . $conexion->error);
            throw new Exception("Error en la preparación de la consulta de categoría: " . $conexion->error);
        }
        
        $stmt_categoria->bind_param("ii", $actividad_id, $categoria_id);
        
        if (!$stmt_categoria->execute()) {
            error_log("Error al asignar la categoría: " . $stmt_categoria->error);
            throw new Exception("Error al asignar la categoría: " . $stmt_categoria->error);
        }
    }

    error_log("Categoría asignada con éxito. ID: " . $stmt_categoria->insert_id);

    $_SESSION['mensaje'] = "Actividad guardada exitosamente";
    header('Location: ../index.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header('Location: ../index.php');
    exit;
}
?>