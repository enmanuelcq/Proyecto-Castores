<!-- ============================================ -->
<!-- MODAL: CAMBIAR CONTRASEÑA -->
<!-- ============================================ -->
<div class="modal fade" id="modalCambiarContrasena" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-key me-2 text-warning"></i>
                    Cambiar Contraseña
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarContrasena" method="POST" action="./Controller/CambiarContrasena_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="alert alert-info" style="background: rgba(13,202,240,0.08); border: 1px solid rgba(13,202,240,0.12); border-radius: 10px;">
                        <i class="bi bi-info-circle-fill me-2 text-info"></i>
                        <small class="text-white-50">
                            La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="custom-form-label">Contraseña Actual</label>
                        <div class="input-group">
                            <input type="password" class="form-control custom-input" 
                                   id="contrasena_actual" name="contrasena_actual" 
                                   placeholder="Ingresa tu contraseña actual" required>
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="togglePassword('contrasena_actual')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="custom-form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control custom-input" 
                                   id="contrasena_nueva" name="contrasena_nueva" 
                                   placeholder="Ingresa tu nueva contraseña" 
                                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}" 
                                   title="Mínimo 8 caracteres, al menos una mayúscula, una minúscula y un número" required>
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="togglePassword('contrasena_nueva')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text text-white-50" style="font-size:0.7rem;">
                            <i class="bi bi-check-circle me-1" id="validacion_longitud" style="color: #6c757d;"></i>
                            Mínimo 8 caracteres
                            <span class="mx-2">|</span>
                            <i class="bi bi-check-circle me-1" id="validacion_mayuscula" style="color: #6c757d;"></i>
                            Mayúscula
                            <span class="mx-2">|</span>
                            <i class="bi bi-check-circle me-1" id="validacion_minuscula" style="color: #6c757d;"></i>
                            Minúscula
                            <span class="mx-2">|</span>
                            <i class="bi bi-check-circle me-1" id="validacion_numero" style="color: #6c757d;"></i>
                            Número
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="custom-form-label">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control custom-input" 
                                   id="contrasena_confirmar" name="contrasena_confirmar" 
                                   placeholder="Confirma tu nueva contraseña" required>
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="togglePassword('contrasena_confirmar')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text text-white-50" style="font-size:0.7rem;">
                            <i class="bi bi-check-circle me-1" id="validacion_coinciden" style="color: #6c757d;"></i>
                            Las contraseñas coinciden
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-warning px-4 rounded-pill fw-bold text-dark">
                            <i class="bi bi-key me-2"></i>Cambiar Contraseña
                        </button>
                        <button type="button" class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>