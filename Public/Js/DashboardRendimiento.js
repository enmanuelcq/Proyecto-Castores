// ============================================
// GRÁFICO COMPARATIVO
// ============================================
const ctxComparativa = document.getElementById('comparativaChart').getContext('2d');

new Chart(ctxComparativa, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [
            {
                label: 'Tu resultado',
                data: <?php echo json_encode($datos_atleta); ?>,
                backgroundColor: 'rgba(255, 193, 7, 0.7)',
                borderColor: '#ffc107',
                borderWidth: 2,
                borderRadius: 4,
            },
            {
                label: 'Promedio ' + '<?php echo $categoria_atleta; ?>',
                data: <?php echo json_encode($datos_promedio); ?>,
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: '#0d6efd',
                borderWidth: 2,
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: {
                    color: 'rgba(255,255,255,0.6)',
                    font: { size: 11 }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: 'rgba(255,255,255,0.3)',
                    font: { size: 10 }
                },
                grid: {
                    color: 'rgba(255,255,255,0.05)'
                }
            },
            x: {
                ticks: {
                    color: 'rgba(255,255,255,0.3)',
                    font: { size: 10 }
                },
                grid: {
                    color: 'rgba(255,255,255,0.05)'
                }
            }
        }
    }
});

// ============================================
// GRÁFICO DE PARTIDOS
// ============================================
const ctxPartidos = document.getElementById('partidosChart').getContext('2d');

new Chart(ctxPartidos, {
    type: 'doughnut',
    data: {
        labels: ['Ganados', 'Empatados', 'Perdidos'],
        datasets: [{
            data: [<?php echo $ganados; ?>, <?php echo $empatados; ?>, <?php echo $perdidos; ?>],
            backgroundColor: ['#198754', '#ffc107', '#dc3545'],
            borderWidth: 2,
            borderColor: '#1a1a2e'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: 'rgba(255,255,255,0.6)',
                    font: { size: 11 },
                    padding: 15
                }
            }
        },
        cutout: '65%'
    }
});

// ============================================
// GRÁFICO DE EVOLUCIÓN
// ============================================
<?php if(!empty($evolucion_labels) && !empty($evolucion_datos)): ?>
const ctxEvolucion = document.getElementById('evolucionChart').getContext('2d');

new Chart(ctxEvolucion, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($evolucion_labels); ?>,
        datasets: [{
            label: '<?php echo obtenerNombreTipo($evolucion_tipo); ?>',
            data: <?php echo json_encode($evolucion_datos); ?>,
            borderColor: '#ffc107',
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
            fill: true,
            tension: 0.3,
            pointBackgroundColor: '#ffc107',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: {
                    color: 'rgba(255,255,255,0.6)',
                    font: { size: 11 }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: 'rgba(255,255,255,0.3)',
                    font: { size: 10 }
                },
                grid: {
                    color: 'rgba(255,255,255,0.05)'
                }
            },
            x: {
                ticks: {
                    color: 'rgba(255,255,255,0.3)',
                    font: { size: 10 }
                },
                grid: {
                    color: 'rgba(255,255,255,0.05)'
                }
            }
        }
    }
});