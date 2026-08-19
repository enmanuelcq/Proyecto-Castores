<?php


$root = dirname(__DIR__);
require_once $root .'/Model/Entities/Usuarios.php';
require_once $root .'/Config/main.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUsuarioRepository.php';
require_once $root .'/Model/Repository/RepositoryUsuario.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo '<div class="alert alert-danger py-2 small text-start" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> Token de seguridad inválido.</div>';
        exit();
    }

    $id = limpiar_cadena($_POST['id'] ?? '');
    if (empty($id)) {
        
        $mensaje = 'ID de usuario no proporcionado.';
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> ID de usuario no proporcionado.
            </div>
        ';
        exit();
    }

    $repo = new RepositoryUsuario();
    
    $resultado = $repo->deactivate($id);
    if ($resultado) {
        echo '<div class="alert alert-success py-2 small text-start" role="alert"><i class="bi bi-check-circle-fill me-2"></i> <strong>Listo:</strong> Usuario desactivado.</div>';
    } else {
        echo '<div class="alert alert-danger py-2 small text-start" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> El usuario no se pudo desactivar.</div>';
    }

}