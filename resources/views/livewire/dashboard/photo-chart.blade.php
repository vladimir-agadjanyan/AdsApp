<div class="card shadow-sm h-100">

    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-pie-chart-fill me-2"></i>
            Фотоотчеты по статусам
        </h5>
    </div>

    <div class="card-body">
        <div style="height: 262px;">
            <canvas id="photoChart"></canvas>
        </div>
    </div>

</div>

@script
<script>
    const canvas = document.getElementById('photoChart');

    if (canvas) {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: @js($chartData['labels']),
                datasets: [{
                    data: @js($chartData['data']),
                    backgroundColor: @js($chartData['colors']),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                        }
                    }
                }
            }
        });
    }
</script>
@endscript