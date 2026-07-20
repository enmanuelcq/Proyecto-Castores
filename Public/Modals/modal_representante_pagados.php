<?php
// ============================================
// /Public/Modals/modal_representante_pagados.php
// MODAL PARA VER MESES PAGADOS
// ============================================
?>

<div class="modal fade" id="modalRepresentantePagados<?php echo $atleta['atleta_cedula']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <!-- ===== HEADER ===== -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                    Meses Pagados
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
                            <span class="badge bg-success"><?php echo $pagados; ?> meses pagados</span>
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

                <!-- ===== TABLA DE MESES PAGADOS ===== -->
                <div class="table-responsive">
                    <table class="table table-dashboard">
                        <thead>
                            <tr>
                                <th class="text-white">Mes</th>
                                <th class="text-white">Monto</th>
                                <th class="text-white">Tipo de Pago</th>
                                <th class="text-white">Referencia</th>
                                <th class="text-white">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $meses_orden = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                            $meses_nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            $tiene_pagados = false;
                            
                            foreach($meses_orden as $index => $mes_clave):
                                if(isset($solvencia_atleta[$mes_clave]) && $solvencia_atleta[$mes_clave]['estado'] == 'Pagado'):
                                    $tiene_pagados = true;
                                    $info = $solvencia_atleta[$mes_clave];
                                    $monto = isset($info['monto']) && !empty($info['monto']) ? $info['monto'] : 0;
                                    $tipo_pago = $info['tipo_pago'] ?? 'N/A';
                                    $referencia = $info['referencia'] ?? 'N/A';
                            ?>
                            <tr>
                                <td><strong><?php echo $meses_nombres[$index]; ?></strong></td>
                                <td style="color: var(--verde-vivido);">$<?php echo number_format($monto, 2); ?></td>
                                <td><span class="badge bg-info" style="background-color: #ff0000;"><?php echo ucfirst(str_replace('_', ' ', $tipo_pago)); ?></span></td>
                                <td><code style="color: #ffc107"><?php echo $referencia; ?></code></td>
                                <td><span class="badge badge-pagado">Pagado</span></td>
                            </tr>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                            <?php if(!$tiene_pagados): ?>
                            <tr>
                                <td colspan="5" class="text-center text-white">
                                    <i class="bi bi-info-circle me-2"></i>
                                    No hay meses pagados registrados
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- ===== RESUMEN ===== -->
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <span class="valor" style="font-size:1.2rem;color:var(--verde-vivido);"><?php echo $pagados; ?></span>
                            <p class="etiqueta">Meses Pagados</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <span class="valor" style="font-size:1.2rem;color:#ffc107;"><?php echo count($meses_activos); ?></span>
                            <p class="etiqueta">Meses del Año</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <span class="valor" style="font-size:1.2rem;color:#fff;">
                                <?php 
                                $porcentaje = count($meses_activos) > 0 ? round(($pagados / count($meses_activos)) * 100) : 0;
                                echo $porcentaje . '%';
                                ?>
                            </span>
                            <p class="etiqueta">Cumplimiento</p>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- ===== FOOTER ===== -->
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>