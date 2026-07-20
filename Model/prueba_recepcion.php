<?php
// ============================================
// PRUEBA DE RECEPCIÓN DE BALÓN
// ============================================

$prueba_recepcion1 = [
    'id_recepcion' => 1,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'distancia_metros' => 15.00,
    'cantidad_pases' => 15,
    'derecha' => 5,
    'izquierda' => 3,
    'derecha_orientado' => 4,
    'izquierda_orientado' => 2,
    'orientado_giro' => 1,
    'fecha' => '2026-01-19'
];

$prueba_recepcion2 = [
    'id_recepcion' => 2,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'distancia_metros' => 15.00,
    'cantidad_pases' => 15,
    'derecha' => 6,
    'izquierda' => 4,
    'derecha_orientado' => 3,
    'izquierda_orientado' => 3,
    'orientado_giro' => 2,
    'fecha' => '2026-01-27'
];

$prueba_recepcion3 = [
    'id_recepcion' => 3,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'distancia_metros' => 12.00,
    'cantidad_pases' => 12,
    'derecha' => 4,
    'izquierda' => 2,
    'derecha_orientado' => 3,
    'izquierda_orientado' => 2,
    'orientado_giro' => 1,
    'fecha' => '2026-01-24'
];

$prueba_recepcion4 = [
    'id_recepcion' => 4,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'distancia_metros' => 18.00,
    'cantidad_pases' => 18,
    'derecha' => 7,
    'izquierda' => 5,
    'derecha_orientado' => 4,
    'izquierda_orientado' => 3,
    'orientado_giro' => 2,
    'fecha' => '2026-01-22'
];

$prueba_recepcion5 = [
    'id_recepcion' => 5,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28761234,
    'categoria_id' => 4,
    'distancia_metros' => 10.00,
    'cantidad_pases' => 10,
    'derecha' => 3,
    'izquierda' => 2,
    'derecha_orientado' => 2,
    'izquierda_orientado' => 1,
    'orientado_giro' => 1,
    'fecha' => '2026-02-06'
];

$prueba_recepcion6 = [
    'id_recepcion' => 6,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 31098765,
    'categoria_id' => 2,
    'distancia_metros' => 14.00,
    'cantidad_pases' => 14,
    'derecha' => 5,
    'izquierda' => 3,
    'derecha_orientado' => 3,
    'izquierda_orientado' => 2,
    'orientado_giro' => 1,
    'fecha' => '2026-02-10'
];

$prueba_recepcion7 = [
    'id_recepcion' => 7,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29456789,
    'categoria_id' => 5,
    'distancia_metros' => 20.00,
    'cantidad_pases' => 20,
    'derecha' => 8,
    'izquierda' => 6,
    'derecha_orientado' => 5,
    'izquierda_orientado' => 4,
    'orientado_giro' => 2,
    'fecha' => '2026-02-17'
];

$prueba_recepcion8 = [
    'id_recepcion' => 8,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 29567123,
    'categoria_id' => 2,
    'distancia_metros' => 12.00,
    'cantidad_pases' => 12,
    'derecha' => 5,
    'izquierda' => 3,
    'derecha_orientado' => 3,
    'izquierda_orientado' => 2,
    'orientado_giro' => 2,
    'fecha' => '2026-02-14'
];

$prueba_recepcion9 = [
    'id_recepcion' => 9,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 30123456,
    'categoria_id' => 3,
    'distancia_metros' => 18.00,
    'cantidad_pases' => 18,
    'derecha' => 8,
    'izquierda' => 5,
    'derecha_orientado' => 4,
    'izquierda_orientado' => 3,
    'orientado_giro' => 2,
    'fecha' => '2026-02-20'
];

$prueba_recepcion10 = [
    'id_recepcion' => 10,
    'usuario_cedula' => 26804373,
    'atleta_cedula' => 28765432,
    'categoria_id' => 1,
    'distancia_metros' => 15.00,
    'cantidad_pases' => 15,
    'derecha' => 7,
    'izquierda' => 4,
    'derecha_orientado' => 4,
    'izquierda_orientado' => 3,
    'orientado_giro' => 2,
    'fecha' => '2026-02-22'
];

// ============ LISTA DE TODAS LAS PRUEBAS DE RECEPCIÓN ============
$todas_pruebas_recepcion = [
    $prueba_recepcion1,
    $prueba_recepcion2,
    $prueba_recepcion3,
    $prueba_recepcion4,
    $prueba_recepcion5,
    $prueba_recepcion6,
    $prueba_recepcion7,
    $prueba_recepcion8,
    $prueba_recepcion9,
    $prueba_recepcion10
];


// ============================================
// /Model/Pruebas/funciones/funciones_recepcion.php
// FUNCIONES PARA PRUEBA DE RECEPCIÓN DE BALÓN
// ============================================

/**
 * Obtener todas las pruebas de recepción
 */
function obtenerTodasRecepcion($pruebas) {
    return $pruebas;
}

/**
 * Buscar prueba de recepción por ID
 */
function buscarRecepcionPorId($id, $pruebas) {
    foreach ($pruebas as $p) {
        if ($p['id_recepcion'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Buscar pruebas de recepción por fecha
 */
function buscarRecepcionPorFecha($fecha, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['fecha'] == $fecha) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de recepción por atleta
 */
function obtenerRecepcionPorAtleta($cedula_atleta, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['atleta_cedula'] == $cedula_atleta) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Obtener pruebas de recepción por categoría
 */
function obtenerRecepcionPorCategoria($categoria_id, $pruebas) {
    $resultado = [];
    foreach ($pruebas as $p) {
        if ($p['categoria_id'] == $categoria_id) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

/**
 * Contar pruebas de recepción por atleta
 */
function contarRecepcionPorAtleta($cedula_atleta, $pruebas) {
    return count(obtenerRecepcionPorAtleta($cedula_atleta, $pruebas));
}

/**
 * Contar pruebas de recepción por categoría
 */
function contarRecepcionPorCategoria($categoria_id, $pruebas) {
    return count(obtenerRecepcionPorCategoria($categoria_id, $pruebas));
}

/**
 * Contar total de pruebas de recepción
 */
function contarTotalRecepcion($pruebas) {
    return count($pruebas);
}

/**
 * Calcular total de controles exitosos por atleta
 */
function totalControlesExitosos($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p['derecha'] + $p['izquierda'] + 
                  $p['derecha_orientado'] + $p['izquierda_orientado'] + 
                  $p['orientado_giro'];
    }
    return $total;
}

/**
 * Calcular efectividad de recepción por atleta
 */
function efectividadRecepcion($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total_pases = 0;
    $exitos = 0;
    foreach ($pruebas_atleta as $p) {
        $total_pases += $p['cantidad_pases'];
        $exitos += $p['derecha'] + $p['izquierda'] + 
                   $p['derecha_orientado'] + $p['izquierda_orientado'] + 
                   $p['orientado_giro'];
    }
    return $total_pases > 0 ? round(($exitos / $total_pases) * 100, 2) : 0;
}

/**
 * Calcular promedio de controles por tipo
 */
function promedioControlesPorTipo($cedula_atleta, $pruebas, $tipo) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return 0;
    
    $total = 0;
    foreach ($pruebas_atleta as $p) {
        $total += $p[$tipo];
    }
    return round($total / count($pruebas_atleta), 2);
}

/**
 * Obtener mejor registro de recepción por atleta
 */
function mejorRecepcionPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    $mejor = $pruebas_atleta[0];
    $mejor_efectividad = efectividadRecepcionPrueba($pruebas_atleta[0]);
    
    foreach ($pruebas_atleta as $p) {
        $efectividad = efectividadRecepcionPrueba($p);
        if ($efectividad > $mejor_efectividad) {
            $mejor = $p;
            $mejor_efectividad = $efectividad;
        }
    }
    return $mejor;
}

/**
 * Calcular efectividad de una prueba individual de recepción
 */
function efectividadRecepcionPrueba($prueba) {
    $exitos = $prueba['derecha'] + $prueba['izquierda'] + 
              $prueba['derecha_orientado'] + $prueba['izquierda_orientado'] + 
              $prueba['orientado_giro'];
    return $prueba['cantidad_pases'] > 0 ? round(($exitos / $prueba['cantidad_pases']) * 100, 2) : 0;
}

/**
 * Obtener último registro de recepción por atleta
 */
function ultimoRecepcionPorAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) return null;
    
    usort($pruebas_atleta, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });
    return $pruebas_atleta[0];
}

/**
 * Calcular distribución de tipos de control
 */
function distribucionControles($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    if (empty($pruebas_atleta)) {
        return [
            'estaticos' => 0,
            'orientados' => 0,
            'con_giro' => 0
        ];
    }
    
    $estaticos = 0;
    $orientados = 0;
    $con_giro = 0;
    
    foreach ($pruebas_atleta as $p) {
        $estaticos += $p['derecha'] + $p['izquierda'];
        $orientados += $p['derecha_orientado'] + $p['izquierda_orientado'];
        $con_giro += $p['orientado_giro'];
    }
    
    return [
        'estaticos' => $estaticos,
        'orientados' => $orientados,
        'con_giro' => $con_giro
    ];
}

/**
 * Obtener estadísticas generales de recepción
 */
function estadisticasGeneralesRecepcion($pruebas) {
    if (empty($pruebas)) {
        return [
            'total_pruebas' => 0,
            'total_pases' => 0,
            'total_exitos' => 0,
            'efectividad_promedio' => 0,
            'total_atletas' => 0
        ];
    }
    
    $total_pases = 0;
    $total_exitos = 0;
    $atletas = [];
    
    foreach ($pruebas as $p) {
        $total_pases += $p['cantidad_pases'];
        $exitos = $p['derecha'] + $p['izquierda'] + 
                  $p['derecha_orientado'] + $p['izquierda_orientado'] + 
                  $p['orientado_giro'];
        $total_exitos += $exitos;
        $atletas[] = $p['atleta_cedula'];
    }
    
    return [
        'total_pruebas' => count($pruebas),
        'total_pases' => $total_pases,
        'total_exitos' => $total_exitos,
        'efectividad_promedio' => $total_pases > 0 ? round(($total_exitos / $total_pases) * 100, 2) : 0,
        'total_atletas' => count(array_unique($atletas))
    ];
}

/**
 * Filtrar pruebas de recepción por criterios
 */
function filtrarRecepcion($pruebas, $filtros = []) {
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
    
    if (isset($filtros['efectividad_min'])) {
        $resultado = array_filter($resultado, function($p) use ($filtros) {
            return efectividadRecepcionPrueba($p) >= $filtros['efectividad_min'];
        });
    }
    
    return array_values($resultado);
}

/**
 * Obtener resumen rápido de recepción para un atleta
 */
function resumenRecepcionAtleta($cedula_atleta, $pruebas) {
    $pruebas_atleta = obtenerRecepcionPorAtleta($cedula_atleta, $pruebas);
    $ultima = ultimoRecepcionPorAtleta($cedula_atleta, $pruebas);
    $mejor = mejorRecepcionPorAtleta($cedula_atleta, $pruebas);
    
    return [
        'total_pruebas' => count($pruebas_atleta),
        'efectividad_promedio' => efectividadRecepcion($cedula_atleta, $pruebas),
        'promedio_controles_estaticos' => promedioControlesPorTipo($cedula_atleta, $pruebas, 'derecha') + 
                                          promedioControlesPorTipo($cedula_atleta, $pruebas, 'izquierda'),
        'promedio_controles_orientados' => promedioControlesPorTipo($cedula_atleta, $pruebas, 'derecha_orientado') + 
                                          promedioControlesPorTipo($cedula_atleta, $pruebas, 'izquierda_orientado'),
        'promedio_controles_con_giro' => promedioControlesPorTipo($cedula_atleta, $pruebas, 'orientado_giro'),
        'mejor_efectividad' => $mejor ? efectividadRecepcionPrueba($mejor) : 0,
        'ultima_fecha' => $ultima ? $ultima['fecha'] : null,
        'distribucion' => distribucionControles($cedula_atleta, $pruebas)
    ];
}
