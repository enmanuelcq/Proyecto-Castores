<!-- Modal de REVISIÓN -->
<div class="modal fade" id="modalSolvenciaRevision<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
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
                                <p class="fw-bold mb-0" id="revisionMes_<?php echo $atleta_id; ?>">Enero</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Monto</small>
                                <p class="fw-bold mb-0" id="revisionMonto_<?php echo $atleta_id; ?>">$1,000.00</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tipo de Pago</small>
                                <p class="fw-bold mb-0" id="revisionTipoPago_<?php echo $atleta_id; ?>">
                                    <span class="badge bg-warning text-dark">Zelle</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Referencia</small>
                                <p class="fw-bold mb-0 text-primary" id="revisionReferencia_<?php echo $atleta_id; ?>">ZLN-9921</p>
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

                <!-- Formulario para acciones de revisión -->
                <div class="card border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Acciones de Revisión</h6>
                    </div>
                    <div class="card-body">
                        <form id="formRevision_<?php echo $atleta_id; ?>">
                            <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                            <input type="hidden" name="cuota_id" id="revisionCuotaId_<?php echo $atleta_id; ?>">
                            
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
                            
                            <div class="mb-3">
                                <label class="form-label">Comentarios (Opcional)</label>
                                <textarea class="form-control" id="comentarioRevision_<?php echo $atleta_id; ?>" rows="2" placeholder="Agregar observaciones..."></textarea>
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

<script>
function mostrarDetalleRevision(mes, monto, tipoPago, referencia, atletaId) {
    document.getElementById('revisionMes_' + atletaId).textContent = mes;
    document.getElementById('revisionMonto_' + atletaId).textContent = '$' + monto;
    document.getElementById('revisionTipoPago_' + atletaId).innerHTML = '<span class="badge bg-warning text-dark">' + tipoPago + '</span>';
    document.getElementById('revisionReferencia_' + atletaId).textContent = referencia || 'Sin referencia';
}

function confirmarPagoRevision(atletaId) {
    var check = document.getElementById('confirmarRevision_' + atletaId);
    if (!check.checked) {
        alert('Debes confirmar que has verificado los datos del pago.');
        return;
    }
    if (confirm('¿Estás seguro de confirmar este pago?')) {
        // Aquí iría la lógica AJAX para confirmar el pago
        alert('Pago confirmado. La cuota se marcará como pagada.');
        // Cerrar modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalSolvenciaRevision' + atletaId));
        if (modal) modal.hide();
    }
}

function rechazarPagoRevision(atletaId) {
    var comentario = document.getElementById('comentarioRevision_' + atletaId).value;
    if (confirm('¿Estás seguro de rechazar este pago?')) {
        // Aquí iría la lógica AJAX para rechazar el pago
        alert('Pago rechazado. Motivo: ' + (comentario || 'Sin motivo especificado.'));
        // Cerrar modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalSolvenciaRevision' + atletaId));
        if (modal) modal.hide();
    }
}
</script>