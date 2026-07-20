<?php
// ============================================
// PRUEBA DE PASES
// ============================================

$prueba_pases1 = [
    'id_pases' => 1,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'total_pases' => 28,
    'derecha' => 15,
    'izquierda' => 8,
    'primera_intencion' => 5,
    'fecha' => '2026-01-18'
];

$prueba_pases2 = [
    'id_pases' => 2,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'total_pases' => 32,
    'derecha' => 18,
    'izquierda' => 9,
    'primera_intencion' => 5,
    'fecha' => '2026-01-26'
];

$prueba_pases3 = [
    'id_pases' => 3,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'total_pases' => 20,
    'derecha' => 10,
    'izquierda' => 6,
    'primera_intencion' => 4,
    'fecha' => '2026-01-23'
];

$prueba_pases4 = [
    'id_pases' => 4,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'total_pases' => 35,
    'derecha' => 20,
    'izquierda' => 10,
    'primera_intencion' => 5,
    'fecha' => '2026-01-21'
];

$prueba_pases5 = [
    'id_pases' => 5,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'total_pases' => 15,
    'derecha' => 8,
    'izquierda' => 4,
    'primera_intencion' => 3,
    'fecha' => '2026-02-05'
];

$prueba_pases6 = [
    'id_pases' => 6,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'total_pases' => 25,
    'derecha' => 14,
    'izquierda' => 7,
    'primera_intencion' => 4,
    'fecha' => '2026-02-09'
];

$prueba_pases7 = [
    'id_pases' => 7,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'total_pases' => 40,
    'derecha' => 22,
    'izquierda' => 12,
    'primera_intencion' => 6,
    'fecha' => '2026-02-16'
];

$prueba_pases8 = [
    'id_pases' => 8,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'total_pases' => 22,
    'derecha' => 12,
    'izquierda' => 6,
    'primera_intencion' => 4,
    'fecha' => '2026-02-13'
];

$prueba_pases9 = [
    'id_pases' => 9,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'total_pases' => 38,
    'derecha' => 21,
    'izquierda' => 11,
    'primera_intencion' => 6,
    'fecha' => '2026-02-19'
];

$prueba_pases10 = [
    'id_pases' => 10,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'total_pases' => 34,
    'derecha' => 19,
    'izquierda' => 10,
    'primera_intencion' => 5,
    'fecha' => '2026-02-21'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE PASES ============
$todas_pruebas_pases = [
    $prueba_pases1,
    $prueba_pases2,
    $prueba_pases3,
    $prueba_pases4,
    $prueba_pases5,
    $prueba_pases6,
    $prueba_pases7,
    $prueba_pases8,
    $prueba_pases9,
    $prueba_pases10
];



// ============================================
// /Model/Pruebas/funciones/funciones_pases.php
// FUNCIONES PARA PRUEBA DE PASES
// ============================================

/**
 * Obtener todas las pruebas de pases
 */
function obtenerTodasPases($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de pases por ID
 */
function buscarPasesPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['id_pases'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de pases por fecha
 */
function buscarPasesPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Buscar pruebas de pases por rango de fechas
 */
function buscarPasesPorRangoFechas($fecha_inicio, $fecha_fin, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] >= $fecha_inicio && $p['fecha'] <= $fecha_fin) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de pases por atleta
 */
function obtenerPasesPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de pases por categoría
 */
function obtenerPasesPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de pases por usuario evaluador
 */
function obtenerPasesPorUsuario($usuario_cedula, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['usuario_cedula'] == $usuario_cedula) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de pases por atleta
 */
function contarPasesPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerPasesPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de pases por categoría
 */
function contarPasesPorCategoria($categoria_id, $pruebas) {
    return count(obtenerPasesPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de pases
 */
function contarTotalPases($pruebas) {
    return count($pruebas);
}

/**
 * Calcular promedio de pases totales por atleta
 */
function promedioPasesTotales($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['total_pases'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Calcular efectividad de pases por pierna
 */
function efectividadPasesPorPierna($cedula_atleta, $pruebas, $pierna = 'derecha') {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $campo = ($pierna == 'derecha') ? 'derecha' : 'izquierda';
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p[$campo];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Calcular porcentaje de pases de primera intención
 */
function porcentajePrimeraIntencion($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total_pases = 0;
    $primera_intencion = 0;
    foreach ($pruebas_atleta as $p) {
        $total_pases += $p['total_pases'];
        $primera_intencion += $p['primera_intencion'];
    }
    return $total_pases > 0 ? round(($primera_intencion / $total_pases) * 100, 2) : 0;
}

/**
 * Obtener mejor registro de pases por atleta
 */
function mejorPasesPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    foreach ($pruebas_atleta as $p) {
        if ($p['total_pases'] > $mejor['total_pases']) {
            $mejor = $p;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de pases por atleta
 */
function ultimoPasesPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular evolución de pases por atleta (para gráficos)
 */
function evolucionPasesPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['fecha'],
            'total_pases' => $p['total_pases'],
            'derecha' => $p['derecha'],
            'izquierda' => $p['izquierda'],
            'primera_intencion' => $p['primera_intencion']
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de pases
 */
function estadisticasGeneralesPases($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'promedio_pases' => 0,
            'max_pases' => 0,
            'min_pases' => 0,
            'total_atletas' => 0,
            'promedio_primera_intencion' => 0
        ];
    }
    
    $total_pases = 0;
    $max_pases = 0;
    $min_pases = PHP_INT_MAX;
    $total_primera = 0;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $total_pases += $p['total_pases'];
        if ($p['total_pases'] > $max_pases) $max_pases = $p['total_pases'];
        if ($p['total_pases'] < $min_pases) $min_pases = $p['total_pases'];
        $total_primera += $p['primera_intencion'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    $count = count($pruebas);
    return [
        'total_pruebas' => $count,
        'promedio_pases' => round($total_pases / $count, 2),
        'max_pases' => $max_pases,
        'min_pases' => $min_pases,
        'total_atletas' => count(array_unique($atletas)),
        'promedio_primera_intencion' => round($total_primera / $count, 2)
    ];
}

/**
 * Filtrar pruebas de pases por criterios
 */
function filtrarPases($pruebas, $filtros = []) {
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
    
    if (isset($filtros['pases_min'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['total_pases'] >= $filtros['pases_min'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de pases para un atleta
 */
function resumenPasesAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoPasesPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorPasesPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_pases' => promedioPasesTotales($cedula_atleta, $pruebas),
        'promedio_derecha' => efectividadPasesPorPierna($cedula_atleta, $pruebas, 'derecha'),
        'promedio_izquierda' => efectividadPasesPorPierna($cedula_atleta, $pruebas, 'izquierda'),
        'porcentaje_primera_intencion' => porcentajePrimeraIntencion($cedula_atleta, $pruebas),
        'mejor_pases' => $mejor ? $mejor['total_pases'] : 0,
        'ultima_fecha' => $ultima ? $ultima['fecha'] : null,
        'ultimo_pases' => $ultima ? $ultima['total_pases'] : 0,
        'evolucion' => evolucionPasesPorAtleta($cedula_atleta, $pruebas)
    ];
}

/**
 * Comparar rendimiento de pases (derecha vs izquierda)
 */
function compararPasesPorPierna($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerPasesPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $total_derecha = 0;
    $total_izquierda = 0;
    $count = 0;
    
    foreach ($pruebas_atleta as $p) {
        $total_derecha += $p['derecha'];
        $total_izquierda += $p['izquierda'];
        $count++;
    }
    
    return [
        'promedio_derecha' => round($total_derecha / $count, 2),
        'promedio_izquierda' => round($total_izquierda / $count, 2),
        'diferencia' => round(abs($total_derecha - $total_izquierda) / $count, 2),
        'pierna_dominante' => ($total_derecha > $total_izquierda) ? 'Derecha' : 'Izquierda'
    ];
}
