<?php

$root = dirname(__DIR__);
// verificar_usuario.php
require_once $root .'/Model/Entities/Usuarios.php';
require_once $root .'/Config/main.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUsuarioRepository.php';
require_once $root .'/Model/Repository/RepositoryUsuario.php';

// Iniciar sesión si no está iniciada (necesario para usar $_SESSION)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: text/html'); // Para enviar HTML

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = limpiar_Cadena($_POST['username'] ?? '');
    $password = limpiar_Cadena($_POST['password'] ?? '');


    if (empty($username) || empty($password)) {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Todos los campos son obligatorios
            </div>
        ';
        exit;
    }

    if (!verificar_datos("/^[a-zA-Z0-9.,]{4,30}$/", $username)) {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> El nombre de usuario no cumple con el formato requerido
            </div>
        ';
        exit;
    }

    if (!verificar_datos("/^[a-zA-Z0-9$@#.]{7,99}$/", $password)) {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> La contraseña no cumple con el formato requerido
            </div>
        ';
        exit;
    }
    
    $repo = new RepositoryUsuario();

    $login = $repo->findByUsername($username);
    
    if($login === null){

        $_SESSION['error_login'] = 'Usuario o contraseña incorrectos.';

        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Usuario o contraseña incorrectos
            </div>
        ';

        exit;
    }

    if(!$login->verificarClave($password)){

        $_SESSION['error_login'] = 'Usuario o contraseña incorrectos.';

            echo '
                <div class="alert alert-danger py-2 small text-start" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                    <strong>Error:</strong> Usuario o contraseña incorrectos
                </div>
            ';

            exit;
    }

    if (!$login->getStatus()) {

        $_SESSION['error_login'] = 'Tu cuenta está desactivada. Contacta al administrador.';
        
        echo '
                <div class="alert alert-danger py-2 small text-start" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                    <strong>Error:</strong> Tu cuenta está desactivada. Contacta al administrador.
                </div>
            ';

        exit;
    }

    $_SESSION['usuario'] = [
        'id'       => $login->getId(),
        'cedula'   => $login->getCedula(),
        'username' => $login->getUsername(),
        'nombre'   => $login->getNombre(),
        'apellido' => $login->getApellido(),
        'email'    => $login->getEmail(),
        'telefono' => $login->getTelefono(),
        'status'   => $login->getStatus(),
        'id_rol'   => $login->getIdRol()
    ];

    if(isset($_SESSION['usuario'])) {
        
        echo '<script>console.log("Session started successfully.");</script>';
        // Devolver HTML de éxito
        echo '
            <div class="alert alert-success py-2 small text-start" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <strong>¡Login exitoso!</strong> Redirigiendo...

                
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "index.php?View=home";
                }, 1500); // Redirigir después de 1.5 segundos
        
        ';
    } else {
        echo '<script>console.log("Session failed to start.");</script>';
    }
    

    
} else {
    // Devolver HTML de error
    echo '
        <div class="alert alert-danger py-2 small text-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
            <strong>Error:</strong> Usuario o contraseña incorrectos
        </div>
    ';
}
?>