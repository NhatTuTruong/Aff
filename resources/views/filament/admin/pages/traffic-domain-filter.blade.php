<x-filament-panels::page>
    <style>
        [x-cloak] { display: none !important; }

        /* Final fallback: hide native file input until FilePond is mounted */
        .traffic-domain-filter-page:not(.file-upload-ready) input[type="file"] {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    </style>

    <div class="space-y-6 traffic-domain-filter-page">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Nhập Apify token/Actor ID riêng của tài khoản bạn ở phần cấu hình bên dưới, sau đó upload file <code>.txt</code> (mỗi dòng 1 domain) và bấm <strong>Chạy &amp; tải CSV</strong>.
        </p>

        <div x-cloak>
            {{ $this->form }}
        </div>

        <div class="space-y-2">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                Log quá trình chạy
            </h2>
            <div class="bg-gray-900 text-gray-100 text-xs rounded-lg p-3 max-h-64 overflow-y-auto">
                @if(empty($this->logs))
                    <div class="text-gray-400">Chưa có log.</div>
                @else
                    @foreach($this->logs as $line)
                        <div>{{ $line }}</div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        (function () {
            function markReady() {
                var root = document.querySelector('.traffic-domain-filter-page');
                if (!root) return true;

                var hasFilePond = !!root.querySelector('.filepond--root');
                if (hasFilePond) {
                    root.classList.add('file-upload-ready');
                    return true;
                }
                return false;
            }

            function initWatcher() {
                if (markReady()) return;

                var observer = new MutationObserver(function () {
                    if (markReady()) {
                        observer.disconnect();
                    }
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                });

                // Safety timeout: avoid long-running observer if component changes.
                setTimeout(function () {
                    observer.disconnect();
                }, 10000);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initWatcher, { once: true });
            } else {
                initWatcher();
            }

            document.addEventListener('livewire:navigated', initWatcher);
            document.addEventListener('livewire:load', initWatcher);
        })();
    </script>
</x-filament-panels::page>

