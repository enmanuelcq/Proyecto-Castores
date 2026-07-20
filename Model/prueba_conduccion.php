<?php
// ============================================
// PRUEBA DE CONDUCCIÓN
// ============================================

$prueba_conduccion1 = [
    'id_conduccion' => 1,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'distancia_metros' => 30.00,
    'distancia_obstaculos' => 5.00,
    'tiempo_derecha' => 6.85,
    'tiempo_izquierda' => 7.12,
    'tiempo_ambas_piernas' => 6.45,
    'fecha' => '2026-01-16'
];

$prueba_conduccion2 = [
    'id_conduccion' => 2,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'distancia_metros' => 30.00,
    'distancia_obstaculos' => 5.00,
    'tiempo_derecha' => 6.52,
    'tiempo_izquierda' => 6.88,
    'tiempo_ambas_piernas' => 6.21,
    'fecha' => '2026-01-23'
];

$prueba_conduccion3 = [
    'id_conduccion' => 3,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'distancia_metros' => 25.00,
    'distancia_obstaculos' => 4.00,
    'tiempo_derecha' => 7.45,
    'tiempo_izquierda' => 7.80,
    'tiempo_ambas_piernas' => 7.12,
    'fecha' => '2026-01-21'
];

$prueba_conduccion4 = [
    'id_conduccion' => 4,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'distancia_metros' => 35.00,
    'distancia_obstaculos' => 6.00,
    'tiempo_derecha' => 5.95,
    'tiempo_izquierda' => 6.10,
    'tiempo_ambas_piernas' => 5.78,
    'fecha' => '2026-01-19'
];

$prueba_conduccion5 = [
    'id_conduccion' => 5,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'distancia_metros' => 20.00,
    'distancia_obstaculos' => 3.00,
    'tiempo_derecha' => 8.20,
    'tiempo_izquierda' => 8.50,
    'tiempo_ambas_piernas' => 7.95,
    'fecha' => '2026-02-02'
];

$prueba_conduccion6 = [
    'id_conduccion' => 6,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'distancia_metros' => 28.00,
    'distancia_obstaculos' => 4.50,
    'tiempo_derecha' => 6.98,
    'tiempo_izquierda' => 7.25,
    'tiempo_ambas_piernas' => 6.67,
    'fecha' => '2026-02-06'
];

$prueba_conduccion7 = [
    'id_conduccion' => 7,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'distancia_metros' => 40.00,
    'distancia_obstaculos' => 7.00,
    'tiempo_derecha' => 5.45,
    'tiempo_izquierda' => 5.60,
    'tiempo_ambas_piernas' => 5.28,
    'fecha' => '2026-02-13'
];

$prueba_conduccion8 = [
    'id_conduccion' => 8,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'distancia_metros' => 25.00,
    'distancia_obstaculos' => 4.00,
    'tiempo_derecha' => 7.12,
    'tiempo_izquierda' => 7.45,
    'tiempo_ambas_piernas' => 6.89,
    'fecha' => '2026-02-11'
];

$prueba_conduccion9 = [
    'id_conduccion' => 9,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'distancia_metros' => 35.00,
    'distancia_obstaculos' => 6.00,
    'tiempo_derecha' => 5.82,
    'tiempo_izquierda' => 5.95,
    'tiempo_ambas_piernas' => 5.65,
    'fecha' => '2026-02-16'
];

$prueba_conduccion10 = [
    'id_conduccion' => 10,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'distancia_metros' => 30.00,
    'distancia_obstaculos' => 5.00,
    'tiempo_derecha' => 6.35,
    'tiempo_izquierda' => 6.62,
    'tiempo_ambas_piernas' => 6.08,
    'fecha' => '2026-02-18'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE CONDUCCIÓN ============
$todas_pruebas_conduccion = [
    $prueba_conduccion1,
    $prueba_conduccion2,
    $prueba_conduccion3,
    $prueba_conduccion4,
    $prueba_conduccion5,
    $prueba_conduccion6,
    $prueba_conduccion7,
    $prueba_conduccion8,
    $prueba_conduccion9,
    $prueba_conduccion10
];


// ============================================
// /Model/Pruebas/funciones/funciones_conduccion.php
// FUNCIONES PARA PRUEBA DE CONDUCCIÓN
// ============================================

/**
 * Obtener todas las pruebas de conducción
 */
function obtenerTodasConduccion($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de conducción por ID
 */
function buscarConduccionPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['id_conduccion'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de conducción por fecha
 */
function buscarConduccionPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Buscar pruebas de conducción por rango de fechas
 */
function buscarConduccionPorRangoFechas($fecha_inicio, $fecha_fin, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] >= $fecha_inicio && $p['fecha'] <= $fecha_fin) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de conducción por atleta
 */
function obtenerConduccionPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de conducción por categoría
 */
function obtenerConduccionPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de conducción por atleta
 */
function contarConduccionPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerConduccionPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de conducción por categoría
 */
function contarConduccionPorCategoria($categoria_id, $pruebas) {
    return count(obtenerConduccionPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de conducción
 */
function contarTotalConduccion($pruebas) {
    return count($pruebas);
}

/**
 * Calcular promedio de tiempo en conducción por atleta
 */
function promedioTiempoConduccion($cedula_atleta, $pruebas, $tipo = 'ambas') {
    $pruebas_atleta = obtenerConduccionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $campo = $tipo == 'derecha' ? 'tiempo_derecha' : ($tipo == 'izquierda' ? 'tiempo_izquierda' : 'tiempo_ambas_piernas');
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p[$campo];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor tiempo de conducción por atleta
 */
function mejorTiempoConduccionPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerConduccionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    foreach ($pruebas_atleta as $p) {
        if ($p['tiempo_ambas_piernas'] < $mejor['tiempo_ambas_piernas']) {
            $mejor = $p;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de conducción por atleta
 */
function ultimoConduccionPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerConduccionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular evolución de tiempos por atleta (para gráficos)
 */
function evolucionConduccionPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerConduccionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['fecha'],
            'tiempo_derecha' => $p['tiempo_derecha'],
            'tiempo_izquierda' => $p['tiempo_izquierda'],
            'tiempo_ambas' => $p['tiempo_ambas_piernas'],
            'distancia' => $p['distancia_metros']
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de conducción
 */
function estadisticasGeneralesConduccion($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'promedio_tiempo' => 0,
            'mejor_tiempo' => 0,
            'peor_tiempo' => 0,
            'total_atletas' => 0,
            'distancia_promedio' => 0
        ];
    }
    
    $total_tiempo = 0;
    $mejor_tiempo = PHP_INT_MAX;
    $peor_tiempo = 0;
    $total_distancia = 0;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $tiempo = $p['tiempo_ambas_piernas'];
        $total_tiempo += $tiempo;
        if ($tiempo < $mejor_tiempo) $mejor_tiempo = $tiempo;
        if ($tiempo > $peor_tiempo) $peor_tiempo = $tiempo;
        $total_distancia += $p['distancia_metros'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    return [
        'total_pruebas' => count($pruebas),
        'promedio_tiempo' => round($total_tiempo / count($pruebas), 2),
        'mejor_tiempo' => $mejor_tiempo,
        'peor_tiempo' => $peor_tiempo,
        'total_atletas' => count(array_unique($atletas)),
        'distancia_promedio' => round($total_distancia / count($pruebas), 2)
    ];
}

/**
 * Filtrar pruebas de conducción por criterios
 */
function filtrarConduccion($pruebas, $filtros = []) {
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
    
    if (isset($filtros['tiempo_max'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['tiempo_ambas_piernas'] <= $filtros['tiempo_max'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de conducción para un atleta
 */
function resumenConduccionAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerConduccionPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoConduccionPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorTiempoConduccionPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_tiempo' => promedioTiempoConduccion($cedula_atleta, $pruebas),
        'mejor_tiempo' => $mejor ? $mejor['tiempo_ambas_piernas'] : 0,
        'ultima_fecha' => $ultima ? $ultima['fecha'] : null,
        'ultimo_tiempo' => $ultima ? $ultima['tiempo_ambas_piernas'] : 0,
        'evolucion' => evolucionConduccionPorAtleta($cedula_atleta, $pruebas)
    ];
}

/**
 * Comparar perfiles (derecha vs izquierda) por atleta
 */
function compararPerfilesConduccion($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerConduccionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $total_derecha = 0;
    $total_izquierda = 0;
    $count = 0;
    
    foreach ($pruebas_atleta as $p) {
        $total_derecha += $p['tiempo_derecha'];
        $total_izquierda += $p['tiempo_izquierda'];
        $count++;
    }
    
    return [
        'promedio_derecha' => round($total_derecha / $count, 2),
        'promedio_izquierda' => round($total_izquierda / $count, 2),
        'diferencia' => round(abs($total_derecha - $total_izquierda) / $count, 2),
        'perfil_dominante' => ($total_derecha < $total_izquierda) ? 'Derecho' : 'Izquierdo'
    ];
}
