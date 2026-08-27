<?php
// ============================================
// CONFIGURACIÓN DE PRUEBAS DEPORTIVAS
// ============================================

// Incluir todos los archivos de pruebas
require_once './Model/prueba_balon_aereo.php';
require_once './Model/prueba_conduccion.php';
require_once './Model/prueba_definicion.php';
require_once './Model/prueba_fuerza.php';
require_once './Model/prueba_pases.php';
require_once './Model/prueba_recepcion.php';
require_once './Model/prueba_resistencia.php';
require_once './Model/prueba_salto.php';
require_once './Model/prueba_velocidad.php';
require_once './Model/Atletas.php';



// Agrupar todas las pruebas
$todas_las_pruebas = [
    'balon_aereo' => $todas_pruebas_balon_aereo,
    'conduccion' => $todas_pruebas_conduccion,
    'definicion' => $todas_pruebas_definicion,
    'fuerza' => $todas_pruebas_fuerza,
    'pases' => $todas_pruebas_pases,
    'recepcion' => $todas_pruebas_recepcion,
    'resistencia' => $todas_pruebas_resistencia,
    'salto' => $todas_pruebas_salto,
    'velocidad' => $todas_pruebas_velocidad
];

// Configuración de tipos de prueba
$tipos_prueba = [
    'balon_aereo' => [
        'nombre' => 'Balón Aéreo',
        'icono' => 'bi-balloon-heart',
        'color' => '#FF6B6B',
        'bg' => 'rgba(13,110,253,0.12)',
        'descripcion' => 'Control y dominio aéreo'
    ],
    'conduccion' => [
        'nombre' => 'Conducción',
        'icono' => 'bi-arrows-move',
        'color' => '#4ECDC4',
        'bg' => 'rgba(25,135,84,0.12)',
        'descripcion' => 'Conducción con obstáculos'
    ],
    'definicion' => [
        'nombre' => 'Definición',
        'icono' => 'bi-bullseye',
        'color' => '#FF9F43',
        'bg' => 'rgba(220,53,69,0.12)',
        'descripcion' => 'Tiros a portería'
    ],
    'fuerza' => [
        'nombre' => 'Fuerza',
        'icono' => 'bi-heart-pulse',
        'color' => '#FECA57',
        'bg' => 'rgba(255,193,7,0.12)',
        'descripcion' => 'Fuerza-resistencia'
    ],
    'pases' => [
        'nombre' => 'Pases',
        'icono' => 'bi-arrow-left-right',
        'color' => '#48DBFB',
        'bg' => 'rgba(13,202,240,0.12)',
        'descripcion' => 'Precisión de pases'
    ],
    'recepcion' => [
        'nombre' => 'Recepción',
        'icono' => 'bi-hand-index',
        'color' => '#FF9FF3',
        'bg' => 'rgba(108,117,125,0.12)',
        'descripcion' => 'Control de balón'
    ],
    'resistencia' => [
        'nombre' => 'Resistencia',
        'icono' => 'bi-clock-history',
        'color' => '#54A0FF',
        'bg' => 'rgba(111,66,193,0.12)',
        'descripcion' => 'Capacidad aeróbica'
    ],
    'salto' => [
        'nombre' => 'Salto',
        'icono' => 'bi-arrow-up',
        'color' => '#5F27CD',
        'bg' => 'rgba(255,193,7,0.12)',
        'descripcion' => 'Potencia explosiva'
    ],
    'velocidad' => [
        'nombre' => 'Velocidad',
        'icono' => 'bi-speedometer2',
        'color' => '#1DD1A1',
        'bg' => 'rgba(13,110,253,0.12)',
        'descripcion' => 'Sprint y aceleración'
    ]
];

// Mapeo de funciones por tipo
$funciones_por_tipo = [
    'balon_aereo' => [
        'obtener_por_atleta' => 'obtenerBalonAereoPorAtleta',
        'contar_por_atleta' => 'contarBalonAereoPorAtleta',
        'resumen' => 'resumenBalonAereoAtleta',
        'estadisticas' => 'estadisticasGeneralesBalonAereo',
        'evolucion' => 'evolucionBalonAereoPorAtleta'
    ],
    'conduccion' => [
        'obtener_por_atleta' => 'obtenerConduccionPorAtleta',
        'contar_por_atleta' => 'contarConduccionPorAtleta',
        'resumen' => 'resumenConduccionAtleta',
        'estadisticas' => 'estadisticasGeneralesConduccion',
        'evolucion' => 'evolucionConduccionPorAtleta'
    ],
    'definicion' => [
        'obtener_por_atleta' => 'obtenerDefinicionPorAtleta',
        'contar_por_atleta' => 'contarDefinicionPorAtleta',
        'resumen' => 'resumenDefinicionAtleta',
        'estadisticas' => 'estadisticasGeneralesDefinicion'
    ],
    'fuerza' => [
        'obtener_por_atleta' => 'obtenerFuerzaPorAtleta',
        'contar_por_atleta' => 'contarFuerzaPorAtleta',
        'resumen' => 'resumenFuerzaAtleta',
        'estadisticas' => 'estadisticasGeneralesFuerza'
    ],
    'pases' => [
        'obtener_por_atleta' => 'obtenerPasesPorAtleta',
        'contar_por_atleta' => 'contarPasesPorAtleta',
        'resumen' => 'resumenPasesAtleta',
        'estadisticas' => 'estadisticasGeneralesPases'
    ],
    'recepcion' => [
        'obtener_por_atleta' => 'obtenerRecepcionPorAtleta',
        'contar_por_atleta' => 'contarRecepcionPorAtleta',
        'resumen' => 'resumenRecepcionAtleta',
        'estadisticas' => 'estadisticasGeneralesRecepcion'
    ],
    'resistencia' => [
        'obtener_por_atleta' => 'obtenerResistenciaPorAtleta',
        'contar_por_atleta' => 'contarResistenciaPorAtleta',
        'resumen' => 'resumenResistenciaAtleta',
        'estadisticas' => 'estadisticasGeneralesResistencia'
    ],
    'salto' => [
        'obtener_por_atleta' => 'obtenerSaltoPorAtleta',
        'contar_por_atleta' => 'contarSaltoPorAtleta',
        'resumen' => 'resumenSaltoAtleta',
        'estadisticas' => 'estadisticasGeneralesSalto'
    ],
    'velocidad' => [
        'obtener_por_atleta' => 'obtenerVelocidadPorAtleta',
        'contar_por_atleta' => 'contarVelocidadPorAtleta',
        'resumen' => 'resumenVelocidadAtleta',
        'estadisticas' => 'estadisticasGeneralesVelocidad'
    ]
];


/**
 * Obtener todas las pruebas de un atleta agrupadas por tipo
 */
function obtenerPruebasPorAtleta($cedula_atleta, $todas_las_pruebas) {
    $resultado = [];
    
    foreach($todas_las_pruebas as $tipo => $pruebas) {
        foreach($pruebas as $prueba) {
            if(isset($prueba['atleta_cedula']) && $prueba['atleta_cedula'] == $cedula_atleta) {
                $resultado[$tipo][] = $prueba;
            }
        }
    }
    
    return $resultado;
}

/**
 * Obtener funciones para un tipo específico
 */
function obtenerFuncionesPorTipo($tipo) {
    global $funciones_por_tipo;
    return isset($funciones_por_tipo[$tipo]) ? $funciones_por_tipo[$tipo] : null;
}

/**
 * Obtener resumen de pruebas por tipo y atleta
 */
function obtenerResumenPruebaPorTipo($tipo, $cedula_atleta, $todas_las_pruebas) {
    $funciones = obtenerFuncionesPorTipo($tipo);
    if (!$funciones || !isset($funciones['resumen'])) return null;
    
    $pruebas = isset($todas_las_pruebas[$tipo]) ? $todas_las_pruebas[$tipo] : [];
    return $funciones['resumen']($cedula_atleta, $pruebas);
}

// Función para obtener el valor principal de una prueba
function obtenerValorPrincipal($prueba, $tipo) {
    if(!$prueba) return '—';
    
    $campos = [
        'balon_aereo' => 'total_toques',
        'conduccion' => 'tiempo_ambas_piernas',
        'definicion' => 'cantidad_tiros',
        'fuerza' => 'pruebaf_flexiones_1min',
        'pases' => 'total_pases',
        'recepcion' => 'cantidad_pases',
        'resistencia' => 'tiempo_total',
        'salto' => 'pruebaS_Promedio',
        'velocidad' => 'pruebav_Promedio'
    ];
    
    $campo = isset($campos[$tipo]) ? $campos[$tipo] : null;
    if($campo && isset($prueba[$campo])) {
        return $prueba[$campo];
    }
    return '—';
}

function formatearValor($valor, $tipo) {
    if($valor === '—') return '—';
    
    $formatos = [
        'balon_aereo' => $valor . ' toques',
        'conduccion' => $valor . 's',
        'definicion' => $valor . ' tiros',
        'fuerza' => $valor . ' rep',
        'pases' => $valor . ' pases',
        'recepcion' => $valor . ' controles',
        'resistencia' => $valor . ' min',
        'salto' => $valor . ' cm',
        'velocidad' => $valor . 's'
    ];
    
    return isset($formatos[$tipo]) ? $formatos[$tipo] : $valor;
}

function obtenerFechaPrueba($prueba) {
    if(isset($prueba['fecha'])) return $prueba['fecha'];
    if(isset($prueba['pruebaf_fecha'])) return $prueba['pruebaf_fecha'];
    if(isset($prueba['pruebaS_fecha'])) return $prueba['pruebaS_fecha'];
    if(isset($prueba['pruebav_fecha'])) return $prueba['pruebav_fecha'];
    return null;
}

function obtenerNombreTipo($clave) {
    global $tipos_prueba;
    return isset($tipos_prueba[$clave]) ? $tipos_prueba[$clave]['nombre'] : $clave;
}

// ===== FUNCIÓN PARA OBTENER COLORES =====
function obtenerColorTipo($clave) {
    global $tipos_prueba;
    
    // Si el tipo tiene un color definido, usarlo
    if(isset($tipos_prueba[$clave]['color'])) {
        return $tipos_prueba[$clave]['color'];
    }
    
    // Colores por defecto si no hay configuración
    $colores_defecto = [
        'balon_aereo' => '#FF6B6B',
        'conduccion' => '#4ECDC4',
        'definicion' => '#FF9F43',
        'fuerza' => '#FECA57',
        'pases' => '#48DBFB',
        'recepcion' => '#FF9FF3',
        'resistencia' => '#54A0FF',
        'salto' => '#5F27CD',
        'velocidad' => '#1DD1A1'
    ];
    
    return isset($colores_defecto[$clave]) ? $colores_defecto[$clave] : '#6c757d';
}

// ===== FUNCIÓN PARA OBTENER EL BG DEL COLOR =====
function obtenerBgColorTipo($clave) {
    $color = obtenerColorTipo($clave);
    return $color . '20'; // 20 = 12% de opacidad en hexadecimal
}