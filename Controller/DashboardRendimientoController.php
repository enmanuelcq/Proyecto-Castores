<?php
require_once './Model/Pruebas.php';
require_once './Model/Repository/RepositoryAtleta.php';
require_once './Model/Repository/RepositoryPrueba.php';
require_once './Model/Repository/RepositoryConvocado.php';
require_once './Model/Repository/RepositoryPartido.php';
require_once './Model/Repository/RepositoryGol.php';
require_once './Model/Repository/RepositoryPartidoTarjeta.php';

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
    return $unidades[$clave] ?? '';
}

$cedula_atleta = isset($_GET['id']) ? trim($_GET['id']) : '';

if(empty($cedula_atleta)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver su dashboard</div>';
    exit();
}

$repoAtleta = new RepositoryAtleta();
$repoPrueba = new RepositoryPrueba();
$repoConvocado = new RepositoryConvocado();
$repoPartido = new RepositoryPartido();
$repoGol = new RepositoryGol();
$repoTarjeta = new RepositoryPartidoTarjeta();

$atletaEntity = $repoAtleta->findById($cedula_atleta);

if(!$atletaEntity) {
    echo '<div class="alert alert-danger">Atleta no encontrado</div>';
    exit();
}

$atletaId = $atletaEntity->getAtletaId();
$categoriaId = $repoAtleta->getCategoriaId($atletaId);
$categoria_atleta = $repoAtleta->getCategoriaAtleta($atletaId) ?? 'Sin categoría';

$atleta = [
    'atleta_cedula' => $atletaEntity->getCedula(),
    'atleta_primer_nombre' => $atletaEntity->getPrimerNombre(),
    'atleta_primer_apellido' => $atletaEntity->getPrimerApellido(),
    'atleta_fecha_nacimiento' => $atletaEntity->getFechaNacimiento(),
    'atleta_categoria' => $categoria_atleta,
];

$pruebas_atleta = $repoPrueba->findAllByAtleta($atletaId);

$total_pruebas = $repoPrueba->countByAtleta($atletaId);

$ultimos_valores = [];
$promedios_categoria = [];

if ($categoriaId !== null) {
    $promedios_categoria = $repoPrueba->getPromediosByCategoria($categoriaId);
}

foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = $pruebas_atleta[$clave] ?? [];
    if(!empty($pruebas_tipo)) {
        $valor = obtenerValorPrincipal(end($pruebas_tipo), $clave);
        $ultimos_valores[$clave] = ($valor !== '—') ? $valor : 0;
    } else {
        $ultimos_valores[$clave] = 0;
    }
}

$convocatorias = $repoConvocado->findByAtleta($atletaId);

$golesAtleta = $repoGol->findByAtleta($atletaId);
$total_goles = 0;
$golesPorPartido = [];
foreach($golesAtleta as $gol) {
    $total_goles += (int) $gol['cant_goles'];
    $pid = (int) $gol['partido_id'];
    $golesPorPartido[$pid] = ($golesPorPartido[$pid] ?? 0) + (int) $gol['cant_goles'];
}

$tarjetasAtleta = $repoTarjeta->findByAtleta($atletaId);
$total_amarillas = 0;
$total_rojas = 0;
foreach($tarjetasAtleta as $t) {
    if((int) $t['tarjeta_id'] === 1) $total_amarillas++;
    elseif((int) $t['tarjeta_id'] === 2) $total_rojas++;
}

$total_partidos = count($convocatorias);
$total_asistencias = 0;

$ganados = 0;
$perdidos = 0;
$empatados = 0;
$partidos_jugados = [];

foreach($convocatorias as $partidoRow) {
    $pid = (int) $partidoRow['partido_id'];
    $gf = (int) $partidoRow['goles_favor'];
    $gc = (int) $partidoRow['goles_contra'];
    $estado = $partidoRow['estado'] ?? 'Pendiente';

    $status = 'Pendiente';
    if($estado === 'Finalizado') {
        if($gf > $gc) { $status = 'Ganado'; $ganados++; }
        elseif($gf < $gc) { $status = 'Perdido'; $perdidos++; }
        else { $status = 'Empatado'; $empatados++; }
    } elseif($estado === 'En Curso') {
        $status = 'En Curso';
    }

    $partidos_jugados[] = [
        'partido' => [
            'id' => $pid,
            'rival' => $partidoRow['equipo_rival'] ?? 'N/A',
            'fecha' => $partidoRow['fecha_partido'],
            'status' => $status,
            'goles_favor' => $gf,
            'goles_contra' => $gc,
            'tipo' => $partidoRow['tipo_partido'] ?? 'Amistoso',
            'competencia' => $partidoRow['nombre_competencia'] ?? '',
        ],
        'participacion' => [
            'goles' => $golesPorPartido[$pid] ?? 0,
            'minutos_jugados' => 0,
        ]
    ];
}

usort($partidos_jugados, function($a, $b) {
    return strtotime($b['partido']['fecha']) - strtotime($a['partido']['fecha']);
});

$datos_graficos = [];
foreach($tipos_prueba as $clave => $tipo) {
    $aspects = RepositoryPrueba::getAspects($clave);
    if (empty($aspects)) continue;

    $latest = $repoPrueba->getLatestAspects($atletaId, $clave);
    $athleteAvg = $repoPrueba->getAspectAveragesByAtleta($atletaId, $clave);
    $catAvg = ($categoriaId !== null) ? $repoPrueba->getAspectAveragesByCategoria($categoriaId, $clave) : [];

    $hasData = false;
    foreach ($latest as $v) { if ($v > 0) { $hasData = true; break; } }
    foreach ($athleteAvg as $v) { if ($v > 0) { $hasData = true; break; } }
    foreach ($catAvg as $v) { if ($v > 0) { $hasData = true; break; } }

    if (!$hasData) continue;

    $datos_graficos[] = [
        'label' => $tipo['nombre'],
        'clave' => $clave,
        'aspect_labels' => array_values($aspects),
        'ultima_prueba' => array_values($latest),
        'promedio_atleta' => array_values($athleteAvg),
        'promedio_categoria' => array_values($catAvg),
        'color' => obtenerColorTipo($clave),
        'unidad' => obtenerUnidadTipo($clave)
    ];
}

$evolucion_todos_datos = [];
$todas_fechas = [];

foreach($pruebas_atleta as $clave => $pruebas) {
    foreach($pruebas as $p) {
        $fecha = obtenerFechaPrueba($p);
        if($fecha && !in_array($fecha, $todas_fechas)) {
            $todas_fechas[] = $fecha;
        }
    }
}

sort($todas_fechas);

foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = $pruebas_atleta[$clave] ?? [];

    $mapa_valores = [];
    foreach($pruebas_tipo as $p) {
        $fecha = obtenerFechaPrueba($p);
        if($fecha) {
            $mapa_valores[$fecha] = obtenerValorPrincipal($p, $clave);
        }
    }

    if(count($mapa_valores) >= 2) {
        $valores_ordenados = [];
        foreach($todas_fechas as $fecha) {
            $valores_ordenados[] = $mapa_valores[$fecha] ?? null;
        }

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

$evolucion_labels = [];
foreach($todas_fechas as $fecha) {
    $evolucion_labels[] = date('d/m', strtotime($fecha));
}

$tipos_menor_mejor = ['conduccion', 'velocidad'];

$evolucion_mini = [];
foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = $pruebas_atleta[$clave] ?? [];
    if (count($pruebas_tipo) < 1) continue;

    usort($pruebas_tipo, function($a, $b) {
        return strtotime(obtenerFechaPrueba($a)) - strtotime(obtenerFechaPrueba($b));
    });

    $labels = [];
    $valores = [];
    foreach ($pruebas_tipo as $p) {
        $fecha = obtenerFechaPrueba($p);
        $valor = obtenerValorPrincipal($p, $clave);
        if ($fecha && $valor !== '—' && $valor > 0) {
            $labels[] = date('d/m', strtotime($fecha));
            $valores[] = (float) $valor;
        }
    }

    if (count($valores) < 1) continue;

    $count = count($valores);
    $mid = max(1, intval($count / 2));
    $first_avg = array_sum(array_slice($valores, 0, $mid)) / $mid;
    $second_avg = array_sum(array_slice($valores, $mid)) / max(1, $count - $mid);

    $tendencia = 'igual';
    $pct_cambio = 0;
    if ($first_avg > 0) {
        $pct_cambio = round((($second_avg - $first_avg) / $first_avg) * 100, 1);
        if (in_array($clave, $tipos_menor_mejor)) {
            $pct_cambio = -$pct_cambio;
        }
        $tendencia = $pct_cambio > 5 ? 'superior' : ($pct_cambio < -5 ? 'inferior' : 'igual');
    }

    $evolucion_mini[$clave] = [
        'nombre' => $tipo['nombre'],
        'icono' => $tipo['icono'],
        'color' => obtenerColorTipo($clave),
        'labels' => $labels,
        'valores' => $valores,
        'total' => count($valores),
        'primero' => $valores[0],
        'ultimo' => end($valores),
        'mejor' => in_array($clave, $tipos_menor_mejor) ? min($valores) : max($valores),
        'tendencia' => $tendencia,
        'pct_cambio' => abs($pct_cambio)
    ];
}

$radar_labels = [];
$radar_atleta = [];
$radar_categoria = [];

foreach($tipos_prueba as $clave => $tipo) {
    $va = $ultimos_valores[$clave] ?? 0;
    $vc = $promedios_categoria[$clave] ?? 0;
    if($va == 0 && $vc == 0) continue;

    $radar_labels[] = $tipo['nombre'];

    if(in_array($clave, $tipos_menor_mejor)) {
        $radar_atleta[] = $va > 0 ? min(100, round(($vc / $va) * 50, 1)) : 0;
        $radar_categoria[] = 50;
    } else {
        $base = max($va, $vc, 1);
        $radar_atleta[] = round(($va / $base) * 100, 1);
        $radar_categoria[] = $vc > 0 ? round(($vc / $base) * 100, 1) : 0;
    }
}

$progreso_tipos = [];
foreach($tipos_prueba as $clave => $tipo) {
    $va = $ultimos_valores[$clave] ?? 0;
    $vc = $promedios_categoria[$clave] ?? 0;
    $total_tipo = count($pruebas_atleta[$clave] ?? []);

    $porcentaje = 0;
    $tendencia = 'igual';
    if($vc > 0) {
        if(in_array($clave, $tipos_menor_mejor)) {
            $porcentaje = $va > 0 ? min(100, round(($vc / $va) * 100)) : 0;
            $tendencia = $porcentaje > 105 ? 'superior' : ($porcentaje < 95 ? 'inferior' : 'igual');
        } else {
            $porcentaje = round(($va / $vc) * 100);
            $tendencia = $porcentaje > 105 ? 'superior' : ($porcentaje < 95 ? 'inferior' : 'igual');
        }
    } elseif($va > 0) {
        $porcentaje = 100;
        $tendencia = 'superior';
    }

    $progreso_tipos[$clave] = [
        'nombre' => $tipo['nombre'],
        'icono' => $tipo['icono'],
        'color' => obtenerColorTipo($clave),
        'valor_atleta' => $va,
        'valor_categoria' => $vc,
        'porcentaje' => min($porcentaje, 150),
        'total_pruebas' => $total_tipo,
        'unidad' => obtenerUnidadTipo($clave),
        'tendencia' => $tendencia
    ];
}
