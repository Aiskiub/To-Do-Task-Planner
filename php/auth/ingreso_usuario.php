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
        
        // Configurar la cookie de sesión específicamente para HTTPS
        $cookieParams = session_get_cookie_params();
        setcookie(session_name(), session_id(), [
            'expires' => time() + 86400,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,     // Forzar HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        // Agregar logs para debugging
        error_log('Session started - ID: ' . session_id());
        error_log('Cookie params: ' . print_r(session_get_cookie_params(), true));
        error_log('Server HTTPS: ' . ($_SERVER['HTTPS'] ?? 'off'));
        error_log('Server HTTP_HOST: ' . $_SERVER['HTTP_HOST']);
    }
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

                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Inicio de sesión exitoso',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location = '../../index.php';
                    });
                </script>
                <?php
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Correo o documento incorrectos'
                    }).then(function() {
                        window.location = '../../login.php';
                    });
                </script>
                <?php
            }
        } else {
            ?>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos'
                }).then(function() {
                    window.location = '../../index.php';
                });
            </script>
            <?php
        }
    } catch (Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        echo "<script>
            alert('Error en el proceso de login');
            window.location.href = '../../login.php';
        </script>";
    }

    // Configurar parámetros de cookies
    ini_set('session.cookie_lifetime', '86400');
    ini_set('session.gc_maxlifetime', '86400');
    session_start();

?>
</body>
</html>