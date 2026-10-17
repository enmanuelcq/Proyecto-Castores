// DashboardRepresentante.js
// Lógica del panel del representante

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manejar cierre de modales
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            // Limpiar formularios al cerrar
            var forms = modal.querySelectorAll('form');
            forms.forEach(function(form) {
                form.reset();
            });
        });
    });

    // Confirmar acciones de pago
    var btnPagos = document.querySelectorAll('[data-bs-target="#modalRegistrarPago"]');
    btnPagos.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var atletaNombre = document.querySelector('.perfil-info h3');
            if (atletaNombre) {
                console.log('Registrando pago para: ' + atletaNombre.textContent);
            }
        });
    });

    // Manejar selección de atleta
    var selectorAtletas = document.querySelectorAll('.selector-atletas .btn-atleta');
    selectorAtletas.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            selectorAtletas.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
        });
    });

    // Animación para las tarjetas de estadísticas
    var statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(function(card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(function() {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Manejar notificaciones de noticias
    var noticias = document.querySelectorAll('.prueba-mini-card');
    noticias.forEach(function(noticia) {
        noticia.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        noticia.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
