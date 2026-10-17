<?php
// ============================================
// /Model/Asistencias.php
// Funciones auxiliares de asistencia a entrenamientos.
// La carga de datos se realiza en Controller/EntrenamientosController.php
// ============================================

// ===== FUNCIONES =====

function obtenerAsistenciaPorEntrenamiento($entrenamiento_id, $asistencia) {
    $resultado = [];
    foreach($asistencia as $a) {
        if($a['entrenamiento_id'] == $entrenamiento_id) {
            $resultado[] = $a;
        }
    }
    return $resultado;
}

function obtenerAsistenciaPorAtleta($atleta_id, $asistencia) {
    $resultado = [];
    foreach($asistencia as $a) {
        if($a['atleta_id'] == $atleta_id) {
            $resultado[] = $a;
        }
    }
    return $resultado;
}

function contarAsistenciasAtleta($atleta_id, $asistencia) {
    $asistencias = obtenerAsistenciaPorAtleta($atleta_id, $asistencia);
    $presentes = 0;
    foreach($asistencias as $a) {
        if($a['asistio'] == 1) $presentes++;
    }
    return [
        'total' => count($asistencias),
        'presentes' => $presentes,
        'ausentes' => count($asistencias) - $presentes
    ];
}

function calcularPorcentajeAsistenciaAtleta($atleta_id, $asistencia) {
    $stats = contarAsistenciasAtleta($atleta_id, $asistencia);
    if($stats['total'] == 0) return 0;
    return round(($stats['presentes'] / $stats['total']) * 100);
}

function obtenerResumenAsistenciaEntrenamiento($entrenamiento_id, $asistencia, $total_atletas) {
    $asistencias = obtenerAsistenciaPorEntrenamiento($entrenamiento_id, $asistencia);
    $presentes = count(array_filter($asistencias, function($a) { return $a['asistio'] == 1; }));
    $ausentes = count(array_filter($asistencias, function($a) { return $a['asistio'] == 0; }));
    
    return [
        'total_registrados' => count($asistencias),
        'presentes' => $presentes,
        'ausentes' => $ausentes,
        'total_atletas' => $total_atletas,
        'porcentaje' => $total_atletas > 0 ? round(($presentes / $total_atletas) * 100) : 0
    ];
}

function obtenerAusentesEntrenamiento($entrenamiento_id, $asistencia) {
    $asistencias = obtenerAsistenciaPorEntrenamiento($entrenamiento_id, $asistencia);
    $ausentes = [];
    foreach($asistencias as $a) {
        if($a['asistio'] == 0) {
            $ausentes[] = $a;
        }
    }
    return $ausentes;
}

function obtenerMejorAsistencia($asistencia, $limite = 5) {
    $atletas = [];
    foreach($asistencia as $a) {
        if(!isset($atletas[$a['atleta_id']])) {
            $atletas[$a['atleta_id']] = [
                'total' => 0,
                'presentes' => 0
            ];
        }
        $atletas[$a['atleta_id']]['total']++;
        if($a['asistio'] == 1) {
            $atletas[$a['atleta_id']]['presentes']++;
        }
    }
    
    foreach($atletas as $atleta_id => &$data) {
        $data['porcentaje'] = $data['total'] > 0 ? round(($data['presentes'] / $data['total']) * 100) : 0;
    }
    
    usort($atletas, function($a, $b) {
        return $b['porcentaje'] - $a['porcentaje'];
    });
    
    return array_slice($atletas, 0, $limite);
}
?>
