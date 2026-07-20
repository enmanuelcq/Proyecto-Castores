<?php
// ============================================
// /Model/Entrenamientos.php
// ============================================

// Datos de ejemplo de entrenamientos con cédula de entrenador 26804373
$entrenamientos = [
    [
        'id' => 1,
        'fecha' => '2026-07-10',
        'hora_inicio' => '08:00',
        'hora_fin' => '10:00',
        'categoria' => 'Sub-14',
        'tipo' => 'Físico',
        'descripcion' => 'Entrenamiento de fuerza y resistencia',
        'lugar' => 'Cancha Principal',
        'entrenador_cedula' => '26804373',
        'entrenador_nombre' => 'Enmanuel Castillo',
        'estado' => 'Finalizado',
        'asistentes' => 12,
        'total_atletas' => 15
    ],
    [
        'id' => 2,
        'fecha' => '2026-07-12',
        'hora_inicio' => '10:00',
        'hora_fin' => '12:00',
        'categoria' => 'Sub-16',
        'tipo' => 'Técnico',
        'descripcion' => 'Entrenamiento de pases y control de balón',
        'lugar' => 'Cancha Alterna',
        'entrenador_cedula' => '26804373',
        'entrenador_nombre' => 'Enmanuel Castillo',
        'estado' => 'Finalizado',
        'asistentes' => 8,
        'total_atletas' => 12
    ],
    [
        'id' => 3,
        'fecha' => '2026-07-15',
        'hora_inicio' => '14:00',
        'hora_fin' => '16:00',
        'categoria' => 'Sub-12',
        'tipo' => 'Táctico',
        'descripcion' => 'Entrenamiento táctico y posicionamiento',
        'lugar' => 'Cancha Principal',
        'entrenador_cedula' => '26804373',
        'entrenador_nombre' => 'Enmanuel Castillo',
        'estado' => 'Programado',
        'asistentes' => 0,
        'total_atletas' => 10
    ],
    [
        'id' => 4,
        'fecha' => '2026-07-08',
        'hora_inicio' => '09:00',
        'hora_fin' => '11:00',
        'categoria' => 'Sub-10',
        'tipo' => 'Físico',
        'descripcion' => 'Entrenamiento de velocidad y agilidad',
        'lugar' => 'Cancha Alterna',
        'entrenador_cedula' => '26804373',
        'entrenador_nombre' => 'Enmanuel Castillo',
        'estado' => 'Finalizado',
        'asistentes' => 9,
        'total_atletas' => 10
    ],
    [
        'id' => 5,
        'fecha' => '2026-07-18',
        'hora_inicio' => '08:00',
        'hora_fin' => '10:00',
        'categoria' => 'Sub-16',
        'tipo' => 'Técnico',
        'descripcion' => 'Entrenamiento de definición y remate',
        'lugar' => 'Cancha Principal',
        'entrenador_cedula' => '26804373',
        'entrenador_nombre' => 'Enmanuel Castillo',
        'estado' => 'Programado',
        'asistentes' => 0,
        'total_atletas' => 8
    ],
    [
        'id' => 6,
        'fecha' => '2026-07-20',
        'hora_inicio' => '15:00',
        'hora_fin' => '17:00',
        'categoria' => 'Sub-14',
        'tipo' => 'Mixto',
        'descripcion' => 'Entrenamiento mixto: físico y técnico',
        'lugar' => 'Cancha Principal',
        'entrenador_cedula' => '26804373',
        'entrenador_nombre' => 'Enmanuel Castillo',
        'estado' => 'Programado',
        'asistentes' => 0,
        'total_atletas' => 15
    ]
];

$todos_los_entrenamientos = $entrenamientos;

// ===== FUNCIONES =====

/**
 * Obtener entrenamientos por categoría
 */
function obtenerEntrenamientosPorCategoria($categoria, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['categoria'] == $categoria) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

/**
 * Obtener entrenamientos por fecha
 */
function obtenerEntrenamientosPorFecha($fecha, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['fecha'] == $fecha) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

/**
 * Obtener entrenamientos por estado
 */
function obtenerEntrenamientosPorEstado($estado, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['estado'] == $estado) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

/**
 * Obtener entrenamientos por entrenador (cédula)
 */
function obtenerEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['entrenador_cedula'] == $entrenador_cedula) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

/**
 * Obtener entrenamiento por ID
 */
function obtenerEntrenamientoPorId($id, $entrenamientos) {
    foreach($entrenamientos as $e) {
        if($e['id'] == $id) {
            return $e;
        }
    }
    return null;
}

/**
 * Contar entrenamientos por categoría
 */
function contarEntrenamientosPorCategoria($categoria, $entrenamientos) {
    return count(obtenerEntrenamientosPorCategoria($categoria, $entrenamientos));
}

/**
 * Contar entrenamientos por estado
 */
function contarEntrenamientosPorEstado($estado, $entrenamientos) {
    return count(obtenerEntrenamientosPorEstado($estado, $entrenamientos));
}

/**
 * Contar entrenamientos por entrenador
 */
function contarEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos) {
    return count(obtenerEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos));
}

/**
 * Calcular porcentaje de asistencia
 */
function calcularPorcentajeAsistencia($asistentes, $total) {
    if($total == 0) return 0;
    return round(($asistentes / $total) * 100);
}

/**
 * Obtener estadísticas de entrenamientos por entrenador
 */
function obtenerEstadisticasEntrenador($entrenador_cedula, $entrenamientos) {
    $entrenamientos_entrenador = obtenerEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos);
    
    $total = count($entrenamientos_entrenador);
    $finalizados = 0;
    $en_curso = 0;
    $programados = 0;
    $total_asistentes = 0;
    $total_atletas = 0;
    
    foreach($entrenamientos_entrenador as $e) {
        if($e['estado'] == 'Finalizado') $finalizados++;
        elseif($e['estado'] == 'En curso') $en_curso++;
        elseif($e['estado'] == 'Programado') $programados++;
        
        $total_asistentes += $e['asistentes'];
        $total_atletas += $e['total_atletas'];
    }
    
    return [
        'total' => $total,
        'finalizados' => $finalizados,
        'en_curso' => $en_curso,
        'programados' => $programados,
        'total_asistentes' => $total_asistentes,
        'total_atletas' => $total_atletas,
        'promedio_asistencia' => $total_atletas > 0 ? round(($total_asistentes / $total_atletas) * 100) : 0
    ];
}

/**
 * Obtener resumen de entrenamientos por categoría y entrenador
 */
function obtenerResumenEntrenamientos($entrenamientos) {
    $resumen = [];
    $categorias = array_unique(array_column($entrenamientos, 'categoria'));
    $entrenadores = array_unique(array_column($entrenamientos, 'entrenador_cedula'));
    
    foreach($categorias as $cat) {
        $por_categoria = array_filter($entrenamientos, function($e) use ($cat) {
            return $e['categoria'] == $cat;
        });
        $resumen['categorias'][$cat] = count($por_categoria);
    }
    
    foreach($entrenadores as $ent) {
        $por_entrenador = array_filter($entrenamientos, function($e) use ($ent) {
            return $e['entrenador_cedula'] == $ent;
        });
        // Obtener nombre del entrenador
        $nombre = '';
        foreach($por_entrenador as $e) {
            $nombre = $e['entrenador_nombre'];
            break;
        }
        $resumen['entrenadores'][$ent] = [
            'nombre' => $nombre,
            'total' => count($por_entrenador)
        ];
    }
    
    return $resumen;
}

/**
 * Obtener próximos entrenamientos (fecha actual en adelante)
 */
function obtenerProximosEntrenamientos($entrenamientos, $limite = 5) {
    $hoy = date('Y-m-d');
    $proximos = array_filter($entrenamientos, function($e) use ($hoy) {
        return $e['fecha'] >= $hoy && $e['estado'] != 'Finalizado';
    });
    
    usort($proximos, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    
    return array_slice($proximos, 0, $limite);
}

/**
 * Obtener entrenamientos por rango de fechas
 */
function obtenerEntrenamientosPorRangoFechas($fecha_inicio, $fecha_fin, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['fecha'] >= $fecha_inicio && $e['fecha'] <= $fecha_fin) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}
?>