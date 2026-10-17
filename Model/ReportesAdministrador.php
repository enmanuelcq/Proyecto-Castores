<?php
// ============================================
// /Model/Reportes/ReportesAdmin.php
// FUNCIONES PARA REPORTES DEL ADMINISTRADOR
// ============================================

/** 
 * Obtener estadísticas generales de la academia
 * @param array $todos_los_atletas - Array de atletas (Model/Atletas.php)
 * @param array $todas_las_solvencias - Array de solvencias (Model/Solvencias.php)
 * @param array $todos_los_entrenamientos - Array de entrenamientos (Model/Entrenamientos.php)
 * @param array $toda_la_asistencia - Array de asistencia (Model/AsistenciaEntrenamientos.php)
 * @param array $todas_las_pruebas - Array de pruebas (Model/Pruebas.php)
 */
/**
 * Obtiene los meses activos (desde enero hasta el mes actual)
 */
function obtenerMesesActivosReportes() {
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $mes_actual = intval(date('n')) - 1;
    return array_slice($meses, 0, $mes_actual + 1);
}

/**
 * OBTENER ESTADÍSTICAS GENERALES DE LA ACADEMIA
 * (ACTUALIZADO PARA SOLO CONSIDERAR MESES ACTIVOS)
 */
function obtenerEstadisticasGenerales($todos_los_atletas, $todas_las_solvencias, $todos_los_entrenamientos, $toda_la_asistencia, $todas_las_pruebas) {
    
    // ===== MESES ACTIVOS PARA SOLVENCIA =====
    $meses_activos = obtenerMesesActivosReportes();
    
    // ===== ATLETAS =====
    $total_atletas = count($todos_los_atletas);
    $atletas_por_categoria = [];
    $atletas_por_sexo = ['Masculino' => 0, 'Femenino' => 0];
    
    foreach($todos_los_atletas as $a) {
        $cat = $a['atleta_categoria'] ?? 'Sin categoría';
        if(!isset($atletas_por_categoria[$cat])) {
            $atletas_por_categoria[$cat] = 0;
        }
        $atletas_por_categoria[$cat]++;
        
        $sexo = $a['atleta_sexo'] ?? 'Masculino';
        if(isset($atletas_por_sexo[$sexo])) {
            $atletas_por_sexo[$sexo]++;
        }
    }
    ksort($atletas_por_categoria);
    
    // ===== SOLVENCIA (SOLO MESES ACTIVOS) =====
    $solvencia_estados = ['Pagado' => 0, 'Pendiente' => 0, 'Revision' => 0];
    $atletas_con_solvencia = 0;
    
    foreach($todas_las_solvencias as $solvencia) {
        $tiene_datos = false;
        foreach($solvencia as $clave => $mes) {
            if($clave !== 'atleta_cedula' && in_array($clave, $meses_activos)) {
                $estado = $mes['estado'] ?? 'Pendiente';
                if($estado == 'Pagado') $solvencia_estados['Pagado']++;
                elseif($estado == 'Revision') $solvencia_estados['Revision']++;
                else $solvencia_estados['Pendiente']++;
                $tiene_datos = true;
            }
        }
        if($tiene_datos) $atletas_con_solvencia++;
    }
    
    // ===== ENTRENAMIENTOS =====
    $total_entrenamientos = count($todos_los_entrenamientos);
    $entrenamientos_por_estado = [];
    $entrenamientos_por_categoria = [];
    $entrenamientos_por_entrenador = [];
    $total_asistentes = 0;
    $total_convocados = 0;
    
    foreach($todos_los_entrenamientos as $e) {
        // Por estado
        $estado = $e['estado'] ?? 'Programado';
        if(!isset($entrenamientos_por_estado[$estado])) {
            $entrenamientos_por_estado[$estado] = 0;
        }
        $entrenamientos_por_estado[$estado]++;
        
        // Por categoría
        $cat = $e['categoria'] ?? 'Sin categoría';
        if(!isset($entrenamientos_por_categoria[$cat])) {
            $entrenamientos_por_categoria[$cat] = 0;
        }
        $entrenamientos_por_categoria[$cat]++;
        
        // Por entrenador
        $cedula = $e['entrenador_cedula'] ?? 'Sin asignar';
        if(!isset($entrenamientos_por_entrenador[$cedula])) {
            $entrenamientos_por_entrenador[$cedula] = [
                'nombre' => $e['entrenador_nombre'] ?? 'Desconocido',
                'total' => 0,
                'asistentes' => 0,
                'convocados' => 0
            ];
        }
        $entrenamientos_por_entrenador[$cedula]['total']++;
        $entrenamientos_por_entrenador[$cedula]['asistentes'] += $e['asistentes'] ?? 0;
        $entrenamientos_por_entrenador[$cedula]['convocados'] += $e['total_atletas'] ?? 0;
        
        // Totales generales
        $total_asistentes += $e['asistentes'] ?? 0;
        $total_convocados += $e['total_atletas'] ?? 0;
    }
    
    // Calcular porcentaje de asistencia por entrenador
    foreach($entrenamientos_por_entrenador as $cedula => &$data) {
        $data['porcentaje'] = $data['convocados'] > 0 ? 
            round(($data['asistentes'] / $data['convocados']) * 100) : 0;
    }
    
    $porcentaje_asistencia_general = $total_convocados > 0 ? 
        round(($total_asistentes / $total_convocados) * 100) : 0;
    
    // ===== PRUEBAS =====
    $total_pruebas = 0;
    $pruebas_por_tipo = [];
    $pruebas_por_categoria = [];
    $pruebas_por_atleta = [];
    
    foreach($todas_las_pruebas as $tipo => $pruebas) {
        $pruebas_por_tipo[$tipo] = count($pruebas);
        $total_pruebas += count($pruebas);
        
        foreach($pruebas as $p) {
            $cedula = $p['atleta_cedula'] ?? null;
            if($cedula) {
                // Buscar categoría del atleta
                $categoria = 'Sin categoría';
                foreach($todos_los_atletas as $a) {
                    if($a['atleta_cedula'] == $cedula) {
                        $categoria = $a['atleta_categoria'] ?? 'Sin categoría';
                        break;
                    }
                }
                if(!isset($pruebas_por_categoria[$categoria])) {
                    $pruebas_por_categoria[$categoria] = 0;
                }
                $pruebas_por_categoria[$categoria]++;
                
                if(!isset($pruebas_por_atleta[$cedula])) {
                    $pruebas_por_atleta[$cedula] = 0;
                }
                $pruebas_por_atleta[$cedula]++;
            }
        }
    }
    
    arsort($pruebas_por_tipo);
    arsort($pruebas_por_categoria);
    arsort($pruebas_por_atleta);
    
    // ===== ASISTENCIA POR ATLETA =====
    $asistencia_atletas = [];
    foreach($toda_la_asistencia as $a) {
        $atleta_id = $a['atleta_id'];
        if(!isset($asistencia_atletas[$atleta_id])) {
            $asistencia_atletas[$atleta_id] = ['total' => 0, 'presentes' => 0];
        }
        $asistencia_atletas[$atleta_id]['total']++;
        if($a['asistio'] == 1) {
            $asistencia_atletas[$atleta_id]['presentes']++;
        }
    }
    
    // ===== RETORNAR =====
    return [
        'atletas' => [
            'total' => $total_atletas,
            'por_categoria' => $atletas_por_categoria,
            'por_sexo' => $atletas_por_sexo
        ],
        'solvencia' => [
            'estados' => $solvencia_estados,
            'atletas_con_registro' => $atletas_con_solvencia,
            'meses_considerados' => count($meses_activos)
        ],
        'entrenamientos' => [
            'total' => $total_entrenamientos,
            'por_estado' => $entrenamientos_por_estado,
            'por_categoria' => $entrenamientos_por_categoria,
            'por_entrenador' => $entrenamientos_por_entrenador,
            'porcentaje_asistencia' => $porcentaje_asistencia_general,
            'total_asistentes' => $total_asistentes,
            'total_convocados' => $total_convocados
        ],
        'pruebas' => [
            'total' => $total_pruebas,
            'por_tipo' => $pruebas_por_tipo,
            'por_categoria' => $pruebas_por_categoria,
            'por_atleta' => $pruebas_por_atleta
        ],
        'asistencia_atletas' => $asistencia_atletas
    ];
}


/**
 * OBTENER ATLETAS MOROSOS (SOLO MESES ACTIVOS)
 */
function obtenerAtletasMorosos($todas_las_solvencias, $todos_los_atletas, $meses_umbral = 3) {
    $morosos = [];
    $meses_activos = obtenerMesesActivosReportes();
    
    foreach($todas_las_solvencias as $solvencia) {
        $cedula = $solvencia['atleta_cedula'] ?? null;
        if(!$cedula) continue;
        
        $pendientes = 0;
        foreach($solvencia as $clave => $info_mes) {
            if($clave !== 'atleta_cedula' && in_array($clave, $meses_activos)) {
                if($info_mes['estado'] == 'Pendiente') {
                    $pendientes++;
                }
            }
        }
        
        if($pendientes >= $meses_umbral) {
            $atleta = null;
            foreach($todos_los_atletas as $a) {
                if($a['atleta_cedula'] == $cedula) {
                    $atleta = $a;
                    break;
                }
            }
            if($atleta) {
                $morosos[] = [
                    'atleta' => $atleta,
                    'meses_pendientes' => $pendientes
                ];
            }
        }
    }
    
    usort($morosos, function($a, $b) {
        return $b['meses_pendientes'] - $a['meses_pendientes'];
    });
    
    return $morosos;
}


/**
 * OBTENER SOLVENCIA POR CATEGORÍA (SOLO MESES ACTIVOS)
 */
function obtenerSolvenciaPorCategoria($todas_las_solvencias, $todos_los_atletas) {
    $resultado = [];
    $meses_activos = obtenerMesesActivosReportes();
    
    foreach($todas_las_solvencias as $solvencia) {
        $cedula = $solvencia['atleta_cedula'] ?? null;
        if(!$cedula) continue;
        
        $categoria = '';
        foreach($todos_los_atletas as $a) {
            if($a['atleta_cedula'] == $cedula) {
                $categoria = $a['atleta_categoria'] ?? 'Sin categoría';
                break;
            }
        }
        
        if(!isset($resultado[$categoria])) {
            $resultado[$categoria] = [
                'Pagado' => 0,
                'Pendiente' => 0,
                'Revision' => 0,
                'Por_Vencer' => 0,
                'Total' => 0,
                'Meses_Activos' => 0
            ];
        }
        
        $meses_procesados = 0;
        foreach($solvencia as $clave => $info_mes) {
            if($clave !== 'atleta_cedula' && in_array($clave, $meses_activos)) {
                $meses_procesados++;
                $estado = $info_mes['estado'] ?? 'Pendiente';
                if($estado == 'Pagado') $resultado[$categoria]['Pagado']++;
                elseif($estado == 'Revision') $resultado[$categoria]['Revision']++;
                else $resultado[$categoria]['Pendiente']++;
            }
        }
        
        $resultado[$categoria]['Meses_Activos'] = $meses_procesados;
        $resultado[$categoria]['Total'] = $meses_procesados;
        
        // Calcular meses futuros (por vencer)
        $total_meses = 12;
        $resultado[$categoria]['Por_Vencer'] = $total_meses - count($meses_activos);
    }
    
    return $resultado;
}


/**
 * OBTENER ESTADÍSTICAS DE SOLVENCIA GENERAL (PARA GRÁFICOS)
 */
function obtenerEstadisticasSolvencia($todas_las_solvencias) {
    $meses_activos = obtenerMesesActivosReportes();
    $estadisticas = [
        'Pagado' => 0,
        'Pendiente' => 0,
        'Revision' => 0,
        'total_meses_procesados' => 0,
        'atletas_procesados' => 0
    ];
    
    foreach($todas_las_solvencias as $solvencia) {
        $tiene_datos = false;
        foreach($solvencia as $clave => $info_mes) {
            if($clave !== 'atleta_cedula' && in_array($clave, $meses_activos)) {
                $tiene_datos = true;
                $estado = $info_mes['estado'] ?? 'Pendiente';
                if($estado == 'Pagado') $estadisticas['Pagado']++;
                elseif($estado == 'Revision') $estadisticas['Revision']++;
                else $estadisticas['Pendiente']++;
            }
        }
        if($tiene_datos) {
            $estadisticas['atletas_procesados']++;
            $estadisticas['total_meses_procesados'] += count($meses_activos);
        }
    }
    
    return $estadisticas;
}


/**
 * OBTENER RESUMEN DE SOLVENCIA POR ATLETA
 */
function obtenerResumenSolvenciaAtleta($todas_las_solvencias, $cedula_atleta) {
    $meses_activos = obtenerMesesActivosReportes();
    $resumen = [
        'pagados' => 0,
        'pendientes' => 0,
        'revision' => 0,
        'por_vencer' => 0,
        'total_activos' => 0,
        'total_anual' => 12,
        'meses_detalle' => []
    ];
    
    foreach($todas_las_solvencias as $solvencia) {
        if($solvencia['atleta_cedula'] == $cedula_atleta) {
            foreach($solvencia as $clave => $info_mes) {
                if($clave !== 'atleta_cedula') {
                    if(in_array($clave, $meses_activos)) {
                        $resumen['total_activos']++;
                        $estado = $info_mes['estado'] ?? 'Pendiente';
                        if($estado == 'Pagado') $resumen['pagados']++;
                        elseif($estado == 'Revision') $resumen['revision']++;
                        else $resumen['pendientes']++;
                    } else {
                        $resumen['por_vencer']++;
                    }
                    
                    $resumen['meses_detalle'][$clave] = [
                        'estado' => $info_mes['estado'] ?? 'Pendiente',
                        'activo' => in_array($clave, $meses_activos)
                    ];
                }
            }
            break;
        }
    }
    
    return $resumen;
}


/**
 * OBTENER MES ACTUAL EN TEXTO PARA MOSTRAR EN REPORTES
 */
function obtenerMesActualReporte() {
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    return $meses[intval(date('n')) - 1];
}


/**
 * OBTENER AÑO ACTUAL
 */
function obtenerAnioActual() {
    return date('Y');
}

/**
 * OBTENER ASISTENCIA POR CATEGORÍA
 */
function obtenerAsistenciaPorCategoria($todos_los_entrenamientos) {
    $resultado = [];
    foreach($todos_los_entrenamientos as $e) {
        $cat = $e['categoria'] ?? 'Sin categoría';
        if(!isset($resultado[$cat])) {
            $resultado[$cat] = [
                'total_entrenamientos' => 0,
                'total_asistentes' => 0,
                'total_convocados' => 0
            ];
        }
        $resultado[$cat]['total_entrenamientos']++;
        $resultado[$cat]['total_asistentes'] += $e['asistentes'] ?? 0;
        $resultado[$cat]['total_convocados'] += $e['total_atletas'] ?? 0;
    }
    
    foreach($resultado as $cat => &$data) {
        $data['porcentaje'] = $data['total_convocados'] > 0 ? 
            round(($data['total_asistentes'] / $data['total_convocados']) * 100) : 0;
    }
    
    return $resultado;
}


/**
 * OBTENER TOP DE ATLETAS CON MEJOR ASISTENCIA
 */
function obtenerTopAsistencia($toda_la_asistencia, $todos_los_atletas, $limite = 5) {
    $asistencia_atletas = [];
    foreach($toda_la_asistencia as $a) {
        $atleta_id = $a['atleta_id'];
        if(!isset($asistencia_atletas[$atleta_id])) {
            $asistencia_atletas[$atleta_id] = ['total' => 0, 'presentes' => 0];
        }
        $asistencia_atletas[$atleta_id]['total']++;
        if($a['asistio'] == 1) {
            $asistencia_atletas[$atleta_id]['presentes']++;
        }
    }
    
    $resultado = [];
    foreach($asistencia_atletas as $atleta_id => $data) {
        $atleta = null;
        foreach($todos_los_atletas as $a) {
            $atletaEntity = $a['atleta'] ?? null;
            if($atletaEntity && $atletaEntity->getAtletaId() == $atleta_id) {
                $atleta = $a;
                break;
            }
        }
        if($atleta && $data['total'] > 0) {
            $resultado[] = [
                'atleta' => $atleta,
                'total' => $data['total'],
                'presentes' => $data['presentes'],
                'porcentaje' => round(($data['presentes'] / $data['total']) * 100)
            ];
        }
    }
    
    usort($resultado, function($a, $b) {
        return $b['porcentaje'] - $a['porcentaje'];
    });
    
    return array_slice($resultado, 0, $limite);
}


/**
 * OBTENER FRECUENCIA DE PRUEBAS POR CATEGORÍA
 */
function obtenerFrecuenciaPruebas($todas_las_pruebas, $todos_los_atletas) {
    $resultado = [];
    $tipos = array_keys($todas_las_pruebas);
    
    foreach($tipos as $tipo) {
        $pruebas_tipo = $todas_las_pruebas[$tipo] ?? [];
        foreach($pruebas_tipo as $p) {
            $cedula = $p['atleta_cedula'] ?? null;
            if(!$cedula) continue;
            
            $categoria = '';
            foreach($todos_los_atletas as $a) {
                if($a['atleta_cedula'] == $cedula) {
                    $categoria = $a['atleta_categoria'] ?? 'Sin categoría';
                    break;
                }
            }
            
            if(!isset($resultado[$categoria])) {
                $resultado[$categoria] = [];
            }
            if(!isset($resultado[$categoria][$tipo])) {
                $resultado[$categoria][$tipo] = 0;
            }
            $resultado[$categoria][$tipo]++;
        }
    }
    
    return $resultado;
}


/**
 * OBTENER RESUMEN DE ENTRENADORES
 */
function obtenerResumenEntrenadores($todos_los_usuarios, $todos_los_entrenamientos) {
    $entrenadores = array_filter($todos_los_usuarios, function($u) {
        return $u['usuario_rol'] == 'Entrenador';
    });
    
    $resultado = [];
    foreach($entrenadores as $e) {
        $cedula = $e['usuario_cedula'];
        $entrenamientos = array_filter($todos_los_entrenamientos, function($en) use ($cedula) {
            return ($en['entrenador_cedula'] ?? '') == $cedula;
        });
        
        $total_entrenamientos = count($entrenamientos);
        $total_asistentes = 0;
        $total_convocados = 0;
        foreach($entrenamientos as $en) {
            $total_asistentes += $en['asistentes'] ?? 0;
            $total_convocados += $en['total_atletas'] ?? 0;
        }
        
        $resultado[] = [
            'usuario' => $e,
            'total_entrenamientos' => $total_entrenamientos,
            'total_asistentes' => $total_asistentes,
            'total_convocados' => $total_convocados,
            'porcentaje_asistencia' => $total_convocados > 0 ? 
                round(($total_asistentes / $total_convocados) * 100) : 0
        ];
    }
    
    usort($resultado, function($a, $b) {
        return $b['total_entrenamientos'] - $a['total_entrenamientos'];
    });
    
    return $resultado;
}

function obtenerRangoMesesReporte() {
    $meses_activos = obtenerMesesActivosReportes();
    $meses_nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    if(empty($meses_activos)) return 'Sin datos';
    if(count($meses_activos) == 1) {
        return $meses_nombres[array_search($meses_activos[0], $meses_nombres)];
    }
    return $meses_nombres[array_search($meses_activos[0], $meses_nombres)] . ' - ' . $meses_nombres[array_search(end($meses_activos), $meses_nombres)];
}