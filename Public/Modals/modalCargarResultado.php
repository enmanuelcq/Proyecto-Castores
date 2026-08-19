<!-- Modal Cargar Resultado -->
<div class="modal fade" id="modalCargarResultado<?php echo $partido['partido_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Cargar Resultado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="Controller/CargarResultadoController.php" method="POST" id="formCargarResultado_<?php echo $partido['partido_id']; ?>">
                <input type="hidden" name="partido_id" value="<?php echo $partido['partido_id']; ?>">
                <div class="modal-body">
                    
                    <!-- ===== 1. RESULTADO DEL PARTIDO ===== -->
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">📅 Fecha</small>
                                    <p class="fw-bold mb-0"><?php echo date('d/m/Y', strtotime($partido['fecha_partido'])); ?></p>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">🏷️ Categoría</small>
                                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($partido['categoria_nombre'] ?? 'Sin categoría'); ?></p>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">⚽ Rival</small>
                                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Goles a Favor</label>
                                    <input type="number" class="form-control form-control-lg text-center" 
                                           name="goles_favor" id="golesFavor_<?php echo $partido['partido_id']; ?>" 
                                           min="0" max="20" placeholder="0"
                                           oninput="generarGoleadores(<?php echo $partido['partido_id']; ?>)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Goles en Contra</label>
                                    <input type="number" class="form-control form-control-lg text-center" 
                                           name="goles_contra" min="0" max="20" placeholder="0">
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted" id="contadorGoles_<?php echo $partido['partido_id']; ?>">
                                    Goles registrados: <span id="golesRegistrados_<?php echo $partido['partido_id']; ?>">0</span> 
                                    de <span id="golesMax_<?php echo $partido['partido_id']; ?>">0</span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- ===== 2. GOLEADORES (DINÁMICO) ===== -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-trophy-fill me-1"></i> Goleadores
                        </div>
                        <div class="card-body">
                            <div id="goleadoresContainer_<?php echo $partido['partido_id']; ?>">
                                <!-- Las filas se generan automáticamente con JavaScript -->
                            </div>
                            <small class="text-muted">Los campos de goleadores se generan automáticamente según los goles a favor.</small>
                        </div>
                    </div>

                    <!-- ===== 3. TARJETAS ===== -->
                    <div class="card mb-3">
                        <div class="card-header bg-danger text-white">
                            <i class="bi bi-card-list me-1"></i> Tarjetas
                        </div>
                        <div class="card-body">
                            <div id="tarjetasContainer_<?php echo $partido['partido_id']; ?>">
                                <div class="row g-2 mb-2 tarjeta-row">
                                    <div class="col-4">
                                        <select class="form-select" name="tarjeta_atleta_id[]">
                                            <option value="">Atleta...</option>
                                            <?php foreach ($atletasCategoria as $atleta): ?>
                                                <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                    <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-select" name="tarjeta_tipo_id[]">
                                            <option value="">Tipo...</option>
                                            <?php foreach ($tiposTarjetas as $tipo): ?>
                                                <option value="<?php echo $tipo->getId(); ?>">
                                                    <?php echo htmlspecialchars($tipo->getTarjeta()); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <input type="text" class="form-control" name="tarjeta_minuto[]" 
                                               placeholder="Minuto">
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this, 'tarjeta-row')">✕</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="agregarFila('tarjetasContainer_<?php echo $partido['partido_id']; ?>', 'tarjeta-row')">
                                <i class="bi bi-plus-circle me-1"></i> Agregar tarjeta
                            </button>
                            <small class="text-muted d-block mt-1">Opcional: deja vacío si no hubo tarjetas.</small>
                        </div>
                    </div>

                    <!-- ===== 4. CAMBIOS ===== -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <i class="bi bi-arrow-left-right me-1"></i> Cambios
                        </div>
                        <div class="card-body">
                            <div id="cambiosContainer_<?php echo $partido['partido_id']; ?>">
                                <div class="row g-2 mb-2 cambio-row">
                                    <div class="col-3">
                                        <select class="form-select" name="cambio_entra_id[]">
                                            <option value="">Entra...</option>
                                            <?php foreach ($atletasCategoria as $atleta): ?>
                                                <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                    <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-select" name="cambio_sale_id[]">
                                            <option value="">Sale...</option>
                                            <?php foreach ($atletasCategoria as $atleta): ?>
                                                <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                    <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" class="form-control" name="cambio_minuto[]" 
                                               placeholder="Minuto">
                                    </div>
                                    <div class="col-3">
                                        <input type="text" class="form-control" name="cambio_desc[]" 
                                               placeholder="Observación">
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this, 'cambio-row')">✕</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="agregarFila('cambiosContainer_<?php echo $partido['partido_id']; ?>', 'cambio-row')">
                                <i class="bi bi-plus-circle me-1"></i> Agregar cambio
                            </button>
                            <small class="text-muted d-block mt-1">Opcional: deja vacío si no hubo cambios.</small>
                        </div>
                    </div>

                    <!-- ===== 5. INCIDENCIAS ===== -->
                    <div class="card mb-3">
                        <div class="card-header bg-warning text-dark">
                            <i class="bi bi-exclamation-triangle me-1"></i> Incidencias
                        </div>
                        <div class="card-body">
                            <div id="incidenciasContainer_<?php echo $partido['partido_id']; ?>">
                                <div class="row g-2 mb-2 incidencia-row">
                                    <div class="col-4">
                                        <select class="form-select" name="incidencia_atleta_id[]">
                                            <option value="">Atleta...</option>
                                            <?php foreach ($atletasCategoria as $atleta): ?>
                                                <option value="<?php echo $atleta->getAtletaId(); ?>">
                                                    <?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-7">
                                        <input type="text" class="form-control" name="incidencia_desc[]" 
                                               placeholder="Descripción de la incidencia">
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this, 'incidencia-row')">✕</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="agregarFila('incidenciasContainer_<?php echo $partido['partido_id']; ?>', 'incidencia-row')">
                                <i class="bi bi-plus-circle me-1"></i> Agregar incidencia
                            </button>
                            <small class="text-muted d-block mt-1">Opcional: deja vacío si no hubo incidencias.</small>
                        </div>
                    </div>

                    <!-- ===== 6. CONFIRMACIÓN ===== -->
                    <div class="mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmarResultado_<?php echo $partido['partido_id']; ?>" required>
                            <label class="form-check-label" for="confirmarResultado_<?php echo $partido['partido_id']; ?>">
                                Confirmo que los datos del resultado son correctos
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-outline text-white fw-bold">Guardar Resultado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
// ============================================================
// 1. GENERAR FILAS DE GOLEADORES
// ============================================================
function generarGoleadores(partidoId) {
    var container = document.getElementById('goleadoresContainer_' + partidoId);
    var input = document.getElementById('golesFavor_' + partidoId);
    var total = parseInt(input.value) || 0;
    
    // Limpiar contenedor
    container.innerHTML = '';
    
    if (total === 0) {
        document.getElementById('golesMax_' + partidoId).textContent = '0';
        document.getElementById('golesRegistrados_' + partidoId).textContent = '0';
        return;
    }
    
    // Generar filas
    var atletasOptions = '';
    <?php foreach ($atletasCategoria as $atleta): ?>
        atletasOptions += '<option value="<?php echo $atleta->getAtletaId(); ?>"><?php echo $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido(); ?></option>';
    <?php endforeach; ?>
    
    for (var i = 1; i <= total; i++) {
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 goleador-row';
        row.innerHTML = `
            <div class="col-6">
                <select class="form-select" name="goleador_id[]" required>
                    <option value="">Seleccionar atleta...</option>
                    ${atletasOptions}
                </select>
            </div>
            <div class="col-3">
                <input type="text" class="form-control" name="gol_tiempo[]" 
                       placeholder="Minuto (ej. 15)" pattern="[0-9]+" required>
            </div>
            <div class="col-2">
                <input type="text" class="form-control" name="gol_desc[]" 
                       placeholder="Observación">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this, 'goleador-row')">✕</button>
            </div>
        `;
        container.appendChild(row);
    }
    
    // Actualizar contador
    document.getElementById('golesMax_' + partidoId).textContent = total;
    document.getElementById('golesRegistrados_' + partidoId).textContent = total;
}

// ============================================================
// 2. AGREGAR FILA GENÉRICA
// ============================================================
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

// ============================================================
// 3. ELIMINAR FILA
// ============================================================
function eliminarFila(btn, rowClass) {
    var row = btn.closest('.' + rowClass);
    var container = row.parentNode;
    var totalFilas = container.querySelectorAll('.' + rowClass).length;
    
    if (totalFilas > 1) {
        row.remove();
        // Si es goleador, actualizar contador (solo si se permite eliminar)
        // En este diseño, no se permite eliminar goleadores porque se generan automáticamente.
        // Pero si se eliminara, se debería actualizar el contador y el input de goles.
    } else {
        alert('Debe haber al menos una fila.');
    }
}
</script>