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
                
                <!-- ===== ENCABEZADO (se actualiza con AJAX) ===== -->
                <div class="text-center mb-4">
                    <div class="display-1 text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4 class="mt-3">Pago Pendiente</h4>
                    <p class="text-muted" id="pendienteInfo_<?php echo $atleta_id; ?>">
                        Mes: <strong id="pendienteMes_<?php echo $atleta_id; ?>">Cargando...</strong> | 
                        Monto: <strong class="text-danger" id="pendienteMonto_<?php echo $atleta_id; ?>">Bs ...</strong>
                    </p>
                </div>

                <!-- ===== DETALLE DE LA CUOTA ===== -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Mes</small>
                                <p class="fw-bold mb-0" id="pendienteMesDetalle_<?php echo $atleta_id; ?>">---</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Monto por Pagar</small>
                                <p class="fw-bold mb-0 text-danger" id="pendienteMontoDetalle_<?php echo $atleta_id; ?>">Bs 0.00</p>
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

                <!-- ===== FORMULARIO ===== -->
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Registrar Pago</h6>
                    </div>
                    <div class="card-body">
                        <form action="Controller/RegistrarPagoController.php" method="POST">
                            <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                            <input type="hidden" name="tipo_concepto" value="Mensualidad">
                            <input type="hidden" name="cuota_id" id="pendienteCuotaId_<?php echo $atleta_id; ?>">

                            <div class="mb-3">
                                <label class="form-label">Monto a Pagar</label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs</span>
                                    <input type="number" class="form-control" name="monto" 
                                           id="pendienteMontoInput_<?php echo $atleta_id; ?>" 
                                           step="0.01" required>
                                </div>
                            </div>

                            <!-- Método de Pago -->
                            <div class="mb-3">
                                <label class="form-label">Método de Pago</label>
                                <select class="form-select" name="tipo_pago_id" id="tipoPagoPendiente_<?php echo $atleta_id; ?>" required>
                                    <option value="">Seleccionar...</option>
                                    <?php 
                                    $repoTipoPago = new RepositoryTipoPago();
                                    $tiposPago = $repoTipoPago->findAll();
                                    foreach ($tiposPago as $tipo): ?>
                                        <option value="<?php echo $tipo->getId(); ?>">
                                            <?php echo $tipo->getTipoPago(); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Banco -->
                            <div class="mb-3" id="bancoDiv_<?php echo $atleta_id; ?>" style="display:none;">
                                <label class="form-label">Banco</label>
                                <select class="form-select" name="banco">
                                    <option value="">Seleccionar...</option>
                                    <option value="Banesco">Banesco</option>
                                    <option value="Mercantil">Mercantil</option>
                                    <option value="Provincial">Provincial</option>
                                    <option value="Venezuela">Banco de Venezuela</option>
                                    <option value="BOD">BOD</option>
                                    <option value="BNC">Banco Nacional de Crédito</option>
                                    <option value="Exterior">Banco Exterior</option>
                                    <option value="BBVA">BBVA Provincial</option>
                                    <option value="Tesoro">Banco del Tesoro</option>
                                    <option value="Plaza">Banco Plaza</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <!-- Referencia -->
                            <div class="mb-3">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" name="referencia" placeholder="N° de transferencia, cheque, etc.">
                                <small class="text-muted">La referencia es obligatoria para pagos no en efectivo</small>
                            </div>

                            <!-- Fecha -->
                            <div class="mb-3">
                                <label class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" name="fecha_pago" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <!-- Confirmación -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="confirmar" required>
                                    <label class="form-check-label">
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