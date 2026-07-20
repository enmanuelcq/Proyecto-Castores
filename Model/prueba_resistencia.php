<?php
// ============================================
// PRUEBA DE RESISTENCIA
// ============================================

$prueba_resistencia1 = [
    'id_resistencia' => 1,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'tiempo_total' => 8.30,
    'pausa' => 2.00,
    'fecha' => '2026-01-20'
];

$prueba_resistencia2 = [
    'id_resistencia' => 2,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'tiempo_total' => 9.15,
    'pausa' => 1.50,
    'fecha' => '2026-01-28'
];

$prueba_resistencia3 = [
    'id_resistencia' => 3,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'tiempo_total' => 7.45,
    'pausa' => 2.00,
    'fecha' => '2026-01-25'
];

$prueba_resistencia4 = [
    'id_resistencia' => 4,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'tiempo_total' => 10.20,
    'pausa' => 1.00,
    'fecha' => '2026-01-23'
];

$prueba_resistencia5 = [
    'id_resistencia' => 5,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'tiempo_total' => 6.15,
    'pausa' => 2.30,
    'fecha' => '2026-02-07'
];

$prueba_resistencia6 = [
    'id_resistencia' => 6,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'tiempo_total' => 8.45,
    'pausa' => 1.50,
    'fecha' => '2026-02-11'
];

$prueba_resistencia7 = [
    'id_resistencia' => 7,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'tiempo_total' => 11.30,
    'pausa' => 0.50,
    'fecha' => '2026-02-18'
];

$prueba_resistencia8 = [
    'id_resistencia' => 8,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'tiempo_total' => 8.00,
    'pausa' => 2.00,
    'fecha' => '2026-02-15'
];

$prueba_resistencia9 = [
    'id_resistencia' => 9,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'tiempo_total' => 10.45,
    'pausa' => 1.00,
    'fecha' => '2026-02-21'
];

$prueba_resistencia10 = [
    'id_resistencia' => 10,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'tiempo_total' => 9.30,
    'pausa' => 1.50,
    'fecha' => '2026-02-23'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE RESISTENCIA ============
$todas_pruebas_resistencia = [
    $prueba_resistencia1,
    $prueba_resistencia2,
    $prueba_resistencia3,
    $prueba_resistencia4,
    $prueba_resistencia5,
    $prueba_resistencia6,
    $prueba_resistencia7,
    $prueba_resistencia8,
    $prueba_resistencia9,
    $prueba_resistencia10
];



// ============================================
// /Model/Pruebas/funciones/funciones_resistencia.php
// FUNCIONES PARA PRUEBA DE RESISTENCIA
// ============================================

/**
 * Obtener todas las pruebas de resistencia
 */
function obtenerTodasResistencia($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de resistencia por ID
 */
function buscarResistenciaPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['id_resistencia'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de resistencia por fecha
 */
function buscarResistenciaPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Buscar pruebas de resistencia por rango de fechas
 */
function buscarResistenciaPorRangoFechas($fecha_inicio, $fecha_fin, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] >= $fecha_inicio && $p['fecha'] <= $fecha_fin) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de resistencia por atleta
 */
function obtenerResistenciaPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de resistencia por categoría
 */
function obtenerResistenciaPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de resistencia por atleta
 */
function contarResistenciaPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerResistenciaPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de resistencia por categoría
 */
function contarResistenciaPorCategoria($categoria_id, $pruebas) {
    return count(obtenerResistenciaPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de resistencia
 */
function contarTotalResistencia($pruebas) {
    return count($pruebas);
}

/**
 * Calcular promedio de tiempo total por atleta
 */
function promedioTiempoResistencia($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerResistenciaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['tiempo_total'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Calcular promedio de pausa por atleta
 */
function promedioPausaResistencia($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerResistenciaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['pausa'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor tiempo de resistencia por atleta
 */
function mejorTiempoResistenciaPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerResistenciaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    foreach ($pruebas_atleta as $p) {
        if ($p['tiempo_total'] > $mejor['tiempo_total']) {
            $mejor = $p;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de resistencia por atleta
 */
function ultimoResistenciaPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerResistenciaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular evolución de resistencia por atleta (para gráficos)
 */
function evolucionResistenciaPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerResistenciaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['fecha'],
            'tiempo_total' => $p['tiempo_total'],
            'pausa' => $p['pausa']
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de resistencia
 */
function estadisticasGeneralesResistencia($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'promedio_tiempo' => 0,
            'max_tiempo' => 0,
            'min_tiempo' => 0,
            'total_atletas' => 0,
            'promedio_pausa' => 0
        ];
    }
    
    $total_tiempo = 0;
    $max_tiempo = 0;
    $min_tiempo = PHP_INT_MAX;
    $total_pausa = 0;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $total_tiempo += $p['tiempo_total'];
        if ($p['tiempo_total'] > $max_tiempo) $max_tiempo = $p['tiempo_total'];
        if ($p['tiempo_total'] < $min_tiempo) $min_tiempo = $p['tiempo_total'];
        $total_pausa += $p['pausa'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    $count = count($pruebas);
    return [
        'total_pruebas' => $count,
        'promedio_tiempo' => round($total_tiempo / $count, 2),
        'max_tiempo' => $max_tiempo,
        'min_tiempo' => $min_tiempo,
        'total_atletas' => count(array_unique($atletas)),
        'promedio_pausa' => round($total_pausa / $count, 2)
    ];
}

/**
 * Filtrar pruebas de resistencia por criterios
 */
function filtrarResistencia($pruebas, $filtros = []) {
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
    
    if (isset($filtros['tiempo_min'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['tiempo_total'] >= $filtros['tiempo_min'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de resistencia para un atleta
 */
function resumenResistenciaAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerResistenciaPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoResistenciaPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorTiempoResistenciaPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_tiempo' => promedioTiempoResistencia($cedula_atleta, $pruebas),
        'promedio_pausa' => promedioPausaResistencia($cedula_atleta, $pruebas),
        'mejor_tiempo' => $mejor ? $mejor['tiempo_total'] : 0,
        'ultima_fecha' => $ultima ? $ultima['fecha'] : null,
        'ultimo_tiempo' => $ultima ? $ultima['tiempo_total'] : 0,
        'evolucion' => evolucionResistenciaPorAtleta($cedula_atleta, $pruebas)
    ];
}
