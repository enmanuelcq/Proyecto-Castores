<!-- Modal de Editar Noticia -->
<div class="modal fade" id="modalEditarNoticia<?php echo $noticia->getId(); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-fill me-2 text-warning"></i>Editar Noticia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="Controller/EditarNoticiaController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="noticia_id" value="<?php echo $noticia->getId(); ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título</label>
                        <input type="text" class="form-control" name="titulo" 
                               value="<?php echo htmlspecialchars($noticia->getTitulo()); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contenido</label>
                        <textarea class="form-control" name="contenido" rows="5" required><?php echo htmlspecialchars($noticia->getContenido()); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Imagen</label>
                        <?php if ($noticia->getImagen()): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($noticia->getImagen()); ?>" 
                                     class="img-thumbnail" style="max-height: 100px;">
                            </div>
                            <small class="text-muted">Deja vacío para mantener la imagen actual</small>
                        <?php else: ?>
                            <p class="text-muted">No hay imagen</p>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="imagen" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado</label>
                        <select class="form-select" name="estado">
                            <option value="1" <?php echo $noticia->getEstado() == 1 ? 'selected' : ''; ?>>Publicado</option>
                            <option value="0" <?php echo $noticia->getEstado() == 0 ? 'selected' : ''; ?>>Oculto</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="destacada" id="destacada" value="1" 
                            <?php echo $noticia->getEsDestacada() == 1 ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="destacada">
                            <strong>⭐ Destacar esta noticia</strong>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
                
            </form>
        </div>
    </div>
</div>