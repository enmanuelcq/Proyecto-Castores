<?php
// ============================================
// PRUEBA DE FUERZA
// ============================================

$prueba_fuerza1 = [
    'pruebaf_id' => 1,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 35,
    'pruebaf_abdominales_1min' => 42,
    'pruebaf_sentadillas_1min' => 38,
    'pruebaf_fecha' => '2026-01-10'
];

$prueba_fuerza2 = [
    'pruebaf_id' => 2,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 38,
    'pruebaf_abdominales_1min' => 45,
    'pruebaf_sentadillas_1min' => 40,
    'pruebaf_fecha' => '2026-01-25'
];

$prueba_fuerza3 = [
    'pruebaf_id' => 3,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 25,
    'pruebaf_abdominales_1min' => 32,
    'pruebaf_sentadillas_1min' => 28,
    'pruebaf_fecha' => '2026-01-12'
];

$prueba_fuerza4 = [
    'pruebaf_id' => 4,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 45,
    'pruebaf_abdominales_1min' => 50,
    'pruebaf_sentadillas_1min' => 48,
    'pruebaf_fecha' => '2026-01-14'
];

$prueba_fuerza5 = [
    'pruebaf_id' => 5,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 15,
    'pruebaf_abdominales_1min' => 22,
    'pruebaf_sentadillas_1min' => 18,
    'pruebaf_fecha' => '2026-02-04'
];

$prueba_fuerza6 = [
    'pruebaf_id' => 6,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 30,
    'pruebaf_abdominales_1min' => 38,
    'pruebaf_sentadillas_1min' => 35,
    'pruebaf_fecha' => '2026-02-08'
];

$prueba_fuerza7 = [
    'pruebaf_id' => 7,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 50,
    'pruebaf_abdominales_1min' => 55,
    'pruebaf_sentadillas_1min' => 52,
    'pruebaf_fecha' => '2026-02-15'
];

$prueba_fuerza8 = [
    'pruebaf_id' => 8,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 28,
    'pruebaf_abdominales_1min' => 35,
    'pruebaf_sentadillas_1min' => 30,
    'pruebaf_fecha' => '2026-02-12'
];

$prueba_fuerza9 = [
    'pruebaf_id' => 9,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 48,
    'pruebaf_abdominales_1min' => 52,
    'pruebaf_sentadillas_1min' => 50,
    'pruebaf_fecha' => '2026-02-18'
];

$prueba_fuerza10 = [
    'pruebaf_id' => 10,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'usuario_cedula' => 26804373,
    'pruebaf_flexiones_1min' => 40,
    'pruebaf_abdominales_1min' => 48,
    'pruebaf_sentadillas_1min' => 42,
    'pruebaf_fecha' => '2026-02-20'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE FUERZA ============
$todas_pruebas_fuerza = [
    $prueba_fuerza1,
    $prueba_fuerza2,
    $prueba_fuerza3,
    $prueba_fuerza4,
    $prueba_fuerza5,
    $prueba_fuerza6,
    $prueba_fuerza7,
    $prueba_fuerza8,
    $prueba_fuerza9,
    $prueba_fuerza10
];


// ============================================
// /Model/Pruebas/funciones/funciones_fuerza.php
// FUNCIONES PARA PRUEBA DE FUERZA
// ============================================

/**
 * Obtener todas las pruebas de fuerza
 */
function obtenerTodasFuerza($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de fuerza por ID
 */
function buscarFuerzaPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['pruebaf_id'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Obtener pruebas de fuerza por atleta
 */
function obtenerFuerzaPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de fuerza por categoría
 */
function obtenerFuerzaPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de fuerza por atleta
 */
function contarFuerzaPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerFuerzaPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Calcular promedio de un ejercicio específico
 */
function promedioFuerzaEjercicio($cedula_atleta, $pruebas, $ejercicio) {
    $pruebas_atleta = obtenerFuerzaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $campo = 'pruebaf_' . $ejercicio . '_1min';
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p[$campo];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Calcular puntuación total de fuerza (suma de los 3 ejercicios)
 */
function puntuacionTotalFuerza($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerFuerzaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['pruebaf_flexiones_1min'] + 
                  $p['pruebaf_abdominales_1min'] + 
                  $p['pruebaf_sentadillas_1min'];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor registro de fuerza por atleta
 */
function mejorFuerzaPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerFuerzaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    $mejor_puntuacion = $pruebas_atleta[0]['pruebaf_flexiones_1min'] + 
                        $pruebas_atleta[0]['pruebaf_abdominales_1min'] + 
                        $pruebas_atleta[0]['pruebaf_sentadillas_1min'];
    
    foreach ($pruebas_atleta as $p) {
        $puntuacion = $p['pruebaf_flexiones_1min'] + 
                      $p['pruebaf_abdominales_1min'] + 
                      $p['pruebaf_sentadillas_1min'];
        if ($puntuacion > $mejor_puntuacion) {
            $mejor = $p;
            $mejor_puntuacion = $puntuacion;
        }
    }
    return $mejor;
}

/**
 * Obtener último registro de fuerza por atleta
 */
function ultimoFuerzaPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerFuerzaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['pruebaf_fecha']) - strtotime($a['pruebaf_fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Obtener evolución de fuerza por atleta
 */
function evolucionFuerzaPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerFuerzaPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return [];
    
    $evolucion = [];
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($a['pruebaf_fecha']) - strtotime($b['pruebaf_fecha']);
    });
    
    foreach ($pruebas_atleta as $p) {
        $evolucion[] = [
            'fecha' => $p['pruebaf_fecha'],
            'flexiones' => $p['pruebaf_flexiones_1min'],
            'abdominales' => $p['pruebaf_abdominales_1min'],
            'sentadillas' => $p['pruebaf_sentadillas_1min'],
            'total' => $p['pruebaf_flexiones_1min'] + 
                       $p['pruebaf_abdominales_1min'] + 
                       $p['pruebaf_sentadillas_1min']
        ];
    }
    return $evolucion;
}

/**
 * Obtener estadísticas generales de fuerza
 */
function estadisticasGeneralesFuerza($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'promedio_flexiones' => 0,
            'promedio_abdominales' => 0,
            'promedio_sentadillas' => 0,
            'total_atletas' => 0
        ];
    }
    
    $total_flexiones = 0;
    $total_abdominales = 0;
    $total_sentadillas = 0;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $total_flexiones += $p['pruebaf_flexiones_1min'];
        $total_abdominales += $p['pruebaf_abdominales_1min'];
        $total_sentadillas += $p['pruebaf_sentadillas_1min'];
        $atletas[] = $p['atleta_cedula'];
    }
    
    $count = count($pruebas);
    return [
        'total_pruebas' => $count,
        'promedio_flexiones' => round($total_flexiones / $count, 2),
        'promedio_abdominales' => round($total_abdominales / $count, 2),
        'promedio_sentadillas' => round($total_sentadillas / $count, 2),
        'total_atletas' => count(array_unique($atletas))
    ];
}

/**
 * Obtener resumen rápido de fuerza para un atleta
 */
function resumenFuerzaAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerFuerzaPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoFuerzaPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorFuerzaPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'promedio_flexiones' => promedioFuerzaEjercicio($cedula_atleta, $pruebas, 'flexiones'),
        'promedio_abdominales' => promedioFuerzaEjercicio($cedula_atleta, $pruebas, 'abdominales'),
        'promedio_sentadillas' => promedioFuerzaEjercicio($cedula_atleta, $pruebas, 'sentadillas'),
        'puntuacion_total' => puntuacionTotalFuerza($cedula_atleta, $pruebas),
        'mejor_puntuacion' => $mejor ? $mejor['pruebaf_flexiones_1min'] + 
                                       $mejor['pruebaf_abdominales_1min'] + 
                                       $mejor['pruebaf_sentadillas_1min'] : 0,
        'ultima_fecha' => $ultima ? $ultima['pruebaf_fecha'] : null,
        'evolucion' => evolucionFuerzaPorAtleta($cedula_atleta, $pruebas)
    ];
}
