<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
    include '../config/conexion_db.php';
    
    // Iniciar sesión al principio del script
    session_start();

    // Agregar logs para debug
    error_log("Iniciando proceso de login");
    error_log("POST data: " . print_r($_POST, true));

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
                
                // Limpiar y reiniciar la sesión
                session_unset();
                session_destroy();
                session_start();
                
                // Establecer variables de sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                
                // Forzar escritura de sesión
                session_write_close();
                
                error_log("Login exitoso - Session ID: " . session_id());
                error_log("Login exitoso - Usuario ID: " . $_SESSION['usuario_id']);
                error_log("Login exitoso - Nombre: " . $_SESSION['nombre']);

                // Redirigir usando JavaScript después de asegurarnos que la sesión está establecida
                echo "<script>
                    window.location.href = '../../index.php';
                </script>";
                exit();
            } else {
                error_log("Login fallido - Credenciales incorrectas");
                echo "<script>
                    alert('Credenciales incorrectas');
                    window.location.href = '../../login.php';
                </script>";
            }
        }
    } catch (Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        echo "<script>
            alert('Error en el proceso de login');
            window.location.href = '../../login.php';
        </script>";
    }
?>
</body>
</html>