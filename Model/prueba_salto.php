<?php
// ============================================
// PRUEBA DE SALTO (POTENCIA EXPLOSIVA)
// ============================================

$prueba_salto1 = [
    'pruebaS_id' => 1,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 210.00,
    'pruebaS_1er_salto' => 245.00,
    'pruebaS_2do_salto' => 248.00,
    'pruebaS_3er_salto' => 243.00,
    'pruebaS_Promedio' => 245.33,
    'pruebaS_fecha' => '2026-01-15'
];

$prueba_salto2 = [
    'pruebaS_id' => 2,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 210.00,
    'pruebaS_1er_salto' => 250.00,
    'pruebaS_2do_salto' => 252.00,
    'pruebaS_3er_salto' => 248.00,
    'pruebaS_Promedio' => 250.00,
    'pruebaS_fecha' => '2026-01-29'
];

$prueba_salto3 = [
    'pruebaS_id' => 3,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 190.00,
    'pruebaS_1er_salto' => 220.00,
    'pruebaS_2do_salto' => 222.00,
    'pruebaS_3er_salto' => 218.00,
    'pruebaS_Promedio' => 220.00,
    'pruebaS_fecha' => '2026-01-22'
];

$prueba_salto4 = [
    'pruebaS_id' => 4,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 230.00,
    'pruebaS_1er_salto' => 270.00,
    'pruebaS_2do_salto' => 275.00,
    'pruebaS_3er_salto' => 268.00,
    'pruebaS_Promedio' => 271.00,
    'pruebaS_fecha' => '2026-01-20'
];

$prueba_salto5 = [
    'pruebaS_id' => 5,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 170.00,
    'pruebaS_1er_salto' => 195.00,
    'pruebaS_2do_salto' => 198.00,
    'pruebaS_3er_salto' => 193.00,
    'pruebaS_Promedio' => 195.33,
    'pruebaS_fecha' => '2026-02-08'
];

$prueba_salto6 = [
    'pruebaS_id' => 6,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 200.00,
    'pruebaS_1er_salto' => 235.00,
    'pruebaS_2do_salto' => 238.00,
    'pruebaS_3er_salto' => 232.00,
    'pruebaS_Promedio' => 235.00,
    'pruebaS_fecha' => '2026-02-12'
];

$prueba_salto7 = [
    'pruebaS_id' => 7,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 240.00,
    'pruebaS_1er_salto' => 285.00,
    'pruebaS_2do_salto' => 290.00,
    'pruebaS_3er_salto' => 282.00,
    'pruebaS_Promedio' => 285.67,
    'pruebaS_fecha' => '2026-02-19'
];

$prueba_salto8 = [
    'pruebaS_id' => 8,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 190.00,
    'pruebaS_1er_salto' => 225.00,
    'pruebaS_2do_salto' => 228.00,
    'pruebaS_3er_salto' => 222.00,
    'pruebaS_Promedio' => 225.00,
    'pruebaS_fecha' => '2026-02-16'
];

$prueba_salto9 = [
    'pruebaS_id' => 9,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 230.00,
    'pruebaS_1er_salto' => 278.00,
    'pruebaS_2do_salto' => 282.00,
    'pruebaS_3er_salto' => 275.00,
    'pruebaS_Promedio' => 278.33,
    'pruebaS_fecha' => '2026-02-22'
];

$prueba_salto10 = [
    'pruebaS_id' => 10,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebaS_alt_inicial' => 210.00,
    'pruebaS_1er_salto' => 255.00,
    'pruebaS_2do_salto' => 258.00,
    'pruebaS_3er_salto' => 252.00,
    'pruebaS_Promedio' => 255.00,
    'pruebaS_fecha' => '2026-02-24'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE SALTO ============
$todas_pruebas_salto = [
    $prueba_salto1,
    $prueba_salto2,
    $prueba_salto3,
    $prueba_salto4,
    $prueba_salto5,
    $prueba_salto6,
    $prueba_salto7,
    $prueba_salto8,
    $prueba_salto9,
    $prueba_salto10
];



// ============================================
// /Model/Pruebas/funciones/funciones_salto.php
// FUNCIONES PARA PRUEBA DE SALTO
// ============================================

/**
 * Obtener todas las pruebas de salto
 */
function obtenerTodasSalto($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de salto por ID
 */
function buscarSaltoPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['pruebaS_id'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de salto por fecha (timestamp)
 */
function buscarSaltoPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['pruebaS_fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de salto por atleta
 */
function obtenerSaltoPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de salto por categoría
 */
function obtenerSaltoPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de salto por atleta
 */
function contarSaltoPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerSaltoPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de salto por categoría
 */
function contarSaltoPorCategoria($categoria_id, $pruebas) {
    return count(obtenerSaltoPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de salto
 */
function contarTotalSalto($pruebas) {
    return count($pruebas);
}

/**
 * Calcular promedio de salto por atleta
 */
function promedioSalto($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerSaltoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['pruebaS_Promedio'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor salto por atleta
 */
function mejorSaltoPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerSaltoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    foreach ($pruebas_atleta as $p) {
        if ($p['pruebaS_Promedio'] > $mejor['pruebaS_Promedio']) {
            $mejor = $p;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de salto por atleta
 */
function ultimoSaltoPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerSaltoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return $b['pruebaS_fecha'] - $a['pruebaS_fecha'];
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular evolución de salto por atleta (para gráficos)
 */
function evolucionSaltoPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerSaltoPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return $a['pruebaS_fecha'] - $b['pruebaS_fecha'];
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['pruebaS_fecha'],
            'altura_inicial' => $p['pruebaS_alt_inicial'],
            'salto_1' => $p['pruebaS_1er_salto'],
            'salto_2' => $p['pruebaS_2do_salto'],
            'salto_3' => $p['pruebaS_3er_salto'],
            'promedio' => $p['pruebaS_Promedio'],
            'mejora' => round($p['pruebaS_Promedio'] - $p['pruebaS_alt_inicial'], 2)
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de salto
 */
function estadisticasGeneralesSalto($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'promedio_salto' => 0,
            'max_salto' => 0,
            'min_salto' => 0,
            'total_atletas' => 0,
            'mejora_promedio' => 0
        ];
    }
    
    $total_salto = 0;
    $max_salto = 0;
    $min_salto = PHP_INT_MAX;
    $total_mejora = 0;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $total_salto += $p['pruebaS_Promedio'];
        if ($p['pruebaS_Promedio'] > $max_salto) $max_salto = $p['pruebaS_Promedio'];
        if ($p['pruebaS_Promedio'] < $min_salto) $min_salto = $p['pruebaS_Promedio'];
        $total_mejora += $p['pruebaS_Promedio'] - $p['pruebaS_alt_inicial'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    $count = count($pruebas);
    return [
        'total_pruebas' => $count,
        'promedio_salto' => round($total_salto / $count, 2),
        'max_salto' => $max_salto,
        'min_salto' => $min_salto,
        'total_atletas' => count(array_unique($atletas)),
        'mejora_promedio' => round($total_mejora / $count, 2)
    ];
}

/**
 * Filtrar pruebas de salto por criterios
 */
function filtrarSalto($pruebas, $filtros = []) {
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
    
    if (isset($filtros['salto_min'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return $p['pruebaS_Promedio'] >= $filtros['salto_min'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de salto para un atleta
 */
function resumenSaltoAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerSaltoPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoSaltoPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorSaltoPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_salto' => promedioSalto($cedula_atleta, $pruebas),
        'mejor_salto' => $mejor ? $mejor['pruebaS_Promedio'] : 0,
        'altura_inicial' => $ultima ? $ultima['pruebaS_alt_inicial'] : 0,
        'ultima_fecha' => $ultima ? $ultima['pruebaS_fecha'] : null,
        'ultimo_promedio' => $ultima ? $ultima['pruebaS_Promedio'] : 0,
        'mejora_total' => $ultima ? round($ultima['pruebaS_Promedio'] - $ultima['pruebaS_alt_inicial'], 2) : 0,
        'evolucion' => evolucionSaltoPorAtleta($cedula_atleta, $pruebas)
    ];
}
