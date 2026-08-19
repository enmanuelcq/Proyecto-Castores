<?php
// ============================================
// /Public/Modals/modal_representante_registrar_pago.php
// MODAL PARA REGISTRAR PAGO DE MES PENDIENTE
// ============================================
?>

<div class="modal fade" id="modalRepresentanteRegistrarPago<?php echo $atleta['atleta_cedula']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <!-- ===== HEADER ===== -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-plus-circle-fill me-2 text-success"></i>
                    Registrar Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- ===== BODY ===== -->
            <div class="modal-body">
                
                <!-- ===== CABECERA DEL ATLETA ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-avatar-modal">
                        <?php 
                        echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
                        ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-danger"><?php echo $pendientes; ?> meses pendientes</span>
                            <span class="badge bg-secondary"><?php echo date('Y'); ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white small">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?php echo date('F Y'); ?>
                        </span>
                    </div>
                </div>

                <!-- ===== FORMULARIO DE PAGO ===== -->
                <form id="formRegistrarPagoRepresentante_<?php echo $atleta['atleta_cedula']; ?>" method="POST" action="./Controller/RegistrarPago_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_cedula" value="<?php echo $atleta['atleta_cedula']; ?>">
                    <input type="hidden" name="representante_cedula" value="<?php echo $representante['usuario_cedula']; ?>">
                    
                    <div class="row g-3">
                        <!-- Selector de mes -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Mes a Pagar</label>
                            <select class="form-select custom-input" name="mes_pago" required>
                                <option value="">Seleccionar mes...</option>
                                <?php 
                                $meses_orden = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                $meses_nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                foreach($meses_orden as $index => $mes_clave):
                                    if(isset($solvencia_atleta[$mes_clave]) && $solvencia_atleta[$mes_clave]['estado'] == 'Pendiente'):
                                ?>
                                <option value="<?php echo $mes_clave; ?>"><?php echo $meses_nombres[$index]; ?></option>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </select>
                            <?php if($pendientes == 0): ?>
                            <small class="text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                No hay meses pendientes para registrar
                            </small>
                            <?php endif; ?>
                        </div>

                        <!-- Monto -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Monto a Pagar</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #fff;">$</span>
                                <input type="number" class="form-control custom-input" name="monto" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                        </div>

                        <!-- Tipo de Pago -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Tipo de Pago</label>
                            <select class="form-select custom-input" name="tipo_pago" required>
                                <option value="">Seleccionar...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="pago_movil">Pago Móvil</option>
                                <option value="zelle">Zelle</option>
                                <option value="pago_online">Pago Online</option>
                            </select>
                        </div>

                        <!-- Referencia -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Referencia (Opcional)</label>
                            <input type="text" class="form-control custom-input" name="referencia" placeholder="Número de referencia o comprobante">
                        </div>

                        <!-- Fecha de Pago -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Fecha de Pago</label>
                            <input type="date" class="form-control custom-input" name="fecha_pago" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <!-- Comentario -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Comentario (Opcional)</label>
                            <input type="text" class="form-control custom-input" name="comentario" placeholder="Observaciones del pago">
                        </div>

                        <!-- Confirmación -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarPagoRepresentante_<?php echo $atleta['atleta_cedula']; ?>" required>
                                <label class="form-check-label" for="confirmarPagoRepresentante_<?php echo $atleta['atleta_cedula']; ?>">
                                    Confirmo que los datos del pago son correctos
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- ===== BOTONES ===== -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold" <?php echo ($pendientes == 0) ? 'disabled' : ''; ?>>
                            <i class="bi bi-check-circle me-2"></i>Registrar Pago
                        </button>
                        <button type="button" class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>