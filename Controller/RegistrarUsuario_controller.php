<?php

$root = dirname(__DIR__);
require_once $root .'/Model/Entities/Usuarios.php';
require_once $root .'/Config/main.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUsuarioRepository.php';
require_once $root .'/Model/Repository/RepositoryUsuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    $usuario = limpiar_cadena($_POST['usuario']);
    $clave_1 = limpiar_cadena($_POST['clave_1']);
    $clave_2 = limpiar_cadena($_POST['clave_2']);
    $nombre = limpiar_cadena($_POST['nombre']);
    $apellido = limpiar_cadena($_POST['apellido']);
    $email = limpiar_cadena($_POST['email']);
    $telefono = limpiar_cadena($_POST['telefono']);
    $cedula = limpiar_cadena($_POST['cedula']);
    $rol = limpiar_cadena($_POST['rol']) ?? null; // Asegúrate de que 'rol' esté definido en el formulario

    if(empty($usuario) || empty($clave_1) || empty($clave_2) || empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($cedula) || empty($rol)) {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Todos los campos son obligatorios
            </div>
        ';
        exit();
        
    }

    if(!verificar_datos("/^[a-zA-Z0-9.,]{4,20}$/", $usuario) || 
       !verificar_datos("/^[a-zA-Z0-9$@#.]{7,99}$/", $clave_1) || 
       !verificar_datos("/^[a-zA-Z0-9$@#.]{7,99}$/", $clave_2) ||
       !verificar_datos("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/", $nombre) ||
       !verificar_datos("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/", $apellido) ||
       !verificar_datos("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) ||
       !verificar_datos("/^[\d\s\-]{7,15}$/", $telefono) ||
       !verificar_datos("/^[0-9]{5,10}$/", $cedula)) {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Alguno de los campos no cumple con el formato requerido
            </div>
        ';
        exit();

    }

    if($clave_1 !== $clave_2) {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Las contraseñas no coinciden
            </div>
        ';
        exit();
    }

    // Ensure $id is always defined. $rol may come as string from the form, so compare as strings.
    $id = uniqid('usr_'); // default id
    switch ((string)$rol) {
        case '1':
            $id = uniqid('adm_');
            break;
        case '2':
            $id = uniqid('sec_');
            break;
        case '3':
            $id = uniqid('ent_');
            break;
        case '4':
            $id = uniqid('rep_');
            break;
    }

    $status = true;

    $newUsuario = new Usuario(

        $id,
        $cedula,
        $usuario,
        $clave_1,
        $nombre,
        $apellido,
        $email,
        $telefono,
        $status,
        $rol 
    );

    $newUsuario->setPassword($clave_1);

    $repo = new RepositoryUsuario();
    
    $verf_cedula = $repo->findByCedula($cedula);

    if ($verf_cedula){
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> La cédula ya éxiste en la Base de Datos
            </div>
        ';
        exit();
    }

    if ($repo->create($newUsuario)) {
        echo '
            <div class="alert alert-success py-2 small text-start" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Éxito:</strong> Usuario registrado correctamente
            </div>
            <script>
                setTimeout(function(){
                    var form = document.getElementById("RegistroUsuarioForm");
                    if (form) form.reset();
                }, 1000);

                setTimeout(function() {
                    window.location.href = "index.php?View=Gestion_usuarios";
                }, 1500); // Redirigir después de 1.5 segundos
            </script>
        ';
    } else {
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error:</strong> Ocurrió un problema al registrar el usuario
            </div>
        ';
    }

    

}
