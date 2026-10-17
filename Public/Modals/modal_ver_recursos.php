<?php foreach($entrenamientos_filtrados as $e): ?>
<div class="modal fade" id="modalVerRecursos<?php echo $e['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0" style="background: var(--primary);">
                <h5 class="modal-title fw-bold text-uppercase text-white">
                    <i class="bi bi-paperclip me-2"></i>
                    Recursos - <?php echo $e['tipo']; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-info-modal">
                        <h4><?php echo htmlspecialchars($e['tipo'] . ' - ' . implode(', ', $e['categorias'] ?: [$e['categoria']])); ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-secondary"><?php echo date('d/m/Y', strtotime($e['fecha'])); ?></span>
                            <span class="badge bg-secondary"><?php echo $e['hora_inicio']; ?> - <?php echo $e['hora_fin']; ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white-50 small">
                            <i class="bi bi-collection me-1"></i>
                            <?php echo count($e['recursos']); ?> recurso(s)
                        </span>
                    </div>
                </div>

                <?php if(empty($e['recursos'])): ?>
                <div class="alert alert-warning text-center mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Este entrenamiento no tiene recursos cargados.
                </div>
                <?php else: ?>
                <div class="row g-3">
                    <?php foreach($e['recursos'] as $rec): ?>
                        <?php if($rec['tipo_recurso'] == 'imagen'): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border">
                                <a href="<?php echo htmlspecialchars($rec['archivo'] ?? ''); ?>" target="_blank">
                                    <img src="<?php echo htmlspecialchars($rec['archivo'] ?? ''); ?>" class="card-img-top" style="height:160px; object-fit:cover;" alt="Imagen">
                                </a>
                                <div class="card-body py-2">
                                    <span class="badge bg-info"><i class="bi bi-image me-1"></i>Imagen</span>
                                    <?php if(!empty($rec['descripcion'])): ?>
                                    <p class="small text-dark mt-2 mb-0"><?php echo htmlspecialchars($rec['descripcion']); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php echo htmlspecialchars($rec['archivo'] ?? ''); ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2 w-100">
                                        <i class="bi bi-eye me-1"></i>Ver imagen
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php elseif($rec['tipo_recurso'] == 'video'): ?>
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-body py-2">
                                    <span class="badge bg-warning text-dark"><i class="bi bi-camera-video me-1"></i>Video</span>
                                    <?php if(!empty($rec['descripcion'])): ?>
                                    <p class="small text-dark mt-2 mb-1"><?php echo htmlspecialchars($rec['descripcion']); ?></p>
                                    <?php endif; ?>
                                    <video controls class="w-100 rounded" style="max-height:220px;" src="<?php echo htmlspecialchars($rec['archivo'] ?? ''); ?>"></video>
                                    <a href="<?php echo htmlspecialchars($rec['archivo'] ?? ''); ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2 w-100">
                                        <i class="bi bi-download me-1"></i>Descargar video
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-body py-2">
                                    <span class="badge bg-primary"><i class="bi bi-link-45deg me-1"></i>Enlace</span>
                                    <?php if(!empty($rec['descripcion'])): ?>
                                    <p class="small text-dark mt-2 mb-1"><?php echo htmlspecialchars($rec['descripcion']); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php echo htmlspecialchars($rec['url'] ?? ''); ?>" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Abrir enlace
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
