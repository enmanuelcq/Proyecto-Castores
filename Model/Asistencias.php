<?php
// ============================================
// /Model/AsistenciaEntrenamientos.php
// ============================================

$asistencia_entrenamientos = [
    // Entrenamiento 1 (Sub-14)
    [
        'id' => 1,
        'entrenamiento_id' => 1,
        'atleta_cedula' => '28765432',
        'asistio' => 1,
        'observacion' => 'Llegó tarde 10 min'
    ],
    [
        'id' => 2,
        'entrenamiento_id' => 1,
        'atleta_cedula' => '29567123',
        'asistio' => 1,
        'observacion' => ''
    ],
    [
        'id' => 3,
        'entrenamiento_id' => 1,
        'atleta_cedula' => '28761234',
        'asistio' => 0,
        'observacion' => 'Justificó falta médica'
    ],
    [
        'id' => 4,
        'entrenamiento_id' => 1,
        'atleta_cedula' => '31098765',
        'asistio' => 1,
        'observacion' => ''
    ],
    [
        'id' => 5,
        'entrenamiento_id' => 1,
        'atleta_cedula' => '29456789',
        'asistio' => 1,
        'observacion' => ''
    ],
    [
        'id' => 6,
        'entrenamiento_id' => 1,
        'atleta_cedula' => '30123456',
        'asistio' => 0,
        'observacion' => 'Falta sin justificar'
    ],
    
    // Entrenamiento 2 (Sub-16)
    [
        'id' => 7,
        'entrenamiento_id' => 2,
        'atleta_cedula' => '30123456',
        'asistio' => 1,
        'observacion' => ''
    ],
    [
        'id' => 8,
        'entrenamiento_id' => 2,
        'atleta_cedula' => '29456789',
        'asistio' => 0,
        'observacion' => 'No asistió sin justificar'
    ],
    [
        'id' => 9,
        'entrenamiento_id' => 2,
        'atleta_cedula' => '28765432',
        'asistio' => 1,
        'observacion' => ''
    ],
    [
        'id' => 10,
        'entrenamiento_id' => 2,
        'atleta_cedula' => '31098765',
        'asistio' => 1,
        'observacion' => ''
    ],
    
    // Entrenamiento 4 (Sub-10)
    [
        'id' => 11,
        'entrenamiento_id' => 4,
        'atleta_cedula' => '28761234',
        'asistio' => 1,
        'observacion' => ''
    ],
    [
        'id' => 12,
        'entrenamiento_id' => 4,
        'atleta_cedula' => '31098765',
        'asistio' => 0,
        'observacion' => 'Falta sin justificar'
    ],
    [
        'id' => 13,
        'entrenamiento_id' => 4,
        'atleta_cedula' => '29567123',
        'asistio' => 1,
        'observacion' => ''
    ]
];

$toda_la_asistencia = $asistencia_entrenamientos;

// ===== FUNCIONES =====

/**
 * Obtener asistencia por entrenamiento
 */
function obtenerAsistenciaPorEntrenamiento($entrenamiento_id, $asistencia) {
    $resultado = [];
    foreach($asistencia as $a) {
        if($a['entrenamiento_id'] == $entrenamiento_id) {
            $resultado[] = $a;
        }
    }
    return $resultado;
}

/**
 * Obtener asistencia por atleta
 */
function obtenerAsistenciaPorAtleta($cedula_atleta, $asistencia) {
    $resultado = [];
    foreach($asistencia as $a) {
        if($a['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $a;
        }
    }
    return $resultado;
}

/**
 * Contar asistencias de un atleta
 */
function contarAsistenciasAtleta($cedula_atleta, $asistencia) {
    $asistencias = obtenerAsistenciaPorAtleta($cedula_atleta, $asistencia);
    $presentes = 0;
    foreach($asistencias as $a) {
        if($a['asistio'] == 1) $presentes++;
    }
    return [
        'total' => count($asistencias),
        'presentes' => $presentes,
        'ausentes' => count($asistencias) - $presentes
    ];
}

/**
 * Calcular porcentaje de asistencia de un atleta
 */
function calcularPorcentajeAsistenciaAtleta($cedula_atleta, $asistencia) {
    $stats = contarAsistenciasAtleta($cedula_atleta, $asistencia);
    if($stats['total'] == 0) return 0;
    return round(($stats['presentes'] / $stats['total']) * 100);
}

/**
 * Obtener resumen de asistencia por entrenamiento
 */
function obtenerResumenAsistenciaEntrenamiento($entrenamiento_id, $asistencia, $total_atletas) {
    $asistencias = obtenerAsistenciaPorEntrenamiento($entrenamiento_id, $asistencia);
    $presentes = count(array_filter($asistencias, function($a) { return $a['asistio'] == 1; }));
    $ausentes = count(array_filter($asistencias, function($a) { return $a['asistio'] == 0; }));
    
    return [
        'total_registrados' => count($asistencias),
        'presentes' => $presentes,
        'ausentes' => $ausentes,
        'total_atletas' => $total_atletas,
        'porcentaje' => $total_atletas > 0 ? round(($presentes / $total_atletas) * 100) : 0
    ];
}

/**
 * Obtener atletas que faltaron a un entrenamiento
 */
function obtenerAusentesEntrenamiento($entrenamiento_id, $asistencia) {
    $asistencias = obtenerAsistenciaPorEntrenamiento($entrenamiento_id, $asistencia);
    $ausentes = [];
    foreach($asistencias as $a) {
        if($a['asistio'] == 0) {
            $ausentes[] = $a;
        }
    }
    return $ausentes;
}

/**
 * Obtener atletas con mejor asistencia
 */
function obtenerMejorAsistencia($asistencia, $limite = 5) {
    $atletas = [];
    foreach($asistencia as $a) {
        if(!isset($atletas[$a['atleta_cedula']])) {
            $atletas[$a['atleta_cedula']] = [
                'total' => 0,
                'presentes' => 0
            ];
        }
        $atletas[$a['atleta_cedula']]['total']++;
        if($a['asistio'] == 1) {
            $atletas[$a['atleta_cedula']]['presentes']++;
        }
    }
    
    foreach($atletas as $cedula => &$data) {
        $data['porcentaje'] = $data['total'] > 0 ? round(($data['presentes'] / $data['total']) * 100) : 0;
    }
    
    usort($atletas, function($a, $b) {
        return $b['porcentaje'] - $a['porcentaje'];
    });
    
    return array_slice($atletas, 0, $limite);
}
?>