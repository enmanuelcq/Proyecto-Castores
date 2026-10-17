<?php
// Controller/RegistrarUsuarioRepresentante_controller.php
// Registra un nuevo usuario tipo Representante

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryUsuario.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: text/html');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> Método no permitido
        </div>
    ';
    exit;
}

// Validar CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> Token CSRF inválido
        </div>
    ';
    exit;
}

// Obtener y limpiar datos
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Validaciones
$errores = [];

if (empty($cedula)) {
    $errores[] = 'La cédula es requerida';
}

if (empty($username) || !preg_match('/^[a-zA-Z0-9.,]{4,30}$/', $username)) {
    $errores[] = 'El nombre de usuario debe tener entre 4 y 30 caracteres alfanuméricos';
}

if (empty($password) || !preg_match('/^[a-zA-Z0-9$@#.]{7,99}$/', $password)) {
    $errores[] = 'La contraseña debe tener entre 7 y 99 caracteres';
}

if ($password !== $confirm_password) {
    $errores[] = 'Las contraseñas no coinciden';
}

if (!empty($errores)) {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> ' . implode(', ', $errores) . '
        </div>
    ';
    exit;
}

// Verificar que la cédula existe en la tabla representante
$repoRepresentante = new RepositoryRepresentante();
$representante = $repoRepresentante->findByCedula($cedula);

if (!$representante) {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> La cédula no está registrada como representante. Contacte a la administración.
        </div>
    ';
    exit;
}

// Verificar que el username no esté en uso
$repoUsuario = new RepositoryUsuario();
$usuarioExistente = $repoUsuario->findByUsername($username);

if ($usuarioExistente) {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> El nombre de usuario ya está en uso
        </div>
    ';
    exit;
}

// Verificar que la cédula no tenga ya un usuario
$usuarioPorCedula = $repoUsuario->findByCedula($cedula);
if ($usuarioPorCedula) {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> Esta cédula ya tiene un usuario registrado
        </div>
    ';
    exit;
}

// Crear el nuevo usuario
require_once $root . '/Model/Entities/Usuarios.php';

$nuevoUsuario = new Usuarios(
    'rep_' . uniqid(),
    $cedula,
    $username,
    password_hash($password, PASSWORD_DEFAULT),
    $representante->getNombre(),
    $representante->getApellido(),
    $representante->getCorreo(),
    $representante->getTelefono(),
    true, // activo
    4 // rol_id = Representante
);

if ($repoUsuario->create($nuevoUsuario)) {
    echo '
        <div class="alert alert-success py-2 small text-start" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>¡Registro exitoso!</strong> Tu cuenta ha sido creada. Redirigiendo al login...
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "index.php?View=Login";
            }, 2000);
        </script>
    ';
} else {
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> No se pudo crear el usuario. Intente nuevamente.
        </div>
    ';
}
exit;
