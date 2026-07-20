<!-- Modal de REVISIÓN -->
<div class="modal fade" id="modalSolvenciaRevision<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="bi bi-hourglass-split me-2"></i>Pago en Revisión
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-1 text-info">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h4 class="mt-3">Pago en Proceso de Revisión</h4>
                    <p class="text-muted">Este pago está siendo verificado por el equipo administrativo</p>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Mes</small>
                                <p class="fw-bold mb-0" id="revisionMes">Enero</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Monto</small>
                                <p class="fw-bold mb-0" id="revisionMonto">$1,000.00</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tipo de Pago</small>
                                <p class="fw-bold mb-0" id="revisionTipoPago">
                                    <span class="badge bg-warning text-dark">Zelle</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Referencia</small>
                                <p class="fw-bold mb-0 text-primary" id="revisionReferencia">ZLN-9921</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Estado Actual</small>
                                <p><span class="badge bg-info fs-6">En Revisión</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario para confirmar/rechazar pago -->
                <div class="card border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Acciones de Revisión</h6>
                    </div>
                    <div class="card-body">
                        <form id="formRevision_<?php echo $atleta_id; ?>">
                            <input type="hidden" name="cedula" value="<?php echo $atleta_id; ?>">
                            <input type="hidden" name="mes_revision" id="mesRevisionHidden">
                            <input type="hidden" name="referencia_revision" id="referenciaRevisionHidden">
                            
                            <div class="mb-3">
                                <label class="form-label">Decisión</label>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success" onclick="confirmarPagoRevision('<?php echo $atleta_id; ?>')">
                                        <i class="bi bi-check-circle me-2"></i>Confirmar Pago
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="rechazarPagoRevision('<?php echo $atleta_id; ?>')">
                                        <i class="bi bi-x-circle me-2"></i>Rechazar Pago
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="comentarioRevisionDiv">
                                <label class="form-label">Comentarios (Opcional)</label>
                                <textarea class="form-control" rows="2" placeholder="Agregar observaciones..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmarRevision_<?php echo $atleta_id; ?>">
                                    <label class="form-check-label" for="confirmarRevision_<?php echo $atleta_id; ?>">
                                        Confirmo que he verificado los datos del pago
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Nota:</strong> El pago será confirmado después de verificar la referencia y el monto.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>