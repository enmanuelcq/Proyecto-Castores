<?php

$partido1 = [
    'id' => '1',
    'fecha' => '01/08/2026',
    'goles_a_favor' => '',
    'goles_contra' => '',
    'duracion' => '',
    'categoria' => 'Sub 14',
    'status' => 'Por Jugar',
    'rival' => 'Halcones FC',
    'estadio' => 'Estadio Central',
    'tipo' => 'Torneo',
    'hora' => '4:00pm'
];

$partido2 = [
    'id' => '2',
    'fecha' => '05/08/2026',
    'goles_a_favor' => '3',
    'goles_contra' => '1',
    'duracion' => '90 min',
    'categoria' => 'Sub 16',
    'status' => 'Por Cargar',
    'rival' => 'Dragones Rojos',
    'estadio' => 'Estadio Olímpico',
    'tipo' => 'Amistoso',
    'hora' => '4:00pm'
];

$partido3 = [
    'id' => '3',
    'fecha' => '10/08/2026',
    'goles_a_favor' => '2',
    'goles_contra' => '2',
    'duracion' => '90 min',
    'categoria' => 'Sub 14',
    'status' => 'Empatado',
    'rival' => 'Toros Negros',
    'estadio' => 'Cancha Principal',
    'tipo' => 'Torneo',
    'hora' => '3:00pm'
];

$partido4 = [
    'id' => '4',
    'fecha' => '15/08/2026',
    'goles_a_favor' => '0',
    'goles_contra' => '3',
    'duracion' => '90 min',
    'categoria' => 'Sub 18',
    'status' => 'Por Cargar',
    'rival' => 'Cóndores Dorados',
    'estadio' => 'Estadio Monumental',
    'tipo' => 'Torneo',
    'hora' => '5:00pm'
];

$partido5 = [
    'id' => '5',
    'fecha' => '20/08/2026',
    'goles_a_favor' => '',
    'goles_contra' => '',
    'duracion' => '',
    'categoria' => 'Sub 16',
    'status' => 'Cancelado',
    'rival' => 'Lobos Grises',
    'estadio' => 'Estadio Municipal',
    'tipo' => 'Torneo',
    'hora' => '10:00am'
];

$partido6 = [
    'id' => '6',
    'fecha' => '25/08/2026',
    'goles_a_favor' => '5',
    'goles_contra' => '0',
    'duracion' => '90 min',
    'categoria' => 'Sub 14',
    'status' => 'Por Cargar',
    'rival' => 'Panteras Negras',
    'estadio' => 'Estadio Principal',
    'tipo' => 'Amistoso',
    'hora' => '4:00pm'
];

$partido7 = [
    'id' => '7',
    'fecha' => '30/08/2026',
    'goles_a_favor' => '',
    'goles_contra' => '',
    'duracion' => '',
    'categoria' => 'Sub 18',
    'status' => 'Por Jugar',
    'rival' => 'Jaguares FC',
    'estadio' => 'Estadio Central',
    'tipo' => 'Torneo',
    'hora' => '6:00pm'
];

$partido8 = [
    'id' => '8',
    'fecha' => '02/09/2026',
    'goles_a_favor' => '4',
    'goles_contra' => '2',
    'duracion' => '90 min',
    'categoria' => 'Sub 16',
    'status' => 'Ganado',
    'rival' => 'Los Leones FC',
    'estadio' => 'Estadio Principal',
    'tipo' => 'Torneo',
    'hora' => '5:00pm'
];

$partido9 = [
    'id' => '9',
    'fecha' => '08/09/2026',
    'goles_a_favor' => '1',
    'goles_contra' => '4',
    'duracion' => '90 min',
    'categoria' => 'Sub 14',
    'status' => 'Perdido',
    'rival' => 'Águilas Doradas',
    'estadio' => 'Cancha Alterna',
    'tipo' => 'Torneo',
    'hora' => '4:00pm'
    
];

$partido10 = [
    'id' => '10',
    'fecha' => '12/09/2026',
    'goles_a_favor' => '',
    'goles_contra' => '',
    'duracion' => '',
    'categoria' => 'Sub 18',
    'status' => 'Por Jugar',
    'rival' => 'Tigres FC',
    'estadio' => 'Estadio Municipal',
    'tipo' => 'Amistoso',
    'hora' => '4:00pm'
];

$partido11 = [
    'id' => '11',
    'fecha' => '18/09/2026',
    'goles_a_favor' => '2',
    'goles_contra' => '3',
    'duracion' => '90 min',
    'categoria' => 'Sub 14',
    'status' => 'Perdido',
    'rival' => 'Pumas Salvajes',
    'estadio' => 'Cancha Alterna',
    'tipo' => 'Torneo',
    'hora' => '2:00pm'

];

$partido12 = [
    'id' => '12',
    'fecha' => '22/09/2026',
    'goles_a_favor' => '6',
    'goles_contra' => '1',
    'duracion' => '90 min',
    'categoria' => 'Sub 18',
    'status' => 'Ganado',
    'rival' => 'Rayos del Norte',
    'estadio' => 'Estadio Olímpico',
    'tipo' => 'Amistoso',
    'hora' => '4:00pm'
];

// ============ LISTA DE TODOS LOS partidoS ============
$todos_los_partidos = [
    $partido1,
    $partido2,
    $partido3,
    $partido4,
    $partido5,
    $partido6,
    $partido7,
    $partido8,
    $partido9,
    $partido10,
    $partido11,
    $partido12
];

// ============================================
// FUNCIONES DE COMPROBACIÓN
// ============================================

/**
 * Busca un partido por su ID
 * @param string $id - ID del partido
 * @param array $lista_partidos - Lista de partidos
 * @return array|null - Retorna el partido encontrado o null si no existe
 */
function buscarPartidoPorId($id, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    foreach ($partidos as $partido) {
        if ($partido['id'] === $id) {
            return $partido;
        }
    }
    return null;
}

/**
 * Busca partidos por categoría
 * @param string $categoria - Categoría del partido (ej. Sub-14)
 * @param array $lista_partidos - Lista de partidos
 * @return array - Retorna los partidos encontrados
 */
function buscarPartidosPorCategoria($categoria, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    $resultado = [];
    
    foreach ($partidos as $partido) {
        if ($partido['categoria'] === $categoria) {
            $resultado[] = $partido;
        }
    }
    return $resultado;
}

/**
 * Busca partidos por estado
 * @param string $status - Estado del partido (Ganado/Perdido/Empatado/Por Jugar/Cancelado)
 * @param array $lista_partidos - Lista de partidos
 * @return array - Retorna los partidos encontrados
 */
function buscarPartidosPorStatus($status, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    $resultado = [];
    
    foreach ($partidos as $partido) {
        if ($partido['status'] === $status) {
            $resultado[] = $partido;
        }
    }
    return $resultado;
}

/**
 * Busca partidos por rival
 * @param string $rival - Nombre del rival
 * @param array $lista_partidos - Lista de partidos
 * @return array - Retorna los partidos encontrados
 */
function buscarPartidosPorRival($rival, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    $resultado = [];
    
    foreach ($partidos as $partido) {
        if (stripos($partido['rival'], $rival) !== false) {
            $resultado[] = $partido;
        }
    }
    return $resultado;
}

/**
 * Busca partidos por estadio
 * @param string $estadio - Nombre del estadio
 * @param array $lista_partidos - Lista de partidos
 * @return array - Retorna los partidos encontrados
 */
function buscarPartidosPorEstadio($estadio, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    $resultado = [];
    
    foreach ($partidos as $partido) {
        if (stripos($partido['estadio'], $estadio) !== false) {
            $resultado[] = $partido;
        }
    }
    return $resultado;
}

/**
 * Busca partidos por rango de fechas
 * @param string $fecha_inicio - Fecha de inicio (formato: dd/mm/yyyy)
 * @param string $fecha_fin - Fecha de fin (formato: dd/mm/yyyy)
 * @param array $lista_partidos - Lista de partidos
 * @return array - Retorna los partidos encontrados
 */
function buscarPartidosPorRangoFechas($fecha_inicio, $fecha_fin, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    $resultado = [];
    
    // Convertir fechas a timestamp para comparar
    $timestamp_inicio = strtotime(str_replace('/', '-', $fecha_inicio));
    $timestamp_fin = strtotime(str_replace('/', '-', $fecha_fin));
    
    foreach ($partidos as $partido) {
        $timestamp_partido = strtotime(str_replace('/', '-', $partido['fecha']));
        if ($timestamp_partido >= $timestamp_inicio && $timestamp_partido <= $timestamp_fin) {
            $resultado[] = $partido;
        }
    }
    return $resultado;
}

/**
 * Verifica si un partido ya está registrado
 * @param string $fecha - Fecha del partido
 * @param string $rival - Nombre del rival
 * @param array $lista_partidos - Lista de partidos
 * @return bool - Retorna true si ya existe, false si no
 */
function partidoYaExiste($fecha, $rival, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    foreach ($partidos as $partido) {
        if ($partido['fecha'] === $fecha && $partido['rival'] === $rival) {
            return true;
        }
    }
    return false;
}

/**
 * Agrega un nuevo partido al sistema
 * @param array $partido - Array con los datos del partido
 * @return array - Resultado de la operación
 */
function agregarPartido($partido) {
    global $todos_los_partidos;
    
    // Verificar si el partido ya existe
    if (partidoYaExiste($partido['fecha'], $partido['rival'])) {
        return [
            'success' => false,
            'mensaje' => 'Ya existe un partido registrado con esta fecha y rival',
            'codigo' => 'DUPLICADO_PARTIDO'
        ];
    }
    
    // Asignar un ID automático si no tiene
    if (!isset($partido['id']) || $partido['id'] === '') {
        $partido['id'] = count($todos_los_partidos) + 1;
    }
    
    // Agregar el nuevo partido al array
    $todos_los_partidos[] = $partido;
    
    // Verificar que se agregó correctamente
    $partido_agregado = buscarPartidoPorId($partido['id']);
    if ($partido_agregado) {
        return [
            'success' => true,
            'mensaje' => 'Partido registrado exitosamente',
            'codigo' => 'REGISTRO_EXITOSO',
            'partido' => $partido_agregado,
            'id' => $partido['id'],
            'fecha' => $partido['fecha'],
            'rival' => $partido['rival'],
            'categoria' => $partido['categoria'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ];
    } else {
        return [
            'success' => false,
            'mensaje' => 'Error al registrar el partido',
            'codigo' => 'ERROR_REGISTRO'
        ];
    }
}

/**
 * Actualiza los datos de un partido existente
 * @param string $id - ID del partido a actualizar
 * @param array $nuevos_datos - Array con los nuevos datos
 * @param array $lista_partidos - Lista de partidos (opcional)
 * @return array - Resultado de la operación
 */
function actualizarPartido($id, $nuevos_datos, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    // Buscar el partido por ID
    $indice = -1;
    foreach ($partidos as $key => $partido) {
        if ($partido['id'] === $id) {
            $indice = $key;
            break;
        }
    }
    
    if ($indice === -1) {
        return [
            'success' => false,
            'mensaje' => 'Partido no encontrado',
            'codigo' => 'NO_ENCONTRADO'
        ];
    }
    
    // Obtener el partido actual para mantener campos no enviados
    $partido_actual = $partidos[$indice];
    
    // Fusionar los datos actuales con los nuevos
    $partido_actualizado = array_merge($partido_actual, $nuevos_datos);
    
    // Actualizar el partido en el array
    $partidos[$indice] = $partido_actualizado;
    
    // Actualizar la variable global
    $GLOBALS['todos_los_partidos'] = $partidos;
    
    return [
        'success' => true,
        'mensaje' => 'Partido actualizado exitosamente',
        'codigo' => 'ACTUALIZADO',
        'partido' => $partido_actualizado,
        'id' => $partido_actualizado['id']
    ];
}

/**
 * Elimina un partido del sistema
 * @param string $id - ID del partido a eliminar
 * @param array $lista_partidos - Lista de partidos (opcional)
 * @return array - Resultado de la operación
 */
function eliminarPartido($id, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    // Buscar el partido por ID
    $indice = -1;
    foreach ($partidos as $key => $partido) {
        if ($partido['id'] === $id) {
            $indice = $key;
            break;
        }
    }
    
    if ($indice === -1) {
        return [
            'success' => false,
            'mensaje' => 'Partido no encontrado',
            'codigo' => 'NO_ENCONTRADO'
        ];
    }
    
    // Obtener el partido antes de eliminarlo
    $partido_eliminado = $partidos[$indice];
    
    // Eliminar el partido del array
    array_splice($partidos, $indice, 1);
    
    // Actualizar la variable global
    $GLOBALS['todos_los_partidos'] = $partidos;
    
    return [
        'success' => true,
        'mensaje' => 'Partido eliminado exitosamente',
        'codigo' => 'ELIMINADO',
        'partido' => $partido_eliminado,
        'id' => $id
    ];
}

/**
 * Obtiene estadísticas de los partidos
 * @param array $lista_partidos - Lista de partidos (opcional)
 * @return array - Estadísticas generales
 */
function obtenerEstadisticasPartidos($lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    $estadisticas = [
        'total_partidos' => 0,
        'ganados' => 0,
        'perdidos' => 0,
        'empatados' => 0,
        'por_jugar' => 0,
        'cancelados' => 0,
        'goles_favor' => 0,
        'goles_contra' => 0,
        'diferencia_goles' => 0,
        'promedio_goles_favor' => 0,
        'promedio_goles_contra' => 0,
        'total_asistencia' => 0,
        'promedio_asistencia' => 0,
        'partidos_con_asistencia' => 0
    ];
    
    $total_partidos = count($partidos);
    $estadisticas['total_partidos'] = $total_partidos;
    
    if ($total_partidos === 0) {
        return $estadisticas;
    }
    
    foreach ($partidos as $partido) {
        // Contar por estado
        switch ($partido['status']) {
            case 'Ganado':
                $estadisticas['ganados']++;
                break;
            case 'Perdido':
                $estadisticas['perdidos']++;
                break;
            case 'Empatado':
                $estadisticas['empatados']++;
                break;
            case 'Por Jugar':
                $estadisticas['por_jugar']++;
                break;
            case 'Cancelado':
                $estadisticas['cancelados']++;
                break;
        }
        
        // Sumar goles
        if ($partido['goles_a_favor'] !== '') {
            $estadisticas['goles_favor'] += intval($partido['goles_a_favor']);
        }
        if ($partido['goles_contra'] !== '') {
            $estadisticas['goles_contra'] += intval($partido['goles_contra']);
        }
        
        // Sumar asistencia
        if ($partido['asistencia'] !== '') {
            $estadisticas['total_asistencia'] += intval($partido['asistencia']);
            $estadisticas['partidos_con_asistencia']++;
        }
    }
    
    // Calcular diferencias y promedios
    $estadisticas['diferencia_goles'] = $estadisticas['goles_favor'] - $estadisticas['goles_contra'];
    
    $partidos_jugados = $total_partidos - $estadisticas['por_jugar'] - $estadisticas['cancelados'];
    if ($partidos_jugados > 0) {
        $estadisticas['promedio_goles_favor'] = round($estadisticas['goles_favor'] / $partidos_jugados, 2);
        $estadisticas['promedio_goles_contra'] = round($estadisticas['goles_contra'] / $partidos_jugados, 2);
    }
    
    if ($estadisticas['partidos_con_asistencia'] > 0) {
        $estadisticas['promedio_asistencia'] = round($estadisticas['total_asistencia'] / $estadisticas['partidos_con_asistencia'], 0);
    }
    
    // Calcular porcentaje de efectividad (victorias + empates)
    $total_efectivos = $estadisticas['ganados'] + $estadisticas['empatados'];
    if ($partidos_jugados > 0) {
        $estadisticas['porcentaje_efectividad'] = round(($total_efectivos / $partidos_jugados) * 100, 2);
    } else {
        $estadisticas['porcentaje_efectividad'] = 0;
    }
    
    return $estadisticas;
}

/**
 * Obtiene el resumen de un partido para mostrar en tarjeta
 * @param array $partido - Array del partido
 * @return array - Resumen del partido
 */
function obtenerResumenPartido($partido) {
    $resumen = [
        'id' => $partido['id'],
        'fecha' => $partido['fecha'],
        'rival' => $partido['rival'],
        'categoria' => $partido['categoria'],
        'status' => $partido['status'],
        'estadio' => $partido['estadio'],
        'resultado' => ''
    ];
    
    // Formatear resultado
    if ($partido['status'] === 'Por Jugar' || $partido['status'] === 'Cancelado') {
        $resumen['resultado'] = $partido['status'];
    } else {
        $resumen['resultado'] = $partido['goles_a_favor'] . ' - ' . $partido['goles_contra'];
    }
    
    return $resumen;
}

/**
 * Obtiene los partidos ordenados por fecha (más reciente primero)
 * @param array $lista_partidos - Lista de partidos (opcional)
 * @return array - Lista de partidos ordenada
 */
function obtenerPartidosOrdenadosPorFecha($lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    usort($partidos, function($a, $b) {
        $timestamp_a = strtotime(str_replace('/', '-', $a['fecha']));
        $timestamp_b = strtotime(str_replace('/', '-', $b['fecha']));
        return $timestamp_b - $timestamp_a; // Más reciente primero
    });
    
    return $partidos;
}

/**
 * Obtiene los próximos partidos (Por Jugar)
 * @param array $lista_partidos - Lista de partidos (opcional)
 * @return array - Lista de próximos partidos
 */
function obtenerProximosPartidos($lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    $proximos = [];
    foreach ($partidos as $partido) {
        if ($partido['status'] === 'Por Jugar') {
            $proximos[] = $partido;
        }
    }
    
    // Ordenar por fecha (más cercano primero)
    usort($proximos, function($a, $b) {
        $timestamp_a = strtotime(str_replace('/', '-', $a['fecha']));
        $timestamp_b = strtotime(str_replace('/', '-', $b['fecha']));
        return $timestamp_a - $timestamp_b;
    });
    
    return $proximos;
}

/**
 * Obtiene los últimos partidos jugados
 * @param int $limite - Número máximo de partidos a retornar
 * @param array $lista_partidos - Lista de partidos (opcional)
 * @return array - Lista de últimos partidos
 */
function obtenerUltimosPartidos($limite = 5, $lista_partidos = null) {
    global $todos_los_partidos;
    $partidos = $lista_partidos ?? $todos_los_partidos;
    
    $jugados = [];
    foreach ($partidos as $partido) {
        if (in_array($partido['status'], ['Ganado', 'Perdido', 'Empatado'])) {
            $jugados[] = $partido;
        }
    }
    
    // Ordenar por fecha (más reciente primero)
    usort($jugados, function($a, $b) {
        $timestamp_a = strtotime(str_replace('/', '-', $a['fecha']));
        $timestamp_b = strtotime(str_replace('/', '-', $b['fecha']));
        return $timestamp_b - $timestamp_a;
    });
    
    // Limitar resultados
    if ($limite > 0 && count($jugados) > $limite) {
        $jugados = array_slice($jugados, 0, $limite);
    }
    
    return $jugados;
}

/*
// ============================================
// EJEMPLOS DE USO
// ============================================

// Ejemplo 1: Buscar partido por ID
$partido_encontrado = buscarPartidoPorId('3');
if ($partido_encontrado) {
    echo "Partido encontrado: " . $partido_encontrado['rival'] . " - " . $partido_encontrado['fecha'] . "\n";
}

// Ejemplo 2: Buscar partidos por categoría
$partidos_sub14 = buscarPartidosPorCategoria('Sub 14');
echo "Partidos Sub-14: " . count($partidos_sub14) . "\n";

// Ejemplo 3: Buscar partidos por estado
$partidos_ganados = buscarPartidosPorStatus('Ganado');
echo "Partidos ganados: " . count($partidos_ganados) . "\n";

// Ejemplo 4: Obtener estadísticas
$estadisticas = obtenerEstadisticasPartidos();
echo "Total partidos: " . $estadisticas['total_partidos'] . "\n";
echo "Efectividad: " . $estadisticas['porcentaje_efectividad'] . "%\n";

// Ejemplo 5: Obtener próximos partidos
$proximos = obtenerProximosPartidos();
echo "Próximos partidos: " . count($proximos) . "\n";

// Ejemplo 6: Agregar un nuevo partido
$nuevo_partido = [
    'fecha' => '28/09/2026',
    'goles_a_favor' => '',
    'goles_contra' => '',
    'duracion' => '',
    'categoria' => 'Sub 14',
    'status' => 'Por Jugar',
    'rival' => 'Leones del Sur',
    'estadio' => 'Estadio Nuevo',
    'asistencia' => ''
];

$resultado = agregarPartido($nuevo_partido);
if ($resultado['success']) {
    echo "Partido agregado exitosamente. ID: " . $resultado['id'] . "\n";
} else {
    echo "Error: " . $resultado['mensaje'] . "\n";
}

// Ejemplo 7: Actualizar un partido
$actualizacion = actualizarPartido('11', [
    'goles_a_favor' => '3',
    'goles_contra' => '2',
    'status' => 'Ganado'
]);

if ($actualizacion['success']) {
    echo "Partido actualizado exitosamente\n";
} else {
    echo "Error: " . $actualizacion['mensaje'] . "\n";
}

// Ejemplo 8: Obtener últimos 3 partidos
$ultimos = obtenerUltimosPartidos(3);
echo "Últimos 3 partidos:\n";
foreach ($ultimos as $partido) {
    echo "- " . $partido['fecha'] . " vs " . $partido['rival'] . "\n";
}*/

