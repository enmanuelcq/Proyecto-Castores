document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const formRest = document.querySelector('.form-rest');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // Si no estamos en la página de login o falta el contenedor de mensajes, no hacemos nada.
    if (!loginForm || !formRest) {
        return;
    }

    // --- EXTRA: Funcionalidad para el ojo de la contraseña ---
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Alterna las clases del icono de Bootstrap
            togglePassword.classList.toggle('bi-eye');
            togglePassword.classList.toggle('bi-eye-slash');
        });
    }

    // --- VALIDACIÓN Y ENVÍO DEL FORMULARIO ---
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Detiene el envío tradicional

            const username = document.getElementById('username').value.trim();
            const password = passwordInput.value.trim();

            // Limpiamos alertas previas
            formRest.innerHTML = '';

            // Regex idénticas a tus atributos "pattern" de HTML
            const usernameRegex = /^[a-zA-Z0-9.,]{4,30}$/;
            const passwordRegex = /^[a-zA-Z0-9$@#.]{7,99}$/;

            // 1. Validaciones del lado del cliente
            if (username === '' || password === '') {
                mostrarAlertaLocal('Todos los campos son obligatorios');
                return;
            }

            if (!usernameRegex.test(username)) {
                mostrarAlertaLocal('El nombre de usuario no cumple con el formato requerido');
                return;
            }

            if (!passwordRegex.test(password)) {
                mostrarAlertaLocal('La contraseña no cumple con el formato requerido');
                return;
            }

            // 2. Preparar datos si las validaciones pasan
            const formData = new FormData(this);

            // 3. Envío asíncrono con Fetch API
            fetch(this.action, {
                method: this.method,
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la comunicación con el servidor');
                }
                return response.text(); // Convertimos la respuesta a texto/HTML
            })
            .then(htmlResponse => {
                /* TRUCO CLAVE: innerHTML no ejecuta etiquetas <script> por seguridad.
                  Usamos createContextualFragment para que el navegador ejecute el 
                  setTimeout de redirección que envías desde PHP.
                */
                formRest.innerHTML = ''; 
                const fragment = document.createRange().createContextualFragment(htmlResponse);
                formRest.appendChild(fragment);
            })
            .catch(error => {
                console.error('Error Ajax:', error);
                mostrarAlertaLocal('Ocurrió un error inesperado al procesar la solicitud');
            });
        });
    }

    // Función auxiliar para pintar los errores de JS con el mismo diseño de tu PHP
    function mostrarAlertaLocal(mensaje) {
        formRest.innerHTML = `
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> ${mensaje}
            </div>
        `;
    }
});