<!-- modalVerPartidoPorCargar.php -->
<div class="modal fade" id="modalVerPartidoPorCargar<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-clock-history me-2"></i>Partido por Cargar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-1 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h4>Partido Jugado - Pendiente de Resultado</h4>
                    <p class="text-muted">Este partido ya se jugó pero el resultado aún no ha sido cargado</p>
                </div>

                <!-- Información del partido -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <small class="text-muted d-block">📅 Fecha</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['fecha']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <small class="text-muted d-block">⏰ Hora</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['hora']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <small class="text-muted d-block">🏷️ Categoría</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['categoria']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <small class="text-muted d-block">🏟️ Estadio</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['estadio']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <small class="text-muted d-block">📋 Tipo</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['tipo']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <small class="text-muted d-block">⚽ Rival</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['rival']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje según rol -->
                <?php if($es_entrenador || $es_admin): ?>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>¡Atención!</strong> Este partido ya fue jugado. 
                        <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#modalCargarResultado<?php echo $id; ?>" data-bs-dismiss="modal">
                            Haz clic aquí para cargar el resultado
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Información:</strong> Este partido ya se jugó. El entrenador cargará el resultado próximamente.
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
                <?php if($es_entrenador || $es_admin): ?>
                    <button type="button" class="btn btn-warning text-dark fw-bold" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalCargarResultado<?php echo $id; ?>"
                            data-bs-dismiss="modal">
                        <i class="bi bi-pencil-square me-2"></i>Cargar Resultado
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>