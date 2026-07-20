<!-- Modal de PENDIENTE -->
<div class="modal fade" id="modalSolvenciaPendiente<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Pago Pendiente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-1 text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4 class="mt-3">Pago Pendiente</h4>
                    <p class="text-muted">Este mes aún no ha sido cancelado</p>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Mes</small>
                                <p class="fw-bold mb-0" id="pendienteMes">Enero</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Monto por Pagar</small>
                                <p class="fw-bold mb-0 text-danger" id="pendienteMonto">$1,000.00</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Estado Actual</small>
                                <p><span class="badge bg-warning text-dark fs-6">Pendiente</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario para registrar pago -->
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Registrar Pago</h6>
                    </div>
                    <div class="card-body">
                        <form id="formPagoPendiente_<?php echo $atleta_id; ?>">
                            <input type="hidden" name="cedula" value="<?php echo $atleta_id; ?>">
                            <input type="hidden" name="mes_pago" id="mesPagoHidden">
                            
                            <div class="mb-3">
                                <label class="form-label">Tipo de Pago</label>
                                <select class="form-select" name="tipo_pago" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="pago_movil">Pago Móvil</option>
                                    <option value="zelle">Zelle</option>
                                    <option value="pago_online">Pago Online</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Monto</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="monto" 
                                           id="pendienteMontoInput" step="0.01" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Referencia (Opcional)</label>
                                <input type="text" class="form-control" name="referencia" 
                                       placeholder="Número de referencia o comprobante">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" name="fecha_pago" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmarPago_<?php echo $atleta_id; ?>" required>
                                    <label class="form-check-label" for="confirmarPago_<?php echo $atleta_id; ?>">
                                        Confirmo que el pago ha sido recibido
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-2"></i>Registrar Pago
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>