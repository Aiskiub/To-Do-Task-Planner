// Listeners para los botones
document.getElementById('btn_register').addEventListener('click', register);
document.getElementById('btn_iniciar_sesion').addEventListener('click', iniciarSesion);

window.addEventListener('resize', anchoPagina);
// Variables de referencia
var contenedor_login_register = document.querySelector('.contenedor_login-register');
var formulario_login = document.querySelector('.formulario_login');
var formulario_register = document.querySelector('.formulario_register');
var caja_trasera_login = document.querySelector('.caja_trasera_login');
var caja_trasera_register = document.querySelector('.caja_trasera_register');



function anchoPagina() {
    if(window.innerWidth > 850) {
        caja_trasera_login.style.display = "block";
        caja_trasera_register.style.display = "block";
    }else{
        caja_trasera_register.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.display = "none";
        formulario_login.style.display = "block";
        formulario_register.style.display = "none";
        contenedor_login_register.style.left = "0px";
    }
}

anchoPagina();

// Función para mostrar el formulario de registro
function register() {
    formulario_register.style.display = "block"; 
    formulario_login.style.display = "none"; 
    contenedor_login_register.style.left = "410px";
    caja_trasera_register.style.opacity = "1";

    setTimeout(function() {
        formulario_register.style.opacity = "1";
    }, 10);
}

function iniciarSesion() {
    formulario_login.style.display = "block";
    formulario_register.style.display = "none";
    contenedor_login_register.style.left = "10px";
    caja_trasera_login.style.opacity = "1";

    setTimeout(function() {
        formulario_login.style.opacity = "1";
    }, 10);
}


