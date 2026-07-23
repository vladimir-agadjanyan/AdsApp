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
    const ctx = document.getElementById('photoChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                data: @json($chartData['data']),
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                ],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                    }
                }
            },

            cutout: '60%',
        }
    });
</script>
@endscript