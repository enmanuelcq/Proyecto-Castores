<!-- modalCrearNoticia.php -->
<div class="modal fade" id="modalCrearNoticia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill me-2"></i>Crear Nueva Noticia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCrearNoticia" method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Campos ocultos -->
                    <input type="hidden" name="accion" value="crear_noticia">
                    
                    <div class="row g-3">
                        <!-- Título -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-heading me-1 text-primary"></i>Título de la Noticia
                            </label>
                            <input type="text" class="form-control form-control-lg" 
                                   name="titulo" 
                                   placeholder="Ej: ¡Súmate al Deportivo los Castores!" 
                                   required>
                        </div>

                        <!-- Categoría y Tipo -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-tag me-1 text-primary"></i>Categoría
                            </label>
                            <select class="form-select" name="categoria" required>
                                <option value="">Seleccionar categoría...</option>
                                <option value="anuncio">📢 Anuncio</option>
                                <option value="torneo">🏆 Torneo</option>
                                <option value="evento">📅 Evento</option>
                                <option value="logro">🎯 Logro</option>
                                <option value="equipo">👥 Equipo</option>
                                <option value="entrenamiento">⚽ Entrenamiento</option>
                                <option value="inscripcion">📝 Inscripción</option>
                                <option value="general">📰 General</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-flag me-1 text-primary"></i>Tipo de Noticia
                            </label>
                            <select class="form-select" name="tipo" required>
                                <option value="normal">Normal</option>
                                <option value="destacada">⭐ Destacada</option>
                                <option value="urgente">🚨 Urgente</option>
                            </select>
                        </div>

                        <!-- Contenido -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-text-paragraph me-1 text-primary"></i>Contenido
                            </label>
                            <textarea class="form-control" name="contenido" rows="5" 
                                      placeholder="Escribe el contenido de la noticia aquí..."
                                      required></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Puedes usar <strong>**negritas**</strong> y <em>*cursivas*</em>
                            </div>
                        </div>

                        <!-- Resumen (opcional) -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-card-text me-1 text-primary"></i>Resumen (Opcional)
                            </label>
                            <textarea class="form-control" name="resumen" rows="2" 
                                      placeholder="Breve resumen de la noticia (se muestra en la tarjeta)"></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Si no se especifica, se usará el inicio del contenido
                            </div>
                        </div>

                        <!-- Fecha y Autor -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar me-1 text-primary"></i>Fecha de Publicación
                            </label>
                            <input type="datetime-local" class="form-control" 
                                   name="fecha_publicacion" 
                                   value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>

                        

                        <!-- Imagen -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-image me-1 text-primary"></i>Imagen de la Noticia
                            </label>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content text-center">
                                    <i class="bi bi-cloud-arrow-up display-4 text-primary"></i>
                                    <h6 class="mt-2">Arrastra una imagen aquí</h6>
                                    <p class="text small">o haz clic para seleccionar</p>
                                    <input type="file" class="d-none" name="imagen" 
                                           id="imagenInput" accept="image/*">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            onclick="document.getElementById('imagenInput').click()">
                                        <i class="bi bi-folder me-1"></i>Seleccionar imagen
                                    </button>
                                </div>
                                <div class="preview-container d-none" id="previewContainer">
                                    <img id="previewImagen" src="#" alt="Vista previa" class="img-fluid rounded">
                                    <button type="button" class="btn btn-danger btn-sm mt-2" 
                                            onclick="eliminarImagen()">
                                        <i class="bi bi-trash me-1"></i>Eliminar imagen
                                    </button>
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Formatos permitidos: JPG, PNG, GIF, WEBP. Máximo 5MB
                            </div>
                        </div>

                        <!-- Etiquetas -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-tags me-1 text-primary"></i>Etiquetas
                            </label>
                            <div class="tags-input-container">
                                <input type="text" class="form-control" 
                                       id="tagInput" 
                                       placeholder="Escribe una etiqueta y presiona Enter"
                                       onkeydown="agregarTag(event)">
                                <div class="tags-container mt-2" id="tagsContainer">
                                    <!-- Las etiquetas se agregan aquí dinámicamente -->
                                </div>
                            </div>
                            <input type="hidden" name="etiquetas" id="etiquetasHidden">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Ej: captación, torneo, sub-15, final
                            </div>
                        </div>

                        <!-- Opciones adicionales -->
                        <div class="col-12">
                            <hr>
                            <h6 class="fw-bold">
                                <i class="bi bi-gear me-1 text-primary"></i>Opciones adicionales
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="publicarAhora" name="publicar_ahora" checked>
                                        <label class="form-check-label" for="publicarAhora">
                                            Publicar ahora
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="mostrarHome" name="mostrar_home" checked>
                                        <label class="form-check-label" for="mostrarHome">
                                            Mostrar en el Home
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="notificar" name="notificar" checked>
                                        <label class="form-check-label" for="notificar">
                                            Notificar a usuarios
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmación -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarNoticia" required>
                                <label class="form-check-label" for="confirmarNoticia">
                                    Confirmo que la información es correcta y verificada
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnPublicarNoticia">
                        <i class="bi bi-check-circle me-2"></i>Publicar Noticia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>