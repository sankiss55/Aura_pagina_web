function showAlert(message, type = 'success') {
    const alert = document.getElementById('customAlert');
    const alertText = alert.querySelector('.alert-text');
    const successIcon = alert.querySelector('.success-icon');
    const errorIcon = alert.querySelector('.error-icon');

    alertText.textContent = message;
    alert.className = `custom-alert ${type}`;
    
    successIcon.style.display = type === 'success' ? 'block' : 'none';
    errorIcon.style.display = type === 'error' ? 'block' : 'none';
    
    alert.style.display = 'block';

    setTimeout(() => {
        alert.style.display = 'none';
    }, 3000);
}

document.getElementById("recovery-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const correo = document.getElementById("correo").value;
    const codigoBox = document.getElementById("codigo-box");
    const loader = document.getElementById("loader");

    loader.style.display = "flex";

    axios.post('./php/correo_recuperacion_password.php', {
        correo_usuario: correo
    }).then(function(respuesta) {
        loader.style.display = "none"; 
        if(respuesta.data.success) {
            codigoBox.innerHTML = "Se ha enviado un código a tu correo";
            document.getElementById("codigo-modal").style.display = "flex";
            window.codigoVerificacion = respuesta.data.codigo;
            window.correoUsuario = correo;
        } else {
            showAlert("Correo no encontrado en el sistema", "error");
        }
    }).catch(function(error) {
        loader.style.display = "none"; 
        console.error(error);
        showAlert("Error al enviar el correo", "error");
    });
});

document.getElementById("btn-verify").addEventListener("click", function () {
    const codigoIngresado = document.getElementById("codigo").value;
    
    if (codigoIngresado === window.codigoVerificacion) {
        document.getElementById("codigo-modal").style.display = "none";
        document.getElementById("password-modal").style.display = "flex";
        showAlert("Código verificado correctamente", "success");
    } else {
        showAlert("Código incorrecto. Intenta nuevamente.", "error");
    }
});

document.getElementById("btn-update-password").addEventListener("click", function () {
    const newPassword = document.getElementById("new-password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    if (newPassword !== confirmPassword) {
        showAlert("Las contraseñas no coinciden", "error");
        return;
    }

    axios.post('./php/actualizar_password.php', {
        correo: window.correoUsuario,
        nueva_password: newPassword
    }).then(function(respuesta) {
        if(respuesta.data.success) {
            showAlert("Contraseña actualizada correctamente", "success");
            setTimeout(() => {
                window.location.href = "index.html";
            }, 2000);
        } else {
            showAlert("Error al actualizar la contraseña", "error");
        }
    }).catch(function(error) {
        console.error(error);
        showAlert("Error en el servidor", "error");
    });
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