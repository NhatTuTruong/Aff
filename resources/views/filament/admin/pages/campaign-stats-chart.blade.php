@php
    $labels = $chartData['labels'] ?? [];
    $clicks = $chartData['clicks'] ?? [];
    $views = $chartData['views'] ?? [];
    $hasData = !empty($labels);
@endphp
<div class="space-y-4"
     x-data="{
         labels: @js($labels),
         clicksData: @js($clicks),
         viewsData: @js($views),
         chartInstance: null,
         init() {
             if (!this.labels.length) return;
             const render = () => {
                 if (typeof Chart === 'undefined') {
                     setTimeout(render, 50);
                     return;
                 }
                 const canvas = this.$refs.canvas;
                 if (!canvas) return;
                 if (this.chartInstance) this.chartInstance.destroy();
                 const ctx = canvas.getContext('2d');
                 this.chartInstance = new Chart(ctx, {
                     type: 'line',
                     data: {
                         labels: this.labels,
                         datasets: [
                             {
                                 label: 'Clicks',
                                 data: this.clicksData,
                                 borderColor: 'rgb(34, 197, 94)',
                                 backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                 fill: true,
                                 tension: 0.3
                             },
                             {
                                 label: 'Views',
                                 data: this.viewsData,
                                 borderColor: 'rgb(59, 130, 246)',
                                 backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                 fill: true,
                                 tension: 0.3
                             }
                         ]
                     },
                     options: {
                         responsive: true,
                         maintainAspectRatio: false,
                         plugins: {
                             legend: { position: 'top' },
                             title: { display: true, text: 'Clicks và Views theo ngày' }
                         },
                         scales: {
                             y: { beginAtZero: true }
                         }
                     }
                 });
             };
             this.$nextTick(() => setTimeout(render, 150));
         }
     }"
     x-init="init()">
    <div class="rounded-lg border bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Chiến dịch: <strong>{{ $campaign->title }}</strong>
        </p>
        @if(!$hasData)
            <p class="text-sm text-gray-500 dark:text-gray-400">Chưa có dữ liệu thống kê cho chiến dịch này.</p>
        @else
            <div class="relative h-[300px]">
                <canvas x-ref="canvas"></canvas>
            </div>
        @endif
    </div>
</div>
