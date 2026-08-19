<?php
// ============================================
// /vistas/perfil_usuario.php
// PERFIL DEL USUARIO
// ============================================

// Obtener datos del usuario desde la sesión
$usuario_cedula = isset($_SESSION['usuario']['usuario_cedula']) ? $_SESSION['usuario']['usuario_cedula'] : '';
$usuario_rol = isset($_SESSION['usuario']['usuario_rol']) ? $_SESSION['usuario']['usuario_rol'] : '';
$usuario_nombre = isset($_SESSION['usuario']['usuario_nombre']) ? $_SESSION['usuario']['usuario_nombre'] : '';
$usuario_apellido = isset($_SESSION['usuario']['usuario_apellido']) ? $_SESSION['usuario']['usuario_apellido'] : '';
$usuario_email = isset($_SESSION['usuario']['usuario_email']) ? $_SESSION['usuario']['usuario_email'] : '';
$usuario_telefono = isset($_SESSION['usuario']['usuario_telefono']) ? $_SESSION['usuario']['usuario_telefono'] : '';
$usuario_usuario = isset($_SESSION['usuario']['usuario_usuario']) ? $_SESSION['usuario']['usuario_usuario'] : '';

// Si no hay sesión, redirigir
if(empty($usuario_cedula)) {
    header('Location: index.php?View=Login');
    exit();
}

// Si es representante, obtener datos adicionales
$representante_info = null;
if($usuario_rol == 'Representante') {
    require_once './Model/Representantes.php';
    $representante_info = buscarRepresentantePorCedula($usuario_cedula);
}

// Si es entrenador, obtener estadísticas
$estadisticas_entrenador = null;
if($usuario_rol == 'Entrenador') {
    require_once './Model/Entrenamiento.php';
    require_once './Model/ReportesAdministrador.php';
    $estadisticas_entrenador = obtenerEstadisticasEntrenador($usuario_cedula, $todos_los_entrenamientos);
}
?>

<div class="container contenido">
    
    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <div>
            <h4>
                <i class="bi bi-person-circle me-2"></i>
                Mi Perfil
            </h4>
            <p class="text-white-50 m-0 small">
                Gestiona tu información personal y configuración de cuenta
            </p>
        </div>
        <div>
            <span class="text-white-50 small">
                <i class="bi bi-clock-history me-1"></i>
                Último acceso: <?php echo date('d/m/Y H:i'); ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- ===== COLUMNA IZQUIERDA: AVATAR Y DATOS BÁSICOS ===== -->
        <div class="col-lg-4">
            <div class="perfil-card text-center">
                <div class="perfil-avatar-grande">
                    <?php 
                    echo strtoupper(substr($usuario_nombre ?? 'U', 0, 1) . substr($usuario_apellido ?? 'U', 0, 1));
                    ?>
                </div>
                <h5 class="perfil-nombre"><?php echo $usuario_nombre . ' ' . $usuario_apellido; ?></h5>
                <span class="perfil-rol">
                    <span class="badge <?php 
                        echo $usuario_rol == 'Administrador' ? 'bg-danger' : 
                             ($usuario_rol == 'Entrenador' ? 'bg-success' : 
                             ($usuario_rol == 'Secretaria' ? 'bg-info' : 'bg-primary')); 
                    ?>">
                        <?php echo $usuario_rol; ?>
                    </span>
                </span>
                <span class="perfil-cedula text-white">
                    <i class="bi bi-person-badge me-1 text-white"></i>
                    Cédula: <?php echo $usuario_cedula; ?>
                </span>
                
                <hr class="border-secondary">
                
                <div class="d-grid gap-2">
                    <button class="btn btn-cambiar-pass" data-bs-toggle="modal" data-bs-target="#modalCambiarContrasena">
                        <i class="bi bi-key me-2"></i>Cambiar Contraseña
                    </button>
                </div>
            </div>

            <!-- ===== INFORMACIÓN RÁPIDA ===== -->
        <div class="col-lg-12 align-self-start">
            <div class="info-card">
                <div class="row g-2">
                    <!-- Correo Electrónico -->
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icono" style="background: rgba(13,110,253,0.12); color: #0d6efd; width: 35px; height: 35px; min-width: 35px; font-size: 1rem;">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <div class="titulo text-white" style="font-size: 0.6rem;">Correo Electrónico</div>
                                <div class="valor" style="font-size: 0.85rem;"><?php echo $usuario_email; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Teléfono -->
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icono" style="background: rgba(25,135,84,0.12); color: #198754; width: 35px; height: 35px; min-width: 35px; font-size: 1rem;">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <div class="titulo text-white" style="font-size: 0.6rem;">Teléfono</div>
                                <div class="valor" style="font-size: 0.85rem;"><?php echo $usuario_telefono ?: 'No registrado'; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usuario -->
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icono" style="background: rgba(255,193,7,0.12); color: #ffc107; width: 35px; height: 35px; min-width: 35px; font-size: 1rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="titulo text-white" style="font-size: 0.6rem;">Usuario</div>
                                <div class="valor" style="font-size: 0.85rem;"><?php echo $usuario_usuario; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SI ES REPRESENTANTE: INFORMACIÓN ADICIONAL ===== -->
                <?php if($usuario_rol == 'Representante' && $representante_info): ?>
                    <hr class="border-secondary my-2">
                    <h6 class="text-white-50 mb-1" style="font-size: 0.75rem;">
                        <i class="bi bi-people me-1"></i>Información Adicional
                    </h6>
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-white-50" style="font-size: 0.7rem;">Ocupación:</span>
                            <span class="text-white" style="font-size: 0.8rem; display: block;"><?php echo $representante_info['representante_ocupacion'] ?? 'No registrada'; ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-white-50" style="font-size: 0.7rem;">Dirección:</span>
                            <span class="text-white" style="font-size: 0.8rem; display: block;"><?php echo $representante_info['representante_direccion'] ?? 'No registrada'; ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        </div>

        <!-- ===== COLUMNA DERECHA: FORMULARIO DE ACTUALIZACIÓN ===== -->
        <div class="col-lg-8 col-md-6 align-self-start">
            <div class="info-card">
                <h5 class="text-white mb-3">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>
                    Editar Información Personal
                </h5>
                <p class="text-white-50 small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    La cédula no puede ser modificada por razones de seguridad.
                </p>
                
                <form id="formActualizarPerfil" method="POST" action="./Controller/ActualizarPerfil_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <!-- Cédula (solo lectura) -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Cédula</label>
                            <input type="text" class="form-control custom-input" 
                                   value="<?php echo $usuario_cedula; ?>" disabled>
                        </div>
                        
                        <!-- Usuario (solo lectura) -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control custom-input" 
                                   value="<?php echo $usuario_usuario; ?>" disabled>
                        </div>

                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Nombre</label>
                            <input type="text" class="form-control custom-input" 
                                   name="nombre" value="<?php echo $usuario_nombre; ?>" 
                                   pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{2,50}" required>
                        </div>

                        <!-- Apellido -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Apellido</label>
                            <input type="text" class="form-control custom-input" 
                                   name="apellido" value="<?php echo $usuario_apellido; ?>" 
                                   pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{2,50}" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Correo Electrónico</label>
                            <input type="email" class="form-control custom-input" 
                                   name="email" value="<?php echo $usuario_email; ?>" required>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Teléfono</label>
                            <input type="tel" class="form-control custom-input" 
                                   name="telefono" value="<?php echo $usuario_telefono; ?>" 
                                   pattern="[0-9+\-\s()]{7,15}" placeholder="0412-1234567">
                        </div>


                        <!-- Botones -->
                        <div class="col-12">
                            <hr class="border-secondary">
                            <div class="d-flex gap-3 flex-wrap">
                                <button type="submit" class="btn btn-actualizar">
                                    <i class="bi bi-save me-2"></i>Actualizar Datos
                                </button>
                                <button type="reset" class="btn btn-secondary" style="border-radius:50px;padding:10px 30px;">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>Restablecer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ===== SI ES ENTRENADOR: ESTADÍSTICAS ===== -->
            <?php if($usuario_rol == 'Entrenador' && $estadisticas_entrenador): ?>
            <div class="info-card mt-3">
                <h6 class="text-white mb-3">
                    <i class="bi bi-bar-chart-fill me-2 text-warning"></i>
                    Mis Estadísticas
                </h6>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="stat-mini-card">
                            <span class="numero"><?php echo $estadisticas_entrenador['total']; ?></span>
                            <span class="etiqueta">Entrenamientos</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-mini-card">
                            <span class="numero" style="color: #198754;"><?php echo $estadisticas_entrenador['finalizados']; ?></span>
                            <span class="etiqueta">Finalizados</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-mini-card">
                            <span class="numero" style="color: #ffc107;"><?php echo $estadisticas_entrenador['en_curso']; ?></span>
                            <span class="etiqueta">En Curso</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-mini-card">
                            <span class="numero" style="color: #0d6efd;"><?php echo $estadisticas_entrenador['programados']; ?></span>
                            <span class="etiqueta">Programados</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="stat-mini-card">
                            <span class="numero" style="font-size:1.2rem;color:#ffc107;">
                                <?php echo $estadisticas_entrenador['promedio_asistencia']; ?>%
                            </span>
                            <span class="etiqueta">Promedio de Asistencia</span>
                            <small class="text-white d-block" style="font-size:0.65rem;">
                                <?php echo $estadisticas_entrenador['total_asistentes']; ?> asistentes / 
                                <?php echo $estadisticas_entrenador['total_atletas']; ?> convocados
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php include './Public/Modals/modal_cambiarContraseña.php'; ?>


<script src="./Public/Js/MiPerfil.js"></script>