<?php
include './Controller/DashboardRendimientoController.php';
$inicial = strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
?>

<div class="container contenido">

    <!-- ===== PERFIL ===== -->
    <div class="rd-perfil">
        <div class="rd-perfil-avatar">
            <?php echo $inicial; ?>
        </div>
        <div class="rd-perfil-info">
            <h3><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h3>
            <div class="rd-perfil-tags">
                <span class="rd-tag rd-tag-green"><i class="bi bi-tag"></i> <?php echo $atleta['atleta_categoria'] ?? 'Sin categoría'; ?></span>
                <span class="rd-tag"><i class="bi bi-person-badge"></i> <?php echo $atleta['atleta_cedula']; ?></span>
                <span class="rd-tag"><i class="bi bi-calendar3"></i> <?php echo $atleta['atleta_fecha_nacimiento'] ?? 'N/A'; ?></span>
            </div>
        </div>
        <div class="rd-perfil-acciones">
            <a href="index.php?View=Pruebas&id=<?php echo $cedula_atleta; ?>" class="rd-btn rd-btn-outline">
                <i class="bi bi-clipboard2-check"></i> Pruebas
            </a>
            <a href="index.php?View=Partidos&id=<?php echo $cedula_atleta; ?>" class="rd-btn rd-btn-solid">
                <i class="bi bi-trophy"></i> Partidos
            </a>
        </div>
    </div>

    <!-- ===== KPI STATS ===== -->
    <div class="rd-kpi-row">
        <div class="rd-kpi rd-kpi-blue">
            <div class="rd-kpi-icon"><i class="bi bi-trophy"></i></div>
            <div class="rd-kpi-data">
                <span class="rd-kpi-valor"><?php echo $total_partidos; ?></span>
                <span class="rd-kpi-label">Partidos</span>
            </div>
        </div>
        <div class="rd-kpi rd-kpi-green">
            <div class="rd-kpi-icon"><i class="bi bi-circle"></i></div>
            <div class="rd-kpi-data">
                <span class="rd-kpi-valor"><?php echo $total_goles; ?></span>
                <span class="rd-kpi-label">Goles</span>
            </div>
        </div>
        <div class="rd-kpi rd-kpi-cyan">
            <div class="rd-kpi-icon"><i class="bi bi-people"></i></div>
            <div class="rd-kpi-data">
                <span class="rd-kpi-valor"><?php echo $total_asistencias; ?></span>
                <span class="rd-kpi-label">Asistencias</span>
            </div>
        </div>
        <div class="rd-kpi rd-kpi-amber">
            <div class="rd-kpi-icon"><i class="bi bi-clipboard-data"></i></div>
            <div class="rd-kpi-data">
                <span class="rd-kpi-valor"><?php echo $total_pruebas; ?></span>
                <span class="rd-kpi-label">Pruebas</span>
            </div>
        </div>
        <div class="rd-kpi rd-kpi-yellow">
            <div class="rd-kpi-icon"><i class="bi bi-square"></i></div>
            <div class="rd-kpi-data">
                <span class="rd-kpi-valor"><?php echo $total_amarillas; ?></span>
                <span class="rd-kpi-label">Amarillas</span>
            </div>
        </div>
        <div class="rd-kpi rd-kpi-red">
            <div class="rd-kpi-icon"><i class="bi bi-square-fill"></i></div>
            <div class="rd-kpi-data">
                <span class="rd-kpi-valor"><?php echo $total_rojas; ?></span>
                <span class="rd-kpi-label">Rojas</span>
            </div>
        </div>
    </div>

    <!-- ===== RADAR + RECORD ===== -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="rd-card">
                <div class="rd-card-header">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>Perfil Deportivo</span>
                </div>
                <div class="rd-card-body rd-card-body-chart">
                    <p class="rd-chart-desc"><i class="bi bi-info-circle"></i> Compara el rendimiento más reciente del atleta contra el promedio de su categoría. Cada eje representa un tipo de prueba. El valor <strong>50</strong> equivale al promedio de la categoría: por encima es superior, por debajo es inferior.</p>
                    <?php if(!empty($radar_labels)): ?>
                    <canvas id="radarChart"></canvas>
                    <?php else: ?>
                    <div class="rd-empty-state">
                        <i class="bi bi-diagram-3"></i>
                        <p>Suficientes pruebas para generar perfil</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="rd-card">
                <div class="rd-card-header">
                    <i class="bi bi-pie-chart-fill"></i>
                    <span>Record de Partidos</span>
                </div>
                <div class="rd-card-body rd-card-body-center">
                    <p class="rd-chart-desc rd-chart-desc-center"><i class="bi bi-info-circle"></i> Distribución de resultados en partidos finalizados.</p>
                    <?php if($total_partidos > 0): ?>
                    <canvas id="recordChart"></canvas>
                    <div class="rd-record-legend">
                        <span class="rd-legend-item"><i class="bi bi-circle-fill" style="color:#0d7a3f"></i> Ganados: <?php echo $ganados; ?></span>
                        <span class="rd-legend-item"><i class="bi bi-circle-fill" style="color:#6b7280"></i> Empates: <?php echo $empatados; ?></span>
                        <span class="rd-legend-item"><i class="bi bi-circle-fill" style="color:#dc2626"></i> Perdidos: <?php echo $perdidos; ?></span>
                    </div>
                    <?php else: ?>
                    <div class="rd-empty-state">
                        <i class="bi bi-trophy"></i>
                        <p>Sin partidos registrados</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PROGRESO POR TIPO ===== -->
    <div class="rd-card mb-4">
        <div class="rd-card-header">
            <i class="bi bi-bar-chart-line-fill"></i>
            <span>Rendimiento por Prueba</span>
            <small>último resultado vs promedio de <?php echo $categoria_atleta; ?></small>
        </div>
        <div class="rd-card-body">
            <p class="rd-chart-desc"><i class="bi bi-info-circle"></i> Muestra el último resultado registrado por el atleta en cada prueba deportiva comparado con el promedio de su categoría. La marca indica el 50% (promedio del grupo). Los badges muestran si el atleta está por encima, por debajo o en el promedio.</p>
            <div class="rd-progreso-grid">
                <?php foreach($progreso_tipos as $clave => $prog): ?>
                <div class="rd-progreso-item">
                    <div class="rd-progreso-top">
                        <div class="rd-progreso-name">
                            <i class="bi <?php echo $prog['icono']; ?>" style="color:<?php echo $prog['color']; ?>;"></i>
                            <span><?php echo $prog['nombre']; ?></span>
                        </div>
                        <div class="rd-progreso-values">
                            <span class="rd-prog-actual" style="color:<?php echo $prog['color']; ?>;"><?php echo $prog['valor_atleta'] > 0 ? $prog['valor_atleta'] : '—'; ?></span>
                            <span class="rd-prog-sep">/</span>
                            <span class="rd-prog-cat"><?php echo $prog['valor_categoria'] > 0 ? $prog['valor_categoria'] : '—'; ?></span>
                            <span class="rd-prog-unit"><?php echo $prog['unidad']; ?></span>
                        </div>
                    </div>
                    <div class="rd-progreso-bar-track">
                        <div class="rd-progreso-bar-fill" style="width:<?php echo min($prog['porcentaje'], 100); ?>%; background:<?php echo $prog['color']; ?>;"></div>
                        <?php if($prog['porcentaje'] > 100): ?>
                        <div class="rd-progreso-bar-fill over" style="width:100%; background:<?php echo $prog['color']; ?>;"></div>
                        <?php endif; ?>
                        <div class="rd-progreso-bar-marker" style="left:50%;"></div>
                    </div>
                    <div class="rd-progreso-bottom">
                        <span class="rd-badge rd-badge-<?php echo $prog['tendencia']; ?>">
                            <?php if($prog['tendencia'] === 'superior'): ?>
                            <i class="bi bi-arrow-up-short"></i> Superior
                            <?php elseif($prog['tendencia'] === 'inferior'): ?>
                            <i class="bi bi-arrow-down-short"></i> Inferior
                            <?php else: ?>
                            <i class="bi bi-dash"></i> Promedio
                            <?php endif; ?>
                        </span>
                        <span class="rd-prog-count"><?php echo $prog['total_pruebas']; ?> prueba<?php echo $prog['total_pruebas'] !== 1 ? 's' : ''; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ===== ANÁLISIS POR ASPECTO ===== -->
    <?php if(!empty($datos_graficos)): ?>
    <div class="rd-card mb-4">
        <div class="rd-card-header">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Análisis Detallado por Aspecto</span>
        </div>
        <div class="rd-card-body">
            <p class="rd-chart-desc"><i class="bi bi-info-circle"></i> Para cada tipo de prueba se desglosan los aspectos evaluados individualmente. Compara la <strong>Última Prueba</strong> (oscuro), el <strong>Promedio del Atleta</strong> (verde claro) y el <strong>Promedio de la Categoría</strong> (naranja). Navega entre pestañas para ver cada prueba.</p>
            <ul class="nav rd-chart-tabs" id="chartTabs" role="tablist">
                <?php foreach($datos_graficos as $idx => $dato): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $idx === 0 ? 'active' : ''; ?>"
                            id="tab-<?php echo $idx; ?>"
                            data-bs-toggle="tab"
                            data-bs-target="#panel-<?php echo $idx; ?>"
                            type="button" role="tab">
                        <i class="bi <?php echo $tipos_prueba[$dato['clave']]['icono'] ?? 'bi-bar-chart'; ?>"
                           style="color:<?php echo $dato['color']; ?>;"></i>
                        <?php echo $dato['label']; ?>
                    </button>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content rd-chart-tab-content" id="chartTabContent">
                <?php foreach($datos_graficos as $idx => $dato): ?>
                <div class="tab-pane fade <?php echo $idx === 0 ? 'show active' : ''; ?>"
                     id="panel-<?php echo $idx; ?>" role="tabpanel">
                    <div class="rd-chart-panel">
                        <canvas id="graficoIndependiente_<?php echo $idx; ?>"></canvas>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== EVOLUCIÓN POR PRUEBA ===== -->
    <?php if(!empty($evolucion_mini)): ?>
    <div class="rd-card mb-4">
        <div class="rd-card-header">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Evolución por Prueba</span>
        </div>
        <div class="rd-card-body">
            <p class="rd-chart-desc"><i class="bi bi-info-circle"></i> Cada mini gráfica muestra la evolución individual del atleta en cada prueba a lo largo del tiempo. Las flechas indican la tendencia general: <strong>verde</strong> = mejora, <strong>rojo</strong> = retroceso, <strong>amarillo</strong> = estable. Los valores muestran el primer registro, el último y el mejor resultado.</p>
            <div class="rd-mini-grid">
                <?php foreach($evolucion_mini as $clave => $mini): ?>
                <div class="rd-mini-card">
                    <div class="rd-mini-top">
                        <div class="rd-mini-name-row">
                            <i class="bi <?php echo $mini['icono']; ?>" style="color:<?php echo $mini['color']; ?>;"></i>
                            <span><?php echo $mini['nombre']; ?></span>
                        </div>
                        <span class="rd-badge rd-badge-<?php echo $mini['tendencia']; ?>">
                            <?php if($mini['tendencia'] === 'superior'): ?>
                            <i class="bi bi-arrow-up-short"></i>+<?php echo $mini['pct_cambio']; ?>%
                            <?php elseif($mini['tendencia'] === 'inferior'): ?>
                            <i class="bi bi-arrow-down-short"></i>-<?php echo $mini['pct_cambio']; ?>%
                            <?php else: ?>
                            <i class="bi bi-dash"></i>estable
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="rd-mini-chart-wrap">
                        <canvas id="mini_<?php echo $clave; ?>"></canvas>
                    </div>
                    <div class="rd-mini-footer">
                        <div class="rd-mini-stat">
                            <span class="rd-mini-stat-label">Primero</span>
                            <span class="rd-mini-stat-val"><?php echo $mini['primero']; ?></span>
                        </div>
                        <div class="rd-mini-stat">
                            <span class="rd-mini-stat-label">Último</span>
                            <span class="rd-mini-stat-val" style="color:<?php echo $mini['color']; ?>;"><?php echo $mini['ultimo']; ?></span>
                        </div>
                        <div class="rd-mini-stat">
                            <span class="rd-mini-stat-label">Mejor</span>
                            <span class="rd-mini-stat-val rd-mini-stat-best"><?php echo $mini['mejor']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== ÚLTIMOS RESULTADOS + PARTIDOS ===== -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="rd-card">
                <div class="rd-card-header">
                    <i class="bi bi-clipboard2-check"></i>
                    <span>Últimas Pruebas</span>
                </div>
                <div class="rd-card-body rd-card-body-list">
                    <?php
                    $contador = 0;
                    foreach($tipos_prueba as $clave => $tipo):
                        $pruebas_tipo = $pruebas_atleta[$clave] ?? [];
                        if(empty($pruebas_tipo) || $contador >= 5) continue;

                        usort($pruebas_tipo, function($a, $b) {
                            return strtotime(obtenerFechaPrueba($b)) - strtotime(obtenerFechaPrueba($a));
                        });
                        $ultima = $pruebas_tipo[0];
                        $valor_atleta = obtenerValorPrincipal($ultima, $clave);
                        $promedio = $promedios_categoria[$clave] ?? 0;
                        $fecha = obtenerFechaPrueba($ultima);
                        $color = obtenerColorTipo($clave);
                        $icono = $tipo['icono'] ?? 'bi-clipboard';

                        $pct = 0;
                        $cls = 'igual';
                        if($valor_atleta > 0 && $promedio > 0) {
                            $pct = round((($valor_atleta - $promedio) / $promedio) * 100, 1);
                            $cls = $pct > 5 ? 'superior' : ($pct < -5 ? 'inferior' : 'igual');
                        }
                        $contador++;
                    ?>
                    <div class="rd-list-item">
                        <div class="rd-list-icon" style="background:<?php echo $color; ?>20; color:<?php echo $color; ?>;">
                            <i class="bi <?php echo $icono; ?>"></i>
                        </div>
                        <div class="rd-list-info">
                            <span class="rd-list-name"><?php echo $tipo['nombre']; ?></span>
                            <span class="rd-list-date"><?php echo $fecha ? formatearFecha($fecha) : 'N/A'; ?></span>
                        </div>
                        <div class="rd-list-right">
                            <span class="rd-list-value" style="color:<?php echo $color; ?>;"><?php echo $valor_atleta > 0 ? $valor_atleta : '—'; ?></span>
                            <?php if($pct != 0): ?>
                            <span class="rd-badge rd-badge-<?php echo $cls; ?>">
                                <?php echo $pct > 0 ? '+' . $pct . '%' : $pct . '%'; ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if($contador == 0): ?>
                    <div class="rd-empty-state">
                        <i class="bi bi-clipboard-x"></i>
                        <p>Sin pruebas registradas</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="rd-card">
                <div class="rd-card-header">
                    <i class="bi bi-trophy"></i>
                    <span>Partidos Recientes</span>
                </div>
                <div class="rd-card-body rd-card-body-list">
                    <?php
                    $contador = 0;
                    foreach($partidos_jugados as $pj):
                        if($contador >= 5) break;
                        $partido = $pj['partido'];
                        $participacion = $pj['participacion'];
                        $contador++;

                        $statusLower = strtolower($partido['status']);
                        $statusColor = $statusLower === 'ganado' ? '#0d7a3f' : ($statusLower === 'perdido' ? '#dc2626' : '#d97706');
                    ?>
                    <div class="rd-list-item">
                        <div class="rd-list-icon" style="background:<?php echo $statusColor; ?>18; color:<?php echo $statusColor; ?>;">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="rd-list-info">
                            <span class="rd-list-name">vs <?php echo $partido['rival']; ?></span>
                            <span class="rd-list-date">
                                <?php echo formatearFecha($partido['fecha']); ?>
                                <span class="rd-badge-mini" style="background:<?php echo $statusColor; ?>20; color:<?php echo $statusColor; ?>;"><?php echo $partido['status']; ?></span>
                            </span>
                        </div>
                        <div class="rd-list-right">
                            <span class="rd-list-value">⚽ <?php echo $participacion['goles']; ?></span>
                            <span class="rd-list-sub"><?php echo $partido['tipo']; ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if($contador == 0): ?>
                    <div class="rd-empty-state">
                        <i class="bi bi-trophy"></i>
                        <p>Sin partidos registrados</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
Chart.register(ChartDataLabels);

const COLORS = {
    green: '#0d7a3f', greenLight: '#5cb67e', orange: '#ff9800',
    red: '#dc2626', gray: '#6b7280', amber: '#d97706'
};

// ===== RADAR CHART =====
<?php if(!empty($radar_labels)): ?>
(function() {
    const ctx = document.getElementById('radarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: <?php echo json_encode($radar_labels); ?>,
            datasets: [
                {
                    label: '<?php echo $atleta['atleta_primer_nombre']; ?>',
                    data: <?php echo json_encode($radar_atleta); ?>,
                    backgroundColor: 'rgba(13,122,63,0.18)',
                    borderColor: COLORS.green,
                    borderWidth: 2.5,
                    pointBackgroundColor: COLORS.green,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Promedio <?php echo $categoria_atleta; ?>',
                    data: <?php echo json_encode($radar_categoria); ?>,
                    backgroundColor: 'rgba(255,152,0,0.10)',
                    borderColor: COLORS.orange,
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: COLORS.orange,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#111827', font: { size: 13, weight: '700' }, padding: 18, usePointStyle: true }
                },
                tooltip: {
                    backgroundColor: 'rgba(26,26,46,0.92)',
                    titleFont: { size: 13, weight: '700' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) { return ctx.dataset.label + ': ' + ctx.parsed.r.toFixed(1) + '%'; }
                    }
                },
                datalabels: { display: false }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        color: '#374151',
                        font: { size: 11, weight: '600' },
                        backdropColor: 'transparent'
                    },
                    pointLabels: {
                        color: '#111827',
                        font: { size: 13, weight: '700' }
                    },
                    grid: { color: 'rgba(0,0,0,0.08)' },
                    angleLines: { color: 'rgba(0,0,0,0.06)' }
                }
            }
        }
    });
})();
<?php endif; ?>

// ===== RECORD DOUGHNUT =====
<?php if($total_partidos > 0): ?>
(function() {
    const ctx = document.getElementById('recordChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Ganados', 'Empates', 'Perdidos'],
            datasets: [{
                data: [<?php echo $ganados; ?>, <?php echo $empatados; ?>, <?php echo $perdidos; ?>],
                backgroundColor: [COLORS.green, COLORS.gray, COLORS.red],
                borderColor: '#eef3ef',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(26,26,46,0.92)',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) { return ctx.label + ': ' + ctx.parsed + ' partidos'; }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: { weight: '700', size: 14 },
                    formatter: function(value, ctx) {
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return total > 0 ? Math.round(value / total * 100) + '%' : '';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
})();
<?php endif; ?>

// ===== PER-ASPECT BAR CHARTS (TABBED) =====
<?php foreach($datos_graficos as $indice => $dato):
    $allVals = array_merge($dato['ultima_prueba'], $dato['promedio_atleta'], $dato['promedio_categoria']);
    $maximo = max(array_merge($allVals, [1]));
    $maximo = ceil($maximo * 1.3);
?>
document.getElementById('tab-<?php echo $indice; ?>').addEventListener('shown.bs.tab', function() {
    const canvas = document.getElementById('graficoIndependiente_<?php echo $indice; ?>');
    if(canvas._chartRendered) return;
    canvas._chartRendered = true;

    const ctx = canvas.getContext('2d');
    const g1 = ctx.createLinearGradient(0, 0, 0, 300);
    g1.addColorStop(0, 'rgba(13,122,63,0.92)');
    g1.addColorStop(1, 'rgba(13,122,63,0.50)');
    const g2 = ctx.createLinearGradient(0, 0, 0, 300);
    g2.addColorStop(0, 'rgba(92,182,126,0.88)');
    g2.addColorStop(1, 'rgba(92,182,126,0.42)');
    const g3 = ctx.createLinearGradient(0, 0, 0, 300);
    g3.addColorStop(0, 'rgba(255,152,0,0.88)');
    g3.addColorStop(1, 'rgba(255,152,0,0.42)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dato['aspect_labels']); ?>,
            datasets: [
                {
                    label: 'Última Prueba',
                    data: <?php echo json_encode($dato['ultima_prueba']); ?>,
                    backgroundColor: g1,
                    borderColor: COLORS.green,
                    borderWidth: 2,
                    borderRadius: 6,
                    barPercentage: 0.75,
                    categoryPercentage: 0.85
                },
                {
                    label: 'Promedio Atleta',
                    data: <?php echo json_encode($dato['promedio_atleta']); ?>,
                    backgroundColor: g2,
                    borderColor: COLORS.greenLight,
                    borderWidth: 2,
                    borderRadius: 6,
                    barPercentage: 0.75,
                    categoryPercentage: 0.85
                },
                {
                    label: 'Promedio Categoría',
                    data: <?php echo json_encode($dato['promedio_categoria']); ?>,
                    backgroundColor: g3,
                    borderColor: COLORS.orange,
                    borderWidth: 2,
                    borderRadius: 6,
                    barPercentage: 0.75,
                    categoryPercentage: 0.85
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { color: '#111827', font: { size: 12, weight: '700' }, boxWidth: 14, padding: 16, usePointStyle: true, pointStyle: 'rectRounded' }
                },
                tooltip: {
                    backgroundColor: 'rgba(26,26,46,0.92)',
                    titleFont: { size: 13, weight: '700' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(c) { return c.dataset.label + ': ' + c.parsed.y.toFixed(2) + ' <?php echo $dato['unidad']; ?>'; }
                    }
                },
                datalabels: {
                    anchor: 'end', align: 'top',
                    color: '#111827',
                    font: { weight: '700', size: 12 },
                    formatter: function(v) { return v > 0 ? v.toFixed(1) : ''; },
                    offset: 4
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: <?php echo $maximo; ?>,
                    ticks: { color: '#111827', font: { size: 12, weight: '600' }, callback: function(v) { return v.toFixed(1); } },
                    grid: { color: 'rgba(0,0,0,0.06)', drawBorder: false }
                },
                x: {
                    ticks: { color: '#111827', font: { size: 12, weight: '700' }, maxRotation: 25 },
                    grid: { display: false }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});

// Render first tab immediately
<?php if($indice === 0): ?>
(function() {
    const canvas = document.getElementById('graficoIndependiente_0');
    if(canvas._chartRendered) return;
    canvas._chartRendered = true;

    const ctx = canvas.getContext('2d');
    const g1 = ctx.createLinearGradient(0, 0, 0, 300);
    g1.addColorStop(0, 'rgba(13,122,63,0.92)');
    g1.addColorStop(1, 'rgba(13,122,63,0.50)');
    const g2 = ctx.createLinearGradient(0, 0, 0, 300);
    g2.addColorStop(0, 'rgba(92,182,126,0.88)');
    g2.addColorStop(1, 'rgba(92,182,126,0.42)');
    const g3 = ctx.createLinearGradient(0, 0, 0, 300);
    g3.addColorStop(0, 'rgba(255,152,0,0.88)');
    g3.addColorStop(1, 'rgba(255,152,0,0.42)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dato['aspect_labels']); ?>,
            datasets: [
                { label: 'Última Prueba', data: <?php echo json_encode($dato['ultima_prueba']); ?>, backgroundColor: g1, borderColor: COLORS.green, borderWidth: 2, borderRadius: 6, barPercentage: 0.75, categoryPercentage: 0.85 },
                { label: 'Promedio Atleta', data: <?php echo json_encode($dato['promedio_atleta']); ?>, backgroundColor: g2, borderColor: COLORS.greenLight, borderWidth: 2, borderRadius: 6, barPercentage: 0.75, categoryPercentage: 0.85 },
                { label: 'Promedio Categoría', data: <?php echo json_encode($dato['promedio_categoria']); ?>, backgroundColor: g3, borderColor: COLORS.orange, borderWidth: 2, borderRadius: 6, barPercentage: 0.75, categoryPercentage: 0.85 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { color: '#111827', font: { size: 12, weight: '700' }, boxWidth: 14, padding: 16, usePointStyle: true, pointStyle: 'rectRounded' } },
                tooltip: { backgroundColor: 'rgba(26,26,46,0.92)', titleFont: { size: 13, weight: '700' }, bodyFont: { size: 12 }, padding: 12, cornerRadius: 8, callbacks: { label: function(c) { return c.dataset.label + ': ' + c.parsed.y.toFixed(2) + ' <?php echo $dato['unidad']; ?>'; } } },
                datalabels: { anchor: 'end', align: 'top', color: '#111827', font: { weight: '700', size: 12 }, formatter: function(v) { return v > 0 ? v.toFixed(1) : ''; }, offset: 4 }
            },
            scales: {
                y: { beginAtZero: true, max: <?php echo $maximo; ?>, ticks: { color: '#111827', font: { size: 12, weight: '600' }, callback: function(v) { return v.toFixed(1); } }, grid: { color: 'rgba(0,0,0,0.06)', drawBorder: false } },
                x: { ticks: { color: '#111827', font: { size: 12, weight: '700' }, maxRotation: 25 }, grid: { display: false } }
            }
        },
        plugins: [ChartDataLabels]
    });
})();
<?php endif; ?>
<?php endforeach; ?>

// ===== MINI EVOLUTION CHARTS =====
<?php foreach($evolucion_mini as $clave => $mini): ?>
(function() {
    const canvas = document.getElementById('mini_<?php echo $clave; ?>');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 100);
    grad.addColorStop(0, '<?php echo $mini['color']; ?>30');
    grad.addColorStop(1, '<?php echo $mini['color']; ?>05');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($mini['labels']); ?>,
            datasets: [{
                data: <?php echo json_encode($mini['valores']); ?>,
                borderColor: '<?php echo $mini['color']; ?>',
                backgroundColor: grad,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '<?php echo $mini['color']; ?>',
                pointBorderColor: '#fff',
                pointBorderWidth: 1.5,
                borderWidth: 2.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(26,26,46,0.92)',
                    titleFont: { size: 11, weight: '700' },
                    bodyFont: { size: 11 },
                    padding: 8,
                    cornerRadius: 6,
                    callbacks: {
                        title: function(items) { return items[0].label; },
                        label: function(c) { return '<?php echo $mini['nombre']; ?>: ' + c.parsed.y.toFixed(2); }
                    }
                },
                datalabels: { display: false }
            },
            scales: {
                x: { display: false },
                y: { display: false, beginAtZero: false }
            },
            interaction: { mode: 'index', intersect: false }
        }
    });
})();
<?php endforeach; ?>
</script>
