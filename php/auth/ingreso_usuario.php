<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
    include '../config/conexion_db.php';
    
    // Asegurar que la sesión está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Configurar cookies de sesión
    ini_set('session.cookie_secure', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', '1440');

    try {
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $documento = mysqli_real_escape_string($conexion, $_POST['documento']);

        error_log("Intento de login - Correo: " . $correo);

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
                
                error_log("Login exitoso - Usuario ID: " . $_SESSION['usuario_id']);
                error_log("Login exitoso - Session ID: " . session_id());
                
                // Forzar la escritura de la sesión
                session_write_close();
                
                // Redirigir usando PHP header en lugar de JavaScript
                header("Location: ../../index.php");
                exit();
            } else {
                error_log("Login fallido - Credenciales incorrectas");
                header("Location: ../../login.php?error=1");
                exit();
            }
        } else {
            error_log("Login fallido - Campos vacíos");
            header("Location: ../../login.php?error=2");
            exit();
        }
    } catch (Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        header("Location: ../../login.php?error=3");
        exit();
    }
?>
</body>
</html>