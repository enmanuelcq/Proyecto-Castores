// ============================================
// AJAX FORM HANDLER (Generic fallback)
// Handles .FormularioAjax1 and unhandled .FormularioAjax
// ============================================
document.addEventListener('DOMContentLoaded', function() {

    // Handle .FormularioAjax1 forms (desactivar, etc.)
    document.querySelectorAll('.FormularioAjax1').forEach(function(formulario) {
        formulario.addEventListener('submit', enviar_formulario_ajax);
    });

    // Handle .FormularioAjax forms that don't have their own page-level handler
    // (Page-specific JS files call e.preventDefault() first, so this won't double-fire)
    document.querySelectorAll('.FormularioAjax').forEach(function(formulario) {
        if (!formulario.dataset.ajaxBound) {
            formulario.addEventListener('submit', enviar_formulario_ajax);
        }
    });

    function enviar_formulario_ajax(e) {
        if (e.target.dataset.ajaxBound) return;
        e.preventDefault();

        var enviar = confirm("¿Quieres enviar el formulario?");
        if (!enviar) return;

        var form = e.target;
        var data = new FormData(form);
        var method = form.getAttribute("method") || "POST";
        var action = form.getAttribute("action");

        if (!action) {
            mostrarError(form, "Este formulario no tiene una URL de destino configurada.");
            return;
        }

        var config = {
            method: method,
            cache: 'no-cache',
            body: data
        };

        fetch(action, config)
            .then(function(respuesta) {
                if (!respuesta.ok) throw new Error('Error del servidor');
                return respuesta.text();
            })
            .then(function(respuesta) {
                var contenedor = form.closest('.modal-body, .tab-pane, .info-card');
                if (contenedor) {
                    var target = contenedor.querySelector(".form-rest, .form-rest1");
                    if (target) {
                        target.innerHTML = respuesta;
                    }
                }
                if (respuesta.includes('alert-success')) {
                    setTimeout(function() { location.reload(); }, 2000);
                }
            })
            .catch(function(error) {
                mostrarError(form, 'Error de conexión: ' + error.message);
            });
    }

    function mostrarError(form, mensaje) {
        var contenedor = form.closest('.modal-body, .tab-pane, .info-card');
        if (contenedor) {
            var target = contenedor.querySelector(".form-rest, .form-rest1");
            if (target) {
                target.innerHTML = '<div class="alert alert-danger py-2 small"><strong>Error:</strong> ' + mensaje + '</div>';
            }
        }
    }
});
