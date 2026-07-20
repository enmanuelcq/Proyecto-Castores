<?php
require_once './Model/Noticias.php';
require_once './Model/Participaciones_partidos.php';
require_once './Model/Pruebas.php';
require_once './Model/Atletas.php';



// ===== DATOS DE PRUEBAS =====

function formatearFecha($fecha) {
    if(!$fecha) return 'N/A';
    return date('d/m/Y', strtotime($fecha));
}

function obtenerUnidadTipo($clave) {
    $unidades = [
        'balon_aereo' => 'toques',
        'conduccion' => 'segundos',
        'definicion' => 'goles',
        'fuerza' => 'repeticiones',
        'pases' => 'pases',
        'recepcion' => 'controles',
        'resistencia' => 'minutos',
        'salto' => 'centímetros',
        'velocidad' => 'segundos'
    ];
    return isset($unidades[$clave]) ? $unidades[$clave] : '';
}

// ===== OBTENER CÉDULA DEL ATLETA =====
$cedula_atleta = isset($_GET['Atleta']) ? $_GET['Atleta'] : '';

if(empty($cedula_atleta)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver su dashboard</div>';
    exit();
}

// Buscar el atleta
$atleta = null;
foreach($todos_los_atletas as $a) {
    if($a['atleta_cedula'] == $cedula_atleta) {
        $atleta = $a;
        break;
    }
}

if(!$atleta) {
    echo '<div class="alert alert-danger">Atleta no encontrado</div>';
    exit();
}

// ===== DATOS DE PRUEBAS =====
$pruebas_atleta = obtenerPruebasPorAtleta($cedula_atleta, $todas_las_pruebas);

$ultimos_valores = [];
$promedios_categoria = [];
$categoria_atleta = $atleta['atleta_categoria'] ?? '';

// Calcular total de pruebas
$total_pruebas = 0;
$tipos_con_pruebas = 0;
$ultima_prueba = null;
$fecha_ultima = '';

foreach($pruebas_atleta as $tipo => $pruebas) {
    if(!empty($pruebas)) {
        $total_pruebas += count($pruebas);
        $tipos_con_pruebas++;
        
        usort($pruebas, function($a, $b) {
            $fecha_a = isset($a['fecha']) ? $a['fecha'] : (isset($a['pruebaf_fecha']) ? $a['pruebaf_fecha'] : (isset($a['pruebav_fecha']) ? $a['pruebav_fecha'] : ''));
            $fecha_b = isset($b['fecha']) ? $b['fecha'] : (isset($b['pruebaf_fecha']) ? $b['pruebaf_fecha'] : (isset($b['pruebav_fecha']) ? $b['pruebav_fecha'] : ''));
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
        $ultima = $pruebas[0];
        $fecha = isset($ultima['fecha']) ? $ultima['fecha'] : (isset($ultima['pruebaf_fecha']) ? $ultima['pruebaf_fecha'] : (isset($ultima['pruebav_fecha']) ? $ultima['pruebav_fecha'] : ''));
        if(!$ultima_prueba || strtotime($fecha) > strtotime($fecha_ultima)) {
            $ultima_prueba = $ultima;
            $fecha_ultima = $fecha;
        }
    }
}

// Calcular últimos valores y promedios por categoría
foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = isset($pruebas_atleta[$clave]) ? $pruebas_atleta[$clave] : [];
    
    if(!empty($pruebas_tipo)) {
        usort($pruebas_tipo, function($a, $b) {
            $fecha_a = obtenerFechaPrueba($a);
            $fecha_b = obtenerFechaPrueba($b);
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
        $ultimos_valores[$clave] = obtenerValorPrincipal($pruebas_tipo[0], $clave);
    } else {
        $ultimos_valores[$clave] = 0;
    }
    
    // Calcular promedio de la categoría
    $total_categoria = 0;
    $contador_categoria = 0;
    
    if(isset($todas_las_pruebas[$clave])) {
        foreach($todas_las_pruebas[$clave] as $p) {
            $categoria_prueba = '';
            foreach($todos_los_atletas as $a) {
                if($a['atleta_cedula'] == $p['atleta_cedula']) {
                    $categoria_prueba = $a['atleta_categoria'] ?? '';
                    break;
                }
            }
            
            if($categoria_prueba == $categoria_atleta && $categoria_prueba != '') {
                $total_categoria += obtenerValorPrincipal($p, $clave);
                $contador_categoria++;
            }
        }
    }
    
    $promedios_categoria[$clave] = $contador_categoria > 0 ? round($total_categoria / $contador_categoria, 2) : 0;
}

// ===== DATOS DE PARTIDOS =====
$resumen_partidos = resumenPartidosAtleta($cedula_atleta, $todas_las_participaciones, $todos_los_partidos);

$total_partidos = $resumen_partidos['total_partidos'];
$total_goles = $resumen_partidos['goles'];
$total_asistencias = $resumen_partidos['asistencias'];
$total_minutos = $resumen_partidos['minutos'];
$tarjetas = $resumen_partidos['tarjetas'];
$promedio_calificacion = $resumen_partidos['promedio_calificacion'];
$partidos_jugados = $resumen_partidos['partidos'];

$ganados = 0;
$perdidos = 0;
$empatados = 0;
foreach($partidos_jugados as $pj) {
    $status = $pj['partido']['status'];
    if($status == 'Ganado') $ganados++;
    elseif($status == 'Perdido') $perdidos++;
    elseif($status == 'Empatado') $empatados++;
}

$ultimo_partido = !empty($partidos_jugados) ? $partidos_jugados[0] : null;

// ===== PREPARAR DATOS PARA GRÁFICOS =====
$datos_graficos = [];
foreach($tipos_prueba as $clave => $tipo) {
    if($ultimos_valores[$clave] > 0 || $promedios_categoria[$clave] > 0) {
        $datos_graficos[] = [
            'label' => $tipo['nombre'],
            'clave' => $clave,
            'atleta' => $ultimos_valores[$clave],
            'promedio' => $promedios_categoria[$clave],
            'color' => obtenerColorTipo($clave),
            'unidad' => obtenerUnidadTipo($clave)
        ];
    }
}

// ===== PREPARAR DATOS DE EVOLUCIÓN PARA TODAS LAS PRUEBAS =====
$evolucion_todos_datos = [];
$todas_fechas = [];

// Primero, recolectar todas las fechas únicas de todas las pruebas
foreach($pruebas_atleta as $clave => $pruebas) {
    foreach($pruebas as $p) {
        $fecha = obtenerFechaPrueba($p);
        if($fecha && !in_array($fecha, $todas_fechas)) {
            $todas_fechas[] = $fecha;
        }
    }
}

// Ordenar fechas
sort($todas_fechas);

// Para cada tipo de prueba, crear un array de valores alineado con las fechas
foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = isset($pruebas_atleta[$clave]) ? $pruebas_atleta[$clave] : [];
    
    // Crear un mapa fecha => valor para este tipo
    $mapa_valores = [];
    foreach($pruebas_tipo as $p) {
        $fecha = obtenerFechaPrueba($p);
        if($fecha) {
            $mapa_valores[$fecha] = obtenerValorPrincipal($p, $clave);
        }
    }
    
    // Si hay al menos 2 registros, agregar a la evolución
    if(count($mapa_valores) >= 2) {
        $valores_ordenados = [];
        foreach($todas_fechas as $fecha) {
            $valores_ordenados[] = isset($mapa_valores[$fecha]) ? $mapa_valores[$fecha] : null;
        }
        
        // Solo agregar si tiene al menos 2 valores no nulos
        $valores_validos = array_filter($valores_ordenados, function($v) { return $v !== null; });
        if(count($valores_validos) >= 2) {
            $evolucion_todos_datos[] = [
                'label' => $tipo['nombre'],
                'clave' => $clave,
                'valores' => $valores_ordenados,
                'color' => obtenerColorTipo($clave)
            ];
        }
    }
}

// Formatear fechas para mostrar en el gráfico
$evolucion_labels = [];
foreach($todas_fechas as $fecha) {
    $evolucion_labels[] = date('d/m', strtotime($fecha));
}
?>