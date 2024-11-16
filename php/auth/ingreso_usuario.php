<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
<?php
include '../config/conexion_db.php';

// Iniciar sesión de manera simple
session_start();

try {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $documento = mysqli_real_escape_string($conexion, $_POST['documento']);

    if ($correo && $documento) {
        $query = "SELECT * FROM usuario WHERE correo = ? AND documento = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $correo, $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Establecer variables de sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            
            // Redirigir con SweetAlert
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Bienvenido!',
                    text: 'Has iniciado sesión correctamente',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location = '../../index.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Credenciales incorrectas',
                }).then(() => {
                    window.location = '../../login.php';
                });
            </script>";
        }
    }
} catch (Exception $e) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ha ocurrido un error inesperado',
        }).then(() => {
            window.location = '../../login.php';
        });
    </script>";
}
?>
</body>
</html>
