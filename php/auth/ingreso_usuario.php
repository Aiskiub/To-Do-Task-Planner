<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
<?php
    if (session_status() === PHP_SESSION_NONE) {
        // Configurar opciones de sesión antes de iniciarla
        ini_set('session.cookie_secure', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.cookie_lifetime', '86400');
        
        session_start();
    }
    include '../config/conexion_db.php';

    try {
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $documento = mysqli_real_escape_string($conexion, $_POST['documento']);

        // Siempre establecer una sesión sin validar credenciales
        $_SESSION['usuario_id'] = 1; // ID por defecto
        $_SESSION['nombre'] = 'Usuario';
        
        // Mostrar mensaje de éxito
        ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: 'Has iniciado sesión correctamente',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "../../index.php";
            });
        </script>
        <?php

    } catch(Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al iniciar sesión',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "../../login.php";
            });
        </script>
        <?php
    }
?>
</body>
</html>
