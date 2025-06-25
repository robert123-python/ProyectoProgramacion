@extends('layouts.menu')

@section('title', 'Estadísticas')

@section('content')
<style>
    body { 
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        background: #f4f4f4;
    }

    .container {
        max-width: 1000px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    h2 {
        text-align: center;
        margin-bottom: 40px;
        color: #333;
    }

    canvas {
        margin: 30px auto;
        display: block;
        max-width: 100%;
    }

    .chart-title {
        text-align: center;
        font-weight: 600;
        color: #555;
        margin-top: 20px;
    }
</style>

<div class="container">
    <h2>Estadísticas de Conteo</h2>

    <div>
        <div class="chart-title">Comparación de Conteo Automático vs Manual</div>
        <canvas id="barChart" height="100"></canvas>
    </div>

    <div>
        <div class="chart-title">Distribución del Conteo Automático</div>
        <canvas id="pieChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>  {{-- Plugin para etiquetas --}}

<script>
    const modelos = {!! json_encode($conteos->pluck('modelo.nombre')) !!};
    const conteosAutomaticos = {!! json_encode($conteos->pluck('total_automatico')) !!};
    const conteosManuales = {!! json_encode($conteos->pluck('total_manual')) !!};

    // Gráfico de barras
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: modelos,
            datasets: [
                {
                    label: 'Automático',
                    data: conteosAutomaticos,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                },
                {
                    label: 'Manual',
                    data: conteosManuales,
                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#444',
                    font: {
                        weight: 'bold'
                    },
                    formatter: (value) => value, // Muestra el número exacto
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Gráfico de pastel con porcentajes
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: modelos,
            datasets: [{
                label: 'Distribución',
                data: conteosAutomaticos,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    formatter: (value, context) => {
                        let sum = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = (value * 100 / sum).toFixed(1) + '%';
                        return percentage;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
@endsection