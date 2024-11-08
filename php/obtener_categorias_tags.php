<?php
include_once 'conexion_db.php';

$sql = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
$result = $conexion->query($sql);

$colors = [
    'Personal' => '#FF9F40',
    'Work' => '#A463F2',
    'Shopping' => '#50E3C2',
    'Health' => '#FF4B4B',
    'Education' => '#4A90E2',
    'Family' => '#50E3C2',
    'Projects' => '#F5A623'
];

if ($result && $result->num_rows > 0) {
    while($categoria = $result->fetch_assoc()) {
        $color = isset($colors[$categoria['nombre']]) ? $colors[$categoria['nombre']] : '#808080';
        echo "<span class='tag' data-id='{$categoria['id']}' style='background-color: {$color}'>";
        echo htmlspecialchars(ucfirst($categoria['nombre']));
        echo "</span>";
    }
}
?> 