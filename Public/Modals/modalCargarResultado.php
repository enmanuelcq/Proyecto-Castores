<!-- Modal Cargar Resultado — Estilo Dashboard Ampliado -->
<div class="modal fade" id="modalCargarResultado<?php echo $partido['partido_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-cargar-resultado">
            <div class="modal-cargar-header">
                <div class="modal-cargar-header-left">
                    <div class="modal-cargar-icon-box">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h5 class="modal-cargar-title">Cargar Resultado</h5>
                        <p class="modal-cargar-subtitle">
                            <?php echo htmlspecialchars($partido['categoria_nombre'] ?? ''); ?> 
                            vs <?php echo htmlspecialchars($partido['equipo_rival'] ?? ''); ?>
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="Controller/CargarResultadoController.php" method="POST" id="formCargarResultado_<?php echo $partido['partido_id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="partido_id" value="<?php echo $partido['partido_id']; ?>">

                <div class="modal-cargar-body">

                    <?php if (empty($convocados)): ?>
                    <div class="cargar-sin-convocados">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong>Sin convocados</strong>
                            <p>No hay jugadores convocados para este partido. Los dropdowns mostrarán todos los atletas de la categoría.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ===== BANNER INFO PARTIDO ===== -->
                    <div class="cargar-match-banner">
                        <div class="cargar-banner-item">
                            <i class="bi bi-calendar3"></i>
                            <div>
                                <span class="cargar-banner-label">Fecha</span>
                                <strong><?php echo date('d/m/Y', strtotime($partido['fecha_partido'])); ?></strong>
                            </div>
                        </div>
                        <div class="cargar-banner-divider"></div>
                        <div class="cargar-banner-item">
                            <i class="bi bi-clock"></i>
                            <div>
                                <span class="cargar-banner-label">Hora</span>
                                <strong><?php echo date('H:i', strtotime($partido['fecha_partido'])); ?></strong>
                            </div>
                        </div>
                        <div class="cargar-banner-divider"></div>
                        <div class="cargar-banner-item">
                            <i class="bi bi-geo-alt"></i>
                            <div>
                                <span class="cargar-banner-label">Campo</span>
                                <strong><?php echo htmlspecialchars($partido['campo_nombre'] ?? 'N/D'); ?></strong>
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECCION 1: MARCADOR ===== -->
                    <div class="cargar-section">
                        <div class="cargar-section-header">
                            <span class="cargar-section-num">1</span>
                            <div>
                                <h6 class="cargar-section-title">Marcador</h6>
                                <p class="cargar-section-desc">Registra el resultado final del partido</p>
                            </div>
                        </div>
                        <div class="cargar-section-body">
                            <div class="cargar-score-row align-items-center">
                                <div class="cargar-team">
                                    <div class="cargar-team-logo">
                                        <i class="bi bi-shield-fill text-success"></i>
                                    </div>
                                    <span class="cargar-team-name">Los Castores</span>
                                </div>
                                <div class="cargar-score-inputs d-flex align-items-center gap-3">
                                    <input type="number" class="cargar-score-input" 
                                           name="goles_favor" id="golesFavor_<?php echo $partido['partido_id']; ?>" 
                                           min="0" max="20" placeholder="0"
                                           oninput="generarGoleadores(<?php echo $partido['partido_id']; ?>)">
                                    <span class="cargar-score-separator">—</span>
                                    <input type="number" class="cargar-score-input" 
                                           name="goles_contra" min="0" max="20" placeholder="0">
                                </div>
                                <div class="cargar-team">
                                    <div class="cargar-team-logo rival">
                                        <i class="bi bi-shield-fill text-danger"></i>
                                    </div>
                                    <span class="cargar-team-name"><?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?></span>
                                </div>
                            </div>
                            <div class="cargar-score-counter mt-2 text-center">
                                <small id="contadorGoles_<?php echo $partido['partido_id']; ?>">
                                    Goles registrados: <strong id="golesRegistrados_<?php echo $partido['partido_id']; ?>">0</strong> 
                                    de <strong id="golesMax_<?php echo $partido['partido_id']; ?>">0</strong>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECCION 2: GOLEADORES ===== -->
                    <div class="cargar-section">
                        <div class="cargar-section-header">
                            <span class="cargar-section-num green"><i class="bi bi-trophy-fill"></i></span>
                            <div>
                                <h6 class="cargar-section-title">Goleadores</h6>
                                <p class="cargar-section-desc">Selecciona los goleadores según los goles anotados</p>
                            </div>
                        </div>
                        <div class="cargar-section-body">
                            <div id="goleadoresContainer_<?php echo $partido['partido_id']; ?>"></div>
                            <small class="cargar-hint"><i class="bi bi-info-circle me-1"></i>Los campos se generan automáticamente al ingresar los goles a favor.</small>
                        </div>
                    </div>

                    <div class="cargar-row-2col">
                        <!-- ===== SECCION 3: TARJETAS ===== -->
                        <div class="cargar-section">
                            <div class="cargar-section-header">
                                <span class="cargar-section-num red"><i class="bi bi-card-list"></i></span>
                                <div>
                                    <h6 class="cargar-section-title">Tarjetas</h6>
                                    <p class="cargar-section-desc">Opcional</p>
                                </div>
                            </div>
                            <div class="cargar-section-body">
                                <div id="tarjetasContainer_<?php echo $partido['partido_id']; ?>">
                                    <div class="row g-2 mb-2 tarjeta-row">
                                        <div class="col-lg-5 col-md-12">
                                            <select class="form-select form-select-sm" name="tarjeta_atleta_id[]">
                                                <option value="">Atleta...</option>
                                                <?php foreach ($convocados as $atleta): ?>
                                                    <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                        <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <select class="form-select form-select-sm" name="tarjeta_tipo_id[]">
                                                <option value="">Tipo...</option>
                                                <?php foreach ($tiposTarjetas as $tipo): ?>
                                                    <option value="<?php echo $tipo->getId(); ?>">
                                                        <?php echo htmlspecialchars($tipo->getTarjeta()); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <input type="text" class="form-control form-control-sm" name="tarjeta_minuto[]" placeholder="Minuto">
                                        </div>
                                        <div class="col-lg-1 col-md-2 d-flex align-items-center">
                                            <button type="button" class="btn btn-cargar-remove" onclick="eliminarFila(this, 'tarjeta-row')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-cargar-add" onclick="agregarFila('tarjetasContainer_<?php echo $partido['partido_id']; ?>', 'tarjeta-row')">
                                    <i class="bi bi-plus-lg me-1"></i>Agregar
                                </button>
                            </div>
                        </div>

                        <!-- ===== SECCION 4: CAMBIOS ===== -->
                        <div class="cargar-section">
                            <div class="cargar-section-header">
                                <span class="cargar-section-num blue"><i class="bi bi-arrow-left-right"></i></span>
                                <div>
                                    <h6 class="cargar-section-title">Cambios</h6>
                                    <p class="cargar-section-desc">Opcional</p>
                                </div>
                            </div>
                            <div class="cargar-section-body">
                                <div id="cambiosContainer_<?php echo $partido['partido_id']; ?>">
                                    <div class="row g-2 mb-2 cambio-row">
                                        <div class="col-lg-3 col-md-6">
                                            <select class="form-select form-select-sm" name="cambio_entra_id[]">
                                                <option value="">Entra...</option>
                                                <?php foreach ($convocados as $atleta): ?>
                                                    <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                        <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <select class="form-select form-select-sm" name="cambio_sale_id[]">
                                                <option value="">Sale...</option>
                                                <?php foreach ($convocados as $atleta): ?>
                                                    <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                        <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4">
                                            <input type="text" class="form-control form-control-sm" name="cambio_minuto[]" placeholder="Minuto">
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <input type="text" class="form-control form-control-sm" name="cambio_desc[]" placeholder="Observación">
                                        </div>
                                        <div class="col-lg-1 col-md-2 d-flex align-items-center">
                                            <button type="button" class="btn btn-cargar-remove" onclick="eliminarFila(this, 'cambio-row')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-cargar-add" onclick="agregarFila('cambiosContainer_<?php echo $partido['partido_id']; ?>', 'cambio-row')">
                                    <i class="bi bi-plus-lg me-1"></i>Agregar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECCION 5: INCIDENCIAS ===== -->
                    <div class="cargar-section">
                        <div class="cargar-section-header">
                            <span class="cargar-section-num amber"><i class="bi bi-exclamation-triangle"></i></span>
                            <div>
                                <h6 class="cargar-section-title">Incidencias</h6>
                                <p class="cargar-section-desc">Opcional — Lesiones, sanciones u otros eventos</p>
                            </div>
                        </div>
                        <div class="cargar-section-body">
                            <div id="incidenciasContainer_<?php echo $partido['partido_id']; ?>">
                                <div class="row g-2 mb-2 incidencia-row">
                                    <div class="col-lg-4 col-md-5">
                                        <select class="form-select form-select-sm" name="incidencia_atleta_id[]">
                                            <option value="">Atleta...</option>
                                            <?php foreach ($convocados as $atleta): ?>
                                                <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                    <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <input type="text" class="form-control form-control-sm" name="incidencia_desc[]" placeholder="Descripción de la incidencia">
                                    </div>
                                    <div class="col-lg-1 col-md-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-cargar-remove" onclick="eliminarFila(this, 'incidencia-row')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-cargar-add" onclick="agregarFila('incidenciasContainer_<?php echo $partido['partido_id']; ?>', 'incidencia-row')">
                                <i class="bi bi-plus-lg me-1"></i>Agregar
                            </button>
                        </div>
                    </div>

                    <!-- ===== CONFIRMACION ===== -->
                    <div class="cargar-confirm">
                        <label class="cargar-confirm-label">
                            <input class="form-check-input" type="checkbox" id="confirmarResultado_<?php echo $partido['partido_id']; ?>" required>
                            <span>Confirmo que los datos del resultado son correctos</span>
                        </label>
                    </div>

                </div>

                <div class="modal-cargar-footer">
                    <button type="button" class="btn btn-cargar-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-cargar-submit">
                        <i class="bi bi-check-circle me-1"></i>Guardar Resultado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
if (!window.convocadosPorPartido) window.convocadosPorPartido = {};
(function() {
    var pid = '<?php echo $partido["partido_id"]; ?>';
    var opts = '<option value="">Seleccionar atleta...</option>';
    <?php foreach ($convocados as $atleta): ?>
    opts += '<option value="<?php echo $atleta->getAtletaId(); ?>"><?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?></option>';
    <?php endforeach; ?>
    window.convocadosPorPartido[pid] = opts;
})();
if (!window.generarGoleadores) {
window.generarGoleadores = function(partidoId) {
    var container = document.getElementById('goleadoresContainer_' + partidoId);
    var input = document.getElementById('golesFavor_' + partidoId);
    var total = parseInt(input.value) || 0;
    
    container.innerHTML = '';
    
    if (total === 0) {
        document.getElementById('golesMax_' + partidoId).textContent = '0';
        document.getElementById('golesRegistrados_' + partidoId).textContent = '0';
        return;
    }
    
    var atletasOptions = window.convocadosPorPartido[partidoId] || '';
    
    for (var i = 1; i <= total; i++) {
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 goleador-row';
        row.innerHTML = `
            <div class="col-lg-5 col-md-12">
                <select class="form-select form-select-sm" name="goleador_id[]" required>
                    ${atletasOptions}
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <input type="text" class="form-control form-control-sm" name="gol_tiempo[]" 
                       placeholder="Minuto (ej. 15)" pattern="[0-9]+" required>
            </div>
            <div class="col-lg-3 col-md-6">
                <input type="text" class="form-control form-control-sm" name="gol_desc[]" 
                       placeholder="Observacion">
            </div>
            <div class="col-lg-1 col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-cargar-remove" onclick="eliminarFila(this, 'goleador-row')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    }
    
    document.getElementById('golesMax_' + partidoId).textContent = total;
    document.getElementById('golesRegistrados_' + partidoId).textContent = total;
};
}

function agregarFila(containerId, rowClass) {
    var container = document.getElementById(containerId);
    if (!container) return;
    
    var template = container.querySelector('.' + rowClass);
    if (!template) return;
    
    var clone = template.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function(el) {
        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
        } else {
            el.value = '';
        }
    });
    container.insertBefore(clone, template);
}

function eliminarFila(btn, rowClass) {
    var row = btn.closest('.' + rowClass);
    var container = row.parentNode;
    var totalFilas = container.querySelectorAll('.' + rowClass).length;
    
    if (totalFilas > 1) {
        row.remove();
    } else {
        alert('Debe haber al menos una fila.');
    }
}
</script>
