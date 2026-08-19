<!-- Modal de PAGADO -->
<div class="modal fade" id="modalSolvenciaPagado<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle-fill me-2"></i>Pago Confirmado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- ===== ENCABEZADO ===== -->
                <div class="text-center mb-4">
                    <div class="display-1 text-success">
                        <i class="bi bi-cash-check"></i>
                    </div>
                    <h4 class="mt-3">Pago Registrado</h4>
                    <p class="text-muted">Este pago ha sido confirmado exitosamente</p>
                </div>

                <!-- ===== DETALLE DEL PAGO ===== -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Mes</small>
                                <p class="fw-bold mb-0" id="detalleMes_<?php echo $atleta_id; ?>">---</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Monto Pagado</small>
                                <p class="fw-bold mb-0 text-success" id="detalleMonto_<?php echo $atleta_id; ?>">Bs 0.00</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tipo de Pago</small>
                                <p class="fw-bold mb-0" id="detalleTipoPago_<?php echo $atleta_id; ?>">
                                    <span class="badge bg-info">---</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Referencia</small>
                                <p class="fw-bold mb-0 text-primary" id="detalleReferencia_<?php echo $atleta_id; ?>">---</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Fecha de Pago</small>
                                <p class="fw-bold mb-0" id="detalleFecha_<?php echo $atleta_id; ?>">---</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Estado</small>
                                <p><span class="badge bg-success fs-6">Pagado</span></p>
                            </div>
                        </div>
                        <?php if (!empty($banco)): ?>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Banco</small>
                                <p class="fw-bold mb-0" id="detalleBanco_<?php echo $atleta_id; ?>">---</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="alert alert-success">
                    <i class="bi bi-info-circle me-2"></i>
                    Este pago ha sido verificado y registrado correctamente en el sistema.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir Comprobante
                </button>
            </div>
        </div>
    </div>
</div>