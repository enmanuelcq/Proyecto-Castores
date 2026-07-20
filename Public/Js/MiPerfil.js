// ============================================
// MOSTRAR/OCULTAR CONTRASEÑA
// ============================================
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.parentElement.querySelector('button');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// ============================================
// VALIDACIÓN DE CONTRASEÑA EN TIEMPO REAL
// ============================================
document.getElementById('contrasena_nueva').addEventListener('input', function() {
    const pass = this.value;
    
    // Longitud
    const iconLongitud = document.getElementById('validacion_longitud');
    if (pass.length >= 8) {
        iconLongitud.style.color = '#198754';
        iconLongitud.classList.remove('bi-check-circle');
        iconLongitud.classList.add('bi-check-circle-fill');
    } else {
        iconLongitud.style.color = '#6c757d';
        iconLongitud.classList.remove('bi-check-circle-fill');
        iconLongitud.classList.add('bi-check-circle');
    }
    
    // Mayúscula
    const iconMayuscula = document.getElementById('validacion_mayuscula');
    if (/[A-Z]/.test(pass)) {
        iconMayuscula.style.color = '#198754';
        iconMayuscula.classList.remove('bi-check-circle');
        iconMayuscula.classList.add('bi-check-circle-fill');
    } else {
        iconMayuscula.style.color = '#6c757d';
        iconMayuscula.classList.remove('bi-check-circle-fill');
        iconMayuscula.classList.add('bi-check-circle');
    }
    
    // Minúscula
    const iconMinuscula = document.getElementById('validacion_minuscula');
    if (/[a-z]/.test(pass)) {
        iconMinuscula.style.color = '#198754';
        iconMinuscula.classList.remove('bi-check-circle');
        iconMinuscula.classList.add('bi-check-circle-fill');
    } else {
        iconMinuscula.style.color = '#6c757d';
        iconMinuscula.classList.remove('bi-check-circle-fill');
        iconMinuscula.classList.add('bi-check-circle');
    }
    
    // Número
    const iconNumero = document.getElementById('validacion_numero');
    if (/\d/.test(pass)) {
        iconNumero.style.color = '#198754';
        iconNumero.classList.remove('bi-check-circle');
        iconNumero.classList.add('bi-check-circle-fill');
    } else {
        iconNumero.style.color = '#6c757d';
        iconNumero.classList.remove('bi-check-circle-fill');
        iconNumero.classList.add('bi-check-circle');
    }
    
    // Verificar coincidencia
    verificarCoincidencia();
});

document.getElementById('contrasena_confirmar').addEventListener('input', function() {
    verificarCoincidencia();
});

function verificarCoincidencia() {
    const pass = document.getElementById('contrasena_nueva').value;
    const confirm = document.getElementById('contrasena_confirmar').value;
    const icon = document.getElementById('validacion_coinciden');
    
    if (confirm.length > 0 && pass === confirm) {
        icon.style.color = '#198754';
        icon.classList.remove('bi-check-circle');
        icon.classList.add('bi-check-circle-fill');
    } else if (confirm.length > 0) {
        icon.style.color = '#dc3545';
        icon.classList.remove('bi-check-circle');
        icon.classList.add('bi-x-circle-fill');
    } else {
        icon.style.color = '#6c757d';
        icon.classList.remove('bi-check-circle-fill');
        icon.classList.add('bi-check-circle');
    }
}

// ============================================
// MANEJAR ENVÍO DEL FORMULARIO DE PERFIL
// ============================================
document.getElementById('formActualizarPerfil').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Validar email
    const email = formData.get('email');
    if (!email.includes('@') || !email.includes('.')) {
        alert('Por favor ingresa un correo electrónico válido');
        return;
    }
    
    if (confirm('¿Confirmas la actualización de tus datos?')) {
        console.log('📝 Datos actualizados:', Object.fromEntries(formData));
        alert('✅ Datos actualizados exitosamente!');
        location.reload();
    }
});

// ============================================
// MANEJAR ENVÍO DEL FORMULARIO DE CONTRASEÑA
// ============================================
document.getElementById('formCambiarContrasena').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const actual = document.getElementById('contrasena_actual').value;
    const nueva = document.getElementById('contrasena_nueva').value;
    const confirmar = document.getElementById('contrasena_confirmar').value;
    
    // Validar que la contraseña actual no esté vacía
    if (!actual) {
        alert('Por favor ingresa tu contraseña actual');
        return;
    }
    
    // Validar que la nueva contraseña cumpla los requisitos
    if (nueva.length < 8) {
        alert('La nueva contraseña debe tener al menos 8 caracteres');
        return;
    }
    
    if (!/[A-Z]/.test(nueva)) {
        alert('La nueva contraseña debe tener al menos una mayúscula');
        return;
    }
    
    if (!/[a-z]/.test(nueva)) {
        alert('La nueva contraseña debe tener al menos una minúscula');
        return;
    }
    
    if (!/\d/.test(nueva)) {
        alert('La nueva contraseña debe tener al menos un número');
        return;
    }
    
    // Validar que coincidan
    if (nueva !== confirmar) {
        alert('Las contraseñas no coinciden');
        return;
    }
    
    // Validar que la nueva sea diferente a la actual
    if (nueva === actual) {
        alert('La nueva contraseña debe ser diferente a la actual');
        return;
    }
    
    if (confirm('¿Confirmas el cambio de contraseña?')) {
        console.log('🔑 Contraseña actualizada');
        alert('✅ Contraseña cambiada exitosamente!');
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambiarContrasena'));
        modal.hide();
        
        // Limpiar campos
        this.reset();
        
        // Resetear íconos de validación
        document.querySelectorAll('[id^="validacion_"]').forEach(el => {
            el.style.color = '#6c757d';
            el.classList.remove('bi-check-circle-fill', 'bi-x-circle-fill');
            el.classList.add('bi-check-circle');
        });
    }
});