<?php
// ============================================
// ARRAY DE REPRESENTANTES (Mínimo 5)
// ============================================

$representante1 = [
    'usuario_cedula' => '12345678',
    'usuario_nombre' => 'Juan',
    'usuario_apellido' => 'Pérez',
    'usuario_usuario' => 'juan.perez',
    'usuario_email' => 'juan.perez@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0412-9876543',
    'usuario_rol' => 'Representante',
    'representante_ocupacion' => 'Ingeniero Civil',
    'representante_direccion' => 'Calle Principal #123, Urbanización Los Pinos'
];

$representante2 = [
    'usuario_cedula' => '23456789',
    'usuario_nombre' => 'María',
    'usuario_apellido' => 'González',
    'usuario_usuario' => 'maria.gonzalez',
    'usuario_email' => 'maria.gonzalez@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0416-7890123',
    'usuario_rol' => 'Representante',
    'representante_ocupacion' => 'Médico Pediatra',
    'representante_direccion' => 'Av. Libertador #456, El Rosal'
];

$representante3 = [
    'usuario_cedula' => '34567890',
    'usuario_nombre' => 'Carlos',
    'usuario_apellido' => 'Rodríguez',
    'usuario_usuario' => 'carlos.rodriguez',
    'usuario_email' => 'carlos.rodriguez@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0414-5678901',
    'usuario_rol' => 'Representante',
    'representante_ocupacion' => 'Arquitecto',
    'representante_direccion' => 'Calle 3 #890, La Candelaria'
];

$representante4 = [
    'usuario_cedula' => '45678901',
    'usuario_nombre' => 'Ana',
    'usuario_apellido' => 'Martínez',
    'usuario_usuario' => 'ana.martinez',
    'usuario_email' => 'ana.martinez@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0412-3456789',
    'usuario_rol' => 'Representante',
    'representante_ocupacion' => 'Docente',
    'representante_direccion' => 'Calle Los Mangos #345, Santa Fe'
];

$representante5 = [
    'usuario_cedula' => '56789012',
    'usuario_nombre' => 'Luis',
    'usuario_apellido' => 'Sánchez',
    'usuario_usuario' => 'luis.sanchez',
    'usuario_email' => 'luis.sanchez@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0416-1234567',
    'usuario_rol' => 'Representante',
    'representante_ocupacion' => 'Comerciante',
    'representante_direccion' => 'Av. Principal #123, Colinas de Bello Monte'
];

$representante6 = [
    'usuario_cedula' => '67890123',
    'usuario_nombre' => 'Carmen',
    'usuario_apellido' => 'Díaz',
    'usuario_usuario' => 'carmen.diaz',
    'usuario_email' => 'carmen.diaz@example.com',
    'usuario_contrasena' => 'representante1234',
    'usuario_telefono' => '0412-7890123',
    'usuario_rol' => 'Representante',
    'representante_ocupacion' => 'Enfermera',
    'representante_direccion' => 'Calle 5 #678, La Trinidad'
];

// ============================================
// ARRAYS DE USUARIOS EXISTENTES (Para comprobaciones)
// ============================================

// ============ LISTA DE TODOS LOS REPRESENTANTES ============
$todos_los_representantes = [
    $representante1,
    $representante2,
    $representante3,
    $representante4,
    $representante5,
    $representante6
];

// ===== FUNCIONES =====

/**
 * Buscar representante por cédula
 */
function buscarRepresentantePorCedula($cedula) {
    global $todos_los_representantes;
    foreach($todos_los_representantes as $r) {
        if($r['usuario_cedula'] == $cedula) {
            return $r;
        }
    }
    return null;
}

/**
 * Obtener atletas asociados a un representante
 */
function obtenerAtletasPorRepresentante($cedula_representante, $todos_los_atletas) {
    $atletas = [];
    foreach($todos_los_atletas as $a) {
        if(isset($a['cedula_representante']) && $a['cedula_representante'] == $cedula_representante) {
            $atletas[] = $a;
        }
    }
    return $atletas;
}

/**
 * Obtener solvencia de un atleta
 */
function obtenerSolvenciaAtleta($cedula_atleta, $todas_las_solvencias) {
    foreach($todas_las_solvencias as $s) {
        if(isset($s['atleta_cedula']) && $s['atleta_cedula'] == $cedula_atleta) {
            return $s;
        }
    }
    return null;
}

/**
 * Contar meses pendientes de un atleta (solo meses activos)
 */
function contarMesesPendientesAtleta($cedula_atleta, $todas_las_solvencias) {
    $meses_activos = obtenerMesesActivosReportes();
    $solvencia = obtenerSolvenciaAtleta($cedula_atleta, $todas_las_solvencias);
    if(!$solvencia) return 0;
    
    $pendientes = 0;
    foreach($solvencia as $clave => $info_mes) {
        if($clave !== 'atleta_cedula' && in_array($clave, $meses_activos)) {
            if($info_mes['estado'] == 'Pendiente') {
                $pendientes++;
            }
        }
    }
    return $pendientes;
}

/**
 * Busca un representante por su correo
 * @param string $email - Correo del representante
 * @param array $lista_representantes - Lista de representantes
 * @return array|null - Retorna el representante encontrado o null si no existe
 */
function buscarRepresentantePorEmail($email, $lista_representantes = null) {
    global $todos_los_representantes;
    $representantes = $lista_representantes ?? $todos_los_representantes;
    
    foreach ($representantes as $representante) {
        if ($representante['usuario_email'] === $email) {
            return $representante;
        }
    }
    return null;
}

function agregarRepresentante($representante) {
    global $todos_los_representantes;
    
    // Verificar si el representante ya existe por cédula
    if (buscarRepresentantePorCedula($representante['usuario_cedula']) !== null) {
        return [
            'success' => false,
            'mensaje' => 'El representante con esta cédula ya existe en el sistema',
            'codigo' => 'DUPLICADO_CEDULA'
        ];
    }
    
    // Verificar si el representante ya existe por email
    if (buscarRepresentantePorEmail($representante['usuario_email']) !== null) {
        return [
            'success' => false,
            'mensaje' => 'El representante con este correo ya existe en el sistema',
            'codigo' => 'DUPLICADO_EMAIL'
        ];
    }
    
    // Verificar si el representante ya existe por nombre y apellido (opcional)
    foreach ($todos_los_representantes as $rep) {
        if ($rep['usuario_nombre'] === $representante['usuario_nombre'] && 
            $rep['usuario_apellido'] === $representante['usuario_apellido']) {
            return [
                'success' => false,
                'mensaje' => 'Ya existe un representante con este nombre y apellido',
                'codigo' => 'DUPLICADO_NOMBRE'
            ];
        }
    }
    
    // Agregar el nuevo representante al array
    $todos_los_representantes[] = $representante;
    
    // Verificar que se agregó correctamente
    $representante_agregado = buscarRepresentantePorCedula($representante['usuario_cedula']);
    if ($representante_agregado) {
        return [
            'success' => true,
            'mensaje' => 'Representante registrado exitosamente',
            'codigo' => 'REGISTRO_EXITOSO',
            'representante' => $representante_agregado,
            'cedula' => $representante['usuario_cedula'],
            'nombre_completo' => $representante['usuario_nombre'] . ' ' . 
                                  $representante['usuario_apellido'],
            'email' => $representante['usuario_email'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ];
    } else {
        return [
            'success' => false,
            'mensaje' => 'Error al registrar el representante',
            'codigo' => 'ERROR_REGISTRO'
        ];
    }
}

