<div class="card shadow-sm border-0 h-100">

    <div class="card-body">

        <h5 class="card-title mb-4">
            Статистика договоров
        </h5>

        <div style="height: 261px;">
            <canvas id="contractChart"></canvas>
        </div>

        <div class="mt-4">

            <div class="d-flex justify-content-between mb-2">
                <span>🟢 Активные</span>
                <strong>{{ $chartData['active'] }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>🟡 Скоро заканчиваются</span>
                <strong>{{ $chartData['expiring'] }}</strong>
            </div>

            <div class="d-flex justify-content-between">
                <span>🔴 Просрочены</span>
                <strong>{{ $chartData['expired'] }}</strong>
            </div>

        </div>

    </div>

</div>

@script
<script>
    const canvas = document.getElementById('contractChart');

    if (canvas) {

        new Chart(canvas, {
            type: 'doughnut',

            data: {
                labels: [
                    'Активные',
                    'Скоро заканчиваются',
                    'Просрочены'
                ],

                datasets: [{
                    data: [
                        @js($chartData['active']),
                        @js($chartData['expiring']),
                        @js($chartData['expired'])
                    ]
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

    }
</script>
@endscript