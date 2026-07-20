<?php
// ============================================
// PRUEBA DE VELOCIDAD (SPRINTS)
// ============================================

$prueba_velocidad1 = [
    'pruebav_id' => 1,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 30.00,
    'pruebav_1er_intento_seg' => 4.35,
    'pruebav_2do_intento_seg' => 4.28,
    'pruebav_3er_intento_seg' => 4.32,
    'pruebav_Promedio' => 4.32,
    'pruebav_fecha' => '2026-01-21'
];

$prueba_velocidad2 = [
    'pruebav_id' => 2,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 30.00,
    'pruebav_1er_intento_seg' => 4.18,
    'pruebav_2do_intento_seg' => 4.22,
    'pruebav_3er_intento_seg' => 4.15,
    'pruebav_Promedio' => 4.18,
    'pruebav_fecha' => '2026-01-30'
];

$prueba_velocidad3 = [
    'pruebav_id' => 3,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 25.00,
    'pruebav_1er_intento_seg' => 4.85,
    'pruebav_2do_intento_seg' => 4.78,
    'pruebav_3er_intento_seg' => 4.82,
    'pruebav_Promedio' => 4.82,
    'pruebav_fecha' => '2026-01-26'
];

$prueba_velocidad4 = [
    'pruebav_id' => 4,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 35.00,
    'pruebav_1er_intento_seg' => 4.65,
    'pruebav_2do_intento_seg' => 4.58,
    'pruebav_3er_intento_seg' => 4.62,
    'pruebav_Promedio' => 4.62,
    'pruebav_fecha' => '2026-01-24'
];

$prueba_velocidad5 = [
    'pruebav_id' => 5,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 20.00,
    'pruebav_1er_intento_seg' => 5.20,
    'pruebav_2do_intento_seg' => 5.15,
    'pruebav_3er_intento_seg' => 5.18,
    'pruebav_Promedio' => 5.18,
    'pruebav_fecha' => '2026-02-09'
];

$prueba_velocidad6 = [
    'pruebav_id' => 6,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 28.00,
    'pruebav_1er_intento_seg' => 4.55,
    'pruebav_2do_intento_seg' => 4.48,
    'pruebav_3er_intento_seg' => 4.52,
    'pruebav_Promedio' => 4.52,
    'pruebav_fecha' => '2026-02-13'
];

$prueba_velocidad7 = [
    'pruebav_id' => 7,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 40.00,
    'pruebav_1er_intento_seg' => 5.45,
    'pruebav_2do_intento_seg' => 5.38,
    'pruebav_3er_intento_seg' => 5.42,
    'pruebav_Promedio' => 5.42,
    'pruebav_fecha' => '2026-02-20'
];

$prueba_velocidad8 = [
    'pruebav_id' => 8,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 25.00,
    'pruebav_1er_intento_seg' => 4.72,
    'pruebav_2do_intento_seg' => 4.68,
    'pruebav_3er_intento_seg' => 4.70,
    'pruebav_Promedio' => 4.70,
    'pruebav_fecha' => '2026-02-17'
];

$prueba_velocidad9 = [
    'pruebav_id' => 9,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 35.00,
    'pruebav_1er_intento_seg' => 4.50,
    'pruebav_2do_intento_seg' => 4.45,
    'pruebav_3er_intento_seg' => 4.48,
    'pruebav_Promedio' => 4.48,
    'pruebav_fecha' => '2026-02-23'
];

$prueba_velocidad10 = [
    'pruebav_id' => 10,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebav_distancia_mtr' => 30.00,
    'pruebav_1er_intento_seg' => 4.10,
    'pruebav_2do_intento_seg' => 4.05,
    'pruebav_3er_intento_seg' => 4.08,
    'pruebav_Promedio' => 4.08,
    'pruebav_fecha' => '2026-02-25'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE VELOCIDAD ============
$todas_pruebas_velocidad = [
    $prueba_velocidad1,
    $prueba_velocidad2,
    $prueba_velocidad3,
    $prueba_velocidad4,
    $prueba_velocidad5,
    $prueba_velocidad6,
    $prueba_velocidad7,
    $prueba_velocidad8,
    $prueba_velocidad9,
    $prueba_velocidad10
];

// ============================================
// /Model/Pruebas/funciones/funciones_velocidad.php
// FUNCIONES PARA PRUEBA DE VELOCIDAD
// ============================================

/**
 * Obtener todas las pruebas de velocidad
 */
function obtenerTodasVelocidad($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de velocidad por ID
 */
function buscarVelocidadPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['pruebav_id'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de velocidad por fecha
 */
function buscarVelocidadPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['pruebav_fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Buscar pruebas de velocidad por rango de fechas
 */
function buscarVelocidadPorRangoFechas($fecha_inicio, $fecha_fin, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['pruebav_fecha'] >= $fecha_inicio && $p['pruebav_fecha'] <= $fecha_fin) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de velocidad por atleta
 */
function obtenerVelocidadPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de velocidad por categoría
 */
function obtenerVelocidadPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de velocidad por atleta
 */
function contarVelocidadPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerVelocidadPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de velocidad por categoría
 */
function contarVelocidadPorCategoria($categoria_id, $pruebas) {
    return count(obtenerVelocidadPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de velocidad
 */
function contarTotalVelocidad($pruebas) {
    return count($pruebas);
}

/**
 * Calcular promedio de velocidad por atleta
 */
function promedioVelocidad($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerVelocidadPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['pruebav_Promedio'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor tiempo de velocidad por atleta (menor tiempo)
 */
function mejorTiempoVelocidadPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerVelocidadPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    foreach ($pruebas_atleta as $p) {
        if ($p['pruebav_Promedio'] < $mejor['pruebav_Promedio']) {
            $mejor = $p;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de velocidad por atleta
 */
function ultimoVelocidadPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerVelocidadPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['pruebav_fecha']) - strtotime($a['pruebav_fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular evolución de velocidad por atleta (para gráficos)
 */
function evolucionVelocidadPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerVelocidadPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($a['pruebav_fecha']) - strtotime($b['pruebav_fecha']);
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['pruebav_fecha'],
            'distancia' => $p['pruebav_distancia_mtr'],
            'intento_1' => $p['pruebav_1er_intento_seg'],
            'intento_2' => $p['pruebav_2do_intento_seg'],
            'intento_3' => $p['pruebav_3er_intento_seg'],
            'promedio' => $p['pruebav_Promedio'],
            'mejora' => round($p['pruebav_1er_intento_seg'] - $p['pruebav_Promedio'], 2)
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de velocidad
 */
function estadisticasGeneralesVelocidad($pruebas) {
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
        $total_tiempo += $p['pruebav_Promedio'];
        if ($p['pruebav_Promedio'] < $mejor_tiempo) $mejor_tiempo = $p['pruebav_Promedio'];
        if ($p['pruebav_Promedio'] > $peor_tiempo) $peor_tiempo = $p['pruebav_Promedio'];
        $total_distancia += $p['pruebav_distancia_mtr'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    $count = count($pruebas);
    return [
        'total_pruebas' => $count,
        'promedio_tiempo' => round($total_tiempo / $count, 2),
        'mejor_tiempo' => $mejor_tiempo,
        'peor_tiempo' => $peor_tiempo,
        'total_atletas' => count(array_unique($atletas)),
        'distancia_promedio' => round($total_distancia / $count, 2)
    ];
}

/**
 * Filtrar pruebas de velocidad por criterios
 */
function filtrarVelocidad($pruebas, $filtros = []) {
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
    
    if (isset($filtros['tiempo_max'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['pruebav_Promedio'] <= $filtros['tiempo_max'];
        });
    }
    
    if (isset($filtros['distancia'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['pruebav_distancia_mtr'] == $filtros['distancia'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de velocidad para un atleta
 */
function resumenVelocidadAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerVelocidadPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoVelocidadPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorTiempoVelocidadPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_tiempo' => promedioVelocidad($cedula_atleta, $pruebas),
        'mejor_tiempo' => $mejor ? $mejor['pruebav_Promedio'] : 0,
        'distancia' => $ultima ? $ultima['pruebav_distancia_mtr'] : 0,
        'ultima_fecha' => $ultima ? $ultima['pruebav_fecha'] : null,
        'ultimo_tiempo' => $ultima ? $ultima['pruebav_Promedio'] : 0,
        'mejora_total' => $ultima ? round($ultima['pruebav_1er_intento_seg'] - $ultima['pruebav_Promedio'], 2) : 0,
        'evolucion' => evolucionVelocidadPorAtleta($cedula_atleta, $pruebas)
    ];
}

/**
 * Comparar intentos de velocidad
 */
function compararIntentosVelocidad($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerVelocidadPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $total_intento1 = 0;
    $total_intento2 = 0;
    $total_intento3 = 0;
    $count = 0;
    
    foreach ($pruebas_atleta as $p) {
        $total_intento1 += $p['pruebav_1er_intento_seg'];
        $total_intento2 += $p['pruebav_2do_intento_seg'];
        $total_intento3 += $p['pruebav_3er_intento_seg'];
        $count++;
    }
    
    return [
        'promedio_intento1' => round($total_intento1 / $count, 2),
        'promedio_intento2' => round($total_intento2 / $count, 2),
        'promedio_intento3' => round($total_intento3 / $count, 2),
        'mejor_intento' => min($total_intento1, $total_intento2, $total_intento3) / $count,
        'mejora_intento1_a_intento3' => round(($total_intento1 - $total_intento3) / $count, 2)
    ];
}
