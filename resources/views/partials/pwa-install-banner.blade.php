<div id="pwaInstallBanner" style="display:none;" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-6 md:w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 p-4 flex items-center gap-3 z-[9999]">
    <div class="w-12 h-12 shrink-0 rounded-xl overflow-hidden bg-indigo-600">
        <img src="/assets/logo.webp" alt="AmikomEventHub" class="w-full h-full object-cover">
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-bold text-sm text-slate-800">Install AmikomEventHub</p>
        <p class="text-xs text-slate-500">Akses lebih cepat langsung dari layar beranda HP-mu.</p>
    </div>
    <div class="flex flex-col gap-1 shrink-0">
        <button id="pwaInstallBtn" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700">Install</button>
        <button id="pwaDismissBtn" class="px-3 py-1 text-slate-400 text-xs font-medium hover:text-slate-600">Nanti</button>
    </div>
</div>

<script>
    (function () {
        var DISMISS_KEY = 'pwaInstallDismissedUntil';
        var deferredPrompt = null;
        var banner = document.getElementById('pwaInstallBanner');

        function isStandalone() {
            return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        }

        function isDismissed() {
            var until = parseInt(localStorage.getItem(DISMISS_KEY) || '0', 10);
            return Date.now() < until;
        }

        window.addEventListener('beforeinstallprompt', function (event) {
            event.preventDefault();
            deferredPrompt = event;

            if (!isStandalone() && !isDismissed()) {
                banner.style.display = 'flex';
            }
        });

        document.getElementById('pwaInstallBtn').addEventListener('click', function () {
            banner.style.display = 'none';

            if (!deferredPrompt) {
                return;
            }

            deferredPrompt.prompt();
            deferredPrompt.userChoice.finally(function () {
                deferredPrompt = null;
            });
        });

        document.getElementById('pwaDismissBtn').addEventListener('click', function () {
            banner.style.display = 'none';
            localStorage.setItem(DISMISS_KEY, String(Date.now() + 7 * 24 * 60 * 60 * 1000));
        });

        window.addEventListener('appinstalled', function () {
            banner.style.display = 'none';
            deferredPrompt = null;
        });
    })();
</script>
