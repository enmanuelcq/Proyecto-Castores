<?php
    require_once ROOT_PATH . '/Model/Entities/Usuarios.php';
    require_once ROOT_PATH . '/Model/Interfaces/IUsuarioRepository.php';
    require_once ROOT_PATH . '/Model/Repository/RepositoryUsuario.php';
    require_once ROOT_PATH . '/Config/Database.php';

    
?>
<div class="dashboard-header text-center mb-5">
    <div class="container">
        <h1 class="fw-bold tracking-wide m-0" style="font-size: 2rem;">GESTIÓN DE TALENTO LOS CASTORES</h1>
        <p class="lead text-white-50 m-0 mt-1" style="font-size: 1rem;">
            Supervisa los roles de quienes hacen posible que nuestros jugadores brillen cada día en la cancha.
        </p>
    </div>
</div>

<div class="container mb-5" style="max-width: 900px;">

    <?php if(isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo htmlspecialchars($_GET['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?php if(isset($_GET['mensaje'])): ?>
            <script>
                setTimeout(function() {
                    window.location.href = "index.php?View=Gestion_usuarios";
                }, 1500); // Redirigir después de 1.5 segundos
            </script>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center mb-5">
        <ul class="nav nav-pills nav-dash bg-dark bg-opacity-50 p-2 rounded-5 shadow-sm" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="registrar-tab" data-bs-toggle="tab" data-bs-target="#registrar-pane" type="button" role="tab" aria-controls="registrar-pane" aria-selected="true">
                    ➕ Registrar Usuario
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="consultar-tab" data-bs-toggle="tab" data-bs-target="#consultar-pane" type="button" role="tab" aria-controls="consultar-pane" aria-selected="false">
                    🔍 Consultar Usuarios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="desactivados-tab" data-bs-toggle="tab" data-bs-target="#desactivados-pane" type="button" role="tab" aria-controls="desactivados-pane" aria-selected="false">
                    🚫 Desactivados
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="dashboardTabsContent">
        
        <!-- Pestaña Registrar -->
        <div class="tab-pane fade show active" id="registrar-pane" role="tabpanel" aria-labelledby="registrar-tab" tabindex="0">
            <header class="register-header d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="register-title text-uppercase m-0">Registrar Nuevo Usuario</h1>
                    <p class="register-subtitle m-0">Cada registro es un paso más para fortalecer la academia. ¿A quién sumaremos hoy?</p>
                </div>
            </header>

            <form action="./Controller/RegistrarUsuario_controller.php" method="POST" class="FormularioAjax" id="RegistroUsuarioForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="card form-section-card">
                    <h2 class="section-card-title"><i class="bi bi-person-fill me-2"></i>Datos Personales</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="custom-form-label">Nombres</label>
                            <input type="text" class="form-control custom-input" name="nombre" id="nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required placeholder="Nombres completos">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Apellidos</label>
                            <input type="text" class="form-control custom-input" name="apellido" id="apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required placeholder="Apellidos completos">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Cédula</label>
                            <input type="text" class="form-control custom-input" name="cedula" id="cedula" pattern="[0-9]{5,10}" maxlength="10" required placeholder="Ej. 12345678">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Teléfono</label>
                            <input type="tel" class="form-control custom-input" name="telefono" id="telefono" pattern="[\d\s\-]{7,15}" maxlength="20" required placeholder="Ej. 0412-1234567">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="custom-form-label">Email</label>
                            <input type="email" class="form-control custom-input" name="email" id="email" maxlength="70" required placeholder="ejemplo@correo.com">
                            <div class="field-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="card form-section-card">
                    <h2 class="section-card-title"><i class="bi bi-shield-lock-fill me-2"></i>Datos de Cuenta</h2>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="custom-form-label">Nombre de usuario</label>
                            <input type="text" class="form-control custom-input usuario-input" name="usuario" id="usuario" pattern="[a-zA-Z0-9.,]{4,30}" maxlength="30" required placeholder="Nombre de usuario para iniciar sesión">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Contraseña</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control custom-input" name="clave_1" id="clave_1" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100" required placeholder="Mínimo 7 caracteres">
                                <i class="bi bi-eye password-toggle-icon" data-target="clave_1"></i>
                            </div>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Confirmar contraseña</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control custom-input" name="clave_2" id="clave_2" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100" required placeholder="Repita la contraseña">
                                <i class="bi bi-eye password-toggle-icon" data-target="clave_2"></i>
                            </div>
                            <div class="field-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="card form-section-card">
                    <h2 class="section-card-title"><i class="bi bi-key-fill me-2"></i>Asignar Rol</h2>
                    <div class="text-center py-3">
                        <div class="role-capsule shadow">
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="rol" id="rolAdmin" value="1" checked>
                                <label class="form-check-label ms-2" for="rolAdmin">Administrador</label>
                            </div>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="rol" id="rolSecretaria" value="2">
                                <label class="form-check-label ms-2" for="rolSecretaria">Secretaria</label>
                            </div>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio" name="rol" id="rolEntrenador" value="3">
                                <label class="form-check-label ms-2" for="rolEntrenador">Entrenador</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark-green px-5 py-2 fs-5 rounded-3 shadow">
                        <i class="bi bi-check-circle me-1"></i> Guardar Usuario
                    </button>
                </div>
            </form>

            <div class="form-rest"></div>
        </div>

        <!-- Pestaña Consultar -->
        <div class="tab-pane fade" id="consultar-pane" role="tabpanel" aria-labelledby="consultar-tab" tabindex="0">
            <div class="mb-4">
                <h3 class="fw-bold h4 mb-4">Consultar Usuarios</h3>
            </div>

            <div class="user-list">
                <?php
                $repo = new RepositoryUsuario();
                $todos_los_usuarios = $repo->findAll();
                $roles = [1 => 'Administrador', 2 => 'Secretaria', 3 => 'Entrenador'];
                foreach ($todos_los_usuarios as $usuario):
                    if($usuario->getStatus() === true):
                    if ($usuario->getUsername() != $_SESSION['usuario']['username']):
                    $rolID = $usuario->getIdRol();
                ?>
                <div class="user-card shadow-sm">
                    <div class="user-info">
                        <?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?>:
                        <?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#modalVer<?= $usuario->getId() ?>">Ver Información</button>
                        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#modalActualizar<?= $usuario->getId() ?>">Actualizar</button>
                        <button class="btn btn-custom-red" data-bs-toggle="modal" data-bs-target="#modalDesactivar<?= $usuario->getId() ?>">Desactivar</button>
                    </div>
                </div>

                <!-- Modal Ver -->
                <div class="modal fade" id="modalVer<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase"><i class="bi bi-person-badge me-2"></i>Información del Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card form-section-card">
                                    <h2 class="section-card-title"><i class="bi bi-person-fill me-2"></i>Datos Personales</h2>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item"><span class="label-title">Nombre</span><span class="value"><?= htmlspecialchars($usuario->getNombre()) ?></span></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item"><span class="label-title">Apellido</span><span class="value"><?= htmlspecialchars($usuario->getApellido()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Cédula</span><span class="value"><?= htmlspecialchars($usuario->getCedula()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Teléfono</span><span class="value"><?= htmlspecialchars($usuario->getTelefono()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Email</span><span class="value"><?= htmlspecialchars($usuario->getEmail()) ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card form-section-card">
                                    <h2 class="section-card-title"><i class="bi bi-shield-fill me-2"></i>Cuenta y Rol</h2>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Usuario</span><span class="value"><?= htmlspecialchars($usuario->getUsername()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Rol</span><span class="value"><?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Estado</span><span class="value text-success fw-bold">Activo</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-action-green px-4" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Actualizar -->
                <div class="modal fade" id="modalActualizar<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase"><i class="bi bi-pencil-square me-2"></i>Actualizar Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="./Controller/ActualizarUsuario_controller.php" method="POST" class="FormularioAjax actualizar-form" id="actualizarForm<?= $usuario->getId() ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">

                                    <div class="card form-section-card">
                                        <h2 class="section-card-title"><i class="bi bi-person-fill me-2"></i>Datos Personales</h2>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Nombres</label>
                                                <input type="text" class="form-control custom-input" name="nombre" value="<?= htmlspecialchars($usuario->getNombre()) ?>" maxlength="40" required>
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Apellidos</label>
                                                <input type="text" class="form-control custom-input" name="apellido" value="<?= htmlspecialchars($usuario->getApellido()) ?>" maxlength="40" required>
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Cédula</label>
                                                <input type="text" class="form-control custom-input" name="cedula" value="<?= htmlspecialchars($usuario->getCedula()) ?>" maxlength="10" required>
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Teléfono</label>
                                                <input type="tel" class="form-control custom-input" name="telefono" value="<?= htmlspecialchars($usuario->getTelefono()) ?>" maxlength="20" required>
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="custom-form-label">Email</label>
                                                <input type="email" class="form-control custom-input" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" maxlength="70">
                                                <div class="field-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card form-section-card">
                                        <h2 class="section-card-title"><i class="bi bi-shield-lock-fill me-2"></i>Datos de Cuenta</h2>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="custom-form-label">Nombre de usuario</label>
                                                <input type="text" class="form-control custom-input usuario-input" name="usuario" value="<?= htmlspecialchars($usuario->getUsername()) ?>" maxlength="30" required data-user-id="<?= $usuario->getId() ?>">
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Contraseña (dejar vacío para no cambiar)</label>
                                                <div class="password-wrapper">
                                                    <input type="password" class="form-control custom-input" name="clave_1" id="clave_1_<?= $usuario->getId() ?>" maxlength="100">
                                                    <i class="bi bi-eye password-toggle-icon" data-target="clave_1_<?= $usuario->getId() ?>"></i>
                                                </div>
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Confirmar contraseña</label>
                                                <div class="password-wrapper">
                                                    <input type="password" class="form-control custom-input" name="clave_2" id="clave_2_<?= $usuario->getId() ?>" maxlength="100">
                                                    <i class="bi bi-eye password-toggle-icon" data-target="clave_2_<?= $usuario->getId() ?>"></i>
                                                </div>
                                                <div class="field-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card form-section-card">
                                        <h2 class="section-card-title"><i class="bi bi-key-fill me-2"></i>Asignar Rol</h2>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Rol</label>
                                                <select class="form-select custom-input" name="rol">
                                                    <option value="1" <?= $usuario->getIdRol() == 1 ? 'selected' : '' ?>>Administrador</option>
                                                    <option value="2" <?= $usuario->getIdRol() == 2 ? 'selected' : '' ?>>Secretaria</option>
                                                    <option value="3" <?= $usuario->getIdRol() == 3 ? 'selected' : '' ?>>Entrenador</option>
                                                </select>
                                                <div class="field-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-form-label">Estado</label>
                                                <select class="form-select custom-input" name="status">
                                                    <option value="1" <?= $usuario->getStatus() ? 'selected' : '' ?>>Activo</option>
                                                    <option value="0" <?= !$usuario->getStatus() ? 'selected' : '' ?>>Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-action-green rounded-pill px-4"><i class="bi bi-check-circle me-1"></i> Actualizar Usuario</button>
                                    </div>
                                </form>
                                <div class="form-rest mt-3"></div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-action-green px-4" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Desactivar -->
                <div class="modal fade" id="modalDesactivar<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase"><i class="bi bi-exclamation-triangle-fill me-2"></i>Desactivar Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card form-section-card">
                                    <p class="fw-bold text-center mb-3">¿Está seguro de desactivar a este usuario?</p>
                                    <div class="inner-white-card">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="fw-bold m-0"><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></h4>
                                                <p class="m-0 text-muted"><?= htmlspecialchars($usuario->getCedula()) ?></p>
                                                <span class="badge bg-primary px-3 py-2 mt-2"><?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <button class="btn btn-action-green px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                        <form action="./Controller/DesactivarUsuario_controller.php" method="POST" class="FormularioAjax1 d-inline" id="desactivarForm<?= $usuario->getId() ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                            <button type="submit" class="btn btn-action-red px-4 rounded-pill fw-bold">Sí, Desactivar</button>
                                        </form>
                                    </div>
                                    <div class="form-rest1 mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pestaña Desactivados -->
        <div class="tab-pane fade" id="desactivados-pane" role="tabpanel" aria-labelledby="desactivados-tab" tabindex="0">
            <div class="mb-4">
                <h3 class="fw-bold h4 mb-4">Usuarios Desactivados</h3>
            </div>

            <div class="user-list">
                <?php
                $desactivados = $repo->findAll();
                $hayDesactivados = false;
                foreach ($desactivados as $usuario):
                    if ($usuario->getStatus() === false && $usuario->getUsername() != $_SESSION['usuario']['username']):
                        $hayDesactivados = true;
                        $rolID = $usuario->getIdRol();
                        $roles = [1 => 'Administrador', 2 => 'Secretaria', 3 => 'Entrenador'];
                ?>
                <div class="user-card shadow-sm">
                    <div class="user-info">
                        <?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?>:
                        <?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?>
                        <span class="badge bg-danger ms-2">Desactivado</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#modalVerDes<?= $usuario->getId() ?>">Ver Información</button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalReactivar<?= $usuario->getId() ?>">Reactivar</button>
                    </div>
                </div>

                <!-- Modal Ver Desactivado -->
                <div class="modal fade" id="modalVerDes<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase"><i class="bi bi-person-badge me-2"></i>Información del Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card form-section-card">
                                    <h2 class="section-card-title"><i class="bi bi-person-fill me-2"></i>Datos Personales</h2>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item"><span class="label-title">Nombre</span><span class="value"><?= htmlspecialchars($usuario->getNombre()) ?></span></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item"><span class="label-title">Apellido</span><span class="value"><?= htmlspecialchars($usuario->getApellido()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Cédula</span><span class="value"><?= htmlspecialchars($usuario->getCedula()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Teléfono</span><span class="value"><?= htmlspecialchars($usuario->getTelefono()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Email</span><span class="value"><?= htmlspecialchars($usuario->getEmail()) ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card form-section-card">
                                    <h2 class="section-card-title"><i class="bi bi-shield-fill me-2"></i>Cuenta y Rol</h2>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Usuario</span><span class="value"><?= htmlspecialchars($usuario->getUsername()) ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Rol</span><span class="value"><?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item"><span class="label-title">Estado</span><span class="value text-danger fw-bold">Desactivado</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-action-green px-4" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Reactivar -->
                <div class="modal fade" id="modalReactivar<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase"><i class="bi bi-arrow-counterclockwise me-2"></i>Reactivar Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card form-section-card">
                                    <p class="fw-bold text-center mb-3">¿Está seguro de reactivar a este usuario?</p>
                                    <div class="inner-white-card">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="fw-bold m-0"><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></h4>
                                                <p class="m-0 text-muted"><?= htmlspecialchars($usuario->getCedula()) ?></p>
                                                <span class="badge bg-danger px-3 py-2 mt-2"><?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <button class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                        <form action="./Controller/ReactivarUsuario_controller.php" method="POST" class="FormularioAjax1 d-inline" id="reactivarForm<?= $usuario->getId() ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                            <button type="submit" class="btn btn-action-green px-4 rounded-pill fw-bold">Sí, Reactivar</button>
                                        </form>
                                    </div>
                                    <div class="form-rest1 mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; ?>

                <?php if (!$hayDesactivados): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold mt-3 text-muted">No hay usuarios desactivados</h4>
                        <p class="text-muted">Todos los usuarios están activos en el sistema.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="/Castores3/Public/Js/Gestion_usuarios.js"></script>
