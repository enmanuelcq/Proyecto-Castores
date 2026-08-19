<!-- Modal de Ver Noticia -->
<div class="modal fade" id="modalVerNoticia<?php echo $noticia->getId(); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><?php echo htmlspecialchars($noticia->getTitulo()); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if ($noticia->getImagen()): ?>
                    <img src="<?php echo htmlspecialchars($noticia->getImagen()); ?>" 
                         class="img-fluid rounded-3 mb-3" 
                         alt="<?php echo htmlspecialchars($noticia->getTitulo()); ?>"
                         style="max-height: 300px; width: 100%; object-fit: cover;">
                <?php endif; ?>
                <p class="text small">
                    <i class="bi bi-calendar me-1"></i>
                    <?php echo date('d/m/Y H:i', strtotime($noticia->getFechaPublicacion())); ?>
                </p>
                <hr>
                <p><?php echo nl2br(htmlspecialchars($noticia->getContenido())); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>