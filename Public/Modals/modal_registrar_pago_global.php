<!-- Modal de Registro de Pago (Global) -->
<div class="modal fade" id="modalRegistrarPago<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Registrar Pago 
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="Controller/RegistrarPagoController.php" method="POST" id="formRegistrarPago<?php echo $atleta_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                    <input type="hidden" name="tipo_pago_global" value="1">
                    
                    <!-- Tipo de concepto -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de Pago</label>
                        <select class="form-select" name="tipo_concepto" id="tipoConcepto<?php echo $atleta_id; ?>" required onchange="toggleCuotaSelect<?php echo $atleta_id; ?>()">
                            <option value="">Seleccionar...</option>
                            <option value="Mensualidad">Mensualidad</option>
                            <option value="Uniforme">Uniforme</option>
                            <option value="Torneo">Torneo</option>
                            <option value="Arbitraje">Arbitraje</option>
                            <option value="Inscripción">Inscripción</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <!-- Concepto personalizado (para "Otro") -->
                    <div class="mb-3" id="conceptoPersonalizadoDiv<?php echo $atleta_id; ?>" style="display:none;">
                        <label class="form-label">Concepto personalizado</label>
                        <input type="text" class="form-control" name="concepto_personalizado" placeholder="Ej. Compra de balones">
                    </div>

                    <!-- Selección de cuota (solo para mensualidad) -->
                    <div class="mb-3" id="cuotaSelectDiv<?php echo $atleta_id; ?>" style="display:none;">
                        <label class="form-label">Seleccionar cuota</label>
                        <select class="form-select" name="cuota_id" id="cuotaSelect<?php echo $atleta_id; ?>">
                            <option value="">Seleccionar...</option>
                            <?php 
                            // Obtener cuotas pendientes del atleta
                            $repoCuota = new RepositoryCuotaMensual();
                            $cuotasPendientes = $repoCuota->findByAtleta($atleta_id);
                            foreach ($cuotasPendientes as $cuota): 
                                $abonado = $repoPagoCuota->sumAbonadoByCuota($cuota->getId());
                                $saldo = $cuota->getMontoEstablecido() - $abonado;
                                if ($saldo > 0):
                            ?>
                                <option value="<?php echo $cuota->getId(); ?>">
                                    Mes <?php echo $cuota->getMes(); ?>/<?php echo $cuota->getAnio(); ?> - Saldo: $<?php echo number_format($saldo, 2); ?>
                                </option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>

                    <!-- Monto -->
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="monto" step="0.01" required>
                        </div>
                    </div>

                    <!-- Tipo de Pago -->
                    <div class="mb-3">
                        <label class="form-label">Método de Pago</label>
                        <select class="form-select" name="tipo_pago_id" id="tipoPago<?php echo $atleta_id; ?>" required onchange="toggleCamposPago<?php echo $atleta_id; ?>()">
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

                    <!-- Banco (solo visible si no es efectivo) -->
                    <div class="mb-3" id="bancoDiv<?php echo $atleta_id; ?>" style="display:none;">
                        <label class="form-label">Banco</label>
                        <select class="form-select" name="banco" id="bancoSelect<?php echo $atleta_id; ?>">
                            <option value="">Seleccionar...</option>
                            <option value="Banesco">Banesco</option>
                            <option value="Mercantil">Mercantil</option>
                            <option value="Provincial">Provincial</option>
                            <option value="Venezuela">Banco de Venezuela</option>
                            <option value="BOD">BOD</option>
                            <option value="Banco Nacional de Crédito">BNC</option>
                            <option value="Banco Exterior">Banco Exterior</option>
                            <option value="BBVA Provincial">BBVA Provincial</option>
                            <option value="Banco del Tesoro">Banco del Tesoro</option>
                            <option value="Banco Plaza">Banco Plaza</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <div id="bancoOtroDiv<?php echo $atleta_id; ?>" style="display:none; margin-top: 5px;">
                            <input type="text" class="form-control" name="banco_otro" placeholder="Especificar banco">
                        </div>
                    </div>

                    <!-- Referencia -->
                    <div class="mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" name="referencia" id="referenciaInput<?php echo $atleta_id; ?>" 
                            placeholder="N° de transferencia, cheque, etc.">
                        <small class="text-muted" id="referenciaAyuda<?php echo $atleta_id; ?>">
                            La referencia es obligatoria para pagos no en efectivo
                        </small>
                        <div class="invalid-feedback" id="referenciaError<?php echo $atleta_id; ?>" style="display:none;">
                            Esta referencia ya ha sido utilizada. Por favor, verifica.
                        </div>
                    </div>

                    <!-- Fecha de Pago -->
                    <div class="mb-3">
                        <label class="form-label">Fecha de Pago</label>
                        <input type="date" class="form-control" name="fecha_pago" value="<?php echo date('Y-m-d'); ?>" required>
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
</div>

<script>
function toggleCuotaSelect<?php echo $atleta_id; ?>() {
    var tipo = document.getElementById('tipoConcepto<?php echo $atleta_id; ?>').value;
    var cuotaDiv = document.getElementById('cuotaSelectDiv<?php echo $atleta_id; ?>');
    var conceptoDiv = document.getElementById('conceptoPersonalizadoDiv<?php echo $atleta_id; ?>');
    
    if (tipo === 'Mensualidad') {
        cuotaDiv.style.display = 'block';
        conceptoDiv.style.display = 'none';
        document.querySelector('#cuotaSelect<?php echo $atleta_id; ?>').required = true;
        document.querySelector('input[name="concepto_personalizado"]').required = false;
    } else if (tipo === 'Otro') {
        cuotaDiv.style.display = 'none';
        conceptoDiv.style.display = 'block';
        document.querySelector('#cuotaSelect<?php echo $atleta_id; ?>').required = false;
        document.querySelector('input[name="concepto_personalizado"]').required = true;
    } else {
        cuotaDiv.style.display = 'none';
        conceptoDiv.style.display = 'none';
        document.querySelector('#cuotaSelect<?php echo $atleta_id; ?>').required = false;
        document.querySelector('input[name="concepto_personalizado"]').required = false;
    }
}

function toggleCamposPago<?php echo $atleta_id; ?>() {
    var tipoPago = document.getElementById('tipoPago<?php echo $atleta_id; ?>').value;
    var bancoDiv = document.getElementById('bancoDiv<?php echo $atleta_id; ?>');
    var referenciaInput = document.getElementById('referenciaInput<?php echo $atleta_id; ?>');
    var referenciaAyuda = document.getElementById('referenciaAyuda<?php echo $atleta_id; ?>');
    
    // Obtener el texto del tipo de pago seleccionado
    var tipoPagoText = tipoPago ? document.getElementById('tipoPago<?php echo $atleta_id; ?>').options[document.getElementById('tipoPago<?php echo $atleta_id; ?>').selectedIndex].text : '';
    
    var esEfectivo = tipoPagoText.toLowerCase() === 'efectivo';
    
    if (esEfectivo) {
        bancoDiv.style.display = 'none';
        referenciaInput.removeAttribute('required');
        referenciaAyuda.style.display = 'none';
        document.querySelector('select[name="banco"]').removeAttribute('required');
    } else {
        bancoDiv.style.display = 'block';
        referenciaInput.setAttribute('required', 'required');
        referenciaAyuda.style.display = 'block';
        document.querySelector('select[name="banco"]').setAttribute('required', 'required');
    }
}

// Validar referencia duplicada en tiempo real (opcional)
document.addEventListener('DOMContentLoaded', function() {
    var referenciaInput = document.getElementById('referenciaInput<?php echo $atleta_id; ?>');
    referenciaInput.addEventListener('blur', function() {
        var tipoPago = document.getElementById('tipoPago<?php echo $atleta_id; ?>').value;
        var banco = document.querySelector('select[name="banco"]').value;
        var referencia = this.value;
        
        if (tipoPago && banco && referencia) {
            // Validar con AJAX
            fetch('Controller/ValidarReferenciaController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'tipo_pago_id=' + encodeURIComponent(tipoPago) + 
                      '&banco=' + encodeURIComponent(banco) + 
                      '&referencia=' + encodeURIComponent(referencia)
            })
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    document.getElementById('referenciaError<?php echo $atleta_id; ?>').style.display = 'block';
                    referenciaInput.classList.add('is-invalid');
                } else {
                    document.getElementById('referenciaError<?php echo $atleta_id; ?>').style.display = 'none';
                    referenciaInput.classList.remove('is-invalid');
                }
            });
        }
    });
});
</script>
