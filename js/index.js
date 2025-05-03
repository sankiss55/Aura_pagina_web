document.getElementById("login-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const correo = document.getElementById("correo").value;
    const contraseña = document.getElementById("contraseña").value;
    const mensaje = document.getElementById("message");

    const contraseñaCorrecta = "12345";
    const correoRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    axios.post('./php/login.php',{
        usuario:correo,
        password:contraseña
    }).then(function(respuesta){
        console.log(respuesta.data);
        if(respuesta.data.success==true){
            mensaje.textContent = "¡Inicio de sesión exitoso! Redirigiendo...";
            mensaje.classList.add("success-message");
            mensaje.classList.remove("error-message");
            setTimeout(function () {
                window.location.href = "pagina_principal.html";
            }, 1500);
        } else {
            mensaje.textContent = respuesta.data.respuesta;
            mensaje.classList.add("error-message");
            mensaje.classList.remove("success-message");
        }
    }).catch(function(error){
        mensaje.textContent = "Error de conexión";
        mensaje.classList.add("error-message");
        mensaje.classList.remove("success-message");
        console.error(error);
    });
    return;
    if (contraseña === contraseñaCorrecta) {
        mensaje.textContent = `¡Código correcto! Redirigiendo...`;
        mensaje.classList.add("success-message");
        mensaje.classList.remove("error-message");

        setTimeout(function () {
            window.location.href = "pagina_principal.html";  
        }, 5000);
    } else {
        mensaje.textContent = "Contraseña incorrecta. Intenta nuevamente.";
        mensaje.classList.add("error-message");
        mensaje.classList.remove("success-message");
    }
});
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}