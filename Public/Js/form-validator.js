/**
 * CastoresFormValidator — Validación en tiempo real para formularios
 * Uso: CastoresForm.init('#miFormulario') o CastoresForm.initAll()
 */
const CastoresForm = (() => {

    // Reglas de validación por nombre de campo
    const RULES = {
        // Representante
        cedula_representante:  { pattern: /^[VEJvej]?\d{5,10}$/, msg: 'Formato: V-12345678 o E-12345678' },
        correo_representante:  { pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, msg: 'Correo electrónico inválido' },
        nombres_representante: { pattern: /^[A-Za-záéíóúñÑ\s]{3,50}$/, msg: 'Mínimo 3 letras, solo texto' },
        apellidos_representante:{ pattern: /^[A-Za-záéíóúñÑ\s]{3,50}$/, msg: 'Mínimo 3 letras, solo texto' },
        ocupacion_representante:{ pattern: /^[A-Za-záéíóúñÑ\s,\.\-]{0,50}$/, msg: 'Formato inválido' },
        telefono_representante: { pattern: /^[\d\s\-]{7,15}$/, msg: '7 a 15 dígitos' },

        // Atleta
        primer_nombre:        { pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{2,40}$/, msg: 'Mínimo 2 letras' },
        segundo_nombre:       { pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{0,40}$/, msg: 'Solo letras' },
        primer_apellido:      { pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{2,40}$/, msg: 'Mínimo 2 letras' },
        segundo_apellido:     { pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{0,40}$/, msg: 'Solo letras' },
        atleta_fecha:         { type: 'date', msg: 'Fecha de nacimiento inválida' },
        atleta_sexo:          { required: true, msg: 'Selecciona una opción' },
        atleta_cedula:        { pattern: /^[0-9]{5,8}$/, msg: '5 a 8 dígitos' },
        pie_dominante:        { required: true, msg: 'Selecciona una opción' },
        atleta_direccion:     { pattern: /^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ #,\.\-]{3,100}$/, msg: 'Mínimo 3 caracteres' },

        // Categoría
        categoria_atleta:     { required: true, msg: 'Selecciona una categoría' },
        categoria_anio:       { required: true, msg: 'Selecciona el año' },

        // Uniforme
        talla_franela:        { pattern: /^[A-Za-z0-9\s]+$/, msg: 'Talla inválida' },
        numero_franela:       { pattern: /^[0-9]{1,2}$/, min: 1, max: 99, msg: '1 a 2 dígitos' },
        talla_short:          { pattern: /^[A-Za-z0-9\s]+$/, msg: 'Talla inválida' },
        talla_zapatos:        { pattern: /^[0-9]+(?:\.[0-9]+)?$/, msg: 'Solo números' },

        // Salud
        tipo_sangre:          { pattern: /^[A-Za-z0-9+\-]+$/, msg: 'Ej: O+, A-, B+' },
        alergias:             { pattern: /^[A-Za-záéíóúñÑ\s,\/\-]*$/, msg: 'Formato inválido' },
        padecimiento_cronico: { pattern: /^[A-Za-záéíóúñÑ\s,\/\-]*$/, msg: 'Formato inválido' },
        medicamento_restriccion:{ pattern: /^[A-Za-záéíóúñÑ\s,\/\-]*$/, msg: 'Formato inválido' },
        lesiones_previas:     { pattern: /^[A-Za-záéíóúñÑ\s,\/\-]*$/, msg: 'Formato inválido' },

        // Seguro
        vigencia_seguro:      { type: 'date', msg: 'Fecha inválida' },
        empresa_aseguradora:  { pattern: /^[A-Za-záéíóúñÑ\s,\.\-]*$/, msg: 'Formato inválido' },
        cobertura_contratada: { pattern: /^[A-Za-záéíóúñÑ\s,\.\-]*$/, msg: 'Formato inválido' },
        numero_poliza:        { pattern: /^[A-Za-z0-9\-]*$/, msg: 'Formato inválido' },
        medico_tratante:      { pattern: /^[A-Za-záéíóúñÑ\s.]*$/, msg: 'Solo texto' },
        telefono_medico:      { pattern: /^[0-9+\-\s()]{7,15}$/, msg: '7 a 15 caracteres' },

        // Colegio
        nombre_colegio:       { pattern: /^[A-Za-záéíóúñÑ\s,.'\-]{2,100}$/, msg: 'Mínimo 2 caracteres' },
        direccion_colegio:    { pattern: /^[A-Za-záéíóúñÑ0-9\s,.°#\-]*$/, msg: 'Formato inválido' },
        grado_cursante:       { pattern: /^[A-Za-záéíóúñÑ0-9\s°]+$/, msg: 'Formato inválido' },

        // Hobbie
        nombre_hobbie:        { pattern: /^[A-Za-záéíóúñÑ\s,\.\-]*$/, msg: 'Formato inválido' },
        horario_hobbie:       { pattern: /^[A-Za-záéíóúñÑ0-9\s,.:apmAPM\-]*$/, msg: 'Formato inválido' },

        // Usuario
        nombre:               { pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{2,50}$/, msg: 'Mínimo 2 letras' },
        apellido:             { pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{2,50}$/, msg: 'Mínimo 2 letras' },
        usuario:              { pattern: /^[a-zA-Z0-9.,]{4,30}$/, msg: '4 a 30 caracteres' },
        email:                { pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, msg: 'Correo inválido' },
        clave_1:              { pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/, msg: 'Mín 8, 1 mayús, 1 min, 1 número' },
        clave_2:              { match: 'clave_1', msg: 'Las contraseñas no coinciden' },
        cedula:               { pattern: /^[0-9]{5,10}$/, msg: '5 a 10 dígitos' },
        telefono:             { pattern: /^[\d\s\-]{7,15}$/, msg: '7 a 15 dígitos' },

        // Contraseña (modal)
        contrasena_actual:    { pattern: /^.{7,}$/, msg: 'Mínimo 7 caracteres' },
        contrasena_nueva:     { pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/, msg: 'Mín 8, 1 mayús, 1 min, 1 número' },
        contrasena_confirmar: { match: 'contrasena_nueva', msg: 'Las contraseñas no coinciden' },

        // Noticia
        titulo:               { pattern: /^.{3,100}$/, msg: '3 a 100 caracteres' },
        contenido:            { pattern: /^.{10,}$/, msg: 'Mínimo 10 caracteres' },

        // Partido
        fecha_hora:           { required: true, msg: 'Fecha y hora requeridas' },
        duracion:             { pattern: /^.{2,20}$/, msg: 'Duración inválida' },
        nuevo_equipo:         { pattern: /^.{2,50}$/, msg: 'Nombre del equipo requerido' },
        nuevo_campo:          { pattern: /^.{2,50}$/, msg: 'Nombre del campo requerido' },

        // Entrenamiento
        fecha:                { required: true, msg: 'Fecha requerida' },
        hora_inicio:          { required: true, msg: 'Hora requerida' },
        hora_fin:             { required: true, msg: 'Hora requerida' },
        lugar:                { pattern: /^.{2,100}$/, msg: 'Lugar requerido' },
        total_atletas:        { pattern: /^[0-9]+$/, min: 0, msg: 'Número inválido' },

        // Genérico
        monto:                { pattern: /^[0-9]+(?:\.[0-9]+)?$/, min: 0.01, msg: 'Monto inválido' },
        referencia:           { pattern: /^[A-Za-z0-9\-]*$/, msg: 'Solo alfanumérico y guiones' },
    };

    // Cache de elementos
    const cache = new WeakMap();

    // Crear elemento de feedback si no existe
    function ensureFeedback(input) {
        const name = input.getAttribute('name');
        let fb = input.parentElement.querySelector('.field-feedback');
        if (!fb) {
            fb = document.createElement('div');
            fb.className = 'field-feedback';
            input.parentElement.appendChild(fb);
        }
        return fb;
    }

    // Validar un campo individual
    function validateField(input) {
        const name = input.getAttribute('name');
        const value = input.value.trim();
        const rule = RULES[name];

        // Campo oculto o sin regla: skip
        if (input.type === 'hidden' || input.type === 'file' || input.type === 'checkbox' || !rule) {
            return true;
        }

        const fb = ensureFeedback(input);

        // Required check (select, etc.)
        if (rule.required && (!value || value === '0' || value === '')) {
            setInvalid(input, fb, rule.msg);
            return false;
        }

        // Sin valor y no requerido: válido
        if (!value && !rule.required) {
            clearState(input, fb);
            return true;
        }

        // Pattern check
        if (rule.pattern && !rule.pattern.test(value)) {
            setInvalid(input, fb, rule.msg);
            return false;
        }

        // Match check (contraseñas)
        if (rule.match) {
            const other = input.closest('form')?.querySelector(`[name="${rule.match}"]`);
            if (other && value !== other.value) {
                setInvalid(input, fb, rule.msg);
                return false;
            }
            // Re-validar el otro campo si cambió
            if (other) {
                const otherFb = other.parentElement.querySelector('.field-feedback');
                if (otherFb && other.value) {
                    validateField(other);
                }
            }
        }

        // Min/max para numbers
        if (rule.min !== undefined && parseFloat(value) < rule.min) {
            setInvalid(input, fb, `Mínimo ${rule.min}`);
            return false;
        }
        if (rule.max !== undefined && parseFloat(value) > rule.max) {
            setInvalid(input, fb, `Máximo ${rule.max}`);
            return false;
        }

        // Date validation
        if (rule.type === 'date' && value) {
            const d = new Date(value);
            if (isNaN(d.getTime())) {
                setInvalid(input, fb, rule.msg);
                return false;
            }
        }

        // Todo OK
        setValid(input, fb);
        return true;
    }

    function setInvalid(input, fb, msg) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        fb.className = 'field-feedback error show';
        fb.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${msg}`;
    }

    function setValid(input, fb) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        fb.className = 'field-feedback success show';
        fb.innerHTML = '<i class="bi bi-check-circle-fill"></i> Correcto';
        // Ocultar después de 1.5s
        setTimeout(() => { fb.classList.remove('show'); }, 1500);
    }

    function clearState(input, fb) {
        input.classList.remove('is-valid', 'is-invalid');
        fb.className = 'field-feedback';
        fb.innerHTML = '';
    }

    // Inicializar un formulario
    function init(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return;

        // Bind eventos blur e input a todos los campos validables
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (!RULES[name] && input.type !== 'hidden' && input.type !== 'file') return;

            // Validar al salir del campo
            input.addEventListener('blur', () => validateField(input));
            // Validar al escribir (con debounce leve)
            let timer;
            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => validateField(input), 300);
            });
            // Select: validar al cambiar
            if (input.tagName === 'SELECT') {
                input.addEventListener('change', () => validateField(input));
            }
        });

        // Intercept submit
        form.addEventListener('submit', (e) => {
            let allValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) allValid = false;
            });
            if (!allValid) {
                e.preventDefault();
                // Scroll al primer error
                const first = form.querySelector('.is-invalid');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Inicializar todos los formularios de la página
    function initAll() {
        document.querySelectorAll('form').forEach(form => {
            if (form.id) {
                init('#' + form.id);
            } else {
                // Formularios sin ID: inicializar por clase
                const inputs = form.querySelectorAll('input[name], select[name], textarea[name]');
                if (inputs.length > 0) {
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (!RULES[name]) return;

                        input.addEventListener('blur', () => validateField(input));
                        let timer;
                        input.addEventListener('input', () => {
                            clearTimeout(timer);
                            timer = setTimeout(() => validateField(input), 300);
                        });
                        if (input.tagName === 'SELECT') {
                            input.addEventListener('change', () => validateField(input));
                        }
                    });
                }
            }
        });
    }

    // API pública
    return { init, initAll, validateField, RULES };
})();

// Auto-inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => CastoresForm.initAll());
