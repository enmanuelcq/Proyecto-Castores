

// ============================================
// CONSULTA ATLETAS - JS GLOBAL
// ============================================

// Stub: initActualizarModal se define en modal_actualizar.php inline.
// Si no hay atletas en la primera pagina, lo definimos aqui como no-op.
if (!window.initActualizarModal) {
    window.initActualizarModal = function() {};
}

// Funcion para re-inicializar modales despues de AJAX pagination
function reinitModales(container) {
    if (!window.initActualizarModal) return;
    container.querySelectorAll('[id^="modalActualizar"]').forEach(function(m) {
        var aid = m.id.replace('modalActualizar', '');
        if (aid) window.initActualizarModal(aid);
    });
}

// Funcion para cargar datos en el modal de PAGADO
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