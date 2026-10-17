<?php
// ============================================
// /Model/Entrenamiento.php
// Funciones auxiliares del módulo de Entrenamientos.
// La carga de datos se realiza en Controller/EntrenamientosController.php
// ============================================

// ===== FUNCIONES =====

function obtenerEntrenamientosPorCategoria($categoria, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['categoria'] == $categoria) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

function obtenerEntrenamientosPorFecha($fecha, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['fecha'] == $fecha) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

function obtenerEntrenamientosPorEstado($estado, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['estado'] == $estado) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

function obtenerEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['entrenador_cedula'] == $entrenador_cedula) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}

function obtenerEntrenamientoPorId($id, $entrenamientos) {
    foreach($entrenamientos as $e) {
        if($e['id'] == $id) {
            return $e;
        }
    }
    return null;
}

function contarEntrenamientosPorCategoria($categoria, $entrenamientos) {
    return count(obtenerEntrenamientosPorCategoria($categoria, $entrenamientos));
}

function contarEntrenamientosPorEstado($estado, $entrenamientos) {
    return count(obtenerEntrenamientosPorEstado($estado, $entrenamientos));
}

function contarEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos) {
    return count(obtenerEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos));
}

function calcularPorcentajeAsistencia($asistentes, $total) {
    if($total == 0) return 0;
    return round(($asistentes / $total) * 100);
}

function obtenerEstadisticasEntrenador($entrenador_cedula, $entrenamientos) {
    $entrenamientos_entrenador = obtenerEntrenamientosPorEntrenador($entrenador_cedula, $entrenamientos);
    
    $total = count($entrenamientos_entrenador);
    $finalizados = 0;
    $en_curso = 0;
    $programados = 0;
    $total_asistentes = 0;
    $total_atletas = 0;
    
    foreach($entrenamientos_entrenador as $e) {
        if($e['estado'] == 'Finalizado') $finalizados++;
        elseif($e['estado'] == 'En curso') $en_curso++;
        elseif($e['estado'] == 'Programado') $programados++;
        
        $total_asistentes += $e['asistentes'];
        $total_atletas += $e['total_atletas'];
    }
    
    return [
        'total' => $total,
        'finalizados' => $finalizados,
        'en_curso' => $en_curso,
        'programados' => $programados,
        'total_asistentes' => $total_asistentes,
        'total_atletas' => $total_atletas,
        'promedio_asistencia' => $total_atletas > 0 ? round(($total_asistentes / $total_atletas) * 100) : 0
    ];
}

function obtenerResumenEntrenamientos($entrenamientos) {
    $resumen = [];
    $categorias = array_unique(array_column($entrenamientos, 'categoria'));
    $entrenadores = array_unique(array_column($entrenamientos, 'entrenador_cedula'));
    
    foreach($categorias as $cat) {
        $por_categoria = array_filter($entrenamientos, function($e) use ($cat) {
            return $e['categoria'] == $cat;
        });
        $resumen['categorias'][$cat] = count($por_categoria);
    }
    
    foreach($entrenadores as $ent) {
        $por_entrenador = array_filter($entrenamientos, function($e) use ($ent) {
            return $e['entrenador_cedula'] == $ent;
        });
        $nombre = '';
        foreach($por_entrenador as $e) {
            $nombre = $e['entrenador_nombre'];
            break;
        }
        $resumen['entrenadores'][$ent] = [
            'nombre' => $nombre,
            'total' => count($por_entrenador)
        ];
    }
    
    return $resumen;
}

function obtenerProximosEntrenamientos($entrenamientos, $limite = 5) {
    $hoy = date('Y-m-d');
    $proximos = array_filter($entrenamientos, function($e) use ($hoy) {
        return $e['fecha'] >= $hoy && $e['estado'] != 'Finalizado';
    });
    
    usort($proximos, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    
    return array_slice($proximos, 0, $limite);
}

function obtenerEntrenamientosPorRangoFechas($fecha_inicio, $fecha_fin, $entrenamientos) {
    $resultado = [];
    foreach($entrenamientos as $e) {
        if($e['fecha'] >= $fecha_inicio && $e['fecha'] <= $fecha_fin) {
            $resultado[] = $e;
        }
    }
    return $resultado;
}
?>
