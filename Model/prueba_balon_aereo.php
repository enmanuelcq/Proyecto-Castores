<?php
// ============================================
// PRUEBA DE BALÓN AÉREO
// ============================================

$prueba_balon_aereo1 = [
    'id_balon_aereo' => 1,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'total_toques' => 45,
    'toques_pecho' => 12,
    'toques_rodilla' => 8,
    'toques_pie' => 25,
    'fecha' => '2026-01-15'
];

$prueba_balon_aereo2 = [
    'id_balon_aereo' => 2,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'total_toques' => 52,
    'toques_pecho' => 15,
    'toques_rodilla' => 10,
    'toques_pie' => 27,
    'fecha' => '2026-01-22'
];

$prueba_balon_aereo3 = [
    'id_balon_aereo' => 3,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'total_toques' => 38,
    'toques_pecho' => 10,
    'toques_rodilla' => 6,
    'toques_pie' => 22,
    'fecha' => '2026-01-20'
];

$prueba_balon_aereo4 = [
    'id_balon_aereo' => 4,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'total_toques' => 60,
    'toques_pecho' => 18,
    'toques_rodilla' => 14,
    'toques_pie' => 28,
    'fecha' => '2026-01-18'
];

$prueba_balon_aereo5 = [
    'id_balon_aereo' => 5,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'total_toques' => 32,
    'toques_pecho' => 8,
    'toques_rodilla' => 5,
    'toques_pie' => 19,
    'fecha' => '2026-02-01'
];

$prueba_balon_aereo6 = [
    'id_balon_aereo' => 6,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'total_toques' => 55,
    'toques_pecho' => 16,
    'toques_rodilla' => 12,
    'toques_pie' => 27,
    'fecha' => '2026-02-05'
];

$prueba_balon_aereo7 = [
    'id_balon_aereo' => 7,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'total_toques' => 68,
    'toques_pecho' => 20,
    'toques_rodilla' => 16,
    'toques_pie' => 32,
    'fecha' => '2026-02-12'
];

$prueba_balon_aereo8 = [
    'id_balon_aereo' => 8,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'total_toques' => 48,
    'toques_pecho' => 14,
    'toques_rodilla' => 9,
    'toques_pie' => 25,
    'fecha' => '2026-01-29'
];

$prueba_balon_aereo9 = [
    'id_balon_aereo' => 9,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'total_toques' => 42,
    'toques_pecho' => 11,
    'toques_rodilla' => 7,
    'toques_pie' => 24,
    'fecha' => '2026-02-10'
];

$prueba_balon_aereo10 = [
    'id_balon_aereo' => 10,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'total_toques' => 58,
    'toques_pecho' => 17,
    'toques_rodilla' => 13,
    'toques_pie' => 28,
    'fecha' => '2026-02-15'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE BALÓN AÉREO ============
$todas_pruebas_balon_aereo = [
    $prueba_balon_aereo1,
    $prueba_balon_aereo2,
    $prueba_balon_aereo3,
    $prueba_balon_aereo4,
    $prueba_balon_aereo5,
    $prueba_balon_aereo6,
    $prueba_balon_aereo7,
    $prueba_balon_aereo8,
    $prueba_balon_aereo9,
    $prueba_balon_aereo10
];

// ============================================
// /Model/Pruebas/funciones/funciones_balon_aereo.php
// FUNCIONES PARA PRUEBA DE BALÓN AÉREO
// ============================================

/**
 * Obtener todas las pruebas de balón aéreo
 */
function obtenerTodasBalonAereo($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de balón aéreo por ID
 */
function buscarBalonAereoPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['id_balon_aereo'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de balón aéreo por fecha
 */
function buscarBalonAereoPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Buscar pruebas de balón aéreo por rango de fechas
 */
function buscarBalonAereoPorRangoFechas($fecha_inicio, $fecha_fin, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] >= $fecha_inicio && $p['fecha'] <= $fecha_fin) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de balón aéreo por atleta
 */
function obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de balón aéreo por categoría
 */
function obtenerBalonAereoPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de balón aéreo por usuario evaluador
 */
function obtenerBalonAereoPorUsuario($usuario_cedula, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['usuario_cedula'] == $usuario_cedula) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de balón aéreo por atleta
 */
function contarBalonAereoPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de balón aéreo por categoría
 */
function contarBalonAereoPorCategoria($categoria_id, $pruebas) {
    return count(obtenerBalonAereoPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de balón aéreo
 */
function contarTotalBalonAereo($pruebas) {
    return count($pruebas);
}

/**
 * Calcular promedio de toques totales por atleta
 */
function promedioToquesBalonAereo($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['total_toques'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor registro de balón aéreo por atleta
 */
function mejorBalonAereoPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    foreach ($pruebas_atleta as $p) {
        if ($p['total_toques'] > $mejor['total_toques']) {
            $mejor = $p;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de balón aéreo por atleta
 */
function ultimoBalonAereoPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular evolución de toques por atleta (para gráficos)
 */
function evolucionBalonAereoPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['fecha'],
            'total_toques' => $p['total_toques'],
            'toques_pecho' => $p['toques_pecho'],
            'toques_rodilla' => $p['toques_rodilla'],
            'toques_pie' => $p['toques_pie']
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de balón aéreo
 */
function estadisticasGeneralesBalonAereo($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'promedio_toques' => 0,
            'max_toques' => 0,
            'min_toques' => 0,
            'total_atletas' => 0
        ];
    }
    
    $total_toques = 0;
    $max_toques = 0;
    $min_toques = PHP_INT_MAX;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $total_toques += $p['total_toques'];
        if ($p['total_toques'] > $max_toques) $max_toques = $p['total_toques'];
        if ($p['total_toques'] < $min_toques) $min_toques = $p['total_toques'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    return [
        'total_pruebas' => count($pruebas),
        'promedio_toques' => round($total_toques / count($pruebas), 2),
        'max_toques' => $max_toques,
        'min_toques' => $min_toques,
        'total_atletas' => count(array_unique($atletas))
    ];
}

/**
 * Filtrar pruebas de balón aéreo por criterios
 */
function filtrarBalonAereo($pruebas, $filtros = []) {
    $resultado = $pruebas;
    
    if (isset($filtros['atleta_cedula'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['atleta_cedula'] == $filtros['atleta_cedula'];
        });
    }
    
    if (isset($filtros['categoria_id'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['categoria_id'] == $filtros['categoria_id'];
        });
    }
    
    if (isset($filtros['fecha_desde'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['fecha'] >= $filtros['fecha_desde'];
        });
    }
    
    if (isset($filtros['fecha_hasta'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['fecha'] <= $filtros['fecha_hasta'];
        });
    }
    
    if (isset($filtros['toques_min'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['total_toques'] >= $filtros['toques_min'];
        });
    }
    
    if (isset($filtros['toques_max'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['total_toques'] <= $filtros['toques_max'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de balón aéreo para un atleta
 */
function resumenBalonAereoAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerBalonAereoPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoBalonAereoPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorBalonAereoPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_toques' => promedioToquesBalonAereo($cedula_atleta, $pruebas),
        'mejor_toques' => $mejor ? $mejor['total_toques'] : 0,
        'ultima_fecha' => $ultima ? $ultima['fecha'] : null,
        'ultimo_toques' => $ultima ? $ultima['total_toques'] : 0,
        'evolucion' => evolucionBalonAereoPorAtleta($cedula_atleta, $pruebas)
    ];
}
