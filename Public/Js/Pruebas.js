// ============================================
// DATOS DE PRUEBAS PRECARGADOS (PHP -> JS)
// ============================================


// ============================================
// TOAST NOTIFICATIONS
// ============================================
function showToast(message, type = 'warning') {
    const container = document.getElementById('pruebasToastContainer');
    const toast    = document.getElementById('pruebasToast');
    const icon     = document.getElementById('pruebasToastIcon');
    const msg      = document.getElementById('pruebasToastMsg');

    toast.className = 'pruebas-toast';

    if (type === 'success') {
        toast.classList.add('pr-toast-success');
        icon.className = 'bi pruebas-toast-icon bi-check-circle-fill';
    } else if (type === 'error') {
        toast.classList.add('pr-toast-error');
        icon.className = 'bi pruebas-toast-icon bi-exclamation-triangle-fill';
    } else {
        toast.classList.add('pr-toast-warning');
        icon.className = 'bi pruebas-toast-icon bi-exclamation-circle-fill';
    }

    msg.textContent = message;
    container.style.display = 'block';

    requestAnimationFrame(() => {
        requestAnimationFrame(() => toast.classList.add('show'));
    });

    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}


// ============================================
// REAL-TIME INPUT CAPPING  (data-ref → id)
// ============================================
function getLimitValue(refId) {
    const el = document.getElementById(refId);
    if (!el) return Infinity;
    const v = parseFloat(el.value);
    return isNaN(v) ? Infinity : v;
}

function clampInput(input, max) {
    let v = parseFloat(input.value);
    if (isNaN(v) || v <= 0) return;
    if (max === Infinity) return;
    if (v > max) {
        input.value = max;
        input.classList.add('error', 'limit-flash');
        setTimeout(() => input.classList.remove('limit-flash'), 450);
        const label = input.closest('.col-md-3, .col-md-2, .col-md-4')
                            ?.querySelector('.form-label')?.textContent || 'Este campo';
        showToast(label + ' no puede superar ' + max, 'warning');
    } else {
        input.classList.remove('error');
    }
}

function bindLimitCap(refInput, limitClass) {
    if (!refInput) return;
    refInput.addEventListener('input', function () {
        const max = parseFloat(this.value) || 0;
        document.querySelectorAll('.' + limitClass).forEach(inp => clampInput(inp, max));
    });
}

document.querySelectorAll('.campo-limite').forEach(inp => {
    inp.addEventListener('input', function () {
        const refId = this.getAttribute('data-ref');
        clampInput(this, getLimitValue(refId));
    });
});

bindLimitCap(document.getElementById('ba_total'),  'campo-limite[data-ref="ba_total"]');
bindLimitCap(document.getElementById('def_tiros'),  'campo-limite[data-ref="def_tiros"]');
bindLimitCap(document.getElementById('pas_total'),  'campo-limite[data-ref="pas_total"]');
bindLimitCap(document.getElementById('rec_pases'),  'campo-limite[data-ref="rec_pases"]');

// Conducción: obstáculos ≤ distancia
document.querySelectorAll('.campo-limite-dist').forEach(inp => {
    inp.addEventListener('input', function () {
        const max = getLimitValue('cond_distancia');
        clampInput(this, max);
    });
});

// Resistencia: pausa ≤ tiempo_total
document.querySelectorAll('.campo-limite-res').forEach(inp => {
    inp.addEventListener('input', function () {
        const max = getLimitValue('res_tiempo');
        clampInput(this, max);
    });
});


// ============================================
// FUNCIONES DEL MODAL (Detalle)
// ============================================
// ============================================
// FILTRO DE CATEGORÍA – estado actual
// ============================================
let _filtroTipo = null;
let _filtroAtletaId = null;
let _filtroNombreAtleta = null;

function abrirModalDetalle(tipo, atletaId, nombreAtleta) {
    _filtroTipo = tipo;
    _filtroAtletaId = atletaId;
    _filtroNombreAtleta = nombreAtleta;

    const modal = new bootstrap.Modal(document.getElementById('modalDetallePruebas'));
    modal.show();

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
    document.getElementById('modalAtletaInfo').textContent  = '- ' + nombreAtleta;

    const pruebas = datosPruebas[tipo] || [];

    if (pruebas.length === 0) {
        document.getElementById('modalDetalleBody').innerHTML = `
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                No hay pruebas de este tipo para el atleta
            </div>`;
        return;
    }

    document.getElementById('modalDetalleBody').innerHTML = generarHTMLDetalle(tipo, pruebas, 'todas');

    const sel = document.getElementById('filtroCategoria');
    if (sel) {
        sel.addEventListener('change', function () {
            document.getElementById('modalDetalleBody').innerHTML = generarHTMLDetalle(tipo, pruebas, this.value);
        });
    }
}

function generarHTMLDetalle(tipo, pruebas, catFilter) {
    let html = '';

    const catFiltradas = {};
    pruebas.forEach(p => {
        const cid = String(p['categoria_id'] || '');
        if (catFilter && catFilter !== 'todas' && cid !== catFilter) return;
        if (!catFiltradas[cid]) catFiltradas[cid] = [];
        catFiltradas[cid].push(p);
    });
    const pruebasFiltradas = Object.values(catFiltradas).flat();

    const total     = pruebasFiltradas.length;
    const ultima    = pruebasFiltradas[0] || null;
    const fechaUlt  = ultima ? obtenerFecha(ultima) : null;

    let options = '<option value="todas">Todas las categorías</option>';
    const catIds = [...new Set(pruebas.map(p => String(p['categoria_id'] || '')))].sort((a,b) => Number(b) - Number(a));
    catIds.forEach(cid => {
        const nombre = (typeof categoriasMap !== 'undefined' && categoriasMap[cid]) ? categoriasMap[cid] : 'Cat. ' + cid;
        const sel = (catFilter === cid) ? ' selected' : '';
        options += '<option value="' + cid + '"' + sel + '>' + nombre + '</option>';
    });

    html += '<div class="d-flex align-items-center gap-3 mb-3 flex-wrap">';
    html += '<div>';
    html += '<label class="form-label fw-bold mb-0 me-2" style="font-size:0.82rem;color:#374151;">Categoría:</label>';
    html += '<select id="filtroCategoria" class="form-select form-select-sm" style="width:auto;display:inline-block;font-size:0.82rem;">';
    html += options;
    html += '</select>';
    html += '</div>';
    html += '<div class="modal-resumen" style="flex:1;margin-bottom:0;">';
    html += '<div class="modal-resumen-item"><span class="valor">' + total + '</span><span class="etiqueta">Total Pruebas</span></div>';
    html += '<div class="modal-resumen-item"><span class="valor">' + (fechaUlt ? formatearFecha(fechaUlt) : 'N/A') + '</span><span class="etiqueta">Última Prueba</span></div>';
    html += '</div>';
    html += '</div>';

    html += '<div class="table-responsive"><table class="tabla-detalle" id="tablaDetalle"><thead><tr>';
    obtenerCabeceras(tipo).forEach(c => html += '<th>' + c + '</th>');
    html += '</tr></thead><tbody>';
    pruebasFiltradas.forEach((p, i) => {
        html += '<tr>';
        obtenerFilaTabla(tipo, p, i + 1).forEach(c => html += '<td>' + c + '</td>');
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    return html;
}

function obtenerCabeceras(tipo) {
    return {
        'balon_aereo': ['#','Fecha','Total Toques','Pecho','Rodilla','Pie'],
        'conduccion':  ['#','Fecha','Distancia','Obstáculos','Derecha','Izquierda','Ambas','Tipo'],
        'definicion':  ['#','Fecha','Distancia','Tiros','Centro B','Centro A','Der. B','Der. A','Izq. B','Izq. A','Empeine'],
        'fuerza':      ['#','Fecha','Flexiones','Abdominales','Sentadillas','Total'],
        'pases':       ['#','Fecha','Total','Derecha','Izquierda','1ra Intención'],
        'recepcion':   ['#','Fecha','Pases','Distancia','Derecha','Izquierda','Der. Or.','Izq. Or.','Giro'],
        'resistencia': ['#','Fecha','Tiempo Total','Pausa'],
        'salto':       ['#','Fecha','Altura Inicial','1er Salto','2do Salto','3er Salto','Promedio'],
        'velocidad':   ['#','Fecha','Distancia','1er Intento','2do Intento','3er Intento','Promedio']
    }[tipo] || ['#','Fecha','Datos'];
}

function obtenerFilaTabla(tipo, prueba, index) {
    const f = [index];
    const fecha = obtenerFecha(prueba);
    f.push(fecha ? formatearFecha(fecha) : 'N/A');

    switch (tipo) {
        case 'balon_aereo': {
            f.push(prueba['total_toques'], prueba['toques_pecho'], prueba['toques_rodilla'], prueba['toques_pie']);
            break;
        }
        case 'conduccion': {
            f.push(prueba['distancia_metros']+'m', prueba['distancia_obstaculos']+'m',
                   prueba['tiempo_derecha']+'s', prueba['tiempo_izquierda']+'s', prueba['tiempo_ambas_piernas']+'s',
                   prueba['descripcion']||'-');
            break;
        }
        case 'definicion': {
            f.push(prueba['distancia_metros']+'m', prueba['cantidad_tiros'],
                   prueba['centro_bajo']||0, prueba['centro_alto']||0,
                   prueba['derecha_bajo']||0, prueba['derecha_alto']||0,
                   prueba['izquierda_bajo']||0, prueba['izquierda_alto']||0,
                   prueba['empeine']||0);
            break;
        }
        case 'fuerza': {
            const t = (prueba['pruebaf_flexiones_1min']||0)+(prueba['pruebaf_abdominales_1min']||0)+(prueba['pruebaf_sentadillas_1min']||0);
            f.push(prueba['pruebaf_flexiones_1min'], prueba['pruebaf_abdominales_1min'],
                   prueba['pruebaf_sentadillas_1min'], t);
            break;
        }
        case 'pases': {
            f.push(prueba['total_pases'], prueba['derecha'], prueba['izquierda'], prueba['primera_intencion']);
            break;
        }
        case 'recepcion': {
            f.push(prueba['cantidad_pases'], prueba['distancia_metros']+'m',
                   prueba['derecha'], prueba['izquierda'],
                   prueba['derecha_orientado'], prueba['izquierda_orientado'],
                   prueba['orientado_giro']);
            break;
        }
        case 'resistencia': {
            f.push(prueba['tiempo_total']+'min', prueba['pausa'] ? prueba['pausa']+'min' : 'Sin pausa');
            break;
        }
        case 'salto': {
            f.push(prueba['pruebaS_alt_inicial']+'cm', prueba['pruebaS_1er_salto']+'cm',
                   prueba['pruebaS_2do_salto']+'cm', prueba['pruebaS_3er_salto']+'cm',
                   prueba['pruebaS_Promedio']+'cm');
            break;
        }
        case 'velocidad': {
            f.push(prueba['pruebav_distancia_mtr']+'m',
                   prueba['pruebav_1er_intento_seg']+'s', prueba['pruebav_2do_intento_seg']+'s',
                   prueba['pruebav_3er_intento_seg']+'s', prueba['pruebav_Promedio']+'s');
            break;
        }
    }
    return f;
}

function badge(text) {
    const cls = text.toLowerCase();
    return '<span class="badge-estado badge-' + cls + '">' + text + '</span>';
}

function obtenerFecha(p)  { return p['fecha'] || p['pruebaf_fecha'] || p['pruebaS_fecha'] || p['pruebav_fecha'] || null; }
function formatearFecha(f) {
    if (!f) return 'N/A';
    const parts = f.split('T')[0].split('-');
    if (parts.length !== 3) return f;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function calcularPromedio(tipo, pruebas) {
    if (!pruebas.length) return 'N/A';
    let total = 0;
    switch (tipo) {
        case 'balon_aereo':  pruebas.forEach(p => total += p['total_toques']);                            return Math.round(total / pruebas.length);
        case 'conduccion':   pruebas.forEach(p => total += p['tiempo_ambas_piernas']);                    return (total / pruebas.length).toFixed(2) + 's';
        case 'definicion':   pruebas.forEach(p => { const tg = (p['centro_bajo']||0)+(p['centro_alto']||0)+(p['derecha_bajo']||0)+(p['derecha_alto']||0)+(p['izquierda_bajo']||0)+(p['izquierda_alto']||0)+(p['empeine']||0); total += tg; }); return Math.round(total/pruebas.length) + ' goles';
        case 'fuerza':       pruebas.forEach(p => total += (p['pruebaf_flexiones_1min']||0)+(p['pruebaf_abdominales_1min']||0)+(p['pruebaf_sentadillas_1min']||0)); return Math.round(total/pruebas.length);
        case 'pases':        pruebas.forEach(p => total += p['total_pases']);                             return Math.round(total / pruebas.length);
        case 'recepcion':    pruebas.forEach(p => { const tc = (p['derecha']||0)+(p['izquierda']||0)+(p['derecha_orientado']||0)+(p['izquierda_orientado']||0)+(p['orientado_giro']||0); total += tc; }); return Math.round(total/pruebas.length) + ' controles';
        case 'resistencia':  pruebas.forEach(p => total += p['tiempo_total']);                            return (total / pruebas.length).toFixed(2) + 'min';
        case 'salto':        pruebas.forEach(p => total += p['pruebaS_Promedio']);                        return (total / pruebas.length).toFixed(2) + 'cm';
        case 'velocidad':    pruebas.forEach(p => total += p['pruebav_Promedio']);                        return (total / pruebas.length).toFixed(2) + 's';
        default: return 'N/A';
    }
}

function obtenerMejor(tipo, pruebas) {
    if (!pruebas.length) return 'N/A';
    let m = null;
    switch (tipo) {
        case 'balon_aereo':  pruebas.forEach(p => { if (m===null || p['total_toques']>m) m=p['total_toques']; }); return m;
        case 'conduccion':   pruebas.forEach(p => { if (m===null || p['tiempo_ambas_piernas']<m) m=p['tiempo_ambas_piernas']; }); return m+'s';
        case 'definicion':   pruebas.forEach(p => { const tg=(p['centro_bajo']||0)+(p['centro_alto']||0)+(p['derecha_bajo']||0)+(p['derecha_alto']||0)+(p['izquierda_bajo']||0)+(p['izquierda_alto']||0)+(p['empeine']||0); if(m===null||tg>m)m=tg; }); return m+' goles';
        case 'fuerza':       pruebas.forEach(p => { const t=(p['pruebaf_flexiones_1min']||0)+(p['pruebaf_abdominales_1min']||0)+(p['pruebaf_sentadillas_1min']||0); if(m===null||t>m)m=t; }); return m;
        case 'pases':        pruebas.forEach(p => { if (m===null || p['total_pases']>m) m=p['total_pases']; }); return m;
        case 'recepcion':    pruebas.forEach(p => { const tc=(p['derecha']||0)+(p['izquierda']||0)+(p['derecha_orientado']||0)+(p['izquierda_orientado']||0)+(p['orientado_giro']||0); if(m===null||tc>m)m=tc; }); return m+' controles';
        case 'resistencia':  pruebas.forEach(p => { if (m===null || p['tiempo_total']>m) m=p['tiempo_total']; }); return m+'min';
        case 'salto':        pruebas.forEach(p => { if (m===null || p['pruebaS_Promedio']>m) m=p['pruebaS_Promedio']; }); return m+'cm';
        case 'velocidad':    pruebas.forEach(p => { if (m===null || p['pruebav_Promedio']<m) m=p['pruebav_Promedio']; }); return m+'s';
        default: return 'N/A';
    }
}

function imprimirTabla() {
    const contenido = document.getElementById('modalDetalleBody');
    if (!contenido) return;
    const w = window.open('','_blank','width=800,height=600');
    w.document.write('<html><head><title>Detalle de Pruebas</title>');
    w.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
    w.document.write('<style>body{padding:20px;background:#fff;color:#000;}.tabla-detalle{width:100%;border-collapse:collapse;}.tabla-detalle th,.tabla-detalle td{padding:8px 12px;border:1px solid #ddd;}.badge-estado{padding:3px 10px;border-radius:12px;font-size:0.75rem;}.badge-excelente{background:#198754;color:#fff;}.badge-bueno{background:#0d6efd;color:#fff;}.badge-regular{background:#ffc107;color:#212529;}.badge-bajo{background:#dc3545;color:#fff;}</style>');
    w.document.write('</head><body><h4>Detalle de Pruebas</h4>');
    w.document.write(contenido.innerHTML);
    w.document.write('</body></html>');
    w.document.close();
    w.print();
}

function exportarExcel() {
    const tabla = document.querySelector('.tabla-detalle');
    if (!tabla) return;
    let csv = '';
    tabla.querySelectorAll('tr').forEach(fila => {
        const fd = [];
        fila.querySelectorAll('th,td').forEach(c => fd.push('"'+c.textContent.trim()+'"'));
        csv += fd.join(',') + '\n';
    });
    const blob = new Blob([csv], { type:'text/csv;charset=utf-8;' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'pruebas.csv';
    a.click();
    URL.revokeObjectURL(a.href);
}


// ============================================
// MOSTRAR / OCULTAR CAMPOS SEGÚN TIPO DE PRUEBA
// ============================================
const mapaCampos = {
    'balon_aereo': 'camposBalonAereo',
    'conduccion':  'camposConduccion',
    'definicion':  'camposDefinicion',
    'fuerza':      'camposFuerza',
    'pases':       'camposPases',
    'recepcion':   'camposRecepcion',
    'resistencia': 'camposResistencia',
    'salto':       'camposSalto',
    'velocidad':   'camposVelocidad'
};

document.getElementById('tipoPruebaSelect').addEventListener('change', function () {
    document.querySelectorAll('.campos-prueba').forEach(el => el.style.display = 'none');
    const id = mapaCampos[this.value];
    if (id) document.getElementById(id).style.display = 'block';
});


// ============================================
// FILTROS TECLADO – solo números y punto
// ============================================
document.querySelectorAll('.campo-filtrado').forEach(input => {
    input.addEventListener('keypress', function (e) {
        if (e.key.length > 1) return;
        if (!/[0-9.]/.test(e.key)) { e.preventDefault(); return false; }
        if (e.key === '.' && this.value.includes('.')) { e.preventDefault(); return false; }
    });

    input.addEventListener('beforeinput', function (e) {
        if (e.data && !/^[0-9.]+$/.test(e.data)) e.preventDefault();
    });

    input.addEventListener('blur', function () {
        const min = parseFloat(this.getAttribute('min')) || 0;
        const max = parseFloat(this.getAttribute('max')) || 999;
        let v = parseFloat(this.value);
        if (this.value !== '') {
            if (isNaN(v))              { this.value = ''; this.classList.add('error'); return; }
            if (v < min)               { this.value = min; this.classList.remove('error'); return; }
            if (v > max)               { this.value = max; this.classList.remove('error'); return; }
            this.classList.remove('error');
        }
    });

    input.addEventListener('input', function () {
        const min = parseFloat(this.getAttribute('min')) || 0;
        const max = parseFloat(this.getAttribute('max')) || 999;
        let v = parseFloat(this.value);
        if (this.value !== '' && !isNaN(v)) {
            this.classList.toggle('error', v < min || v > max);
        } else {
            this.classList.remove('error');
        }
    });
});


// ============================================
// SALTO – calcular diferencias y promedio
// ============================================
function calcularSalto() {
    const ini = parseFloat(document.getElementById('salto_inicial')?.value) || 0;
    const r1  = parseFloat(document.getElementById('salto_1er_raw')?.value) || 0;
    const r2  = parseFloat(document.getElementById('salto_2do_raw')?.value) || 0;
    const r3  = parseFloat(document.getElementById('salto_3er_raw')?.value) || 0;

    const c1 = r1 > 0 ? Math.max(0, r1 - ini) : 0;
    const c2 = r2 > 0 ? Math.max(0, r2 - ini) : 0;
    const c3 = r3 > 0 ? Math.max(0, r3 - ini) : 0;

    document.getElementById('salto_1er_calc').value = c1 ? c1.toFixed(2) : '';
    document.getElementById('salto_2do_calc').value = c2 ? c2.toFixed(2) : '';
    document.getElementById('salto_3er_calc').value = c3 ? c3.toFixed(2) : '';

    let prom = 0, n = 0;
    if (r1 > 0) { prom += c1; n++; }
    if (r2 > 0) { prom += c2; n++; }
    if (r3 > 0) { prom += c3; n++; }
    prom = n > 0 ? prom / n : 0;
    document.getElementById('salto_promedio').value = n > 0 ? prom.toFixed(2) + 'cm' : '';

    if (ini > 0 && (r1 > 0 || r2 > 0 || r3 > 0)) {
        if (r1 > 0 && r1 < ini) showToast('1er salto crudo no puede ser menor que la altura inicial', 'warning');
        if (r2 > 0 && r2 < ini) showToast('2do salto crudo no puede ser menor que la altura inicial', 'warning');
        if (r3 > 0 && r3 < ini) showToast('3er salto crudo no puede ser menor que la altura inicial', 'warning');
    }
}

['salto_inicial','salto_1er_raw','salto_2do_raw','salto_3er_raw'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calcularSalto);
});


// ============================================
// VELOCIDAD – calcular promedio
// ============================================
function calcularVelocidad() {
    const i1 = parseFloat(document.getElementById('vel_intento1')?.value) || 0;
    const i2 = parseFloat(document.getElementById('vel_intento2')?.value) || 0;
    const i3 = parseFloat(document.getElementById('vel_intento3')?.value) || 0;
    let prom = 0, n = 0;
    if (i1 > 0) { prom += i1; n++; }
    if (i2 > 0) { prom += i2; n++; }
    if (i3 > 0) { prom += i3; n++; }
    prom = n > 0 ? prom / n : 0;
    document.getElementById('vel_promedio').value = n > 0 ? prom.toFixed(2) + 's' : '';
}

['vel_intento1','vel_intento2','vel_intento3'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calcularVelocidad);
});


// ============================================
// LIMPIAR FORMULARIO
// ============================================
function limpiarFormulario() {
    document.querySelectorAll('.campo-filtrado, .campo-limite, .campo-limite-dist, .campo-limite-res').forEach(inp => {
        inp.value = '';
        inp.classList.remove('error','limit-flash');
    });
    document.querySelectorAll('.campos-prueba').forEach(el => el.style.display = 'none');
    document.getElementById('tipoPruebaSelect').value = '';
    document.getElementById('formRegistrarPrueba').querySelectorAll('input:not([type="hidden"]), textarea').forEach(el => el.value = '');
    const ps = document.getElementById('salto_promedio');
    if (ps) ps.value = '';
    const pv = document.getElementById('vel_promedio');
    if (pv) pv.value = '';
    ['salto_1er_calc','salto_2do_calc','salto_3er_calc'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
}


// ============================================
// ENVIAR FORMULARIO
// ============================================
document.getElementById('formRegistrarPrueba').addEventListener('submit', function (e) {
    e.preventDefault();

    const tipo = document.getElementById('tipoPruebaSelect').value;
    if (!tipo) { showToast('Selecciona un tipo de prueba', 'error'); return; }

    const errores = [];

    if (tipo === 'balon_aereo') {
        const total  = parseFloat(document.getElementById('ba_total')?.value) || 0;
        const pecho  = parseFloat(document.querySelector('[name="toques_pecho"]')?.value) || 0;
        const rodilla= parseFloat(document.querySelector('[name="toques_rodilla"]')?.value) || 0;
        const pie    = parseFloat(document.querySelector('[name="toques_pie"]')?.value) || 0;
        if (!total)                    errores.push('El total de toques es requerido');
        if (pecho > total && total)    errores.push('Toques pecho supera el total');
        if (rodilla > total && total)  errores.push('Toques rodilla supera el total');
        if (pie > total && total)      errores.push('Toques pie supera el total');
    }

    if (tipo === 'conduccion') {
        const dist = parseFloat(document.getElementById('cond_distancia')?.value) || 0;
        const obst = parseFloat(document.querySelector('[name="cond_distancia_obstaculos"]')?.value) || 0;
        const desc = document.querySelector('[name="cond_descripcion"]')?.value?.trim();
        if (!dist)                    errores.push('La distancia total es requerida');
        if (obst > dist && dist)      errores.push('Dist. obstáculos supera la distancia total');
        if (!desc)                    errores.push('El tipo de ejercicio es requerido');
    }

    if (tipo === 'definicion') {
        const tiros = parseFloat(document.getElementById('def_tiros')?.value) || 0;
        if (!tiros) errores.push('La cantidad de tiros es requerida');
    }

    if (tipo === 'resistencia') {
        const tiempo = parseFloat(document.getElementById('res_tiempo')?.value) || 0;
        const pausa  = parseFloat(document.querySelector('[name="pausa"]')?.value) || 0;
        if (!tiempo)                  errores.push('El tiempo total es requerido');
        if (pausa > tiempo && tiempo) errores.push('La pausa supera el tiempo total');
    }

    if (tipo === 'salto') {
        const ini = parseFloat(document.getElementById('salto_inicial')?.value) || 0;
        if (!ini) errores.push('La altura inicial es requerida');
    }

    if (tipo === 'velocidad') {
        const dist = parseFloat(document.querySelector('[name="pruebav_distancia_mtr"]')?.value) || 0;
        if (!dist) errores.push('La distancia es requerida');
    }

    if (errores.length) {
        errores.forEach((msg, i) => {
            setTimeout(() => showToast(msg, 'error'), i * 350);
        });
        return;
    }

    showToast('Registrando prueba...', 'success');

    const params = new URLSearchParams(new FormData(this));

    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Guardando...';

    fetch(this.action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(r => r.text().then(() => r))
    .then(r => {
        const finalUrl = r.url || '';
        if (finalUrl.includes('error=')) {
            const msg = decodeURIComponent(finalUrl.split('error=')[1].split('&')[0].replace(/\+/g, ' '));
            showToast(msg, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        } else {
            showToast('Prueba registrada correctamente', 'success');
            setTimeout(() => window.location.href = finalUrl, 600);
        }
    })
    .catch(err => {
        showToast('Error de conexión: ' + err.message, 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});


// ============================================
// ABRIR MODAL
// ============================================
function abrirModalRegistro(tipo, atletaId, nombreAtleta, categoriaId) {
    limpiarFormulario();

    document.getElementById('registroAtletaId').value    = atletaId;
    document.getElementById('registroCategoriaId').value = categoriaId || '';
    document.getElementById('registroAtletaInfo').textContent = '- ' + nombreAtleta;

    if (tipo) {
        document.getElementById('tipoPruebaSelect').value = tipo;
        document.getElementById('tipoPruebaSelect').dispatchEvent(new Event('change'));
    }

    new bootstrap.Modal(document.getElementById('modalRegistrarPrueba')).show();
}

// Cerrar modal → limpiar
document.getElementById('modalRegistrarPrueba').addEventListener('hidden.bs.modal', limpiarFormulario);
