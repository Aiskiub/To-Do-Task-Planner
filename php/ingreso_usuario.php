<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
    include '../conexion_db.php';
    
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $documento = mysqli_real_escape_string($conexion, $_POST['documento']);

        if ($correo && $documento) {
            // Consulta para verificar las credenciales
            $query = "SELECT * FROM usuario WHERE correo = '$correo' AND documento = '$documento'";
            $resultado = mysqli_query($conexion, $query);

            if (mysqli_num_rows($resultado) == 1) {
                // Usuario encontrado, iniciar sesión
                $usuario = mysqli_fetch_assoc($resultado);
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                
                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Inicio de sesión exitoso',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location = '../index.php';
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
                        window.location = '../login.php';
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
                    window.location = '../index.php';
                });
            </script>
            <?php
        }
?>
</body>
</html>