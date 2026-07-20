<?php


$root = dirname(__DIR__);
require_once $root .'/Model/Entities/Usuarios.php';
require_once $root .'/Config/main.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUsuarioRepository.php';
require_once $root .'/Model/Repository/RepositoryUsuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';
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
    try{
    if ($resultado) {
        
        $mensaje = 'Usuario desactivado correctamente.';
            // Redirigir de vuelta al dashboard
        echo '
            <div class="alert alert-success py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Listo:</strong> Usuario desactivado.
            </div>
        ';
        header('Location: ../index.php?View=Gestion_usuarios&mensaje='. $mensaje. '');
    } else {
        $mensaje = 'Error al desactivar el usuario.';
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> El usuario no se pudo desactivar.
            </div>
        ';
        header('Location: ../index.php?View=Gestion_usuarios&mensaje='. $mensaje. '');
    }
    }catch (Exception $e) {
        $mensaje = 'Error en la base de datos: ' . $e->getMessage();
    }

}