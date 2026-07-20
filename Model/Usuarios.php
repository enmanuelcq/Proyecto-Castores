<?php
// includes/usuarios.php

// ============ USUARIOS INDIVIDUALES ============

$secretaria = [
    'usuario_cedula' => '32499686',
    'usuario_nombre' => 'Clara',
    'usuario_apellido' => 'Medina',
    'usuario_usuario' => 'clara.medina',
    'usuario_email' => 'clara.medina@example.com',
    'usuario_contrasena' => 'secretaria1234',
    'usuario_telefono' => '0412-1234567',
    'usuario_rol' => 'Secretaria'
];

$entrenador = [
    'usuario_cedula' => '26804373',
    'usuario_nombre' => 'Enmanuel',
    'usuario_apellido' => 'Castillo',
    'usuario_usuario' => 'enmanuel.castillo',
    'usuario_email' => 'enmanuel.castillo@example.com',
    'usuario_contrasena' => 'entrenador1234',
    'usuario_telefono' => '0412-7890123',
    'usuario_rol' => 'Entrenador'
];

$administrador = [
    'usuario_cedula' => '32079110',
    'usuario_nombre' => 'Abraham',
    'usuario_apellido' => 'Camacho',
    'usuario_usuario' => 'abraham.camacho',
    'usuario_email' => 'abraham.camacho@example.com',
    'usuario_contrasena' => 'administrador1234',
    'usuario_telefono' => '0412-4567890',
    'usuario_rol' => 'Administrador'
];

$representante = [
    'usuario_cedula' => '12345678',
    'usuario_nombre' => 'Juan',
    'usuario_apellido' => 'Perez',
    'usuario_usuario' => 'juan.perez',
    'usuario_email' => 'juan.perez@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0412-9876543',
    'usuario_rol' => 'Representante'
];

// ============ LISTA DE TODOS LOS USUARIOS ============
// Este array contendrá todos los usuarios para facilitar la búsqueda
$todos_los_usuarios = [
    $secretaria,
    $entrenador,
    $administrador,
    $representante
];

// ============ FUNCIÓN PARA VERIFICAR CREDENCIALES ============
function verificarCredenciales($username, $password) {
    global $todos_los_usuarios;
    
    // Recorrer todos los usuarios
    foreach ($todos_los_usuarios as $usuario) {
        // Verificar si el usuario y contraseña coinciden
        if ($usuario['usuario_usuario'] === $username && 
            $usuario['usuario_contrasena'] === $password) {
            
            // Devolver los datos del usuario
            return [
                'success' => true,
                'cedula' => $usuario['usuario_cedula'],
                'nombre' => $usuario['usuario_nombre'],
                'apellido' => $usuario['usuario_apellido'],
                'usuario' => $usuario,
                'email' => $usuario['usuario_email'],
                'rol' => $usuario['usuario_rol'],
                'telefono' => $usuario['usuario_telefono']
            ];
        }
    }
    
    // No se encontró coincidencia
    return [
        'success' => false,
        'mensaje' => 'Usuario o contraseña incorrectos'
    ];
}

// ============ FUNCIÓN PARA OBTENER USUARIO POR CÉDULA ============
function obtenerUsuarioPorCedula($cedula) {
    global $todos_los_usuarios;
    
    foreach ($todos_los_usuarios as $usuario) {
        if ($usuario['usuario_cedula'] === $cedula) {
            return $usuario;
        }
    }
    
    return null;
}

// ============ FUNCIÓN PARA OBTENER USUARIO POR EMAIL ============
function obtenerUsuarioPorEmail($email) {
    global $todos_los_usuarios;
    
    foreach ($todos_los_usuarios as $usuario) {
        if ($usuario['usuario_email'] === $email) {
            return $usuario;
        }
    }
    
    return null;
}

function agregarUsuario($usuario) {
    global $todos_los_usuarios;
    
    // Verificar si el usuario ya existe por cédula o email
    if (obtenerUsuarioPorCedula($usuario['usuario_cedula']) !== null || 
        obtenerUsuarioPorEmail($usuario['usuario_email']) !== null) {
        return [
            'success' => false,
            'mensaje' => 'El usuario ya existe'
        ];
    }
    
    // Agregar el nuevo usuario al array
    $todos_los_usuarios[] = $usuario;

    foreach ($todos_los_usuarios as $us) {
        // Verificar si el usuario y contraseña coinciden
        if ($us['usuario_usuario'] === $usuario['usuario_usuario']){
            
            // Devolver los datos del usuario
            return [
                'success' => true,
                'cedula' => $usuario['usuario_cedula'],
                'nombre' => $usuario['usuario_nombre'],
                'apellido' => $usuario['usuario_apellido'],
                'usuario' => $usuario['usuario_usuario'],
                'email' => $usuario['usuario_email'],
                'rol' => $usuario['usuario_rol'],
                'telefono' => $usuario['usuario_telefono']
            ];
        }
    }
}
?>