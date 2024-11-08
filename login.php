<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro - Kiriku</title>
    <link rel="stylesheet" href="assets/css/styles_login.css">
</head>
<body>
    
    <main>

        <div class="contenedor_todo">
            <div class="caja_trasera">
                <div class="caja_trasera_login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar a la página</p>
                    <button id="btn_iniciar_sesion">Iniciar Sesión</button>
                </div>
                <div class="caja_trasera_register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para crear una cuenta</p>
                    <button id="btn_register">Registrarse</button>
                </div>
            </div>
            <!-- Formulario de Login y Register -->
            <div class="contenedor_login-register">
                <!-- Login -->
                <form action="php/ingreso_usuario.php" method="POST" class="formulario_login">
                    <h2>Iniciar Sesión</h2>
                    <input type="correo" placeholder="Correo Electrónico" name="correo">
                    <input type="text" placeholder="Documento" name="documento">
                    <button>Entrar</button>
                </form>
                <!-- Register -->
                <form action="php/registro_usuario_db.php" method="POST" class="formulario_register">
                    <h2>Registrarse</h2>
                    <input type="text" placeholder="Nombre" name="nombre">
                    <input type="text" placeholder="Apellido" name="apellido">
                    <input type="correo" placeholder="Correo Electrónico" name="correo">
                    <input type="text" placeholder="Número de Teléfono" name="telefono">
                    <input type="text" placeholder="Tipo de Documento" name="tipodocumento">
                    <input type="text" placeholder="Documento" name="documento">
                    <button>Registrarse</button>
                </form> <!-- Aquí cierro el formulario correctamente -->
            </div>
        </div>
    </main>
    
    <script src="assets/js/script_login.js"></script>
</body>
</html>
