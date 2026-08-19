<!-- Modal de Reactivación -->
<div class="modal fade" id="modalReactivar<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-arrow-clockwise me-2"></i>Confirmar Reactivación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas <strong>reactivar</strong> a este atleta?</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    El atleta <strong><?php echo htmlspecialchars($nombre_completo); ?></strong> volverá a estar <strong>activo</strong> y aparecerá en las listas principales.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Cédula:</strong> <?php echo htmlspecialchars($atleta->getCedula() ?: 'No registrada'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Categoría:</strong> <?php echo htmlspecialchars($categoria); ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="Controller/CambiarStatusAtleta_controller.php" method="POST" class="d-inline">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                    <input type="hidden" name="accion" value="reactivar">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reactivar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>