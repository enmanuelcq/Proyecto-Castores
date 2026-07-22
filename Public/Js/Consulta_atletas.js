

document.addEventListener('DOMContentLoaded', () => {
    // ============================================
    // 1. OBTENER REFERENCIAS DEL FORMULARIO
    // ============================================
    const athleteForm = document.getElementById('actualizarAtletaForm');
    const formRest = document.querySelector('.form-rest');

    // Datos del atleta
    const primer_nombre = document.getElementById('primer_nombre');
    const segundo_nombre = document.getElementById('segundo_nombre');
    const primer_apellido = document.getElementById('primer_apellido');
    const segundo_apellido = document.getElementById('segundo_apellido');
    const atleta_fecha = document.getElementById('atleta_fecha');
    const atleta_sexo = document.getElementById('atleta_sexo');
    const atleta_cedula = document.getElementById('atleta_cedula');
    const pie_dominante = document.getElementById('pie_dominante');
    const atleta_direccion = document.getElementById('atleta_direccion');

    // Categoría
    const categoria_atleta = document.getElementById('categoria_atleta');
    const categoria_anio = document.getElementById('categoria_anio');

    // Uniforme
    const talla_franela = document.getElementById('talla_franela');
    const numero_franela = document.getElementById('numero_franela');
    const talla_short = document.getElementById('talla_short');
    const talla_zapatos = document.getElementById('talla_zapatos');

    // Ficha de Salud
    const tipo_sangre = document.getElementById('tipo_sangre');
    const alergias = document.getElementById('alergias');
    const padecimiento_cronico = document.getElementById('padecimiento_cronico');
    const medicamento_restriccion = document.getElementById('medicamento_restriccion');
    const lesiones_previas = document.getElementById('lesiones_previas');
    const poliza_seguro = document.getElementById('polizaSeguro');

    // Campos del seguro (solo si aplica)
    const vigencia_seguro = document.getElementById('vigencia_seguro');
    const empresa_aseguradora = document.getElementById('empresa_aseguradora');
    const cobertura_contratada = document.getElementById('cobertura_contratada');
    const numero_poliza = document.getElementById('numero_poliza');
    const medico_tratante = document.getElementById('medico_tratante');
    const telefono_medico = document.getElementById('telefono_medico');

    // Información Académica
    const nombre_colegio = document.getElementById('nombre_colegio');
    const direccion_colegio = document.getElementById('direccion_colegio');
    const grado_cursante = document.getElementById('grado_cursante');

    // Actividades Extra / Hobbie
    const nombre_hobbie = document.getElementById('nombre_hobbie');
    const horario_hobbie = document.getElementById('horario_hobbie');


    // Si no existe el formulario o el contenedor de mensajes, no continuamos
    if (!athleteForm || !formRest) {
        console.warn('Formulario o contenedor de mensajes no encontrado');
        return;
    }


    // ============================================
    // 3. FUNCIÓN PARA VALIDAR FECHAS
    // ============================================
    function validarFecha(fecha) {
        if (!fecha) return false;
        const fechaObj = new Date(fecha);
        const minDate = new Date('2000-01-01');
        const maxDate = new Date('2023-12-31');
        return fechaObj >= minDate && fechaObj <= maxDate;
    }

    // ============================================
    // 4. EXPRESIONES REGULARES (DEFINIDAS FUERA DEL EVENTO)
    // ============================================
    const cedulaRegex = /^[VEJvej]{1}-?\d{5,10}$/;
    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const nombreRegex = /^[A-Za-záéíóúñÑ\s]+$/;
    const telefonoRegex = /^[\d\s\-]{7,15}$/;
    const textoRegex = /^[A-Za-záéíóúñÑ\s,\.\-]+$/;
    const textoBasicoRegex = /^[A-Za-záéíóúñÑ\s]+$/;
    const nombreAtletaRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/;
    const direccionRegex = /^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ ,.#]{3,100}$/;
    const tallaRegex = /^[A-Za-z0-9\s]+$/;
    const tallaZapatosRegex = /^[0-9]+(?:\.[0-9]+)?$/;
    const sangreRegex = /^[A-Za-z0-9+\-]+$/;
    const polizaNumeroRegex = /^[A-Za-z0-9\-]+$/;
    const hobbieRegex = /^[A-Za-záéíóúñÑ\s,\.\-]+$/;
    const horarioRegex = /^[A-Za-záéíóúñÑ0-9\s,.:apmAPM\-]+$/;
    const colegioRegex = /^[A-Za-záéíóúñÑ\s,.'\-]+$/;
    const direccionColegioRegex = /^[A-Za-záéíóúñÑ0-9\s,.°#\-]+$/;
    const gradoRegex = /^[A-Za-záéíóúñÑ0-9\s°]+$/;
    const numeroFranelaRegex = /^[0-9]{1,2}$/;
    const alergiaRegex = /^[A-Za-záéíóúñÑ\s,\/\-]+$/;

    // ============================================
    // 5. VALIDACIÓN Y ENVÍO DEL FORMULARIO
    // ============================================
    athleteForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const getFieldValue = (field) => field ? field.value.trim() : '';


        const primerNombreValue = getFieldValue(primer_nombre);
        const segundoNombreValue = getFieldValue(segundo_nombre);
        const primerApellidoValue = getFieldValue(primer_apellido);
        const segundoApellidoValue = getFieldValue(segundo_apellido);
        const atletaFechaValue = getFieldValue(atleta_fecha);
        const atletaSexoValue = getFieldValue(atleta_sexo);
        const atletaCedulaValue = getFieldValue(atleta_cedula);
        const pieDominanteValue = getFieldValue(pie_dominante);
        const atletaDireccionValue = getFieldValue(atleta_direccion);
        const categoriaAtletaValue = getFieldValue(categoria_atleta);
        const categoriaAnioValue = getFieldValue(categoria_anio);
        const tallaFranelaValue = getFieldValue(talla_franela);
        const numeroFranelaValue = getFieldValue(numero_franela);
        const tallaShortValue = getFieldValue(talla_short);
        const tallaZapatosValue = getFieldValue(talla_zapatos);
        const tipoSangreValue = getFieldValue(tipo_sangre);
        const alergiasValue = getFieldValue(alergias);
        const padecimientoCronicoValue = getFieldValue(padecimiento_cronico);
        const medicamentoRestriccionValue = getFieldValue(medicamento_restriccion);
        const lesionesPreviasValue = getFieldValue(lesiones_previas);
        const polizaSeguroValue = getFieldValue(poliza_seguro);
        const vigenciaSeguroValue = getFieldValue(vigencia_seguro);
        const empresaAseguradoraValue = getFieldValue(empresa_aseguradora);
        const coberturaContratadaValue = getFieldValue(cobertura_contratada);
        const numeroPolizaValue = getFieldValue(numero_poliza);
        const medicoTratanteValue = getFieldValue(medico_tratante);
        const telefonoMedicoValue = getFieldValue(telefono_medico);
        const nombreColegioValue = getFieldValue(nombre_colegio);
        const direccionColegioValue = getFieldValue(direccion_colegio);
        const gradoCursanteValue = getFieldValue(grado_cursante);
        const nombreHobbieValue = getFieldValue(nombre_hobbie);
        const horarioHobbieValue = getFieldValue(horario_hobbie);

        
        if (!primerNombreValue) {
            mostrarAlerta('El primer nombre del atleta es obligatorio');
            primer_nombre.focus();
            return;
        }
        if (!nombreAtletaRegex.test(primerNombreValue)) {
            mostrarAlerta('El primer nombre debe tener entre 3 y 40 caracteres (solo letras)');
            primer_nombre.focus();
            return;
        }

        if (segundoNombreValue && !nombreAtletaRegex.test(segundoNombreValue)) {
            mostrarAlerta('El segundo nombre debe tener entre 3 y 40 caracteres (solo letras)');
            segundo_nombre.focus();
            return;
        }

        if (!primerApellidoValue) {
            mostrarAlerta('El primer apellido del atleta es obligatorio');
            primer_apellido.focus();
            return;
        }
        if (!nombreAtletaRegex.test(primerApellidoValue)) {
            mostrarAlerta('El primer apellido debe tener entre 3 y 40 caracteres (solo letras)');
            primer_apellido.focus();
            return;
        }

        if (segundoApellidoValue && !nombreAtletaRegex.test(segundoApellidoValue)) {
            mostrarAlerta('El segundo apellido debe tener entre 3 y 40 caracteres (solo letras)');
            segundo_apellido.focus();
            return;
        }

        if (!atletaFechaValue) {
            mostrarAlerta('La fecha de nacimiento del atleta es obligatoria');
            atleta_fecha.focus();
            return;
        }
        if (!validarFecha(atletaFechaValue)) {
            mostrarAlerta('La fecha debe estar entre el 01/01/2000 y el 31/12/2023');
            atleta_fecha.focus();
            return;
        }

        if (!atletaSexoValue) {
            mostrarAlerta('Debe seleccionar el sexo del atleta');
            atleta_sexo.focus();
            return;
        }
        if (atletaCedulaValue && !/^[0-9]{5,8}$/.test(atletaCedulaValue)) {
            mostrarAlerta('La cédula debe tener entre 5 y 8 dígitos numéricos');
            atleta_cedula.focus();
            return;
        }

        if (!pieDominanteValue) {
            mostrarAlerta('Debe seleccionar el pie dominante');
            pie_dominante.focus();
            return;
        }

        if (!atletaDireccionValue) {
            mostrarAlerta('La dirección del atleta es obligatoria');
            atleta_direccion.focus();
            return;
        }
        if (!direccionRegex.test(atletaDireccionValue)) {
            mostrarAlerta('La dirección debe tener entre 3 y 100 caracteres');
            atleta_direccion.focus();
            return;
        }

        if (!categoriaAtletaValue) {
            mostrarAlerta('Debe seleccionar una categoría');
            categoria_atleta.focus();
            return;
        }

        if (!categoriaAnioValue) {
            mostrarAlerta('Debe seleccionar el año de la categoría');
            categoria_anio.focus();
            return;
        }

        if (!tallaFranelaValue) {
            mostrarAlerta('La talla de franela es obligatoria');
            talla_franela.focus();
            return;
        }
        if (!tallaRegex.test(tallaFranelaValue)) {
            mostrarAlerta('La talla de franela no es válida');
            talla_franela.focus();
            return;
        }

        if (numeroFranelaValue && !numeroFranelaRegex.test(numeroFranelaValue)) {
            mostrarAlerta('El número de franela debe ser entre 1 y 99');
            numero_franela.focus();
            return;
        }

        if (!tallaShortValue) {
            mostrarAlerta('La talla de short es obligatoria');
            talla_short.focus();
            return;
        }
        if (!tallaRegex.test(tallaShortValue)) {
            mostrarAlerta('La talla de short no es válida');
            talla_short.focus();
            return;
        }

        if (!tallaZapatosValue) {
            mostrarAlerta('La talla de zapatos es obligatoria');
            talla_zapatos.focus();
            return;
        }
        if (!tallaZapatosRegex.test(tallaZapatosValue)) {
            mostrarAlerta('La talla de zapatos no es válida (ej. 39, 40, 41.5)');
            talla_zapatos.focus();
            return;
        }

        if (!tipoSangreValue) {
            mostrarAlerta('El tipo de sangre es obligatorio');
            tipo_sangre.focus();
            return;
        }
        if (!sangreRegex.test(tipoSangreValue)) {
            mostrarAlerta('El tipo de sangre no es válido (ej. A+, B-, O+)');
            tipo_sangre.focus();
            return;
        }

        if (alergiasValue && !alergiaRegex.test(alergiasValue)) {
            mostrarAlerta('Las alergias contienen caracteres no válidos');
            alergias.focus();
            return;
        }

        if (padecimientoCronicoValue && !alergiaRegex.test(padecimientoCronicoValue)) {
            mostrarAlerta('El padecimiento contiene caracteres no válidos');
            padecimiento_cronico.focus();
            return;
        }

        if (medicamentoRestriccionValue && !alergiaRegex.test(medicamentoRestriccionValue)) {
            mostrarAlerta('Las restricciones contienen caracteres no válidos');
            medicamento_restriccion.focus();
            return;
        }

        if (lesionesPreviasValue && !alergiaRegex.test(lesionesPreviasValue)) {
            mostrarAlerta('Las lesiones contienen caracteres no válidos');
            lesiones_previas.focus();
            return;
        }

        if (polizaSeguroValue === 'posee') {
            if (!vigenciaSeguroValue) {
                mostrarAlerta('La vigencia del seguro es obligatoria cuando posee póliza');
                vigencia_seguro.focus();
                return;
            }

            if (!empresaAseguradoraValue) {
                mostrarAlerta('La empresa aseguradora es obligatoria cuando posee póliza');
                empresa_aseguradora.focus();
                return;
            }
            if (!textoRegex.test(empresaAseguradoraValue)) {
                mostrarAlerta('La empresa aseguradora contiene caracteres no válidos');
                empresa_aseguradora.focus();
                return;
            }

            if (!coberturaContratadaValue) {
                mostrarAlerta('La cobertura contratada es obligatoria cuando posee póliza');
                cobertura_contratada.focus();
                return;
            }
            if (!textoRegex.test(coberturaContratadaValue)) {
                mostrarAlerta('La cobertura contiene caracteres no válidos');
                cobertura_contratada.focus();
                return;
            }

            if (!numeroPolizaValue) {
                mostrarAlerta('El número de póliza es obligatorio cuando posee seguro');
                numero_poliza.focus();
                return;
            }
            if (!polizaNumeroRegex.test(numeroPolizaValue)) {
                mostrarAlerta('El número de póliza solo admite letras, números y guiones');
                numero_poliza.focus();
                return;
            }

            if (medicoTratanteValue && !/^[A-Za-záéíóúñÑ\s.]+$/.test(medicoTratanteValue)) {
                mostrarAlerta('El nombre del médico contiene caracteres no válidos');
                medico_tratante.focus();
                return;
            }

            if (telefonoMedicoValue && !telefonoRegex.test(telefonoMedicoValue)) {
                mostrarAlerta('El teléfono del médico no tiene un formato válido');
                telefono_medico.focus();
                return;
            }
        }

        if (!nombreColegioValue) {
            mostrarAlerta('El nombre del colegio es obligatorio');
            nombre_colegio.focus();
            return;
        }
        if (!colegioRegex.test(nombreColegioValue)) {
            mostrarAlerta('El nombre del colegio contiene caracteres no válidos');
            nombre_colegio.focus();
            return;
        }

        if (direccionColegioValue && !direccionColegioRegex.test(direccionColegioValue)) {
            mostrarAlerta('La dirección del colegio contiene caracteres no válidos');
            direccion_colegio.focus();
            return;
        }

        if (!gradoCursanteValue) {
            mostrarAlerta('El grado/año cursante es obligatorio');
            grado_cursante.focus();
            return;
        }
        if (!gradoRegex.test(gradoCursanteValue)) {
            mostrarAlerta('El grado/año no es válido (ej. 4to Grado, 5° Año)');
            grado_cursante.focus();
            return;
        }

        if (nombreHobbieValue && !hobbieRegex.test(nombreHobbieValue)) {
            mostrarAlerta('El nombre del hobbie contiene caracteres no válidos');
            nombre_hobbie.focus();
            return;
        }

        if (horarioHobbieValue && !horarioRegex.test(horarioHobbieValue)) {
            mostrarAlerta('El horario del hobbie no es válido (ej. Martes y Jueves 4pm)');
            horario_hobbie.focus();
            return;
        }

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

    // ============================================
    // 7. FUNCIÓN PARA DESHABILITAR/HABILITAR CAMPOS DEL SEGURO
    // ============================================
    window.toggleSeguroFields = function() {
        const select = document.getElementById('polizaSeguro');
        const seguroFields = document.querySelectorAll('.seguro-field');
        const selectedValue = select.value;
        
        if (selectedValue === 'no_posee') {
            seguroFields.forEach(field => {
                const inputs = field.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    }
                });
                field.style.opacity = '0.6';
            });
        } else {
            seguroFields.forEach(field => {
                const inputs = field.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.disabled = false;
                });
                field.style.opacity = '1';
            });
        }
    };

    // ============================================
    // 8. EJECUTAR AL CARGAR LA PÁGINA
    // ============================================
    const selectSeguro = document.getElementById('polizaSeguro');
    if (selectSeguro && selectSeguro.value === 'no_posee') {
        window.toggleSeguroFields();
    }

    console.log('✅ Validaciones del formulario de atleta cargadas correctamente');

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

// Función para cargar datos en el modal de PAGADO
function mostrarDetallePago(mes, monto, tipoPago, referencia, cedula) {
    document.getElementById('detalleMes').textContent = mes;
    document.getElementById('detalleMonto').textContent = '$' + parseFloat(monto).toFixed(2);
    
    // Formatear tipo de pago
    const tipoPagoMap = {
        'transferencia': 'Transferencia Bancaria',
        'pago_movil': 'Pago Móvil',
        'efectivo': 'Efectivo',
        'zelle': 'Zelle'
    };
    document.getElementById('detalleTipoPago').innerHTML = 
        '<span class="badge bg-info">' + (tipoPagoMap[tipoPago] || tipoPago) + '</span>';
    document.getElementById('detalleReferencia').textContent = referencia || 'N/A';
}

// Función para cargar datos en el modal de PENDIENTE
function mostrarDetallePendiente(mes, monto, cedula) {
    document.getElementById('pendienteMes').textContent = mes;
    document.getElementById('pendienteMonto').textContent = '$' + parseFloat(monto).toFixed(2);
    document.getElementById('mesPagoHidden').value = mes;
    document.getElementById('pendienteMontoInput').value = monto;
}

// Función para cargar datos en el modal de REVISIÓN
function mostrarDetalleRevision(mes, monto, tipoPago, referencia, cedula) {
    document.getElementById('revisionMes').textContent = mes;
    document.getElementById('revisionMonto').textContent = '$' + parseFloat(monto).toFixed(2);
    
    const tipoPagoMap = {
        'transferencia': 'Transferencia Bancaria',
        'pago_movil': 'Pago Móvil',
        'efectivo': 'Efectivo',
        'zelle': 'Zelle',
        'pago_online': 'Pago Online'
    };
    document.getElementById('revisionTipoPago').innerHTML = 
        '<span class="badge bg-warning text-dark">' + (tipoPagoMap[tipoPago] || tipoPago) + '</span>';
    document.getElementById('revisionReferencia').textContent = referencia || 'N/A';
    document.getElementById('mesRevisionHidden').value = mes;
    document.getElementById('referenciaRevisionHidden').value = referencia;
}

// Función para confirmar pago en revisión
function confirmarPagoRevision(cedula) {
    if (confirm('¿Estás seguro de confirmar este pago?')) {
        // Aquí tu lógica para confirmar el pago
        alert('Pago confirmado exitosamente para cédula: ' + cedula);
        // Cerrar modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalSolvenciaRevision' + cedula));
        modal.hide();
    }
}

    // Preservar filtro al buscar
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const filterInput = document.getElementById('filterInput');
                filterInput.value = '<?php echo $current_filter; ?>';
            });
        }
    });

// Función para rechazar pago en revisión
function rechazarPagoRevision(cedula) {
    if (confirm('¿Estás seguro de rechazar este pago?')) {
        // Aquí tu lógica para rechazar el pago
        alert('Pago rechazado para cédula: ' + cedula);
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalSolvenciaRevision' + cedula));
        modal.hide();
    }
}

// Manejar el envío del formulario de pago pendiente
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('[id^="formPagoPendiente_"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('¿Confirmas registrar este pago?')) {
                const formData = new FormData(this);
                // Aquí procesas los datos con AJAX o PHP
                console.log('Datos del pago:', Object.fromEntries(formData));
                alert('Pago registrado exitosamente!');
                // Cerrar modal
                const modalId = this.closest('.modal').id;
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                modal.hide();
            }
        });
    });

    

});

function cargarDetalleCuota(atletaId, cuotaId) {
    // Verificar que se haya recibido un cuotaId válido
    if (!cuotaId || cuotaId === 'null' || cuotaId === 'undefined') {
        console.error('ID de cuota no válido:', cuotaId);
        alert('Error: No se pudo identificar la cuota.');
        return;
    }
    
    // Mostrar estado de carga en el modal
    document.getElementById('pendienteMes_' + atletaId).textContent = 'Cargando...';
    document.getElementById('pendienteMonto_' + atletaId).textContent = 'Bs ...';
    
    // Realizar petición AJAX para obtener los datos de la cuota
    fetch('Controller/ObtenerDetalleCuotaController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cuota_id=' + encodeURIComponent(cuotaId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
            document.getElementById('pendienteMes_' + atletaId).textContent = 'Error';
            document.getElementById('pendienteMonto_' + atletaId).textContent = 'Error';
            alert('Error al cargar los datos de la cuota: ' + data.error);
            return;
        }
        
        // Actualizar el modal con los datos recibidos
        var mesNombre = data.mes_nombre || 'Mes no especificado';
        var monto = parseFloat(data.monto) || 0;
        var montoFormateado = 'Bs ' + monto.toLocaleString('es-VE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        // Actualizar elementos del encabezado
        document.getElementById('pendienteMes_' + atletaId).textContent = mesNombre;
        document.getElementById('pendienteMonto_' + atletaId).textContent = montoFormateado;
        
        // Actualizar elementos de detalle
        document.getElementById('pendienteMesDetalle_' + atletaId).textContent = mesNombre;
        document.getElementById('pendienteMontoDetalle_' + atletaId).textContent = montoFormateado;
        
        // Asignar valores al formulario
        document.getElementById('pendienteCuotaId_' + atletaId).value = cuotaId;
        document.getElementById('pendienteMontoInput_' + atletaId).value = monto.toFixed(2);
    })
    .catch(error => {
        console.error('Error en la petición AJAX:', error);
        document.getElementById('pendienteMes_' + atletaId).textContent = 'Error';
        document.getElementById('pendienteMonto_' + atletaId).textContent = 'Error';
        alert('Error al cargar los datos de la cuota. Por favor, intenta de nuevo.');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Capturar todos los botones que abren el modal de pendiente
    document.querySelectorAll('[data-bs-target^="#modalSolvenciaPendiente"]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            // Obtener datos de los atributos data-*
            var mes = this.getAttribute('data-mes') || 'Mes no especificado';
            var monto = this.getAttribute('data-monto') || '0';
            var cuotaId = this.getAttribute('data-cuota') || '';
            var atletaId = this.getAttribute('data-atleta') || '';
            var estado = this.getAttribute('data-estado') || 'Pendiente';
            
            // Llamar a la función que actualiza el modal
            if (window.mostrarDetallePendiente) {
                window.mostrarDetallePendiente(mes, monto, atletaId, cuotaId, estado);
            } else {
                console.warn('Función mostrarDetallePendiente no definida');
            }
        });
    });
});

function mostrarDetallePendiente(mes, monto, atletaId, cuotaId, estado) {
    // Validar valores
    if (!mes) mes = 'Mes no especificado';
    if (!monto || isNaN(parseFloat(monto))) monto = '0';
    
    var montoNum = parseFloat(monto) || 0;
    var montoFormateado = 'Bs ' + montoNum.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Actualizar elementos
    function actualizarElemento(id, valor) {
        var el = document.getElementById(id);
        if (el) el.textContent = valor;
    }
    
    actualizarElemento('pendienteMes_' + atletaId, mes);
    actualizarElemento('pendienteMesDetalle_' + atletaId, mes);
    actualizarElemento('pendienteMonto_' + atletaId, montoFormateado);
    actualizarElemento('pendienteMontoDetalle_' + atletaId, montoFormateado);
    actualizarElemento('pendienteEstado_' + atletaId, estado || 'Pendiente');
    
    // Asignar cuota_id al campo oculto
    var cuotaInput = document.getElementById('pendienteCuotaId_' + atletaId);
    if (cuotaInput) cuotaInput.value = cuotaId || '';
    
    // Asignar monto al input
    var montoInput = document.getElementById('pendienteMontoInput_' + atletaId);
    if (montoInput) montoInput.value = montoNum.toFixed(2);
    
    // Inicializar toggle de campos de banco
    if (typeof toggleCamposPendiente === 'function') {
        toggleCamposPendiente(atletaId);
    }
}

function mostrarDetallePago(mes, monto, tipoPago, referencia, atletaId, fechaPago = '', banco = '') {
    // Validar valores
    if (!mes) mes = 'Mes no especificado';
    if (!monto || isNaN(parseFloat(monto))) monto = '0';
    if (!tipoPago) tipoPago = 'No especificado';
    if (!referencia) referencia = 'Sin referencia';
    if (!fechaPago) fechaPago = 'No registrada';
    
    var montoNum = parseFloat(monto) || 0;
    var montoFormateado = 'Bs ' + montoNum.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Actualizar elementos
    function actualizarElemento(id, valor) {
        var el = document.getElementById(id);
        if (el) el.textContent = valor;
    }
    
    actualizarElemento('detalleMes_' + atletaId, mes);
    actualizarElemento('detalleMonto_' + atletaId, montoFormateado);
    actualizarElemento('detalleFecha_' + atletaId, fechaPago);  // Recibe '15/01/2024' o 'No registrada'
    
    var tipoPagoEl = document.getElementById('detalleTipoPago_' + atletaId);
    if (tipoPagoEl) {
        tipoPagoEl.innerHTML = '<span class="badge bg-info">' + tipoPago + '</span>';
    }
    
    actualizarElemento('detalleReferencia_' + atletaId, referencia);
    
    var bancoEl = document.getElementById('detalleBanco_' + atletaId);
    if (bancoEl) {
        bancoEl.textContent = banco || 'No especificado';
    }
}