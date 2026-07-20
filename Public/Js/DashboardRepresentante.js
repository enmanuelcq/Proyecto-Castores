// ============================================
// FUNCIONES PARA ABRIR MODALES
// ============================================

function abrirModalPagados(cedula) {
    const modal = new bootstrap.Modal(document.getElementById('modalRepresentantePagados' + cedula));
    modal.show();
}

function abrirModalRegistrarPago(cedula) {
    const modal = new bootstrap.Modal(document.getElementById('modalRepresentanteRegistrarPago' + cedula));
    modal.show();
}

function abrirModalVerMas(cedula) {
    const modal = new bootstrap.Modal(document.getElementById('modalRepresentanteVerMas' + cedula));
    modal.show();
}

// ============================================
// MANEJAR ENVÍO DEL FORMULARIO DE PAGO
// ============================================
document.querySelectorAll('[id^="formRegistrarPagoRepresentante_"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cedula = this.querySelector('[name="atleta_cedula"]').value;
        const formData = new FormData(this);
        
        // Validar que se haya seleccionado un mes
        if (!formData.get('mes_pago')) {
            alert('Por favor selecciona un mes para pagar');
            return;
        }
        
        // Validar monto
        if (!formData.get('monto') || parseFloat(formData.get('monto')) <= 0) {
            alert('Por favor ingresa un monto válido');
            return;
        }
        
        if (confirm('¿Confirmas el registro de este pago?')) {
            console.log('📝 Datos del pago:', Object.fromEntries(formData));
            alert('✅ Pago registrado exitosamente!');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalRepresentanteRegistrarPago' + cedula));
            modal.hide();
            
            // Recargar página para mostrar cambios
            location.reload();
        }
    });
});