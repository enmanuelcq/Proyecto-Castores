// ============================================
// DATOS DE PRUEBAS PRECARGADOS (PHP -> JS)
// ============================================


// ============================================
// FUNCIONES DEL MODAL
// ============================================

function abrirModalDetalle(tipo, cedula, nombreAtleta) {
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetallePruebas'));
    modal.show();
    
    // Actualizar título
    const nombres = {
        'balon_aereo': 'Balón Aéreo',
        'conduccion': 'Conducción',
        'definicion': 'Definición',
        'fuerza': 'Fuerza',
        'pases': 'Pases',
        'recepcion': 'Recepción',
        'resistencia': 'Resistencia',
        'salto': 'Salto',
        'velocidad': 'Velocidad'
    };
    document.getElementById('modalTipoPrueba').textContent = nombres[tipo] || tipo;
    document.getElementById('modalAtletaInfo').textContent = '- ' + nombreAtleta;
    
    // Obtener pruebas del tipo seleccionado
    const pruebas = datosPruebas[tipo] || [];
    
    if (pruebas.length === 0) {
        document.getElementById('modalDetalleBody').innerHTML = `
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                No hay pruebas de este tipo para el atleta
            </div>
        `;
        return;
    }
    
    // Generar HTML
    const html = generarHTMLDetalle(tipo, pruebas);
    document.getElementById('modalDetalleBody').innerHTML = html;
}

function generarHTMLDetalle(tipo, pruebas) {
    let html = '';
    
    // ===== RESUMEN =====
    const total = pruebas.length;
    const ultima = pruebas[0];
    const fechaUltima = obtenerFecha(ultima);
    
    html += '<div class="modal-resumen">';
    html += '<div class="modal-resumen-item">';
    html += '<span class="valor">' + total + '</span>';
    html += '<span class="etiqueta">Total Pruebas</span>';
    html += '</div>';
    
    html += '<div class="modal-resumen-item">';
    html += '<span class="valor">' + (fechaUltima ? formatearFecha(fechaUltima) : 'N/A') + '</span>';
    html += '<span class="etiqueta">Última Prueba</span>';
    html += '</div>';
    
    html += '<div class="modal-resumen-item">';
    html += '<span class="valor">' + calcularPromedio(tipo, pruebas) + '</span>';
    html += '<span class="etiqueta">Promedio</span>';
    html += '</div>';
    
    html += '<div class="modal-resumen-item">';
    html += '<span class="valor">' + obtenerMejor(tipo, pruebas) + '</span>';
    html += '<span class="etiqueta">Mejor Resultado</span>';
    html += '</div>';
    html += '</div>';
    
    // ===== TABLA =====
    html += '<div class="table-responsive">';
    html += '<table class="tabla-detalle" id="tablaDetalle">';
    html += '<thead><tr>';
    
    const cabeceras = obtenerCabeceras(tipo);
    cabeceras.forEach(cab => {
        html += '<th>' + cab + '</th>';
    });
    html += '</tr></thead><tbody>';
    
    pruebas.forEach((prueba, index) => {
        const fila = obtenerFilaTabla(tipo, prueba, index + 1);
        html += '<tr>';
        fila.forEach(celda => {
            html += '<td>' + celda + '</td>';
        });
        html += '</tr>';
    });
    
    html += '</tbody></table>';
    html += '</div>';
    
    return html;
}

function obtenerCabeceras(tipo) {
    const cabeceras = {
        'balon_aereo': ['#', 'Fecha', 'Total Toques', 'Pecho', 'Rodilla', 'Pie', 'Evaluación'],
        'conduccion': ['#', 'Fecha', 'Distancia', 'Derecha', 'Izquierda', 'Ambas', 'Evaluación'],
        'definicion': ['#', 'Fecha', 'Distancia', 'Tiros', 'Goles', 'Efectividad', 'Evaluación'],
        'fuerza': ['#', 'Fecha', 'Flexiones', 'Abdominales', 'Sentadillas', 'Total', 'Evaluación'],
        'pases': ['#', 'Fecha', 'Total', 'Derecha', 'Izquierda', '1ra Intención', 'Evaluación'],
        'recepcion': ['#', 'Fecha', 'Pases Recibidos', 'Controles Exitosos', 'Controles Fallidos', 'Efectividad', 'Evaluación'],
        'resistencia': ['#', 'Fecha', 'Tiempo Total', 'Pausa', 'Evaluación'],
        'salto': ['#', 'Fecha', 'Altura Inicial', '1er Salto', '2do Salto', '3er Salto', 'Promedio', 'Evaluación'],
        'velocidad': ['#', 'Fecha', 'Distancia', '1er Intento', '2do Intento', '3er Intento', 'Promedio', 'Evaluación']
    };
    return cabeceras[tipo] || ['#', 'Fecha', 'Datos'];
}

function obtenerFilaTabla(tipo, prueba, index) {
    const fila = [index];
    const fecha = obtenerFecha(prueba);
    fila.push(fecha ? formatearFecha(fecha) : 'N/A');
    
    switch(tipo) {
        case 'balon_aereo':
            const totalToques = prueba['total_toques'];
            const evalAereo = totalToques >= 50 ? 'Excelente' : totalToques >= 40 ? 'Bueno' : totalToques >= 30 ? 'Regular' : 'Bajo';
            fila.push(totalToques);
            fila.push(prueba['toques_pecho']);
            fila.push(prueba['toques_rodilla']);
            fila.push(prueba['toques_pie']);
            fila.push('<span class="badge-estado badge-' + evalAereo.toLowerCase() + '">' + evalAereo + '</span>');
            break;
            
        case 'conduccion':
            const tiempo = prueba['tiempo_ambas_piernas'];
            const evalConduccion = tiempo <= 6 ? 'Excelente' : tiempo <= 7 ? 'Bueno' : tiempo <= 8 ? 'Regular' : 'Bajo';
            fila.push(prueba['distancia_metros'] + 'm');
            fila.push(prueba['tiempo_derecha'] + 's');
            fila.push(prueba['tiempo_izquierda'] + 's');
            fila.push(prueba['tiempo_ambas_piernas'] + 's');
            fila.push('<span class="badge-estado badge-' + evalConduccion.toLowerCase() + '">' + evalConduccion + '</span>');
            break;
            
        case 'definicion':
            const goles = prueba['goles'];
            const efectividad = prueba['cantidad_tiros'] > 0 ? Math.round((goles / prueba['cantidad_tiros']) * 100) : 0;
            const evalDefinicion = efectividad >= 70 ? 'Excelente' : efectividad >= 50 ? 'Bueno' : efectividad >= 30 ? 'Regular' : 'Bajo';
            fila.push(prueba['distancia_metros'] + 'm');
            fila.push(prueba['cantidad_tiros']);
            fila.push(goles);
            fila.push(efectividad + '%');
            fila.push('<span class="badge-estado badge-' + evalDefinicion.toLowerCase() + '">' + evalDefinicion + '</span>');
            break;
            
        case 'fuerza':
            const totalFuerza = prueba['pruebaf_flexiones_1min'] + prueba['pruebaf_abdominales_1min'] + prueba['pruebaf_sentadillas_1min'];
            const evalFuerza = totalFuerza >= 120 ? 'Excelente' : totalFuerza >= 90 ? 'Bueno' : totalFuerza >= 60 ? 'Regular' : 'Bajo';
            fila.push(prueba['pruebaf_flexiones_1min']);
            fila.push(prueba['pruebaf_abdominales_1min']);
            fila.push(prueba['pruebaf_sentadillas_1min']);
            fila.push(totalFuerza);
            fila.push('<span class="badge-estado badge-' + evalFuerza.toLowerCase() + '">' + evalFuerza + '</span>');
            break;
            
        case 'pases':
            const totalPases = prueba['total_pases'];
            const evalPases = totalPases >= 35 ? 'Excelente' : totalPases >= 25 ? 'Bueno' : totalPases >= 15 ? 'Regular' : 'Bajo';
            fila.push(totalPases);
            fila.push(prueba['derecha']);
            fila.push(prueba['izquierda']);
            fila.push(prueba['primera_intencion']);
            fila.push('<span class="badge-estado badge-' + evalPases.toLowerCase() + '">' + evalPases + '</span>');
            break;
            
        case 'recepcion':
            const exitos = prueba['controles_exitosos'];
            const efectividadRecepcion = prueba['pases_recibidos'] > 0 ? Math.round((exitos / prueba['pases_recibidos']) * 100) : 0;
            const evalRecepcion = efectividadRecepcion >= 80 ? 'Excelente' : efectividadRecepcion >= 60 ? 'Bueno' : efectividadRecepcion >= 40 ? 'Regular' : 'Bajo';
            fila.push(prueba['pases_recibidos']);
            fila.push(exitos);
            fila.push(prueba['controles_fallidos']);
            fila.push(efectividadRecepcion + '%');
            fila.push('<span class="badge-estado badge-' + evalRecepcion.toLowerCase() + '">' + evalRecepcion + '</span>');
            break;
            
        case 'resistencia':
            const tiempoResistencia = prueba['tiempo_total'];
            const evalResistencia = tiempoResistencia >= 10 ? 'Excelente' : tiempoResistencia >= 8 ? 'Bueno' : tiempoResistencia >= 6 ? 'Regular' : 'Bajo';
            fila.push(prueba['tiempo_total'] + 'min');
            fila.push(prueba['pausa'] + 'min');
            fila.push('<span class="badge-estado badge-' + evalResistencia.toLowerCase() + '">' + evalResistencia + '</span>');
            break;
            
        case 'salto':
            const promedioSalto = prueba['pruebaS_Promedio'];
            const evalSalto = promedioSalto >= 270 ? 'Excelente' : promedioSalto >= 240 ? 'Bueno' : promedioSalto >= 210 ? 'Regular' : 'Bajo';
            fila.push(prueba['pruebaS_alt_inicial'] + 'cm');
            fila.push(prueba['pruebaS_1er_salto'] + 'cm');
            fila.push(prueba['pruebaS_2do_salto'] + 'cm');
            fila.push(prueba['pruebaS_3er_salto'] + 'cm');
            fila.push(prueba['pruebaS_Promedio'] + 'cm');
            fila.push('<span class="badge-estado badge-' + evalSalto.toLowerCase() + '">' + evalSalto + '</span>');
            break;
            
        case 'velocidad':
            const promedioVelocidad = prueba['pruebav_Promedio'];
            const evalVelocidad = promedioVelocidad <= 4.2 ? 'Excelente' : promedioVelocidad <= 4.8 ? 'Bueno' : promedioVelocidad <= 5.5 ? 'Regular' : 'Bajo';
            fila.push(prueba['pruebav_distancia_mtr'] + 'm');
            fila.push(prueba['pruebav_1er_intento_seg'] + 's');
            fila.push(prueba['pruebav_2do_intento_seg'] + 's');
            fila.push(prueba['pruebav_3er_intento_seg'] + 's');
            fila.push(prueba['pruebav_Promedio'] + 's');
            fila.push('<span class="badge-estado badge-' + evalVelocidad.toLowerCase() + '">' + evalVelocidad + '</span>');
            break;
    }
    
    return fila;
}

function obtenerFecha(prueba) {
    return prueba['fecha'] || prueba['pruebaf_fecha'] || prueba['pruebav_fecha'] || null;
}

function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    const d = new Date(fecha);
    return d.getDate().toString().padStart(2, '0') + '/' + 
           (d.getMonth() + 1).toString().padStart(2, '0') + '/' + 
           d.getFullYear();
}

function calcularPromedio(tipo, pruebas) {
    if (pruebas.length === 0) return 'N/A';
    
    let total = 0;
    switch(tipo) {
        case 'balon_aereo':
            pruebas.forEach(p => total += p['total_toques']);
            return Math.round(total / pruebas.length);
        case 'conduccion':
            pruebas.forEach(p => total += p['tiempo_ambas_piernas']);
            return (total / pruebas.length).toFixed(2) + 's';
        case 'definicion':
            pruebas.forEach(p => {
                const goles = p['goles'];
                total += (goles / p['cantidad_tiros']) * 100;
            });
            return Math.round(total / pruebas.length) + '%';
        case 'fuerza':
            pruebas.forEach(p => {
                total += p['pruebaf_flexiones_1min'] + p['pruebaf_abdominales_1min'] + p['pruebaf_sentadillas_1min'];
            });
            return Math.round(total / pruebas.length);
        case 'pases':
            pruebas.forEach(p => total += p['total_pases']);
            return Math.round(total / pruebas.length);
        case 'recepcion':
            pruebas.forEach(p => {
                total += (p['controles_exitosos'] / p['pases_recibidos']) * 100;
            });
            return Math.round(total / pruebas.length) + '%';
        case 'resistencia':
            pruebas.forEach(p => total += p['tiempo_total']);
            return (total / pruebas.length).toFixed(2) + 'min';
        case 'salto':
            pruebas.forEach(p => total += p['pruebaS_Promedio']);
            return (total / pruebas.length).toFixed(2) + 'cm';
        case 'velocidad':
            pruebas.forEach(p => total += p['pruebav_Promedio']);
            return (total / pruebas.length).toFixed(2) + 's';
        default:
            return 'N/A';
    }
}

function obtenerMejor(tipo, pruebas) {
    if (pruebas.length === 0) return 'N/A';
    
    let mejor = null;
    switch(tipo) {
        case 'balon_aereo':
            pruebas.forEach(p => { if (mejor === null || p['total_toques'] > mejor) mejor = p['total_toques']; });
            return mejor;
        case 'conduccion':
            pruebas.forEach(p => { if (mejor === null || p['tiempo_ambas_piernas'] < mejor) mejor = p['tiempo_ambas_piernas']; });
            return mejor + 's';
        case 'definicion':
            pruebas.forEach(p => {
                const ef = (p['goles'] / p['cantidad_tiros']) * 100;
                if (mejor === null || ef > mejor) mejor = ef;
            });
            return Math.round(mejor) + '%';
        case 'fuerza':
            pruebas.forEach(p => {
                const total = p['pruebaf_flexiones_1min'] + p['pruebaf_abdominales_1min'] + p['pruebaf_sentadillas_1min'];
                if (mejor === null || total > mejor) mejor = total;
            });
            return mejor;
        case 'pases':
            pruebas.forEach(p => { if (mejor === null || p['total_pases'] > mejor) mejor = p['total_pases']; });
            return mejor;
        case 'recepcion':
            pruebas.forEach(p => {
                const ef = (p['controles_exitosos'] / p['pases_recibidos']) * 100;
                if (mejor === null || ef > mejor) mejor = ef;
            });
            return Math.round(mejor) + '%';
        case 'resistencia':
            pruebas.forEach(p => { if (mejor === null || p['tiempo_total'] > mejor) mejor = p['tiempo_total']; });
            return mejor + 'min';
        case 'salto':
            pruebas.forEach(p => { if (mejor === null || p['pruebaS_Promedio'] > mejor) mejor = p['pruebaS_Promedio']; });
            return mejor + 'cm';
        case 'velocidad':
            pruebas.forEach(p => { if (mejor === null || p['pruebav_Promedio'] < mejor) mejor = p['pruebav_Promedio']; });
            return mejor + 's';
        default:
            return 'N/A';
    }
}

function imprimirTabla() {
    const contenido = document.getElementById('modalDetalleBody');
    if (!contenido) return;
    
    const ventana = window.open('', '_blank', 'width=800,height=600');
    ventana.document.write('<html><head><title>Detalle de Pruebas</title>');
    ventana.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
    ventana.document.write('<style>body{padding:20px;background:#fff;color:#000;}.tabla-detalle{width:100%;border-collapse:collapse;}.tabla-detalle th,.tabla-detalle td{padding:8px 12px;border:1px solid #ddd;}.badge-estado{padding:3px 10px;border-radius:12px;font-size:0.75rem;}.badge-excelente{background:#198754;color:#fff;}.badge-bueno{background:#0d6efd;color:#fff;}.badge-regular{background:#ffc107;color:#212529;}.badge-bajo{background:#dc3545;color:#fff;}</style>');
    ventana.document.write('</head><body>');
    ventana.document.write('<h4>Detalle de Pruebas</h4>');
    ventana.document.write(contenido.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.print();
}

function exportarExcel() {
    const tabla = document.querySelector('.tabla-detalle');
    if (!tabla) return;
    
    let csv = '';
    const filas = tabla.querySelectorAll('tr');
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('th, td');
        const filaData = [];
        celdas.forEach(celda => {
            filaData.push('"' + celda.textContent.trim() + '"');
        });
        csv += filaData.join(',') + '\n';
    });
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'pruebas.csv';
    link.click();
    URL.revokeObjectURL(link.href);
}

// ============================================
//Registro de nuevas pruebas
// ============================================

// ============================================
// MOSTRAR/OCULTAR CAMPOS SEGÚN TIPO DE PRUEBA
// ============================================
document.getElementById('tipoPruebaSelect').addEventListener('change', function() {
    const tipo = this.value;
    
    // Ocultar todos los campos
    document.querySelectorAll('.campos-prueba').forEach(el => {
        el.style.display = 'none';
    });
    
    // Mostrar los campos correspondientes
    if (tipo) {
        // Mapeo de tipos a IDs de campos
        const mapa = {
            'balon_aereo': 'camposBalonAereo',
            'conduccion': 'camposConduccion',
            'definicion': 'camposDefinicion',
            'fuerza': 'camposFuerza',
            'pases': 'camposPases',
            'recepcion': 'camposRecepcion',
            'resistencia': 'camposResistencia',
            'salto': 'camposSalto',
            'velocidad': 'camposVelocidad'
        };
        
        const camposId = mapa[tipo];
        if (camposId) {
            document.getElementById(camposId).style.display = 'block';
        }
    }
});

// ============================================
// FILTROS PARA EVITAR DATOS MALICIOSOS
// ============================================
document.querySelectorAll('.campo-filtrado').forEach(input => {
    // Filtrar caracteres no numéricos
    input.addEventListener('keypress', function(e) {
        // Permitir: números, punto decimal (para float), backspace, tab, enter
        const char = String.fromCharCode(e.keyCode);
        const permitidos = /[0-9.]/;
        const teclasEspeciales = [8, 9, 13, 27, 46, 37, 38, 39, 40];
        
        if (teclasEspeciales.includes(e.keyCode)) {
            return true;
        }
        
        if (!permitidos.test(char)) {
            e.preventDefault();
            return false;
        }
        
        // Evitar múltiples puntos decimales
        if (char === '.' && this.value.includes('.')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Validar al perder el foco
    input.addEventListener('blur', function() {
        const min = parseFloat(this.getAttribute('min')) || 0;
        const max = parseFloat(this.getAttribute('max')) || 999;
        let valor = parseFloat(this.value);
        
        if (this.value !== '') {
            // Si no es un número válido
            if (isNaN(valor)) {
                this.value = '';
                this.classList.add('error');
                return;
            }
            
            // Si es menor que el mínimo
            if (valor < min) {
                this.value = min;
                this.classList.remove('error');
                return;
            }
            
            // Si es mayor que el máximo
            if (valor > max) {
                this.value = max;
                this.classList.remove('error');
                return;
            }
            
            this.classList.remove('error');
        }
    });
    
    // Validar al escribir
    input.addEventListener('input', function() {
        const min = parseFloat(this.getAttribute('min')) || 0;
        const max = parseFloat(this.getAttribute('max')) || 999;
        let valor = parseFloat(this.value);
        
        if (this.value !== '' && !isNaN(valor)) {
            if (valor < min || valor > max) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        } else {
            this.classList.remove('error');
        }
    });
});

// ============================================
// CALCULAR PROMEDIO DE SALTO
// ============================================
document.querySelectorAll('[name="pruebaS_1er_salto"], [name="pruebaS_2do_salto"], [name="pruebaS_3er_salto"]').forEach(input => {
    input.addEventListener('input', function() {
        const salto1 = parseFloat(document.querySelector('[name="pruebaS_1er_salto"]').value) || 0;
        const salto2 = parseFloat(document.querySelector('[name="pruebaS_2do_salto"]').value) || 0;
        const salto3 = parseFloat(document.querySelector('[name="pruebaS_3er_salto"]').value) || 0;
        
        const promedio = (salto1 + salto2 + salto3) / 3;
        document.querySelector('[name="pruebaS_Promedio"]').value = promedio.toFixed(2);
    });
});

// ============================================
// CALCULAR PROMEDIO DE VELOCIDAD
// ============================================
document.querySelectorAll('[name="pruebav_1er_intento_seg"], [name="pruebav_2do_intento_seg"], [name="pruebav_3er_intento_seg"]').forEach(input => {
    input.addEventListener('input', function() {
        const intento1 = parseFloat(document.querySelector('[name="pruebav_1er_intento_seg"]').value) || 0;
        const intento2 = parseFloat(document.querySelector('[name="pruebav_2do_intento_seg"]').value) || 0;
        const intento3 = parseFloat(document.querySelector('[name="pruebav_3er_intento_seg"]').value) || 0;
        
        const promedio = (intento1 + intento2 + intento3) / 3;
        document.querySelector('[name="pruebav_Promedio"]').value = promedio.toFixed(2);
    });
});

// ============================================
// LIMPIAR FORMULARIO
// ============================================
function limpiarFormulario() {
    document.querySelectorAll('.campo-filtrado').forEach(input => {
        input.value = '';
        input.classList.remove('error');
    });
    document.querySelectorAll('.campos-prueba').forEach(el => {
        el.style.display = 'none';
    });
    document.getElementById('tipoPruebaSelect').value = '';
    document.getElementById('formRegistrarPrueba').reset();
}

// ============================================
// MANEJAR ENVÍO DEL FORMULARIO
// ============================================
document.getElementById('formRegistrarPrueba').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const tipo = document.getElementById('tipoPruebaSelect').value;
    if (!tipo) {
        alert('⚠️ Por favor selecciona un tipo de prueba');
        return;
    }
    
    // Validar campos según tipo
    const mapa = {
        'balon_aereo': 'camposBalonAereo',
        'conduccion': 'camposConduccion',
        'definicion': 'camposDefinicion',
        'fuerza': 'camposFuerza',
        'pases': 'camposPases',
        'recepcion': 'camposRecepcion',
        'resistencia': 'camposResistencia',
        'salto': 'camposSalto',
        'velocidad': 'camposVelocidad'
    };
    
    const camposId = mapa[tipo];
    if (camposId) {
        const campos = document.getElementById(camposId).querySelectorAll('.campo-filtrado');
        let valido = true;
        let camposVacios = [];
        
        campos.forEach(input => {
            if (input.value.trim() === '') {
                valido = false;
                input.classList.add('error');
                camposVacios.push(input.getAttribute('name'));
            } else {
                input.classList.remove('error');
            }
        });
        
        if (!valido) {
            alert('⚠️ Por favor completa todos los campos de la prueba');
            return;
        }
    }
    
    // Recopilar datos
    const formData = new FormData(this);
    const datos = {};
    formData.forEach((value, key) => {
        datos[key] = value;
    });
    
    console.log('📝 Datos de la prueba:', datos);
    
    // Validar que los valores sean números válidos
    let datosValidos = true;
    for (let key in datos) {
        if (key !== 'tipo_prueba' && key !== 'fecha' && key !== 'categoria_id' && 
            key !== 'atleta_cedula' && key !== 'usuario_cedula') {
            if (datos[key] !== '' && isNaN(parseFloat(datos[key]))) {
                datosValidos = false;
                break;
            }
        }
    }
    
    if (!datosValidos) {
        alert('⚠️ Todos los campos numéricos deben contener valores válidos');
        return;
    }
    
    // Aquí iría la petición AJAX para guardar
    // fetch('guardar_prueba.php', {
    //     method: 'POST',
    //     body: formData
    // })
    // .then(response => response.json())
    // .then(data => {
    //     if (data.success) {
    //         alert('✅ Prueba registrada exitosamente!');
    //         const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarPrueba'));
    //         modal.hide();
    //         location.reload();
    //     } else {
    //         alert('❌ Error al registrar: ' + data.message);
    //     }
    // })
    // .catch(error => {
    //     alert('❌ Error de conexión: ' + error.message);
    // });
    
    // Simulación de guardado exitoso
    alert('✅ Prueba registrada exitosamente!');
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarPrueba'));
    modal.hide();
    location.reload();
});

// ============================================
// FUNCIÓN PARA ABRIR EL MODAL
// ============================================
function abrirModalRegistro(tipo, cedula, nombreAtleta) {
    document.getElementById('registroAtletaCedula').value = cedula;
    document.getElementById('registroAtletaInfo').textContent = '- ' + nombreAtleta;
    
    // Limpiar formulario
    limpiarFormulario();
    
    if (tipo) {
        document.getElementById('tipoPruebaSelect').value = tipo;
        document.getElementById('tipoPruebaSelect').dispatchEvent(new Event('change'));
    }
    
    const modal = new bootstrap.Modal(document.getElementById('modalRegistrarPrueba'));
    modal.show();
}

// ============================================
// REINICIAR FORMULARIO AL CERRAR EL MODAL
// ============================================
document.getElementById('modalRegistrarPrueba').addEventListener('hidden.bs.modal', function() {
    limpiarFormulario();
});
