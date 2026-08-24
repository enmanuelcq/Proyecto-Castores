<!-- Modal Ver Partido Pendiente — con gestión de convocados -->
<div class="modal fade" id="modalVerPartidoPendiente<?php echo $partido['partido_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-pendiente-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-pendiente-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Partido Programado</h5>
                        <small class="opacity-75"><?php echo htmlspecialchars($partido['categoria_nombre'] ?? ''); ?> — <?php echo htmlspecialchars($partido['equipo_rival'] ?? ''); ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Info del partido -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="pendiente-info-box">
                            <i class="bi bi-calendar3 text-primary"></i>
                            <div>
                                <span class="pendiente-info-label">Fecha</span>
                                <strong><?php 
                                $fecha_obj = null;
                                if (!empty($partido['fecha_partido']) && $partido['fecha_partido'] !== '0000-00-00 00:00:00') {
                                    try { $fecha_obj = new DateTime($partido['fecha_partido']); } catch (Exception $e) {}
                                }
                                echo $fecha_obj ? $fecha_obj->format('d/m/Y') : '--/--/----';
                                ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="pendiente-info-box">
                            <i class="bi bi-clock text-info"></i>
                            <div>
                                <span class="pendiente-info-label">Hora</span>
                                <strong><?php echo $fecha_obj ? $fecha_obj->format('H:i') : '--:--'; ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="pendiente-info-box">
                            <i class="bi bi-tag text-warning"></i>
                            <div>
                                <span class="pendiente-info-label">Tipo</span>
                                <strong><?php echo htmlspecialchars($partido['tipo_partido'] ?? 'Amistoso'); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="pendiente-info-box">
                            <i class="bi bi-clock-history text-secondary"></i>
                            <div>
                                <span class="pendiente-info-label">Duración</span>
                                <strong><?php echo htmlspecialchars($partido['duracion'] ?? ''); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="pendiente-info-box">
                            <i class="bi bi-geo-alt text-danger"></i>
                            <div>
                                <span class="pendiente-info-label">Campo</span>
                                <strong><?php echo htmlspecialchars($partido['campo_nombre'] ?? 'Sin campo'); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pendiente-info-box">
                            <i class="bi bi-trophy text-success"></i>
                            <div>
                                <span class="pendiente-info-label">Competencia</span>
                                <strong><?php echo htmlspecialchars($partido['nombre_competencia'] ?? 'N/A'); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($partido['mapa_campo'])): ?>
                <div class="mb-4">
                    <a href="<?php echo htmlspecialchars($partido['mapa_campo']); ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="bi bi-map me-1"></i>Abrir en Google Maps
                    </a>
                    <div class="ratio ratio-16x9">
                        <iframe src="<?php echo htmlspecialchars($partido['mapa_campo']); ?>&output=embed" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ===== CONVOCADOS ===== -->
                <?php if (!empty($_SESSION['usuario'])): ?>
                <div class="convocados-section">
                    <div class="convocados-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill text-success"></i>
                            <h6 class="mb-0 fw-bold">Convocados</h6>
                            <span class="badge bg-success" id="convocadoCount_<?php echo $partido['partido_id']; ?>"><?php echo $numConvocados; ?></span>
                        </div>
                        <?php if ($_SESSION['usuario']['id_rol'] == 3 || $_SESSION['usuario']['id_rol'] == 1): ?>
                        <small class="text-muted">Categoría del partido: <?php echo htmlspecialchars($partido['categoria_nombre'] ?? ''); ?> + 2 categorías inferiores</small>
                        <?php endif; ?>
                    </div>

                    <div class="convocados-body" id="convocadosContainer_<?php echo $partido['partido_id']; ?>">
                        <?php
                        $puedeEditar = ($_SESSION['usuario']['id_rol'] == 3 || $_SESSION['usuario']['id_rol'] == 1);
                        foreach ($atletasCategoria as $atleta):
                            $yaConvocado = in_array($atleta->getAtletaId(), $convocadoIds);
                            $catDelAtleta = $mapaCatAtletas[$atleta->getAtletaId()] ?? '';
                        ?>
                        <div class="convocado-row <?php echo $yaConvocado ? 'ya-convocado' : ''; ?>">
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
                            <?php if ($puedeEditar): ?>
                            <button class="btn-convocado-toggle <?php echo $yaConvocado ? 'convocado-active' : ''; ?>" 
                                    onclick="event.stopPropagation(); toggleConvocado(this, '<?php echo $partido['partido_id']; ?>', '<?php echo $atleta->getAtletaId(); ?>')">
                                <i class="bi <?php echo $yaConvocado ? 'bi-check-circle-fill' : 'bi-plus-circle'; ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($puedeEditar && $numConvocados === 0): ?>
                    <div class="convocados-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Debes convocar al menos un jugador antes de cargar el resultado.
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="alert alert-info mt-3 mb-0">
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
