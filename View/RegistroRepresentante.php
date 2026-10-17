<?php
// ============================================
// /View/RegistroRepresentante.php
// Registro de usuario para representantes
// ============================================

$root = dirname(__DIR__);

// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cargar helpers
require_once $root . '/Config/main.php';
require_once $root . '/Model/Entities/Usuarios.php';

// Verificar si ya está logueado
if (isset($_SESSION['usuario'])) {
    header('Location: index.php?View=home');
    exit;
}

// PASO 1: Validar cédula
$paso = 1;
$representante = null;
$error = '';
$success = '';

// Función auxiliar para normalizar cédula
function normalizarCedula($cedula) {
    // Convertir a mayúsculas y eliminar espacios
    $cedula = strtoupper(trim($cedula));
    $cedula = preg_replace('/[\s]/', '', $cedula);
    return $cedula;
}

// Procesar validación de cédula
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validar_cedula'])) {
    $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
    
    if (!empty($cedula)) {
        require_once $root . '/Model/Repository/RepositoryRepresentante.php';
        $repo = new RepositoryRepresentante();
        
        // Normalizar cédula ingresada
        $cedula_norm = normalizarCedula($cedula);
        
        // Buscar con diferentes formatos posibles
        $representante = null;
        
        // Intentar con la cédula tal como se ingresó (normalizada)
        $representante = $repo->findByCedula($cedula_norm);
        
        // Si no se encuentra, intentar sin guion
        if (!$representante) {
            $cedula_sin_guion = str_replace('-', '', $cedula_norm);
            $representante = $repo->findByCedula($cedula_sin_guion);
        }
        
        // Si aún no se encuentra, intentar con V- prefijo
        if (!$representante && !str_starts_with($cedula_norm, 'V')) {
            $representante = $repo->findByCedula('V-' . $cedula_norm);
            if (!$representante) {
                $representante = $repo->findByCedula('V' . $cedula_norm);
            }
        }
        
        // Si aún no se encuentra, intentar solo números con V- (para cédulas como "12345678")
        if (!$representante) {
            $solo_numeros = preg_replace('/[^0-9]/', '', $cedula_norm);
            if ($solo_numeros) {
                $representante = $repo->findByCedula('V-' . $solo_numeros);
                if (!$representante) {
                    $representante = $repo->findByCedula('V' . $solo_numeros);
                }
            }
        }
        
        if ($representante) {
            $paso = 2;
            $success = 'Representante encontrado. Complete los datos para crear su cuenta.';
        } else {
            $error = 'La cédula no se encuentra registrada en el sistema. Contacte a la administración.';
        }
    } else {
        $error = 'Ingrese su cédula para continuar';
    }
}

// Procesar registro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_usuario'])) {
    $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validaciones
    if (empty($cedula)) {
        $error = 'La cédula es requerida';
        $paso = 1;
    } elseif (empty($username) || !preg_match('/^[a-zA-Z0-9.,]{4,30}$/', $username)) {
        $error = 'El nombre de usuario debe tener entre 4 y 30 caracteres alfanuméricos';
        $paso = 2;
    } elseif (empty($password) || !preg_match('/^[a-zA-Z0-9$@#.]{7,99}$/', $password)) {
        $error = 'La contraseña debe tener entre 7 y 99 caracteres';
        $paso = 2;
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
        $paso = 2;
    } else {
        require_once $root . '/Model/Repository/RepositoryRepresentante.php';
        require_once $root . '/Model/Repository/RepositoryUsuario.php';
        
        $repoRepresentante = new RepositoryRepresentante();
        $representante = $repoRepresentante->findByCedula($cedula);
        
        if (!$representante) {
            $error = 'La cédula no está registrada como representante';
            $paso = 1;
        } else {
            $repoUsuario = new RepositoryUsuario();
            
            // Verificar username único
            if ($repoUsuario->findByUsername($username)) {
                $error = 'El nombre de usuario ya está en uso';
                $paso = 2;
            } elseif ($repoUsuario->findByCedula($cedula)) {
                $error = 'Esta cédula ya tiene un usuario registrado';
                $paso = 2;
            } else {
                // Crear usuario
                $nuevoUsuario = new Usuario(
                    'rep_' . uniqid(),
                    $cedula,
                    $username,
                    '', // Contraseña vacía initially
                    $representante->getNombre(),
                    $representante->getApellido(),
                    $representante->getCorreo(),
                    $representante->getTelefono(),
                    true,
                    4 // rol_id = Representante
                );
                // Hashear la contraseña correctamente
                $nuevoUsuario->setPassword($password);
                
                if ($repoUsuario->create($nuevoUsuario)) {
                    $paso = 3;
                    $success = '¡Cuenta creada exitosamente! Ya puede iniciar sesión.';
                } else {
                    $error = 'Error al crear el usuario. Intente nuevamente.';
                    $paso = 2;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Representante - Deportivo Los Castores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="Public/css/RegistroRepresentante.css">
    <link rel="stylesheet" href="Public/css/Styles.css">
</head>
<body>
    <div class="login-background">
        <div class="login-card text-center">
            
            <img src="Public/img/logo.png" alt="Logo Los Castores" class="brand-logo mb-2">
            <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px;">Deportivo Los Castores</h5>
            <p class="text-muted small mb-4">Academia de Fútbol</p>

            <!-- PASO 1: Validar Cédula -->
            <?php if ($paso == 1): ?>
            <h2 class="text-start">Registro de Representante</h2>
            <p class="text-start subtitle-welcome">Ingrese su cédula para validar sus datos</p>

            <?php if ($error): ?>
            <div class="alert alert-danger py-2 small text-start">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="index.php?View=RegistroRepresentante" class="text-start">
                <input type="hidden" name="validar_cedula" value="1">
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula de Identidad</label>
                    <input type="text" name="cedula" id="cedula" class="form-control form-control-custom" placeholder="Ej: V-12345678" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn-submit-login">
                        Validar Cédula <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>

            <!-- PASO 2: Completar Registro -->
            <?php elseif ($paso == 2 && $representante): ?>
            <h2 class="text-start">Completar Registro</h2>
            <p class="text-start subtitle-welcome">Verifique sus datos y cree su usuario</p>

            <?php if ($success): ?>
            <div class="alert alert-success py-2 small text-start">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
            </div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-danger py-2 small text-start">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
            </div>
            <?php endif; ?>

            <div class="representante-info mb-3 text-start">
                <div class="info-row"><strong>Nombre:</strong> <?php echo htmlspecialchars($representante->getNombre() . ' ' . $representante->getApellido()); ?></div>
                <div class="info-row"><strong>Cédula:</strong> <?php echo htmlspecialchars($representante->getCedula()); ?></div>
                <div class="info-row"><strong>Correo:</strong> <?php echo htmlspecialchars($representante->getCorreo()); ?></div>
                <div class="info-row"><strong>Teléfono:</strong> <?php echo htmlspecialchars($representante->getTelefono()); ?></div>
            </div>

            <form method="POST" action="index.php?View=RegistroRepresentante" class="text-start">
                <input type="hidden" name="registrar_usuario" value="1">
                <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($representante->getCedula()); ?>">
                
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario</label>
                    <input type="text" name="username" id="username" class="form-control form-control-custom" placeholder="Ej: juan.perez" pattern="[a-zA-Z0-9.,]{4,30}" maxlength="30" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control form-control-custom w-100" placeholder="••••••••" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100" required>
                        <i class="bi bi-eye password-toggle-icon" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-custom w-100" placeholder="••••••••" required>
                        <i class="bi bi-eye password-toggle-icon" onclick="togglePassword('confirm_password')"></i>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn-submit-login">
                        Crear Cuenta <i class="bi bi-check-lg"></i>
                    </button>
                </div>
            </form>

            <!-- PASO 3: Éxito -->
            <?php elseif ($paso == 3): ?>
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h2 class="text-start">¡Registro Exitoso!</h2>
            <p class="text-start subtitle-welcome">Su cuenta ha sido creada correctamente</p>
            
            <div class="d-grid">
                <a href="index.php?View=Login" class="btn-submit-login text-decoration-none">
                    Ir al Login <i class="bi bi-box-arrow-in-right"></i>
                </a>
            </div>
            <?php endif; ?>

            <div class="form-rest"></div>

            <div class="register-footer">
                <a href="index.php?View=Login"><i class="bi bi-arrow-left me-1"></i> Volver al Login</a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
