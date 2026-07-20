
<!-- modalPendiente.php -->
<div class="modal fade" id="modalPendiente<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-event me-2"></i>Detalle del Partido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-1 text-primary">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h4>Partido Programado</h4>
                    <p class="text-muted">Este partido está pendiente por jugarse</p>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Fecha</small>
                                <p class="fw-bold"><?php echo $partido['fecha']; ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Hora</small>
                                <p class="fw-bold"><?php echo $partido['hora']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Categoría</small>
                                <p class="fw-bold"><?php echo $partido['categoria']; ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Tipo</small>
                                <p class="fw-bold"><?php echo $partido['tipo']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-12">
                                <small class="text-muted">Rival</small>
                                <p class="fw-bold"><?php echo $partido['rival']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Estadio</small>
                                <p class="fw-bold"><?php echo $partido['estadio']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Estado:</strong> <span class="badge bg-primary"><?php echo $partido['status']; ?></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
