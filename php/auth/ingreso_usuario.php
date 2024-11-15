<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
    include '../config/conexion_db.php';
    
    // Asegurarse de que la sesión está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

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
                
                // Verificar que las variables de sesión se establecieron
                error_log("Session ID: " . session_id());
                error_log("Usuario ID: " . $_SESSION['usuario_id']);
                error_log("Nombre: " . $_SESSION['nombre']);

                // Forzar la escritura de la sesión
                session_write_close();

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
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al iniciar sesión'
            }).then(function() {
                window.location = '../../login.php';
            });
        </script>
        <?php
    }
?>
</body>
</html>