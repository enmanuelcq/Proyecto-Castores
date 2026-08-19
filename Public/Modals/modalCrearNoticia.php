<div class="modal fade" id="modalCrearNoticia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="Controller/GuardarNoticiaController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Noticia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="accion" value="crear">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenido</label>
                        <textarea class="form-control" name="contenido" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file" class="form-control" name="imagen" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Publicación</label>
                        <input type="datetime-local" class="form-control" name="fecha_publicacion" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="destacada" id="destacada" value="1">
                        <label class="form-check-label" for="destacada">
                            <strong>⭐ Destacar esta noticia</strong>
                            <small class="d-block text-muted">(Ocupará el espacio principal en la portada)</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </div>
                
            </form>
        </div>
    </div>
</div>