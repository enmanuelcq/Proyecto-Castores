<!-- Modal de Confirmar Eliminación -->
<div class="modal fade" id="modalEliminarNoticia<?php echo $noticia->getId(); ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la noticia <strong>"<?php echo htmlspecialchars($noticia->getTitulo()); ?>"</strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="Controller/EliminarNoticiaController.php?id=<?php echo $noticia->getId(); ?>" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i>Eliminar
                </a>
            </div>
        </div>
    </div>
</div>