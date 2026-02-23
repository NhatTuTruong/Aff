@if(app()->environment('production'))
    <script>
        (function () {
            function ensureToast() {
                var el = document.getElementById('admin-error-toast');
                if (el) return el;

                el = document.createElement('div');
                el.id = 'admin-error-toast';
                el.style.position = 'fixed';
                el.style.right = '16px';
                el.style.bottom = '16px';
                el.style.zIndex = '99999';
                el.style.maxWidth = '420px';
                el.style.padding = '12px 14px';
                el.style.borderRadius = '12px';
                el.style.background = 'rgba(17,24,39,0.95)';
                el.style.color = '#fff';
                el.style.boxShadow = '0 12px 28px rgba(0,0,0,0.18)';
                el.style.fontSize = '14px';
                el.style.lineHeight = '1.4';
                el.style.display = 'none';
                el.style.cursor = 'pointer';
                el.addEventListener('click', function () {
                    el.style.display = 'none';
                });

                document.body.appendChild(el);
                return el;
            }

            var hideTimer = null;
            function showToast(message) {
                var el = ensureToast();
                el.textContent = message || 'Có lỗi hệ thống. Vui lòng thử lại sau.';
                el.style.display = 'block';
                if (hideTimer) clearTimeout(hideTimer);
                hideTimer = setTimeout(function () {
                    el.style.display = 'none';
                }, 4000);
            }

            // Generic JS runtime errors
            window.addEventListener('error', function () {
                showToast();
            });
            window.addEventListener('unhandledrejection', function () {
                showToast();
            });

            // Detect failed fetch/XHR (best-effort)
            if (window.fetch) {
                var _fetch = window.fetch;
                window.fetch = function () {
                    return _fetch.apply(this, arguments).then(function (res) {
                        if (!res || (res.status >= 500)) {
                            showToast();
                        }
                        return res;
                    }).catch(function (err) {
                        showToast();
                        throw err;
                    });
                };
            }
        })();
    </script>
@endif

