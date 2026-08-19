<!-- Modal Ver Partido Pendiente -->
<div class="modal fade" id="modalVerPartidoPendiente<?php echo $partido['partido_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📅 Partido Programado</h5>
                
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-1 text-primary">
                        <i class="bi bi-clock"></i>
                    </div>
                     <h4 style="color: black;">Partido Programado</h4>
                    <p class="text-muted">Este partido está pendiente por jugarse</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tipo</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['tipo_partido'] ?? 'Amistoso'); ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Competencia</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['nombre_competencia'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Fecha</small>
                                <?php 
                                $fecha_obj = null;
                                if (!empty($partido['fecha_partido']) && $partido['fecha_partido'] !== '0000-00-00 00:00:00') {
                                    try {
                                        $fecha_obj = new DateTime($partido['fecha_partido']);
                                    } catch (Exception $e) {
                                        $fecha_obj = null;
                                    }
                                }
                                $fecha_mostrar = $fecha_obj ? $fecha_obj->format('d/m/Y') : '--/--/----';
                                ?>
                                <p class="fw-bold"><?php echo $fecha_mostrar; ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Hora</small>
                                <?php 
                                $hora_mostrar = $fecha_obj ? $fecha_obj->format('H:i') : '--:--';
                                ?>
                                <p class="fw-bold"><?php echo $hora_mostrar; ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Categoría</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['categoria_nombre'] ?? 'Sin categoría'); ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Duración</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['duracion']); ?></p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Rival</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['equipo_rival'] ?? 'No especificado'); ?></p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Campo</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($partido['campo_nombre'] ?? 'No especificado'); ?></p>
                            </div>
                            <!-- ===== MAPA ===== -->
                            <div class="col-12 mt-3">
                                <hr>
                                <small class="text-muted fw-bold">📍 Ubicación del Campo</small>
                                <?php if (!empty($partido['mapa_campo'])): ?>
                                    <div class="mt-2">
                                        
                                        <div class="ratio ratio-16x9">
                                            <iframe 
                                                src="<?php echo htmlspecialchars($partido['mapa_campo']); ?>&output=embed" 
                                                style="border:0; border-radius: 8px;" 
                                                allowfullscreen="" 
                                                loading="lazy">
                                            </iframe>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mt-2">No hay mapa disponible para este campo.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Estado:</strong> <span class="badge bg-primary"><?php echo $partido['estado'] ?? 'Pendiente'; ?></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>