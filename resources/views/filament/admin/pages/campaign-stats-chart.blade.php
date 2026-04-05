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
         chartScriptLoading: null,
         loadChartLibrary() {
             const existing = typeof Chart !== 'undefined' ? Chart : (window.Chart || window.chartjs);
             if (existing) return Promise.resolve(existing);
             if (this.chartScriptLoading) return this.chartScriptLoading;

             this.chartScriptLoading = new Promise((resolve, reject) => {
                 const current = document.querySelector('script[data-chartjs-campaign-stats=1]');
                 if (current) {
                     current.addEventListener('load', () => resolve(window.Chart || window.chartjs));
                     current.addEventListener('error', reject);
                     return;
                 }

                 const script = document.createElement('script');
                 script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
                 script.async = true;
                 script.dataset.chartjsCampaignStats = '1';
                 script.onload = () => resolve(window.Chart || window.chartjs);
                 script.onerror = reject;
                 document.head.appendChild(script);
             });

             return this.chartScriptLoading;
         },
         init() {
             if (!this.labels.length) return;
             const render = async () => {
                 try {
                     const ChartLib = await this.loadChartLibrary();
                     if (!ChartLib) return;

                     const canvas = this.$refs.canvas;
                     if (!canvas || typeof canvas.getContext !== 'function' || !canvas.isConnected) {
                         setTimeout(render, 120);
                         return;
                     }

                     if (this.chartInstance) {
                         try {
                             this.chartInstance.destroy();
                         } catch (e) {
                             // Bỏ qua lỗi destroy khi modal đã unmount canvas trước đó
                         }
                         this.chartInstance = null;
                     }

                     this.chartInstance = new ChartLib(canvas, {
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
                             maintainAspectRatio: true,
                             aspectRatio: 2,
                             animation: false,
                             plugins: {
                                 legend: { position: 'top' },
                                 title: { display: true, text: 'Clicks và Views theo ngày' }
                             },
                             scales: {
                                 y: { beginAtZero: true }
                             }
                         }
                     });
                 } catch (e) {
                     console.error('Không tải được Chart.js cho campaign stats', e);
                 }
             };

             this.$nextTick(() => setTimeout(render, 250));
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
            <div class="relative w-full" style="min-height: 280px;">
                <canvas x-ref="canvas" style="max-height: 300px;"></canvas>
            </div>
        @endif
    </div>
</div>
