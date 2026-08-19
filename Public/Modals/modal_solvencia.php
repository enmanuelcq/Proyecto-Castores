<!-- Modal de Solvencia -->
<div class="modal fade" id="modalSolvencia<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <!-- ===== HEADER ===== -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-cash-stack me-2"></i>Solvencia del Atleta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- ===== BODY ===== -->
            <div class="modal-body">
                
                <!-- ===== CABECERA DEL ATLETA ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-avatar-modal">
                        <?php 
                        echo strtoupper(substr($atleta->getPrimerNombre() ?? 'A', 0, 1) . substr($atleta->getPrimerApellido() ?? 'A', 0, 1));
                        ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo htmlspecialchars($nombre_completo); ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($categoria); ?></span>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php echo date('F Y'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white small">
                            <i class="bi bi-clock-history me-1"></i>
                            <?php 
                            if (!empty($solvencia)) {
                                $pendientes = array_filter($solvencia, function($m) { 
                                    return in_array($m['estado'], ['Pendiente', 'Parcial']); 
                                });
                                echo count($pendientes) . ' pendientes';
                            } else {
                                echo 'Sin datos';
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <!-- ===== MESES ===== -->
                <div class="inner-white-card mb-3">
                    <?php if (empty($solvencia)): ?>
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            No hay registros de cuotas para este atleta.
                        </div>
                    <?php else: ?>
                    <div class="row g-2">
                        <?php foreach ($solvencia as $mesData): 
                            $mes = $mesData['nombre_mes'];
                            $estado = $mesData['estado'];
                            $monto = $mesData['monto'];
                            $referencia = $mesData['referencia'] ?? '';
                            $tipoPago = $mesData['tipo_pago'] ?? '';
                            $esFuturo = $mesData['es_futuro'] ?? false;
                            $cuotaId = $mesData['cuota_id'] ?? null;
                            $abonado = $mesData['abonado'] ?? 0;
                            $saldo = $mesData['saldo'] ?? $monto;
                            
                            // Determinar monto a mostrar y estado real
                            $montoMostrar = ($estado === 'Parcial') ? $saldo : $monto;
                            $estadoMostrar = ($estado === 'Parcial') ? 'Parcial' : $estado;
                        ?>
                            <!-- Cada mes ocupa una columna -->
                            <div class="col-6 col-md-3">
                                <?php if ($esFuturo): ?>
                                    <!-- Por Vencer -->
                                    <div class="month-card" style="background-color: #6c757d; opacity: 0.5; color: #fff;">
                                        <?php echo $mes; ?>
                                    </div>
                                    <button class="btn btn-secondary w-100" disabled style="border-radius: 0 0 8px 8px; opacity: 0.5; cursor: not-allowed;">
                                        <span class="status-badge">Por Vencer</span>
                                        <small class="d-block">—</small>
                                    </button>
                                <?php elseif ($estado === 'Pagado'): ?>
                                    <!-- Pagado -->
                                    <div class="month-card"><?php echo $mes; ?></div>
                                    <button class="btn btn-action-green w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalSolvenciaPagado<?php echo $atleta_id; ?>"
                                        onclick="mostrarDetallePago(
                                            '<?php echo addslashes($mes); ?>',
                                            '<?php echo number_format($monto, 2, '.', ''); ?>',
                                            '<?php echo addslashes($tipoPago); ?>',
                                            '<?php echo addslashes($referencia); ?>',
                                            '<?php echo $atleta_id; ?>',
                                            '<?php 
                                                $fecha = $mesData['fecha_pago'] ?? '';
                                                // Si la fecha no está vacía, formatearla, sino mostrar 'No registrada'
                                                echo !empty($fecha) ? date('d/m/Y', strtotime($fecha)) : 'No registrada';
                                            ?>',
                                            '<?php echo addslashes($mesData['banco'] ?? ''); ?>'
                                        )">
                                    <span class="status-badge">Pagado</span>
                                    <small class="d-block">$<?php echo number_format($monto, 2); ?></small>
                                </button>

                                <?php elseif ($estado === 'Parcial' || $estado === 'Pendiente'): ?>
                                    <!-- Parcial / Pendiente - Usan el mismo modal con data-* -->
                                    <div class="month-card" style="background-color: <?php echo $estado === 'Parcial' ? '#fff3cd' : '#f5c6cb'; ?>; <?php echo $estado === 'Parcial' ? 'color: #856404;' : 'color: #721c24;'; ?>">
                                        <?php echo $mes; ?>
                                    </div>
                                    <button class="btn <?php echo $estado === 'Parcial' ? 'btn-pendiente' : 'btn-action-red'; ?> w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSolvenciaPendiente<?php echo $atleta_id; ?>"
                                            data-mes="<?php echo htmlspecialchars($mes); ?>"
                                            data-monto="<?php echo number_format($montoMostrar, 2, '.', ''); ?>"
                                            data-cuota="<?php echo $cuotaId; ?>"
                                            data-atleta="<?php echo $atleta_id; ?>"
                                            data-estado="<?php echo $estadoMostrar; ?>">
                                        <span class="status-badge"><?php echo $estadoMostrar; ?></span>
                                        <small class="d-block">Pagar Bs <?php echo number_format($montoMostrar, 2); ?></small>
                                    </button>
                                <?php elseif ($estado === 'En Revisión'): ?>
                                    <!-- En Revisión -->
                                    <div class="month-card" style="background-color: #ffeeba;"><?php echo $mes; ?></div>
                                    <button class="btn btn-action-red w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSolvenciaPendiente<?php echo $atleta_id; ?>"
                                            data-mes="<?php echo htmlspecialchars($mes); ?>"
                                            data-monto="<?php echo number_format($monto, 2, '.', ''); ?>"
                                            data-cuota="<?php echo $cuotaId; ?>"
                                            data-atleta="<?php echo $atleta_id; ?>"
                                            data-estado="En Revisión">
                                        <span class="status-badge">En Revisión</span>
                                        <small class="d-block">Bs <?php echo number_format($monto, 2); ?></small>
                                    </button>
                                <?php else: ?>
                                    <!-- Por defecto (Pendiente sin cuota) -->
                                    <div class="month-card" style="background-color: #f5c6cb; color: #721c24;"><?php echo $mes; ?></div>
                                    <?php if ($cuotaId): ?>
                                        <button class="btn btn-action-red w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSolvenciaPendiente<?php echo $atleta_id; ?>"
                                                data-mes="<?php echo htmlspecialchars($mes); ?>"
                                                data-monto="<?php echo number_format($monto, 2, '.', ''); ?>"
                                                data-cuota="<?php echo $cuotaId; ?>"
                                                data-atleta="<?php echo $atleta_id; ?>"
                                                data-estado="Pendiente">
                                            <span class="status-badge">Pendiente</span>
                                            <small class="d-block">Pagar Bs <?php echo number_format($monto, 2); ?></small>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary w-100" disabled>
                                            <span class="status-badge">Sin Cuota</span>
                                            <small class="d-block">—</small>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ===== LEYENDA ===== -->
                <div class="row g-2 mt-3">
                    <div class="col-12">
                        <small class="text-white-50">
                            <span class="badge bg-success me-1">●</span> Pagado
                            <span class="badge bg-danger ms-2 me-1">●</span> Pendiente
                            <span class="badge bg-warning ms-2 me-1">●</span> En Revisión
                            <span class="badge bg-secondary ms-2 me-1">●</span> Por Vencer
                            <span class="badge bg-info ms-2 me-1">●</span> Parcial
                        </small>
                        <small class="text-white-50 ms-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Mostrando hasta <?php echo date('F Y'); ?>
                        </small>
                    </div>
                </div>

            </div>
            
            <!-- ===== FOOTER ===== -->
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarPago<?php echo $atleta_id; ?>">
                    <i class="bi bi-plus-circle me-2"></i>Registrar Pago
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Incluir los modales secundarios -->
<?php include __DIR__ . '/modalSolvenciaPagado.php'; ?>
<?php include __DIR__ . '/modalSolvenciaPendiente.php'; ?>
<?php include __DIR__ . '/modalSolvenciaRevision.php'; ?>