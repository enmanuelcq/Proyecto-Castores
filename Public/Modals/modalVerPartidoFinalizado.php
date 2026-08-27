<!-- Modal Ver Partido Finalizado -->
<div class="modal fade" id="modalVerPartidoFinalizado<?php echo $partido['partido_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">🏁 Partido Finalizado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <!-- === Resumen del partido === -->
                <div class="text-center mb-4">
                    <div class="display-1 text-success">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h4>Partido Finalizado</h4>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <!-- Marcador -->
                        <div class="row text-center align-items-center">
                            <div class="col-5">
                                <h6>Los Castores</h6>
                                <span class="display-4 fw-bold text-success"><?php echo $partido['goles_favor'] ?? 0; ?></span>
                            </div>
                            <div class="col-2">
                                <span class="h4 text-muted">VS</span>
                            </div>
                            <div class="col-5">
                                <h6><?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?></h6>
                                <span class="display-4 fw-bold text-danger"><?php echo $partido['goles_contra'] ?? 0; ?></span>
                            </div>
                        </div>
                        <hr>
                        
                        <!-- Información general -->
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Fecha</small>
                                <?php 
                                $fecha_obj = new DateTime($partido['fecha_partido']);
                                $fecha_mostrar = $fecha_obj ? $fecha_obj->format('d/m/Y') : '--/--/----';
                                ?>
                                <p class="fw-bold"><?php echo $fecha_mostrar; ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Categoría</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['categoria_nombre'] ?? 'Sin categoría'); ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Tipo</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['tipo_partido'] ?? 'Amistoso'); ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Competencia</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['nombre_competencia'] ?? 'N/A'); ?></p>
                            </div>
                        </div>

                        <!-- ===================================================== -->
                        <!-- SECCIÓN: DATOS DEL PARTIDO (CONDICIONAL POR ROL)       -->
                        <!-- ===================================================== -->
                        
                        <!-- ===== CONVOCADOS (solo usuarios logueados, solo lectura) ===== -->
                        <?php if (!empty($_SESSION['usuario']) && $numConvocados > 0): ?>
                        <hr>
                        <div class="convocados-section">
                            <div class="convocados-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-people-fill text-success"></i>
                                    <h6 class="mb-0 fw-bold">Convocados</h6>
                                    <span class="badge bg-success"><?php echo $numConvocados; ?></span>
                                </div>
                            </div>
                            <div class="convocados-body">
                                <?php foreach ($convocados as $atleta): ?>
                                <?php
                                    $catDelAtleta = $mapaCatAtletas[$atleta->getAtletaId()] ?? '';
                                ?>
                                <div class="convocado-row ya-convocado">
                                    <div class="convocado-avatar">
                                        <?php if ($atleta->getFoto()): ?>
                                            <img src="./Public/uploads/<?php echo htmlspecialchars($atleta->getFoto()); ?>" alt="" class="convocado-avatar-img">
                                        <?php else: ?>
                                            <div class="convocado-avatar-initials">
                                                <?php echo mb_substr($atleta->getPrimerNombre(), 0, 1); ?><?php echo mb_substr($atleta->getPrimerApellido(), 0, 1); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="convocado-info">
                                        <span class="convocado-name"><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido()); ?></span>
                                        <?php if (!empty($catDelAtleta)): ?>
                                        <span class="convocado-cat-badge"><?php echo htmlspecialchars($catDelAtleta); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- ===== 1. GOLEADORES (visible para todos) ===== -->
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success"><i class="bi bi-trophy-fill me-1"></i> Goleadores</h6>
                                <?php if (!empty($goleadores)): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($goleadores as $gol): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    <?php echo htmlspecialchars($gol['1er_nombre'] . ' ' . $gol['1er_apellido']); ?>
                                                    <small class="text-muted">(min <?php echo $gol['tiempo'] ? date('i', strtotime($gol['tiempo'])) : '?'; ?>)</small>
                                                </span>
                                                <span class="badge bg-success rounded-pill"><?php echo $gol['cant_goles']; ?> ⚽</span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No hubo goles registrados.</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- ===== 2. TARJETAS (visible para todos) ===== -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-danger"><i class="bi bi-card-list me-1"></i> Tarjetas</h6>
                                <?php if (!empty($tarjetas)): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($tarjetas as $tarjeta): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    <?php echo htmlspecialchars($tarjeta['1er_nombre'] . ' ' . $tarjeta['1er_apellido']); ?>
                                                    <small class="text-muted">(<?php echo htmlspecialchars($tarjeta['tarjeta']); ?>)</small>
                                                </span>
                                                <span class="badge bg-<?php 
                                                    echo match(strtolower($tarjeta['tarjeta'])) {
                                                        'amarilla' => 'warning text-dark',
                                                        'roja' => 'danger',
                                                        'doble amarilla' => 'warning text-dark',
                                                        default => 'secondary'
                                                    };
                                                ?> rounded-pill">
                                                    <?php echo htmlspecialchars($tarjeta['tarjeta']); ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No hubo tarjetas registradas.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- ========================================================== -->
                        <!-- SECCIONES EXCLUSIVAS PARA ENTRENADOR                        -->
                        <!-- ========================================================== -->
                        <?php if ($rol_usuario == 3): // Solo entrenador ?>
                        
                        <!-- ===== 3. CAMBIOS ===== -->
                        <hr>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold text-info"><i class="bi bi-arrow-left-right me-1"></i> Cambios</h6>
                                <?php if (!empty($cambios)): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($cambios as $cambio): ?>
                                            <li class="list-group-item">
                                                <span>
                                                    <strong>Entra:</strong> <?php echo htmlspecialchars($cambio['entra_nombre'] . ' ' . $cambio['entra_apellido']); ?>
                                                    <span class="mx-2">→</span>
                                                    <strong>Sale:</strong> <?php echo htmlspecialchars($cambio['sale_nombre'] . ' ' . $cambio['sale_apellido']); ?>
                                                    <small class="text-muted">(min <?php echo $cambio['minuto']; ?>)</small>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No hubo cambios registrados.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- ===== 4. INCIDENCIAS ===== -->
                        <hr>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold text-warning"><i class="bi bi-exclamation-triangle me-1"></i> Incidencias</h6>
                                <?php if (!empty($incidencias)): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($incidencias as $incidencia): ?>
                                            <li class="list-group-item">
                                                <span>
                                                    <?php echo htmlspecialchars($incidencia['1er_nombre'] . ' ' . $incidencia['1er_apellido']); ?>
                                                    <span class="mx-2">-</span>
                                                    <?php echo htmlspecialchars($incidencia['descripcion']); ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No hubo incidencias registradas.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php endif; // Fin de la condición de entrenador ?>
                        <!-- ========================================================== -->

                    </div>
                </div>

                <!-- Estado -->
                <div class="alert alert-success mt-3">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Estado:</strong> <span class="badge bg-success">Finalizado</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
            </div>
        </div>
    </div>
</div>