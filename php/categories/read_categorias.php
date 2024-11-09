<?php
require_once dirname(dirname(__FILE__)) . '/config/conexion_db.php';

    $sql = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['id']) . "'>" 
                 . htmlspecialchars(ucfirst($row['nombre'])) . "</option>";
        }
    } else {
        echo "<option value='' disabled>No categories available</option>";
    }

    $conexion->close();
?>
