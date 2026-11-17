<script>
    (function () {
        var stored = null;

        try {
            stored = localStorage.getItem('theme');
        } catch (error) {
            stored = null;
        }

        var prefersLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
        var theme = stored || (prefersLight ? 'light' : 'dark');
        var root = document.documentElement;

        root.classList.toggle('dark', theme === 'dark');
        root.setAttribute('data-theme', theme);
        root.style.colorScheme = theme;

        // Tampilkan overlay transisi halaman sedini mungkin supaya
        // perpindahan antar halaman terasa mulus (tidak berkedip).
        root.classList.add('page-loading');

        // Jaring pengaman: halaman tetap terlihat walau script utama gagal dimuat.
        window.setTimeout(function () {
            root.classList.remove('page-loading');
        }, 2500);
    })();
</script>
<link rel="stylesheet" href="{{ asset('css/theme.css') }}">
<script src="{{ asset('js/theme.js') }}" defer></script>
