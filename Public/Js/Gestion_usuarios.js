document.addEventListener('DOMContentLoaded', () => {

    // Marcar formularios AJAX para evitar doble envío con ajax.js
    document.querySelectorAll('.FormularioAjax').forEach(function(form) {
        form.dataset.ajaxBound = 'true';
    });

    // ==============================================================
    // 1. PASSWORD TOGGLE
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
    // 2. FIELD VALIDATION HELPERS
    // ==============================================================
    function setFieldValid(input) {
        const fb = input.parentElement.querySelector('.field-feedback');
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (fb) { fb.innerHTML = ''; fb.className = 'field-feedback'; }
    }

    function setFieldInvalid(input, msg) {
        const fb = input.parentElement.querySelector('.field-feedback');
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        if (fb) { fb.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + msg; fb.className = 'field-feedback show error'; }
    }

    function clearFieldState(input) {
        const fb = input.parentElement.querySelector('.field-feedback');
        input.classList.remove('is-valid', 'is-invalid');
        if (fb) { fb.innerHTML = ''; fb.className = 'field-feedback'; }
    }

    // ==============================================================
    // 3. REGISTRATION FORM - REAL-TIME VALIDATION
    // ==============================================================
    const RegistroUsuarioForm = document.getElementById('RegistroUsuarioForm');
    const formRestRegistro = document.querySelector('.form-rest');

    const regex = {
        nombre: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/,
        usuario: /^[a-zA-Z0-9.,]{4,30}$/,
        clave: /^[a-zA-Z0-9$@#.]{7,99}$/,
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        telefono: /^[\d\s\-]{7,15}$/,
        cedula: /^[0-9]{5,10}$/
    };

    const regFieldRules = [
        { id: 'nombre', regex: regex.nombre, msg: 'Solo letras, entre 3 y 40 caracteres', required: true },
        { id: 'apellido', regex: regex.nombre, msg: 'Solo letras, entre 3 y 40 caracteres', required: true },
        { id: 'cedula', regex: regex.cedula, msg: 'Solo números, entre 5 y 10 dígitos', required: true },
        { id: 'telefono', regex: regex.telefono, msg: 'Formato: 0412-1234567', required: true },
        { id: 'email', regex: regex.email, msg: 'Ingrese un correo válido', required: true },
        { id: 'usuario', regex: regex.usuario, msg: 'Letras, números, puntos o comas, entre 4 y 30', required: true },
        { id: 'clave_1', regex: regex.clave, msg: 'Mínimo 7 caracteres (letras, números, $@#.)', required: true },
        { id: 'clave_2', regex: null, msg: '', required: true, customValidate: function(v) {
            var c1 = document.getElementById('clave_1');
            return c1 && v === c1.value.trim();
        }, customMsg: 'Las contraseñas no coinciden' }
    ];

    const regTouched = new Set();

    function validateRegField(input) {
        const rule = regFieldRules.find(r => r.id === input.id);
        if (!rule) return;
        const val = input.value.trim();
        if (!val) {
            if (rule.required) {
                setFieldInvalid(input, 'Este campo es obligatorio');
            } else {
                clearFieldState(input);
            }
            return;
        }
        if (rule.customValidate && !rule.customValidate(val)) {
            setFieldInvalid(input, rule.customMsg);
            return;
        }
        if (rule.regex && !rule.regex.test(val)) {
            setFieldInvalid(input, rule.msg);
        } else {
            setFieldValid(input);
        }
    }

    if (RegistroUsuarioForm) {
        RegistroUsuarioForm.addEventListener('input', function(e) {
            const input = e.target;
            if (!input.id || !input.tagName) return;
            regTouched.add(input.id);
            validateRegField(input);
        });

        RegistroUsuarioForm.addEventListener('change', function(e) {
            const input = e.target;
            if (input.id) {
                regTouched.add(input.id);
                validateRegField(input);
            }
        });

        RegistroUsuarioForm.addEventListener('focusout', function(e) {
            const input = e.target;
            if (input.id) {
                regTouched.add(input.id);
                validateRegField(input);
            }
        });
    }

    // ==============================================================
    // 4. USERNAME UNIQUENESS CHECK (AJAX)
    // ==============================================================
    let usernameValid = true;

    function checkUsername(input) {
        const username = input.value.trim();
        if (!username) return;
        const excludeId = input.dataset.userId || '';
        const fd = new FormData();
        fd.append('usuario', username);
        if (excludeId) fd.append('exclude_id', excludeId);

        fetch('./Controller/VerificarUsuario.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                const fb = input.parentElement.querySelector('.field-feedback') ||
                           input.closest('.password-wrapper')?.parentElement.querySelector('.field-feedback') ||
                           input.parentElement.nextElementSibling;
                if (data.existe) {
                    setFieldInvalid(input, 'Este nombre de usuario ya está en uso');
                    usernameValid = false;
                } else if (input.classList.contains('is-invalid') && input.dataset.usernameInvalid === 'true') {
                    setFieldValid(input);
                    usernameValid = true;
                } else {
                    if (!input.classList.contains('is-invalid')) {
                        setFieldValid(input);
                    }
                    usernameValid = true;
                }
                input.dataset.usernameInvalid = data.existe ? 'true' : 'false';
            })
            .catch(() => {});
    }

    document.addEventListener('focusout', function(e) {
        if (e.target.classList.contains('usuario-input')) {
            checkUsername(e.target);
        }
    }, true);

    // ==============================================================
    // 5. REGISTRATION FORM SUBMIT
    // ==============================================================
    if (RegistroUsuarioForm && formRestRegistro) {
        RegistroUsuarioForm.addEventListener('submit', function(e) {
            e.preventDefault();

            formRestRegistro.innerHTML = '';

            // Validate all fields
            for (const rule of regFieldRules) {
                const input = document.getElementById(rule.id);
                if (!input) continue;
                regTouched.add(rule.id);
                validateRegField(input);
            }

            // Check for any invalid
            const firstInvalid = RegistroUsuarioForm.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                return;
            }

            // Check username uniqueness
            if (!usernameValid) {
                mostrarAlertaLocal(formRestRegistro, 'El nombre de usuario ya está en uso');
                return;
            }

            // Check all required are filled
            for (const rule of regFieldRules) {
                if (rule.required) {
                    const input = document.getElementById(rule.id);
                    if (!input || !input.value.trim()) {
                        mostrarAlertaLocal(formRestRegistro, 'Todos los campos son obligatorios');
                        return;
                    }
                }
            }

            // Check rol
            const rol = document.querySelector('input[name="rol"]:checked');
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
    // 5. UPDATE MODAL FORMS
    // ==============================================================
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.actualizar-form');
        if (!form) return;
        e.preventDefault();

        const modalContent = form.closest('.modal-content');
        const formRest = modalContent ? modalContent.querySelector('.form-rest') : form.querySelector('.form-rest');
        if (!formRest) return;

        formRest.innerHTML = '';

        const nombre = form.querySelector('[name="nombre"]')?.value.trim() || '';
        const apellido = form.querySelector('[name="apellido"]')?.value.trim() || '';
        const usuario = form.querySelector('[name="usuario"]')?.value.trim() || '';
        const email = form.querySelector('[name="email"]')?.value.trim() || '';
        const clave1 = form.querySelector('[name="clave_1"]')?.value || '';
        const clave2 = form.querySelector('[name="clave_2"]')?.value || '';
        const telefono = form.querySelector('[name="telefono"]')?.value.trim() || '';
        const cedula = form.querySelector('[name="cedula"]')?.value.trim() || '';
        const rolEl = form.querySelector('[name="rol"]');
        const rol = rolEl ? (rolEl.value || '') : '';

        if (!nombre || !apellido || !usuario || !email || !telefono || !cedula) {
            mostrarAlertaLocal(formRest, 'Todos los campos son obligatorios (excepto contraseña)');
            return;
        }
        if (!regex.nombre.test(nombre)) {
            mostrarAlertaLocal(formRest, 'El nombre no cumple con el formato requerido');
            return;
        }
        if (!regex.nombre.test(apellido)) {
            mostrarAlertaLocal(formRest, 'El apellido no cumple con el formato requerido');
            return;
        }
        if (!regex.usuario.test(usuario)) {
            mostrarAlertaLocal(formRest, 'El nombre de usuario no cumple con el formato requerido');
            return;
        }
        // Check username uniqueness via AJAX
        const usuarioInput = form.querySelector('[name="usuario"]');
        const excludeId = usuarioInput?.dataset.userId || '';
        const ufd = new FormData();
        ufd.append('usuario', usuario);
        if (excludeId) ufd.append('exclude_id', excludeId);
        let usernameDuplicado = false;
        const uXhr = new XMLHttpRequest();
        uXhr.open('POST', './Controller/VerificarUsuario.php', false);
        uXhr.send(ufd);
        try {
            const uRes = JSON.parse(uXhr.responseText);
            if (uRes.existe) usernameDuplicado = true;
        } catch(e) {}
        if (usernameDuplicado) {
            mostrarAlertaLocal(formRest, 'Este nombre de usuario ya está en uso');
            return;
        }
        if (!regex.email.test(email)) {
            mostrarAlertaLocal(formRest, 'El email no cumple con el formato requerido');
            return;
        }
        if (!regex.telefono.test(telefono)) {
            mostrarAlertaLocal(formRest, 'El teléfono no cumple con el formato requerido');
            return;
        }
        if (!regex.cedula.test(cedula)) {
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
            if (!regex.clave.test(clave1)) {
                mostrarAlertaLocal(formRest, 'La contraseña debe tener entre 7 y 99 caracteres');
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
            if (html.includes('alert-success')) {
                setTimeout(() => {
                    const modal = form.closest('.modal');
                    if (modal) {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) modalInstance.hide();
                        location.reload();
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
    // 6. ALERT HELPER
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
