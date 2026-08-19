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
            <header class="mb-4">
                <h2 class="fw-bold text-uppercase h3">¡HOLA! Bienvenido de nuevo</h2>
                <p class="text-white-80">Cada registro es un paso más para fortalecer la academia. ¿A quién sumaremos hoy?</p>
            </header>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold fs-5 m-0 text-white-50">Llena los siguientes datos</h3>
            </div>

            <form action="./Controller/RegistrarUsuario_controller.php" method="POST" class="FormularioAjax" id="RegistroUsuarioForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombres</label>
                        <input type="text" class="form-control custom-input" name="nombre" id="nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Apellidos</label>
                        <input type="text" class="form-control custom-input" name="apellido" id="apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Usuario</label>
                        <input type="text" class="form-control custom-input" name="usuario" id="usuario" pattern="[a-zA-Z0-9.,]{4,30}" maxlength="30" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control custom-input" name="email" id="email" maxlength="70">
                    </div>
                    <div class="col-md-6 position-relative">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input type="password" class="form-control custom-input" name="clave_1" id="clave_1" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100" required>
                        <i class="bi bi-eye password-toggle-icon" data-target="clave_1"></i>
                    </div>
                    <div class="col-md-6 position-relative">
                        <label class="form-label fw-semibold">Confirme contraseña</label>
                        <input type="password" class="form-control custom-input" name="clave_2" id="clave_2" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100" required>
                        <i class="bi bi-eye password-toggle-icon" data-target="clave_2"></i>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="tel" class="form-control custom-input" name="telefono" id="telefono" pattern="[\d\s\-]{7,15}" maxlength="20" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Cédula</label>
                        <input type="text" class="form-control custom-input" name="cedula" id="cedula" pattern="[0-9]{5,10}" maxlength="10" required>
                    </div>
                </div>

                <div class="text-center my-5">
                    <h4 class="fw-bold fs-5 mb-3">Seleccionar rol</h4>
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

                <div class="text-center">
                    <button type="submit" class="btn btn-dark-green px-5 py-2 fs-5 rounded-3 shadow">Guardar Usuario</button>
                </div>
            </form>

            <div class="form-rest"></div>
        </div>

        <!-- Pestaña Consultar -->
        <div class="tab-pane fade" id="consultar-pane" role="tabpanel" aria-labelledby="consultar-tab" tabindex="0">
            <div class="mb-4">
                <h3 class="fw-bold h4 mb-4">Consultar Usuario</h3>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <h3 class="user-info">
                        <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') . ' ' . htmlspecialchars($_SESSION['usuario']['apellido'] ?? '') ?>
                    </h3>
                <?php endif; ?>
            </div>

            <div class="user-list">
                <?php
                $repo = new RepositoryUsuario();
                $todos_los_usuarios = $repo->findAll();
                foreach ($todos_los_usuarios as $usuario):
                    if($usuario->getStatus() === true):
                    // Saltar el usuario actual (el que está logueado)
                    if ($usuario->getUsername() != $_SESSION['usuario']['username']):
                        
                    $rolID = $usuario->getIdRol();
                    $roles = [1 => 'Administrador', 2 => 'Secretaria', 3 => 'Entrenador'];
                    
                ?>
                <div class="user-card shadow-sm">
                    <div class="user-info">
                        <?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?>:
                        <?= htmlspecialchars($roles[$rolID]) ?>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#modalVer<?= $usuario->getId() ?>">Ver Información</button>
                        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#modalActualizar<?= $usuario->getId() ?>">Actualizar</button>
                        <button class="btn btn-custom-red bg-danger bg-opacity-75" data-bs-toggle="modal" data-bs-target="#modalDesactivar<?= $usuario->getId() ?>">Desactivar</button>
                    </div>
                </div>

                <!-- Modal Ver -->
                <div class="modal fade" id="modalVer<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase">📋 Información del Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="inner-white-card shadow-sm">
                                    <div class="row g-3">
                                        <div class="col-md-6"><h6><strong>Nombre:</strong> <?= htmlspecialchars($usuario->getNombre()) ?></h6></div>
                                        <div class="col-md-6"><h6><strong>Apellido:</strong> <?= htmlspecialchars($usuario->getApellido()) ?></h6></div>
                                        <hr class="my-2">
                                        <div class="col-md-4"><p class="m-0"><strong>Usuario:</strong> <?= htmlspecialchars($usuario->getUsername()) ?></p></div>
                                        <div class="col-md-4"><p class="m-0"><strong>Cédula:</strong> <?= htmlspecialchars($usuario->getCedula()) ?></p></div>
                                        <div class="col-md-4"><p class="m-0"><strong>Email:</strong> <?= htmlspecialchars($usuario->getEmail()) ?></p></div>
                                        <hr class="my-2">
                                        <div class="col-md-6"><p class="m-0"><strong>Rol:</strong> <?= htmlspecialchars($usuario->getIdRol()) ?></p></div>
                                        <div class="col-md-6"><p class="m-0"><strong>Teléfono:</strong> <?= htmlspecialchars($usuario->getTelefono()) ?></p></div>
                                        <div class="col-md-6"><p class="m-0"><strong>Status:</strong> <?= $usuario->getStatus() ? 'Activo' : 'Inactivo' ?></p></div>
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
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold text-uppercase">✏️ Actualizar Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="./Controller/ActualizarUsuario_controller.php" method="POST" class="FormularioAjax" id="actualizarForm<?= $usuario->getId() ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nombres</label>
                                            <input type="text" class="form-control custom-input" name="nombre" value="<?= htmlspecialchars($usuario->getNombre()) ?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Apellidos</label>
                                            <input type="text" class="form-control custom-input" name="apellido" value="<?= htmlspecialchars($usuario->getApellido()) ?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Usuario</label>
                                            <input type="text" class="form-control custom-input" name="usuario" value="<?= htmlspecialchars($usuario->getUsername()) ?>" pattern="[a-zA-Z0-9.,]{4,30}" maxlength="30" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" class="form-control custom-input" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" maxlength="70">
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="form-label fw-semibold">Contraseña (dejar vacío para no cambiar)</label>
                                            <input type="password" class="form-control custom-input" name="clave_1" id="clave_1_<?= $usuario->getId() ?>" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100">
                                            <i class="bi bi-eye password-toggle-icon" data-target="clave_1_<?= $usuario->getId() ?>"></i>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="form-label fw-semibold">Confirmar contraseña</label>
                                            <input type="password" class="form-control custom-input" name="clave_2" id="clave_2_<?= $usuario->getId() ?>" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100">
                                            <i class="bi bi-eye password-toggle-icon" data-target="clave_2_<?= $usuario->getId() ?>"></i>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Teléfono</label>
                                            <input type="tel" class="form-control custom-input" name="telefono" value="<?= htmlspecialchars($usuario->getTelefono()) ?>" pattern="[\d\s\-]{7,15}" maxlength="20" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Cédula</label>
                                            <input type="text" class="form-control custom-input" name="cedula" value="<?= htmlspecialchars($usuario->getCedula()) ?>" pattern="[0-9]{5,10}" maxlength="10" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Status</label>
                                            <select class="form-control custom-input" name="status">
                                                <option value="1" <?= $usuario->getStatus() ? 'selected' : '' ?>>Activo</option>
                                                <option value="0" <?= !$usuario->getStatus() ? 'selected' : '' ?>>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-center my-5">
                                        <h4 class="fw-bold fs-5 mb-3">Seleccionar rol</h4>
                                        <div class="role-capsule shadow">
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="rol" id="rolAdmin_<?= $usuario->getId() ?>" value="1" <?= $usuario->getIdRol() == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label ms-2" for="rolAdmin_<?= $usuario->getId() ?>">Administrador</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="rol" id="rolSecretaria_<?= $usuario->getId() ?>" value="2" <?= $usuario->getIdRol() == 2 ? 'checked' : '' ?>>
                                                <label class="form-check-label ms-2" for="rolSecretaria_<?= $usuario->getId() ?>">Secretaria</label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio" name="rol" id="rolEntrenador_<?= $usuario->getId() ?>" value="3" <?= $usuario->getIdRol() == 3 ? 'checked' : '' ?>>
                                                <label class="form-check-label ms-2" for="rolEntrenador_<?= $usuario->getId() ?>">Entrenador</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark-green px-5 py-2 fs-5 rounded-3 shadow">Actualizar Usuario</button>
                                    </div>
                                </form>
                                <div class="form-rest"></div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-action-green px-4" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar -->
                <div class="modal fade" id="modalDesactivar<?= $usuario->getId() ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header border-0 text-center w-100 d-block">
                                <h4 class="modal-title fw-bold text-white text-center">¿Está seguro de desactivar a este Usuario?</h4>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-center gap-4 mb-4">
                                    <button class="btn btn-success px-4 rounded-pill fw-bold" data-bs-dismiss="modal">NO</button>
                                    <form action="./Controller/DesactivarUsuario_controller.php" method="POST" class="FormularioAjax1" id="desactivarForm<?= $usuario->getId() ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                        <button type="submit" class="btn btn-warning px-4 rounded-pill fw-bold">SI, DESACTIVAR</button>
                                    </form>
                                </div>
                                <!-- Contenedor para mensajes de respuesta -->
                                <div class="form-rest1 mt-3"></div>

                                <div class="inner-white-card">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h4 class="fw-bold m-0"><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></h4>
                                            <p class="fs-5 m-0 text-muted"><?= htmlspecialchars($usuario->getCedula()) ?></p>
                                            <span class="badge bg-dark px-3 py-2 mt-2"><?= htmlspecialchars($usuario->getIdRol()) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center rounded fw-bold" 
                                            style="width: 100px; height: 100px; background-color: #000; color: #fff; font-size: 1.1rem;">
                                            Foto
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="text-center mt-5">
                    <button class="btn btn-dark-green px-4 py-2 rounded-5" onclick="document.getElementById('registrar-tab').click()">
                        Volver al inicio
                    </button>
                </div>
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
                                <h5 class="modal-title fw-bold text-uppercase">📋 Información del Usuario</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="inner-white-card shadow-sm">
                                    <div class="row g-3">
                                        <div class="col-md-6"><h6><strong>Nombre:</strong> <?= htmlspecialchars($usuario->getNombre()) ?></h6></div>
                                        <div class="col-md-6"><h6><strong>Apellido:</strong> <?= htmlspecialchars($usuario->getApellido()) ?></h6></div>
                                        <hr class="my-2">
                                        <div class="col-md-4"><p class="m-0"><strong>Usuario:</strong> <?= htmlspecialchars($usuario->getUsername()) ?></p></div>
                                        <div class="col-md-4"><p class="m-0"><strong>Cédula:</strong> <?= htmlspecialchars($usuario->getCedula()) ?></p></div>
                                        <div class="col-md-4"><p class="m-0"><strong>Email:</strong> <?= htmlspecialchars($usuario->getEmail()) ?></p></div>
                                        <hr class="my-2">
                                        <div class="col-md-6"><p class="m-0"><strong>Rol:</strong> <?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?></p></div>
                                        <div class="col-md-6"><p class="m-0"><strong>Teléfono:</strong> <?= htmlspecialchars($usuario->getTelefono()) ?></p></div>
                                        <div class="col-md-6"><p class="m-0"><strong>Status:</strong> <span class="text-danger fw-bold">Desactivado</span></p></div>
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
                            <div class="modal-header border-0 text-center w-100 d-block">
                                <h4 class="modal-title fw-bold text-white text-center">¿Está seguro de reactivar a este usuario?</h4>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-center gap-4 mb-4">
                                    <button class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">NO</button>
                                    <form action="./Controller/ReactivarUsuario_controller.php" method="POST" class="FormularioAjax1" id="reactivarForm<?= $usuario->getId() ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold">SI, REACTIVAR</button>
                                    </form>
                                </div>
                                <div class="form-rest1 mt-3"></div>
                                <div class="inner-white-card">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h4 class="fw-bold m-0"><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></h4>
                                            <p class="fs-5 m-0 text-muted"><?= htmlspecialchars($usuario->getCedula()) ?></p>
                                            <span class="badge bg-danger px-3 py-2 mt-2"><?= htmlspecialchars($roles[$rolID] ?? 'Desconocido') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; ?>

                <?php if (!$hayDesactivados): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold mt-3 text-white-50">No hay usuarios desactivados</h4>
                        <p class="text-white-50">Todos los usuarios están activos en el sistema.</p>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-5">
                    <button class="btn btn-dark-green px-4 py-2 rounded-5" onclick="document.getElementById('registrar-tab').click()">
                        Volver al inicio
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/Castores3/Public/Js/Gestion_usuarios.js"></script>
