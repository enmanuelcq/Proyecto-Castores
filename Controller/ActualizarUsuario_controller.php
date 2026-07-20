<?php

$root = dirname(__DIR__);
require_once $root .'/Model/Entities/Usuarios.php';
require_once $root .'/Config/main.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUsuarioRepository.php';
require_once $root .'/Model/Repository/RepositoryUsuario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

// Recoger datos del formulario
$id = $_POST['id'] ?? '';
$nombre = limpiar_cadena($_POST['nombre']);
$apellido = limpiar_cadena($_POST['apellido']);
$username = limpiar_cadena($_POST['usuario']);
$email = limpiar_cadena($_POST['email']);
$clave1 = limpiar_cadena($_POST['clave_1']);
$clave2 = limpiar_cadena($_POST['clave_2']);
$telefono = limpiar_cadena($_POST['telefono']);
$cedula = limpiar_cadena($_POST['cedula']);
$status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
$id_rol = isset($_POST['rol']) ? (int)$_POST['rol'] : 0;


if(!verificar_datos("/^[a-zA-Z0-9.,]{4,20}$/", $username) || 
       !verificar_datos("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/", $nombre) ||
       !verificar_datos("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$/", $apellido) ||
       !verificar_datos("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) ||
       !verificar_datos("/^[\d\s\-]{7,15}$/", $telefono) ||
       !verificar_datos("/^[0-9]{5,10}$/", $cedula)) {

        $mensaje = 'Alguno de los campos no cumple con el formato requerido.';
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Alguno de los campos no cumple con el formato requerido
            </div>
        ';
        exit;

    }

    if(empty($username) || empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($cedula) || empty($id_rol)) {
        $mensaje = 'Todos los campos obligatorios deben estar llenos.';
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Todos los campos son obligatorios
            </div>
        ';
        exit;
        
    }

    // Si se proporciona contraseña, verificar que coincidan y cumplan patrón
    $password = null;
    if (!empty($clave1) || !empty($clave2)) {
        if ($clave1 !== $clave2) {
            $mensaje = 'Las contraseñas no coinciden.';
            echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> Las contraseñas no coinciden
            </div>
            ';
            exit();
        }
        if (!preg_match('/[a-zA-Z0-9$@#.]{7,99}/', $clave1)) {
            $mensaje = 'La contraseña debe tener al menos 7 caracteres y solo permitir letras, números y $@#.';
            echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> La contraseña debe tener al menos 7 caracteres y solo permitir letras, números y $@#.
            </div>
            ';
            exit();
        }
        $password = $clave1; // Se hasheará en el setter
    }

    // Obtener el usuario actual desde la BD para preservar la contraseña si no se cambia
    $repo = new RepositoryUsuario();
    $usuarioExistente = $repo->findById($id);
    if (!$usuarioExistente) {
        $mensaje = 'El usuario no existe.';
        echo '
            <div class="alert alert-danger py-2 small text-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error:</strong> El usuario no existe.
            </div>
            ';
        exit();
    }

    // Crear objeto Usuario con los nuevos datos (incluyendo la contraseña anterior si no se cambia)
    $usuario = new Usuario(
        $id,
        $cedula,
        $username,
        $password ?? $usuarioExistente->getPassword(), // si no se cambia, conserva la anterior
        $nombre,
        $apellido,
        $email,
        $telefono,
        (bool)$status,
        $id_rol
    );

    // Si se cambió la contraseña, usar el setter para hashearla
    if (!empty($password)) {
        $usuario->setPassword($password);
    }

    // Actualizar en la BD
    try {
        $resultado = $repo->update($usuario);
        if ($resultado) {
            $mensaje = 'Usuario actualizado correctamente.';
            // Redirigir de vuelta al dashboard
        header('Location: ../index.php?View=Gestion_usuarios&&mensaje='. $mensaje. '');
        exit();
        } else {
            $mensaje = 'Error al actualizar el usuario.';
            header('Location: ../index.php?View=Gestion_usuarios&mensaje='. $mensaje. '');
        exit();
        }
    } catch (Exception $e) {
        $mensaje = 'Error en la base de datos: ' . $e->getMessage();
    }



}