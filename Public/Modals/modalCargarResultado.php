<!-- modalCargarResultado.php -->
<div class="modal fade" id="modalCargarResultado<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Cargar Resultado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCargarResultado_<?php echo $id; ?>" method="POST" action="">
                <div class="modal-body">
                    <!-- Resumen del partido -->
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">📅 Fecha</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['fecha']; ?></p>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">⏰ Hora</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['hora']; ?></p>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">🏷️ Categoría</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['categoria']; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center align-items-center">
                                <div class="col-5">
                                    <h5>🏠 Los Castores</h5>
                                </div>
                                <div class="col-2">
                                    <span class="badge bg-dark fs-5">VS</span>
                                </div>
                                <div class="col-5">
                                    <h5>✈️ <?php echo $partido['rival']; ?></h5>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="badge bg-secondary"><?php echo $partido['estadio']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cargar resultado -->
                    <input type="hidden" name="id_partido" value="<?php echo $id; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-emoji-smile me-1"></i>Goles a Favor
                            </label>
                            <input type="number" class="form-control form-control-lg text-center" 
                                   name="goles_a_favor" min="0" max="20" 
                                   placeholder="0" required
                                   onchange="calcularResultado('<?php echo $id; ?>')">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-emoji-frown me-1"></i>Goles en Contra
                            </label>
                            <input type="number" class="form-control form-control-lg text-center" 
                                   name="goles_contra" min="0" max="20" 
                                   placeholder="0" required
                                   onchange="calcularResultado('<?php echo $id; ?>')">
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Resultado Final</label>
                            <select class="form-select" name="status" id="statusResultado_<?php echo $id; ?>" required>
                                <option value="">Seleccionar...</option>
                                <option value="Ganado">🏆 Ganado</option>
                                <option value="Perdido">😞 Perdido</option>
                                <option value="Empatado">🤝 Empatado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Duración del Partido</label>
                            <select class="form-select" name="duracion">
                                <option value="45 min">45 min (1er Tiempo)</option>
                                <option value="90 min" selected>90 min (Completo)</option>
                                <option value="120 min">120 min (Tiempo Extra)</option>
                                <option value="Penales">Penales</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Goleadores -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-trophy-fill text-warning me-1"></i>Goleadores del Partido
                            </label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="goleador1" placeholder="Nombre del goleador #1">
                                <span class="input-group-text">⚽</span>
                                <input type="number" class="form-control" name="goles_goleador1" placeholder="Goles" min="0" max="5" style="max-width: 80px;">
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="goleador2" placeholder="Nombre del goleador #2">
                                <span class="input-group-text">⚽</span>
                                <input type="number" class="form-control" name="goles_goleador2" placeholder="Goles" min="0" max="5" style="max-width: 80px;">
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" name="goleador3" placeholder="Nombre del goleador #3">
                                <span class="input-group-text">⚽</span>
                                <input type="number" class="form-control" name="goles_goleador3" placeholder="Goles" min="0" max="5" style="max-width: 80px;">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Observaciones -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Observaciones del Partido</label>
                            <textarea class="form-control" name="observaciones" rows="2" 
                                      placeholder="Detalles importantes del partido..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Validación -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarResultado_<?php echo $id; ?>" required>
                                <label class="form-check-label" for="confirmarResultado_<?php echo $id; ?>">
                                    Confirmo que los datos del resultado son correctos
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        <i class="bi bi-save me-2"></i>Guardar Resultado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>