<?php 
// ===== CALCULAR PENDIENTES REALES ANTES DEL MODAL =====
$pendientes_reales = 0;
$meses_activos = obtenerMesesActivos();

// Buscar la solvencia del atleta actual
foreach($todas_las_solvencias as $solvencia_buscar){
    if($solvencia_buscar['atleta_cedula'] === $atleta_id){
        foreach($solvencia_buscar as $clave => $info_mes){
            if($clave !== 'atleta_cedula' && in_array($clave, $meses_activos)){
                if($info_mes['estado'] === 'Pendiente'){
                    $pendientes_reales++;
                }
            }
        }
        break;
    }
}
?>

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
                        echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
                        ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo $nombre_completo; ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo $atleta['atleta_categoria']; ?></span>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php echo date('F Y'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white small">
                            <i class="bi bi-clock-history me-1"></i>
                            <?php echo $pendientes_reales . ' pendientes'; ?>
                        </span>
                    </div>
                </div>

                <!-- ===== CONTENIDO ===== -->
                <?php 
                foreach($todas_las_solvencias as $solvencia){
                    if($solvencia['atleta_cedula'] === $atleta_id){ 
                ?>
                        <div class="inner-white-card mb-3">
                            <div class="row g-2">
                                <?php 
                                $meses_activos = obtenerMesesActivos();
                                foreach($solvencia as $clave => $info_mes){
                                    if($clave === 'atleta_cedula') continue;

                                    $mes = $info_mes['mes'];
                                    $estado = $info_mes['estado'];
                                    $monto = $info_mes['monto'];
                                    $referencia = $info_mes['referencia'];
                                    $tipo_pago = $info_mes['tipo_pago'];

                                    // Verificar si el mes es futuro
                                    $es_futuro = !in_array($clave, $meses_activos);

                                    // Si es futuro, mostrar como "Por Vencer" (deshabilitado)
                                    if($es_futuro) {
                                ?>
                                        <div class="col-6 col-md-3">
                                            <div class="month-card" style="background-color: #6c757d; opacity: 0.5; color: #fff;">
                                                <?php echo $mes; ?>
                                            </div>
                                            <button class="btn btn-secondary w-100" disabled style="border-radius: 0 0 8px 8px; opacity: 0.5; cursor: not-allowed;">
                                                <span class="status-badge">Por Vencer</span>
                                                <small class="d-block">—</small>
                                            </button>
                                        </div>
                                <?php
                                    } elseif($estado == 'Pagado'){
                                ?>
                                        <div class="col-6 col-md-3">
                                            <div class="month-card"><?php echo $mes; ?></div>
                                            <button class="btn btn-action-green w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSolvenciaPagado<?php echo $atleta_id; ?>"
                                                    onclick="mostrarDetallePago(
                                                        '<?php echo $info_mes['mes']; ?>',
                                                        '<?php echo $info_mes['monto']; ?>',
                                                        '<?php echo $info_mes['tipo_pago']; ?>',
                                                        '<?php echo $info_mes['referencia']; ?>',
                                                        '<?php echo $atleta_id; ?>'
                                                    )">
                                                <span class="status-badge">Pagado</span>
                                                <small class="d-block">$<?php echo number_format($info_mes['monto'], 2); ?></small>
                                            </button>
                                        </div>
                                <?php
                                    } elseif($estado == 'revision' || $estado == 'Revision'){
                                ?>
                                        <div class="col-6 col-md-3">
                                            <div class="month-card" style="background-color: #ffeeba;"><?php echo $mes; ?></div>
                                            <button class="btn btn-pendiente w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSolvenciaRevision<?php echo $atleta_id; ?>"
                                                    onclick="mostrarDetalleRevision(
                                                        '<?php echo $info_mes['mes']; ?>',
                                                        '<?php echo $info_mes['monto']; ?>',
                                                        '<?php echo $info_mes['tipo_pago']; ?>',
                                                        '<?php echo $info_mes['referencia']; ?>',
                                                        '<?php echo $atleta_id; ?>'
                                                    )">
                                                <span class="status-badge">En Revisión</span>
                                                <small class="d-block">$<?php echo number_format($info_mes['monto'] ?: 0, 2); ?></small>
                                            </button>
                                        </div>
                                <?php
                                    } else { // Pendiente
                                ?>
                                        <div class="col-6 col-md-3">
                                            <div class="month-card" style="background-color: #f5c6cb; color: #721c24;"><?php echo $mes; ?></div>
                                            <button class="btn btn-action-red w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSolvenciaPendiente<?php echo $atleta_id; ?>"
                                                    onclick="mostrarDetallePendiente(
                                                        '<?php echo $info_mes['mes']; ?>',
                                                        '<?php echo $info_mes['monto']; ?>',
                                                        '<?php echo $atleta_id; ?>'
                                                    )">
                                                <span class="status-badge">Pendiente</span>
                                                <small class="d-block">Pagar $<?php echo number_format($info_mes['monto'] ?: 0, 2); ?></small>
                                            </button>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                <?php 
                        break;
                    }
                }
                ?>

                <!-- ===== LEYENDA ===== -->
                <div class="row g-2 mt-3">
                    <div class="col-12">
                        <small class="text-white-50">
                            <span class="badge bg-success me-1">●</span> Pagado
                            <span class="badge bg-danger ms-2 me-1">●</span> Pendiente
                            <span class="badge bg-warning ms-2 me-1">●</span> En Revisión
                            <span class="badge bg-secondary ms-2 me-1">●</span> Por Vencer
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<?php
include './Public/Modals/modalSolvenciaPagado.php';
include './Public/Modals/modalSolvenciaPendiente.php';
include './Public/Modals/modalSolvenciaRevision.php';
?>