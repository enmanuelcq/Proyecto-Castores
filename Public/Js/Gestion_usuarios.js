document.addEventListener('DOMContentLoaded', () => {

    // Marcar formularios AJAX de esta página para evitar doble envío con ajax.js
    // Solo FormularioAjax (registros/actualizaciones), NO FormularioAjax1 (desactivar/reactivar)
    document.querySelectorAll('.FormularioAjax').forEach(function(form) {
        form.dataset.ajaxBound = 'true';
    });

    // ==============================================================
    // 1. FUNCIONALIDAD PARA MOSTRAR/OCULTAR CONTRASEÑA (para todos)
    // ==============================================================
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.password-toggle-icon');
        if (!toggle) return;
        
        const inputId = toggle.getAttribute('data-target');
        const passwordInput = document.getElementById(inputId);
        if (!passwordInput) return;
        
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        
        toggle.classList.toggle('bi-eye');
        toggle.classList.toggle('bi-eye-slash');
    });

    // ==============================================================
    // 2. FORMULARIO DE REGISTRO (existente)
    // ==============================================================
    const RegistroUsuarioForm = document.getElementById('RegistroUsuarioForm');
    const formRestRegistro = document.querySelector('.form-rest');

    if (RegistroUsuarioForm && formRestRegistro) {
        RegistroUsuarioForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const usuario = document.getElementById('usuario').value.trim();
            const clave_1 = document.getElementById('clave_1').value.trim();
            const clave_2 = document.getElementById('clave_2').value.trim();
            const nombre = document.getElementById('nombre').value.trim();
            const apellido = document.getElementById('apellido').value.trim();
            const email = document.getElementById('email').value.trim();
            const telefono = document.getElementById('telefono').value.trim();
            const cedula = document.getElementById('cedula').value.trim();
            const rol = document.querySelector('input[name="rol"]:checked')?.value || '';

            formRestRegistro.innerHTML = '';

            // Validaciones
            const usuarioRegex = /^[a-zA-Z0-9.,]{4,30}$/;
            const claveRegex = /^[a-zA-Z0-9$@#.]{7,99}$/;
            const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/;
            const apellidoRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const telefonoRegex = /^[\d\s\-]{7,15}$/;
            const cedulaRegex = /^[0-9]{5,10}$/;

            if (!usuario || !clave_1 || !clave_2 || !nombre || !apellido || !email || !telefono || !cedula) {
                mostrarAlertaLocal(formRestRegistro, 'Todos los campos son obligatorios');
                return;
            }
            if (!usuarioRegex.test(usuario)) {
                mostrarAlertaLocal(formRestRegistro, 'El nombre de usuario no cumple con el formato requerido');
                return;
            }
            if (!claveRegex.test(clave_1)) {
                mostrarAlertaLocal(formRestRegistro, 'La contraseña no cumple con el formato requerido');
                return;
            }
            if (clave_1 !== clave_2) {
                mostrarAlertaLocal(formRestRegistro, 'Las contraseñas no coinciden');
                return;
            }
            if (!nombreRegex.test(nombre)) {
                mostrarAlertaLocal(formRestRegistro, 'El nombre no cumple con el formato requerido');
                return;
            }
            if (!apellidoRegex.test(apellido)) {
                mostrarAlertaLocal(formRestRegistro, 'El apellido no cumple con el formato requerido');
                return;
            }
            if (!emailRegex.test(email)) {
                mostrarAlertaLocal(formRestRegistro, 'El email no cumple con el formato requerido');
                return;
            }
            if (!telefonoRegex.test(telefono)) {
                mostrarAlertaLocal(formRestRegistro, 'El teléfono no cumple con el formato requerido');
                return;
            }
            if (!cedulaRegex.test(cedula)) {
                mostrarAlertaLocal(formRestRegistro, 'La cédula no cumple con el formato requerido');
                return;
            }
            if (!rol) {
                mostrarAlertaLocal(formRestRegistro, 'Debe seleccionar un rol');
                return;
            }

            const formData = new FormData(this);
            fetch(this.action, {
                method: this.method,
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en la comunicación con el servidor');
                return response.text();
            })
            .then(html => {
                formRestRegistro.innerHTML = '';
                const fragment = document.createRange().createContextualFragment(html);
                formRestRegistro.appendChild(fragment);
            })
            .catch(error => {
                console.error('Error Ajax:', error);
                mostrarAlertaLocal(formRestRegistro, 'Ocurrió un error inesperado al procesar la solicitud');
            });
        });
    }

    // ==============================================================
    // 3. FORMULARIO DE ACTUALIZACIÓN (MODALES)
    // ==============================================================
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.actualizar-form');
        if (!form) return;
        e.preventDefault();

        const modalContent = form.closest('.modal-content');
        const formRest = modalContent ? modalContent.querySelector('.form-rest') : form.querySelector('.form-rest');
        if (!formRest) {
            console.warn('No se encontró .form-rest para mostrar mensajes');
            return;
        }

        formRest.innerHTML = '';

        const nombre = form.querySelector('[name="nombre"]')?.value.trim() || '';
        const apellido = form.querySelector('[name="apellido"]')?.value.trim() || '';
        const usuario = form.querySelector('[name="usuario"]')?.value.trim() || '';
        const email = form.querySelector('[name="email"]')?.value.trim() || '';
        const clave1 = form.querySelector('[name="clave_1"]')?.value || '';
        const clave2 = form.querySelector('[name="clave_2"]')?.value || '';
        const telefono = form.querySelector('[name="telefono"]')?.value.trim() || '';
        const cedula = form.querySelector('[name="cedula"]')?.value.trim() || '';
        const rol = form.querySelector('[name="rol"]:checked')?.value || '';

        const usuarioRegex = /^[a-zA-Z0-9.,]{4,30}$/;
        const claveRegex = /^[a-zA-Z0-9$@#.]{7,99}$/;
        const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/;
        const apellidoRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const telefonoRegex = /^[\d\s\-]{7,15}$/;
        const cedulaRegex = /^[0-9]{5,10}$/;

        if (!nombre || !apellido || !usuario || !email || !telefono || !cedula) {
            mostrarAlertaLocal(formRest, 'Todos los campos son obligatorios (excepto contraseña)');
            return;
        }
        if (!usuarioRegex.test(usuario)) {
            mostrarAlertaLocal(formRest, 'El nombre de usuario no cumple con el formato requerido');
            return;
        }
        if (!nombreRegex.test(nombre)) {
            mostrarAlertaLocal(formRest, 'El nombre no cumple con el formato requerido');
            return;
        }
        if (!apellidoRegex.test(apellido)) {
            mostrarAlertaLocal(formRest, 'El apellido no cumple con el formato requerido');
            return;
        }
        if (!emailRegex.test(email)) {
            mostrarAlertaLocal(formRest, 'El email no cumple con el formato requerido');
            return;
        }
        if (!telefonoRegex.test(telefono)) {
            mostrarAlertaLocal(formRest, 'El teléfono no cumple con el formato requerido');
            return;
        }
        if (!cedulaRegex.test(cedula)) {
            mostrarAlertaLocal(formRest, 'La cédula no cumple con el formato requerido');
            return;
        }
        if (!rol) {
            mostrarAlertaLocal(formRest, 'Debe seleccionar un rol');
            return;
        }
        if (clave1 || clave2) {
            if (clave1 !== clave2) {
                mostrarAlertaLocal(formRest, 'Las contraseñas no coinciden');
                return;
            }
            if (!claveRegex.test(clave1)) {
                mostrarAlertaLocal(formRest, 'La contraseña debe tener entre 7 y 99 caracteres y solo permitir letras, números y $@#.');
                return;
            }
        }

        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la comunicación con el servidor');
            return response.text();
        })
        .then(html => {
            formRest.innerHTML = html;
            // Si el mensaje es de éxito, podemos cerrar el modal después de un tiempo
            if (html.includes('alert-success')) {
                setTimeout(() => {
                    const modal = form.closest('.modal');
                    if (modal) {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) modalInstance.hide();
                        // Recargar la página o la lista de usuarios (opcional)
                        location.reload(); // o actualizar solo la lista
                    }
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error Ajax:', error);
            mostrarAlertaLocal(formRest, 'Ocurrió un error inesperado al procesar la solicitud');
        });
    });

    // ==============================================================
    // 5. FUNCIÓN AUXILIAR PARA MOSTRAR ALERTAS
    // ==============================================================
    function mostrarAlertaLocal(contenedor, mensaje) {
        contenedor.innerHTML = `
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> ${mensaje}
            </div>
        `;
    }

});