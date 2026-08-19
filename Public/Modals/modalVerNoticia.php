<!-- Modal de Ver Noticia — Estilo Newspaper -->
<div class="modal fade" id="modalVerNoticia<?php echo $noticia->getId(); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-news-reader">
            <?php if ($noticia->getImagen()): ?>
            <div class="modal-news-hero">
                <img src="<?php echo htmlspecialchars($noticia->getImagen()); ?>" 
                     alt="<?php echo htmlspecialchars($noticia->getTitulo()); ?>"
                     class="modal-news-hero-img">
                <div class="modal-news-hero-overlay"></div>
                <div class="modal-news-hero-caption">
                    <span class="modal-news-kicker">
                        <i class="bi bi-newspaper me-1"></i>Noticia
                    </span>
                </div>
            </div>
            <?php endif; ?>

            <div class="modal-news-body">
                <!-- Meta info -->
                <div class="modal-news-meta d-flex align-items-center gap-3 mb-3">
                    <span class="modal-news-date">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php 
                        setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain.1252');
                        $ts = strtotime($noticia->getFechaPublicacion());
                        echo strftime('%d de %B de %Y · %H:%M', $ts);
                        ?>
                    </span>
                </div>

                <!-- Título -->
                <h2 class="modal-news-title"><?php echo htmlspecialchars($noticia->getTitulo()); ?></h2>

                <!-- Separador decorativo -->
                <div class="modal-news-rule">
                    <span class="modal-news-rule-dot"></span>
                </div>

                <!-- Contenido -->
                <div class="modal-news-content">
                    <?php 
                    $parrafos = explode("\n", $noticia->getContenido());
                    foreach ($parrafos as $parrafo):
                        $parrafo = trim($parrafo);
                        if (!empty($parrafo)):
                    ?>
                    <p><?php echo htmlspecialchars($parrafo); ?></p>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>

            <div class="modal-news-footer">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-news-footer-icon">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <span class="modal-news-footer-text">Academia Deportivo Los Castores</span>
                </div>
                <button type="button" class="modal-news-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
