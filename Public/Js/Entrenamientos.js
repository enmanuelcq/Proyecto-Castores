// ============================================
// Public/Js/Entrenamientos.js
// Funcionalidad del módulo de Entrenamientos:
//  - Agregar/eliminar recursos multimedia (enlaces, imágenes, videos)
//  - Validación del total de atletas vs categorías seleccionadas
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ===== RECURSOS MULTIMEDIA =====
    // Funciona en cualquier contenedor con data-recurso-contenedor
    window.agregarRecursoNuevo = function (boton, tipo) {
        var form = boton.closest('form');
        var cont = form ? form.querySelector('[data-recurso-contenedor]') : null;
        if (!cont) return;

        var fila = document.createElement('div');
        fila.className = 'row g-2 align-items-center mb-2 recurso-fila-nueva';

        if (tipo === 'enlace') {
            fila.innerHTML =
                '<div class="col-md-4">' +
                '  <input type="hidden" name="recursos_tipo[]" value="enlace">' +
                '  <span class="badge bg-info text-white">Enlace</span>' +
                '</div>' +
                '<div class="col-md-6">' +
                '  <input type="url" class="form-control form-control-sm" name="recursos_enlace[]" placeholder="https://ejemplo.com/video" required>' +
                '</div>' +
                '<div class="col-md-2">' +
                '  <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarRecursoNuevo(this)"><i class="bi bi-x-lg"></i> Quitar</button>' +
                '</div>';
        } else {
            fila.innerHTML =
                '<div class="col-md-4">' +
                '  <input type="hidden" name="recursos_tipo[]" value="archivo">' +
                '  <span class="badge bg-warning text-dark">Archivo (imagen/video)</span>' +
                '</div>' +
                '<div class="col-md-6">' +
                '  <input type="file" class="form-control form-control-sm" name="recursos_archivo[]" accept="image/*,video/*" required>' +
                '</div>' +
                '<div class="col-md-2">' +
                '  <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarRecursoNuevo(this)"><i class="bi bi-x-lg"></i> Quitar</button>' +
                '</div>';
        }
        cont.appendChild(fila);
    };

    window.eliminarRecursoNuevo = function (btn) {
        var fila = btn.closest('.recurso-fila-nueva');
        if (fila) fila.remove();
    };

    // ===== VALIDACIÓN TOTAL DE ATLETAS VS CATEGORÍAS =====
    // Para cada formulario con checkboxes .categoria-entrenamiento
    document.querySelectorAll('form').forEach(function (form) {
        var checkboxes = form.querySelectorAll('.categoria-entrenamiento');
        if (checkboxes.length === 0) return;

        var maxLabel = form.querySelector('[data-max-atletas-label]');
        var errorSpan = form.querySelector('[data-error-total-atletas]');
        var totalInput = form.querySelector('input[name="total_atletas"]');
        var enlaceAfet = null;

        function actualizar() {
            var seleccionadas = [];
            checkboxes.forEach(function (cb) {
                if (cb.checked) seleccionadas.push(cb.value);
            });

            if (seleccionadas.length === 0) {
                if (maxLabel) maxLabel.textContent = '0';
                if (errorSpan) errorSpan.classList.add('d-none');
                return;
            }

            var fd = new FormData();
            seleccionadas.forEach(function (cat) { fd.append('categoria[]', cat); });

            fetch('./Controller/AjaxTotalAtletasController.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var max = data.max || 0;
                    if (maxLabel) maxLabel.textContent = max;
                    if (totalInput) totalInput.setAttribute('data-max', max);
                    var val = totalInput ? parseInt(totalInput.value, 10) : 0;
                    if (errorSpan) {
                        if (max > 0 && val > max) errorSpan.classList.remove('d-none');
                        else errorSpan.classList.add('d-none');
                    }
                })
                .catch(function () {});
        }

        checkboxes.forEach(function (cb) { cb.addEventListener('change', actualizar); });
        if (totalInput) totalInput.addEventListener('input', actualizar);
        actualizar();
    });
});
