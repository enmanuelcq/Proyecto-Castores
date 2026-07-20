<!-- modalVerPartidoFinalizado.php -->
<div class="modal fade" id="modalVerPartidoFinalizado<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title text-white">
                    <i class="bi bi-trophy me-2"></i>Detalle del Partido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Encabezado con resultado -->
                <div class="text-center mb-4">
                    <div class="display-1 <?php 
                        echo ($status == 'Ganado') ? 'text-success' : 
                             (($status == 'Perdido') ? 'text-danger' : 'text-warning'); 
                    ?>">
                        <?php echo ($status == 'Ganado') ? '🏆' : (($status == 'Perdido') ? '😞' : '🤝'); ?>
                    </div>
                    <h4>Partido <?php echo $status; ?></h4>
                    <p class="text-muted">Resultado final del encuentro</p>
                </div>

                <!-- Marcador -->
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row text-center align-items-center">
                            <div class="col-5">
                                <h5 class="fw-bold">Los Castores</h5>
                                <span class="display-4 fw-bold text-success">
                                    <?php echo $partido['goles_a_favor'] ?: '0'; ?>
                                </span>
                            </div>
                            <div class="col-2">
                                <span class="display-6 text-muted">VS</span>
                            </div>
                            <div class="col-5">
                                <h5 class="fw-bold"><?php echo $partido['rival']; ?></h5>
                                <span class="display-4 fw-bold text-danger">
                                    <?php echo $partido['goles_contra'] ?: '0'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información detallada -->
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
                                    <small class="text-muted d-block">⏱️ Duración</small>
                                    <p class="fw-bold mb-0"><?php echo $partido['duracion'] ?: 'N/A'; ?></p>
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
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="alert <?php 
                    echo ($status == 'Ganado') ? 'alert-success' : 
                         (($status == 'Perdido') ? 'alert-danger' : 'alert-warning'); 
                ?> mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Estado:</strong> 
                    <span class="badge <?php 
                        echo ($status == 'Ganado') ? 'bg-success' : 
                             (($status == 'Perdido') ? 'bg-danger' : 'bg-warning text-dark'); 
                    ?>">
                        <?php echo $status; ?>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>