document.addEventListener('DOMContentLoaded', () => {
    const athleteForm = document.getElementById('athleteRegisterForm');
    const formRest = document.querySelector('.form-rest');
    if (!athleteForm || !formRest) return;

    // ============================================
    // REGEX
    // ============================================
    const regex = {
        cedulaRep: /^[VEJvej]{1}-?\d{5,10}$/,
        cedulaAtleta: /^[VEJvej]{1}-?\d{5,10}$/,
        correo: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        nombre: /^[A-Za-záéíóúñÑ\s]+$/,
        telefono: /^[\d\s\-\+\(\)]{7,20}$/,
        texto: /^[A-Za-záéíóúñÑ\s,\.\-]+$/,
        nombreAtleta: /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/,
        direccion: /^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ ,.#\-]{3,100}$/,
        talla: /^[A-Za-z0-9\s]+$/,
        tallaZapatos: /^[0-9]+(?:\.[0-9]+)?$/,
        polizaNumero: /^[A-Za-z0-9\-]+$/,
        hobbie: /^[A-Za-záéíóúñÑ\s,\.\-]+$/,
        horario: /^[A-Za-záéíóúñÑ0-9\s,.:apmAPM\-]+$/,
        colegio: /^[A-Za-záéíóúñÑ\s,.'\-]+$/,
        direccionColegio: /^[A-Za-záéíóúñÑ0-9\s,.°#\-]+$/,
        grado: /^[A-Za-záéíóúñÑ0-9\s°]+$/,
        numeroFranela: /^[0-9]{1,2}$/,
        alergia: /^[A-Za-záéíóúñÑ\s,\/\-]+$/
    };

    const pv = (id) => {
        const el = document.getElementById(id);
        return el ? el.value.trim() : '';
    };

    // ============================================
    // FIELD FEEDBACK HELPERS
    // ============================================
    function setFieldValid(input) {
        const fb = input.parentElement.querySelector('.field-feedback');
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (fb) { fb.textContent = ''; fb.className = 'field-feedback'; }
    }

    function setFieldInvalid(input, msg) {
        const fb = input.parentElement.querySelector('.field-feedback');
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        if (fb) { fb.textContent = msg; fb.className = 'field-feedback text-danger small mt-1'; }
    }

    function clearFieldState(input) {
        const fb = input.parentElement.querySelector('.field-feedback');
        input.classList.remove('is-valid', 'is-invalid');
        if (fb) { fb.textContent = ''; fb.className = 'field-feedback'; }
    }

    function mostrarAlerta(mensaje) {
        formRest.innerHTML = '<div class="alert alert-danger py-2 small text-start" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> ' + mensaje + '</div>';
        formRest.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // ============================================
    // WIZARD NAVIGATION
    // ============================================
    let currentStep = 1;
    const totalSteps = 5;
    const panels = document.querySelectorAll('.wizard-panel');
    const steps = document.querySelectorAll('.wizard-step');
    const btnPrev = document.getElementById('btn-wizard-prev');
    const btnNext = document.getElementById('btn-wizard-next');
    const btnSubmit = document.getElementById('btn-wizard-submit');

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        panels.forEach(p => p.classList.remove('active'));
        steps.forEach(s => {
            s.classList.remove('active', 'completed');
            const sn = parseInt(s.dataset.step);
            if (sn < step) s.classList.add('completed');
            if (sn === step) s.classList.add('active');
        });
        document.querySelector('[data-panel="' + step + '"]').classList.add('active');
        currentStep = step;
        btnPrev.classList.toggle('d-none', step === 1);
        btnNext.classList.toggle('d-none', step === totalSteps);
        btnSubmit.classList.toggle('d-none', step !== totalSteps);
        formRest.innerHTML = '';
    }

    btnNext.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            goToStep(currentStep + 1);
        }
    });

    btnPrev.addEventListener('click', () => {
        goToStep(currentStep - 1);
    });

    // ============================================
    // STEP VALIDATIONS
    // ============================================
    function validateStep(step) {
        switch (step) {
            case 1: return validateRepresentantes();
            case 2: return validateAtleta();
            case 3: return validateCategoriaUniforme();
            case 4: return validateSalud();
            case 5: return validateAcademico();
        }
        return true;
    }

    function validateRepresentantes() {
        const blocks = document.querySelectorAll('.representante-block');
        const cedulas = new Set();
        for (let i = 0; i < blocks.length; i++) {
            const b = blocks[i];
            const ced = (b.querySelector('[name="cedula_representante[]"]')?.value || '').trim();
            const cor = (b.querySelector('[name="correo_representante[]"]')?.value || '').trim();
            const nom = (b.querySelector('[name="nombres_representante[]"]')?.value || '').trim();
            const ape = (b.querySelector('[name="apellidos_representante[]"]')?.value || '').trim();
            const tel = (b.querySelector('[name="telefono_representante[]"]')?.value || '').trim();
            const tieneDatos = ced || cor || nom || ape || tel;
            const esPrimero = (i === 0);
            if (!tieneDatos && !esPrimero) continue;

            if (!ced) { mostrarAlerta('La cédula del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' es obligatoria'); return false; }
            if (!regex.cedulaRep.test(ced)) { mostrarAlerta('La cédula del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' no es válida (ej. V12345678)'); return false; }
            if (cedulas.has(ced)) { mostrarAlerta('No puede haber dos representantes con la misma cédula'); return false; }
            cedulas.add(ced);
            if (!cor) { mostrarAlerta('El correo del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' es obligatorio'); return false; }
            if (!regex.correo.test(cor)) { mostrarAlerta('El correo del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' no es válido'); return false; }
            if (!nom) { mostrarAlerta('Los nombres del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' son obligatorios'); return false; }
            if (!regex.nombre.test(nom)) { mostrarAlerta('Los nombres del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' solo deben contener letras y espacios'); return false; }
            if (!ape) { mostrarAlerta('Los apellidos del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' son obligatorios'); return false; }
            if (!regex.nombre.test(ape)) { mostrarAlerta('Los apellidos del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' solo deben contener letras y espacios'); return false; }
            if (!tel) { mostrarAlerta('El teléfono del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' es obligatorio'); return false; }
            if (!regex.telefono.test(tel)) { mostrarAlerta('El teléfono del representante' + (esPrimero ? '' : ' #' + (i + 1)) + ' no es válido (ej. +58 412-1234567)'); return false; }
        }
        return true;
    }

    function validateAtleta() {
        if (!pv('primer_nombre')) { mostrarAlerta('El primer nombre del atleta es obligatorio'); return false; }
        if (!regex.nombreAtleta.test(pv('primer_nombre'))) { mostrarAlerta('El primer nombre debe tener entre 3 y 40 caracteres (solo letras)'); return false; }
        if (pv('segundo_nombre') && !regex.nombreAtleta.test(pv('segundo_nombre'))) { mostrarAlerta('El segundo nombre debe tener entre 3 y 40 caracteres (solo letras)'); return false; }
        if (!pv('primer_apellido')) { mostrarAlerta('El primer apellido del atleta es obligatorio'); return false; }
        if (!regex.nombreAtleta.test(pv('primer_apellido'))) { mostrarAlerta('El primer apellido debe tener entre 3 y 40 caracteres (solo letras)'); return false; }
        if (pv('segundo_apellido') && !regex.nombreAtleta.test(pv('segundo_apellido'))) { mostrarAlerta('El segundo apellido debe tener entre 3 y 40 caracteres (solo letras)'); return false; }
        if (!pv('atleta_fecha')) { mostrarAlerta('La fecha de nacimiento del atleta es obligatoria'); return false; }
        if (!validarFecha(pv('atleta_fecha'))) { mostrarAlerta('La fecha debe estar entre el 01/01/2000 y el 31/12/2026'); return false; }
        if (!pv('atleta_sexo')) { mostrarAlerta('Debe seleccionar el sexo del atleta'); return false; }
        if (pv('atleta_cedula') && !regex.cedulaAtleta.test(pv('atleta_cedula'))) { mostrarAlerta('La cédula del atleta no es válida (ej. V12345678)'); return false; }
        if (!pv('pie_dominante')) { mostrarAlerta('Debe seleccionar el pie dominante'); return false; }
        if (!pv('atleta_direccion')) { mostrarAlerta('La dirección del atleta es obligatoria'); return false; }
        if (!regex.direccion.test(pv('atleta_direccion'))) { mostrarAlerta('La dirección debe tener entre 3 y 100 caracteres'); return false; }
        return true;
    }

    function validateCategoriaUniforme() {
        if (!pv('categoria_atleta')) { mostrarAlerta('Debe seleccionar una categoría'); return false; }
        if (!pv('categoria_anio')) { mostrarAlerta('Debe seleccionar el año de la categoría'); return false; }
        if (!pv('talla_franela')) { mostrarAlerta('La talla de franela es obligatoria'); return false; }
        if (!regex.talla.test(pv('talla_franela'))) { mostrarAlerta('La talla de franela no es válida'); return false; }
        if (pv('numero_franela') && !regex.numeroFranela.test(pv('numero_franela'))) { mostrarAlerta('El número de franela debe ser entre 1 y 99'); return false; }
        if (!pv('talla_short')) { mostrarAlerta('La talla de short es obligatoria'); return false; }
        if (!regex.talla.test(pv('talla_short'))) { mostrarAlerta('La talla de short no es válida'); return false; }
        if (!pv('talla_zapatos')) { mostrarAlerta('La talla de zapatos es obligatoria'); return false; }
        if (!regex.tallaZapatos.test(pv('talla_zapatos'))) { mostrarAlerta('La talla de zapatos no es válida (ej. 39, 40, 41.5)'); return false; }
        return true;
    }

    function validateSalud() {
        if (!pv('tipo_sangre')) { mostrarAlerta('El tipo de sangre es obligatorio'); return false; }
        if (pv('alergias') && !regex.alergia.test(pv('alergias'))) { mostrarAlerta('Las alergias contienen caracteres no válidos'); return false; }
        if (pv('padecimiento_cronico') && !regex.alergia.test(pv('padecimiento_cronico'))) { mostrarAlerta('El padecimiento contiene caracteres no válidos'); return false; }
        if (pv('medicamento_restriccion') && !regex.alergia.test(pv('medicamento_restriccion'))) { mostrarAlerta('Las restricciones contienen caracteres no válidos'); return false; }
        if (pv('lesiones_previas') && !regex.alergia.test(pv('lesiones_previas'))) { mostrarAlerta('Las lesiones contienen caracteres no válidos'); return false; }
        if (pv('polizaSeguro') === 'posee') {
            if (!pv('vigencia_seguro')) { mostrarAlerta('La vigencia del seguro es obligatoria cuando posee póliza'); return false; }
            if (!pv('empresa_aseguradora')) { mostrarAlerta('La empresa aseguradora es obligatoria cuando posee póliza'); return false; }
            if (!regex.texto.test(pv('empresa_aseguradora'))) { mostrarAlerta('La empresa aseguradora contiene caracteres no válidos'); return false; }
            if (!pv('cobertura_contratada')) { mostrarAlerta('La cobertura contratada es obligatoria cuando posee póliza'); return false; }
            if (!regex.texto.test(pv('cobertura_contratada'))) { mostrarAlerta('La cobertura contiene caracteres no válidos'); return false; }
            if (!pv('numero_poliza')) { mostrarAlerta('El número de póliza es obligatorio cuando posee seguro'); return false; }
            if (!regex.polizaNumero.test(pv('numero_poliza'))) { mostrarAlerta('El número de póliza solo admite letras, números y guiones'); return false; }
        }
        return true;
    }

    function validateAcademico() {
        if (!pv('nombre_colegio')) { mostrarAlerta('El nombre del colegio es obligatorio'); return false; }
        if (!regex.colegio.test(pv('nombre_colegio'))) { mostrarAlerta('El nombre del colegio contiene caracteres no válidos'); return false; }
        if (pv('direccion_colegio') && !regex.direccionColegio.test(pv('direccion_colegio'))) { mostrarAlerta('La dirección del colegio contiene caracteres no válidos'); return false; }
        if (!pv('grado_cursante')) { mostrarAlerta('El grado/año cursante es obligatorio'); return false; }
        if (!regex.grado.test(pv('grado_cursante'))) { mostrarAlerta('El grado/año no es válido (ej. 4to Grado, 5° Año)'); return false; }
        if (pv('nombre_hobbie') && !regex.hobbie.test(pv('nombre_hobbie'))) { mostrarAlerta('El nombre del hobbie contiene caracteres no válidos'); return false; }
        if (pv('horario_hobbie') && !regex.horario.test(pv('horario_hobbie'))) { mostrarAlerta('El horario del hobbie no es válido'); return false; }
        return true;
    }

    // ============================================
    // PER-FIELD BLUR VALIDATION
    // ============================================
    const fieldRules = [
        { id: 'primer_nombre', regex: regex.nombreAtleta, msg: 'Solo letras, 3-40 caracteres', required: true },
        { id: 'segundo_nombre', regex: regex.nombreAtleta, msg: 'Solo letras, 3-40 caracteres', required: false },
        { id: 'primer_apellido', regex: regex.nombreAtleta, msg: 'Solo letras, 3-40 caracteres', required: true },
        { id: 'segundo_apellido', regex: regex.nombreAtleta, msg: 'Solo letras, 3-40 caracteres', required: false },
        { id: 'atleta_cedula', regex: regex.cedulaAtleta, msg: 'Formato: V12345678', required: false },
        { id: 'atleta_direccion', regex: regex.direccion, msg: 'Solo letras, números, 3-100 caracteres', required: true },
        { id: 'talla_franela', regex: regex.talla, msg: 'Solo letras y números', required: true },
        { id: 'numero_franela', regex: regex.numeroFranela, msg: 'Entre 1 y 99', required: false },
        { id: 'talla_short', regex: regex.talla, msg: 'Solo letras y números', required: true },
        { id: 'talla_zapatos', regex: regex.tallaZapatos, msg: 'Solo números (ej. 39, 41.5)', required: true },
        { id: 'alergias', regex: regex.alergia, msg: 'Solo letras, espacios, comas y guiones', required: false },
        { id: 'padecimiento_cronico', regex: regex.alergia, msg: 'Solo letras, espacios, comas y guiones', required: false },
        { id: 'medicamento_restriccion', regex: regex.alergia, msg: 'Solo letras, espacios, comas y guiones', required: false },
        { id: 'lesiones_previas', regex: regex.alergia, msg: 'Solo letras, espacios, comas y guiones', required: false },
        { id: 'empresa_aseguradora', regex: regex.texto, msg: 'Solo letras, espacios y puntos', required: false },
        { id: 'cobertura_contratada', regex: regex.texto, msg: 'Solo letras, espacios y puntos', required: false },
        { id: 'numero_poliza', regex: regex.polizaNumero, msg: 'Solo letras, números y guiones', required: false },
        { id: 'medico_tratante', regex: /^[A-Za-záéíóúñÑ\s.]+$/, msg: 'Solo letras y espacios', required: false },
        { id: 'telefono_medico', regex: regex.telefono, msg: 'Formato: +58 412-1234567', required: false },
        { id: 'nombre_colegio', regex: regex.colegio, msg: 'Solo letras, espacios y puntos', required: true },
        { id: 'direccion_colegio', regex: regex.direccionColegio, msg: 'Solo letras, números y símbolos', required: false },
        { id: 'grado_cursante', regex: regex.grado, msg: 'Ej. 4to Grado', required: true },
        { id: 'nombre_hobbie', regex: regex.hobbie, msg: 'Solo letras, espacios y puntos', required: false },
        { id: 'horario_hobbie', regex: regex.horario, msg: 'Formato no válido', required: false }
    ];

    athleteForm.addEventListener('focusout', function(e) {
        const input = e.target;
        if (!input.id) return;
        const rule = fieldRules.find(r => r.id === input.id);
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
        if (!rule.regex.test(val)) {
            setFieldInvalid(input, rule.msg);
        } else {
            setFieldValid(input);
        }
    });

    // Representante fields (delegated)
    athleteForm.addEventListener('focusout', function(e) {
        const input = e.target;
        const name = input.getAttribute('name');
        if (!name) return;

        if (name === 'cedula_representante[]') {
            const val = input.value.trim();
            if (!val) { setFieldInvalid(input, 'Obligatorio'); return; }
            if (!regex.cedulaRep.test(val)) { setFieldInvalid(input, 'Formato: V12345678'); return; }
            setFieldValid(input);
        } else if (name === 'correo_representante[]') {
            const val = input.value.trim();
            if (!val) { setFieldInvalid(input, 'Obligatorio'); return; }
            if (!regex.correo.test(val)) { setFieldInvalid(input, 'Correo no válido'); return; }
            setFieldValid(input);
        } else if (name === 'nombres_representante[]') {
            const val = input.value.trim();
            if (!val) { setFieldInvalid(input, 'Obligatorio'); return; }
            if (!regex.nombre.test(val)) { setFieldInvalid(input, 'Solo letras y espacios'); return; }
            setFieldValid(input);
        } else if (name === 'apellidos_representante[]') {
            const val = input.value.trim();
            if (!val) { setFieldInvalid(input, 'Obligatorio'); return; }
            if (!regex.nombre.test(val)) { setFieldInvalid(input, 'Solo letras y espacios'); return; }
            setFieldValid(input);
        } else if (name === 'telefono_representante[]') {
            const val = input.value.trim();
            if (!val) { setFieldInvalid(input, 'Obligatorio'); return; }
            if (!regex.telefono.test(val)) { setFieldInvalid(input, 'Formato: +58 412-1234567'); return; }
            setFieldValid(input);
        }
    });

    // ============================================
    // AJAX CÉDULA REPRESENTANTE
    // ============================================
    document.addEventListener('focusout', function(e) {
        if (!e.target.classList.contains('cedula-rep-input')) return;
        const input = e.target;
        const cedula = input.value.trim();
        const infoEl = input.parentElement.querySelector('.cedula-rep-info');
        if (!cedula || !regex.cedulaRep.test(cedula)) {
            if (infoEl) infoEl.classList.add('d-none');
            return;
        }
        const fd = new FormData();
        fd.append('cedula', cedula);
        fetch('./Controller/VerificarCedulaRepresentante.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.existe && infoEl) {
                    infoEl.classList.remove('d-none', 'text-info');
                    infoEl.classList.add('text-warning');
                    infoEl.innerHTML = '<i class="bi bi-info-circle"></i> ' + data.nombre + ' ' + data.apellido + ' ya está registrado (' + data.cantidad_atletas + ' atleta(s)). Se reutilizarán sus datos.';
                } else if (infoEl) {
                    infoEl.classList.add('d-none');
                }
            })
            .catch(() => { if (infoEl) infoEl.classList.add('d-none'); });
    }, true);

    // ============================================
    // ADD/REMOVE REPRESENTANTES
    // ============================================
    let repIndex = 0;

    document.getElementById('btn-add-representante')?.addEventListener('click', function() {
        repIndex++;
        const container = document.getElementById('representantes-container');
        const firstBlock = container.querySelector('.representante-block');
        const newBlock = firstBlock.cloneNode(true);
        newBlock.dataset.index = repIndex;
        newBlock.querySelectorAll('input, select').forEach(el => { el.value = ''; clearFieldState(el); });
        const infoEl = newBlock.querySelector('.cedula-rep-info');
        if (infoEl) infoEl.classList.add('d-none');

        const removeBtn = document.createElement('div');
        removeBtn.className = 'text-end mb-2';
        removeBtn.innerHTML = '<button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-remove-representante"><i class="bi bi-trash"></i> Quitar</button>';
        newBlock.prepend(removeBtn);
        container.appendChild(newBlock);
    });

    document.getElementById('representantes-container')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-representante');
        if (btn) btn.closest('.representante-block').remove();
    });

    // ============================================
    // PHOTO UPLOAD PREVIEW
    // ============================================
    const photoArea = document.getElementById('photoUploadArea');
    const photoInput = document.getElementById('foto_atleta');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');

    if (photoArea && photoInput) {
        photoArea.addEventListener('click', () => photoInput.click());
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                mostrarAlerta('La imagen no puede superar 2MB');
                this.value = '';
                return;
            }
            if (!file.type.startsWith('image/')) {
                mostrarAlerta('Solo se permiten archivos de imagen (JPG, PNG)');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                photoPreview.src = ev.target.result;
                photoPreview.classList.remove('d-none');
                photoPlaceholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    // ============================================
    // FECHA VALIDATION
    // ============================================
    function validarFecha(fecha) {
        if (!fecha) return false;
        const f = new Date(fecha);
        return f >= new Date('2000-01-01') && f <= new Date('2026-12-31');
    }

    // ============================================
    // FORM SUBMIT (AJAX)
    // ============================================
    athleteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        fetch(this.action, { method: 'POST', body: fd })
            .then(r => { if (!r.ok) throw new Error('Error del servidor'); return r.text(); })
            .then(html => {
                formRest.innerHTML = '';
                const fragment = document.createRange().createContextualFragment(html);
                formRest.appendChild(fragment);
            })
            .catch(err => {
                console.error('Error Ajax:', err);
                mostrarAlerta('Ocurrió un error inesperado al procesar la solicitud');
            });
    });

    // ============================================
    // TOGGLE SEGURO
    // ============================================
    window.toggleSeguroFields = function() {
        const select = document.getElementById('polizaSeguro');
        const fields = document.querySelectorAll('.seguro-field');
        const disable = select.value === 'no_posee' || select.value === '';
        fields.forEach(f => {
            f.querySelectorAll('input').forEach(inp => {
                inp.disabled = disable;
                if (disable) inp.value = '';
            });
            f.style.opacity = disable ? '0.5' : '1';
        });
    };

    const polizaSelect = document.getElementById('polizaSeguro');
    if (polizaSelect) {
        polizaSelect.addEventListener('change', window.toggleSeguroFields);
        window.toggleSeguroFields();
    }
});
