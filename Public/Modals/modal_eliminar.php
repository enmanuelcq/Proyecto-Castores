<div class="modal fade" id="modalEliminar<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            
            <!-- ===== HEADER ===== -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Confirmar Eliminación
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
                            <span class="badge bg-secondary"><?php echo $atleta['atleta_cedula']; ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-danger">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            ¡Esta acción no se puede deshacer!
                        </span>
                    </div>
                </div>

                <!-- ===== MENSAJE DE CONFIRMACIÓN ===== -->
                <div class="text-center mb-4">
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 12px; padding: 20px;">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                        <h5 class="mt-2 text-white">¿Está seguro de eliminar a este atleta?</h5>
                        <p class="text-white small mb-0">
                            Esta acción eliminará todos los datos asociados al atleta de forma permanente.
                        </p>
                    </div>
                </div>

                <!-- ===== BOTONES ===== -->
                <div class="d-flex justify-content-center gap-4 mb-3">
                    <button class="btn btn-secondary px-5 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </button>
                    <button class="btn btn-danger px-5 py-2 rounded-pill fw-bold" 
                            style="background-color: var(--rojo-eliminar) !important; border-color: var(--rojo-eliminar) !important;" 
                            id="confirmDeleteBtn">
                        <i class="bi bi-check-lg me-2"></i>Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>