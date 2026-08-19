<!-- Modal de Desactivación -->
<div class="modal fade" id="modalDesactivar<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Desactivación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas <strong>desactivar</strong> a este atleta?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    El atleta <strong><?php echo htmlspecialchars($nombre_completo); ?></strong> quedará <strong>inactivo</strong> y no aparecerá en las listas principales. Puedes reactivarlo más tarde desde la vista de "Inactivos".
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
                <form action="./Controller/CambiarStatusAtleta_controller.php" method="POST" class="FormularioAjax">
                    <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                    <input type="hidden" name="accion" value="desactivar">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Desactivar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>