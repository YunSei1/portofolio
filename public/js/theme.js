(function () {
    'use strict';

    var root = document.documentElement;
    var STORAGE_KEY = 'theme';
    var transitionTimer = null;

    function currentTheme() {
        return root.classList.contains('dark') ? 'dark' : 'light';
    }

    function syncButtons(theme) {
        var label = theme === 'dark' ? 'Aktifkan mode terang' : 'Aktifkan mode gelap';

        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            button.setAttribute('aria-label', label);
            button.setAttribute('title', label);
            button.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
        });
    }

    function applyTheme(theme, animate) {
        if (animate) {
            root.classList.add('theme-transition');
            // Paksa reflow agar transisi warna benar-benar dijalankan.
            void root.offsetWidth;
        }

        root.classList.toggle('dark', theme === 'dark');
        root.setAttribute('data-theme', theme);
        root.style.colorScheme = theme;

        syncButtons(theme);

        if (animate) {
            window.clearTimeout(transitionTimer);
            transitionTimer = window.setTimeout(function () {
                root.classList.remove('theme-transition');
            }, 520);
        }
    }

    function toggleTheme() {
        var next = currentTheme() === 'dark' ? 'light' : 'dark';

        applyTheme(next, true);

        try {
            localStorage.setItem(STORAGE_KEY, next);
        } catch (error) {
            // localStorage bisa diblokir; tema tetap berfungsi dalam sesi ini.
        }
    }

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-theme-toggle]');

        if (!trigger) {
            return;
        }

        event.preventDefault();
        toggleTheme();
    });

    // Ikuti preferensi sistem jika pengguna belum memilih tema secara manual.
    var mediaQuery = window.matchMedia('(prefers-color-scheme: light)');
    var handleSystemChange = function (event) {
        var stored = null;

        try {
            stored = localStorage.getItem(STORAGE_KEY);
        } catch (error) {
            stored = null;
        }

        if (!stored) {
            applyTheme(event.matches ? 'light' : 'dark', true);
        }
    };

    if (typeof mediaQuery.addEventListener === 'function') {
        mediaQuery.addEventListener('change', handleSystemChange);
    } else if (typeof mediaQuery.addListener === 'function') {
        mediaQuery.addListener(handleSystemChange);
    }

    // Samakan label tombol dengan tema yang sudah dipasang di <head>.
    syncButtons(currentTheme());

    // Animasi muncul saat elemen masuk viewport.
    document.addEventListener('DOMContentLoaded', function () {
        var bars = document.querySelectorAll('.skill-progress');
        var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (bars.length) {
            if (reduceMotion || !('IntersectionObserver' in window)) {
                bars.forEach(function (bar) {
                    bar.classList.add('is-filled');
                });
            } else {
                var barObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-filled');
                        barObserver.unobserve(entry.target);
                    });
                }, { threshold: 0.4 });

                bars.forEach(function (bar) {
                    barObserver.observe(bar);
                });
            }
        }

        var targets = document.querySelectorAll('section:not([data-reveal-ignore]), [data-reveal]');

        if (!targets.length) {
            return;
        }

        if (!('IntersectionObserver' in window)) {
            targets.forEach(function (element) {
                element.classList.add('is-visible');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px'
        });

        targets.forEach(function (element, index) {
            element.classList.add('reveal');
            element.style.transitionDelay = Math.min(index % 3, 2) * 80 + 'ms';
            observer.observe(element);
        });
    });
})();

/* ============================================================
   FITUR INTERAKTIF
   1. Animasi ganti halaman
   2. Progress scroll & tombol kembali ke atas
   3. Navigasi cepat (quick nav)
   4. Preview proyek (modal)
   ============================================================ */
(function () {
    'use strict';

    var root = document.documentElement;
    var body = document.body;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var TRANSITION_MS = reduceMotion ? 0 : 380;

    if (!body) {
        return;
    }

    /* ---------------- 1. Animasi ganti halaman ---------------- */

    // Hilangkan overlay transisi begitu halaman siap ditampilkan.
    function revealPage() {
        if (!root.classList.contains('page-loading')) {
            return;
        }

        window.requestAnimationFrame(function () {
            root.classList.remove('page-loading');
        });
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        revealPage();
    } else {
        document.addEventListener('DOMContentLoaded', revealPage);
    }

    window.addEventListener('load', revealPage);

    // Kembalikan halaman ke kondisi normal saat dipulihkan dari cache (tombol back).
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            revealPage();
        }
    });

    function samePageAnchor(url) {
        return url.origin === window.location.origin
            && url.pathname === window.location.pathname
            && url.search === window.location.search
            && url.hash.length > 1;
    }

    document.addEventListener('click', function (event) {
        if (event.defaultPrevented || event.button !== 0) {
            return;
        }

        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        var link = event.target.closest ? event.target.closest('a[href]') : null;

        if (!link) {
            return;
        }

        if ((link.target && link.target !== '_self') || link.hasAttribute('download') || link.hasAttribute('data-no-transition')) {
            return;
        }

        var href = link.getAttribute('href');

        if (!href || href.charAt(0) === '#' || /^(mailto:|tel:|javascript:|data:|blob:)/i.test(href)) {
            return;
        }

        var url;

        try {
            url = new URL(link.href, window.location.href);
        } catch (error) {
            return;
        }

        // Link ke situs lain dibuka seperti biasa.
        if (url.origin !== window.location.origin) {
            return;
        }

        // Anchor di halaman yang sama: gulir halus tanpa memuat ulang halaman.
        if (samePageAnchor(url)) {
            var target = document.getElementById(url.hash.slice(1));

            if (target) {
                event.preventDefault();
                target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });

                if (window.history && window.history.pushState) {
                    window.history.pushState(null, '', url.hash);
                }
            }

            return;
        }

        // Link ke halaman yang sedang dibuka: cukup gulir ke atas, tanpa reload.
        if (url.pathname === window.location.pathname && url.search === window.location.search) {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
            return;
        }

        // Navigasi antar halaman: mainkan animasi lalu pindah.
        event.preventDefault();
        root.classList.add('page-loading');

        window.setTimeout(function () {
            window.location.href = url.href;
        }, TRANSITION_MS);
    });

    /* ---------------- 2. Progress scroll & tombol kembali ke atas ---------------- */

    var progress = document.createElement('div');
    progress.className = 'scroll-progress';
    progress.setAttribute('aria-hidden', 'true');
    body.appendChild(progress);

    var toTop = document.createElement('button');
    toTop.type = 'button';
    toTop.className = 'back-to-top';
    toTop.setAttribute('aria-label', 'Kembali ke atas');
    toTop.setAttribute('title', 'Kembali ke atas');
    toTop.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>';
    body.appendChild(toTop);

    toTop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });

    /* ---------------- 3. Navigasi cepat ---------------- */

    var ICONS = {
        home: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>',
        user: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        grid: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>',
        book: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
        mail: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
        chat: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>'
    };

    // Ambil URL dari meta tag supaya mengikuti route Laravel, bukan path hardcode.
    function metaUrl(name, fallback) {
        var meta = document.querySelector('meta[name="' + name + '"]');

        return meta && meta.content ? meta.content : fallback;
    }

    var navItems = [
        { href: '/', label: 'Beranda', icon: ICONS.home, page: function (path) { return path === '/'; } },
        { href: '/about', label: 'Tentang', icon: ICONS.user, page: function (path) { return path.indexOf('/about') === 0; } },
        { href: '/#projects', label: 'Proyek', icon: ICONS.grid, section: 'projects', page: function (path) { return path.indexOf('/projects') === 0; } },
        { href: '/blog', label: 'Blog', icon: ICONS.book, page: function (path) { return path.indexOf('/blog') === 0; } },
        { href: '/kesan-pesan', label: 'Kesan', icon: ICONS.chat, page: function (path) { return path.indexOf('/kesan-pesan') === 0; } },
        { href: metaUrl('contact-url', '/contact'), label: 'Kontak', icon: ICONS.mail, section: 'contact', page: function (path) { return path.indexOf('/contact') === 0; } }
    ];

    var quickNav = document.createElement('nav');
    quickNav.className = 'quick-nav';
    quickNav.setAttribute('aria-label', 'Navigasi cepat');

    navItems.forEach(function (item) {
        var link = document.createElement('a');
        link.className = 'quick-nav__link';
        link.href = item.href;
        link.setAttribute('aria-label', item.label);
        link.innerHTML = '<span class="quick-nav__icon">' + item.icon + '</span><span class="quick-nav__label">' + item.label + '</span>';
        quickNav.appendChild(link);
        item.el = link;
    });

    body.appendChild(quickNav);

    window.requestAnimationFrame(function () {
        quickNav.classList.add('is-visible');
    });

    function updateActive() {
        var path = window.location.pathname.replace(/\/+$/, '') || '/';
        var viewport = window.innerHeight || root.clientHeight;
        var marker = viewport * 0.42;
        var active = -1;

        // Deteksi bagian yang sedang terlihat (khusus beranda).
        navItems.forEach(function (item, index) {
            if (!item.section) {
                return;
            }

            var section = document.getElementById(item.section);

            if (!section) {
                return;
            }

            var rect = section.getBoundingClientRect();

            if (rect.top <= marker && rect.bottom >= marker) {
                active = index;
            }
        });

        // Jika tidak ada bagian yang aktif, tandai berdasarkan halaman saat ini.
        if (active === -1) {
            navItems.forEach(function (item, index) {
                if (active === -1 && item.page && item.page(path)) {
                    active = index;
                }
            });
        }

        navItems.forEach(function (item, index) {
            item.el.classList.toggle('is-active', index === active);
        });
    }

    var scheduled = false;

    function onScroll() {
        var max = root.scrollHeight - root.clientHeight;
        var top = window.pageYOffset || root.scrollTop || 0;
        var ratio = max > 0 ? Math.min(Math.max(top / max, 0), 1) : 0;

        progress.style.transform = 'scaleX(' + ratio + ')';
        toTop.classList.toggle('is-visible', top > 420);
        updateActive();

        scheduled = false;
    }

    window.addEventListener('scroll', function () {
        if (!scheduled) {
            scheduled = true;
            window.requestAnimationFrame(onScroll);
        }
    }, { passive: true });

    window.addEventListener('resize', onScroll, { passive: true });

    onScroll();

    /* ---------------- 4. Preview proyek ---------------- */

    if (document.querySelector('[data-project-preview]')) {
        var modal = document.createElement('div');
        modal.className = 'project-preview';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-hidden', 'true');
        modal.setAttribute('aria-label', 'Preview proyek');
        modal.innerHTML = [
            '<div class="project-preview__backdrop" data-preview-close></div>',
            '<div class="project-preview__dialog" role="document">',
            '<button type="button" class="project-preview__close" data-preview-close aria-label="Tutup preview">',
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>',
            '</button>',
            '<div class="project-preview__media">',
            '<img alt="" decoding="async">',
            '<div class="project-preview__media-fallback" hidden>',
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>',
            '</div>',
            '</div>',
            '<div class="project-preview__body">',
            '<span class="project-preview__badge" data-preview-role hidden></span>',
            '<h2 class="project-preview__title" data-preview-title></h2>',
            '<p class="project-preview__desc" data-preview-desc hidden></p>',
            '<div class="project-preview__techs" data-preview-techs></div>',
            '<div class="project-preview__actions">',
            '<a class="project-preview__btn project-preview__btn--primary" data-preview-detail href="#">Lihat Detail Lengkap</a>',
            '<a class="project-preview__btn" data-preview-demo target="_blank" rel="noopener noreferrer" hidden>Live Demo</a>',
            '<a class="project-preview__btn" data-preview-source target="_blank" rel="noopener noreferrer" hidden>Source Code</a>',
            '</div>',
            '</div>',
            '</div>'
        ].join('');

        body.appendChild(modal);

        var closeBtn = modal.querySelector('.project-preview__close');
        var mediaImg = modal.querySelector('.project-preview__media img');
        var mediaFallback = modal.querySelector('.project-preview__media-fallback');
        var roleEl = modal.querySelector('[data-preview-role]');
        var titleEl = modal.querySelector('[data-preview-title]');
        var descEl = modal.querySelector('[data-preview-desc]');
        var techsEl = modal.querySelector('[data-preview-techs]');
        var detailEl = modal.querySelector('[data-preview-detail]');
        var demoEl = modal.querySelector('[data-preview-demo]');
        var sourceEl = modal.querySelector('[data-preview-source]');
        var lastFocused = null;

        mediaImg.addEventListener('error', function () {
            mediaImg.hidden = true;
            mediaFallback.hidden = false;
        });

        function fill(el, value) {
            if (value) {
                el.textContent = value;
                el.hidden = false;
            } else {
                el.hidden = true;
            }
        }

        function openPreview(trigger) {
            var data = trigger.dataset;

            if (data.image) {
                mediaImg.hidden = false;
                mediaFallback.hidden = true;
                mediaImg.src = data.image;
                mediaImg.alt = data.title ? 'Preview ' + data.title : 'Preview proyek';
            } else {
                mediaImg.hidden = true;
                mediaImg.removeAttribute('src');
                mediaFallback.hidden = false;
            }

            fill(roleEl, data.role);
            titleEl.textContent = data.title || 'Detail Proyek';
            fill(descEl, data.description);

            techsEl.innerHTML = '';

            (data.technologies || '').split(',').forEach(function (tech) {
                var value = tech.trim();

                if (!value) {
                    return;
                }

                var chip = document.createElement('span');
                chip.className = 'project-preview__tech';
                chip.textContent = value;
                techsEl.appendChild(chip);
            });

            if (data.detail) {
                detailEl.href = data.detail;
                detailEl.hidden = false;
            } else {
                detailEl.hidden = true;
            }

            if (data.demo) {
                demoEl.href = data.demo;
                demoEl.hidden = false;
            } else {
                demoEl.hidden = true;
            }

            if (data.source) {
                sourceEl.href = data.source;
                sourceEl.hidden = false;
            } else {
                sourceEl.hidden = true;
            }

            lastFocused = document.activeElement;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            body.classList.add('preview-open');

            window.setTimeout(function () {
                closeBtn.focus();
            }, 60);
        }

        function closePreview() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            body.classList.remove('preview-open');

            if (lastFocused && typeof lastFocused.focus === 'function') {
                lastFocused.focus();
            }
        }

        document.addEventListener('click', function (event) {
            var trigger = event.target.closest ? event.target.closest('[data-project-preview]') : null;

            if (!trigger) {
                return;
            }

            // Data proyek dibaca dari kartu induk agar tidak perlu diulang di tiap tombol.
            var source = trigger.closest('[data-project-data]') || trigger;

            event.preventDefault();
            openPreview(source);
        });

        modal.addEventListener('click', function (event) {
            if (event.target.closest('[data-preview-close]')) {
                closePreview();
                return;
            }

            // Tutup modal saat pengguna membuka detail (agar tidak tertinggal terbuka).
            if (event.target.closest('[data-preview-detail]')) {
                closePreview();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closePreview();
            }
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted && modal.classList.contains('is-open')) {
                closePreview();
            }
        });
    }

    /* ---------------- 5. Animasi kartu proyek ---------------- */

    var cards = document.querySelectorAll('.project-card');

    if (cards.length) {
        // Muncul bertahap saat kartu masuk viewport.
        if (!('IntersectionObserver' in window) || reduceMotion) {
            cards.forEach(function (card) {
                card.classList.add('reveal-card', 'is-visible');
            });
        } else {
            var cardObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    var card = entry.target;
                    var delay = parseInt(card.getAttribute('data-card-delay') || '0', 10);

                    card.style.transitionDelay = delay + 'ms';
                    card.classList.add('is-visible');

                    // Lepas delay agar efek hover kembali responsif.
                    window.setTimeout(function () {
                        card.style.transitionDelay = '';
                    }, delay + 700);

                    cardObserver.unobserve(card);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            cards.forEach(function (card, index) {
                card.classList.add('reveal-card');
                card.setAttribute('data-card-delay', String((index % 4) * 90));
                cardObserver.observe(card);
            });
        }

        // Efek tilt halus hanya untuk perangkat dengan pointer presisi.
        var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

        if (finePointer && !reduceMotion) {
            cards.forEach(function (card) {
                card.classList.add('project-card--tilt');

                card.addEventListener('pointermove', function (event) {
                    var rect = card.getBoundingClientRect();

                    if (!rect.width || !rect.height) {
                        return;
                    }

                    var x = (event.clientX - rect.left) / rect.width - 0.5;
                    var y = (event.clientY - rect.top) / rect.height - 0.5;

                    card.style.transform = 'perspective(900px) rotateX(' + (-y * 4).toFixed(2) + 'deg) rotateY(' + (x * 4).toFixed(2) + 'deg) translateY(-4px)';
                });

                card.addEventListener('pointerleave', function () {
                    card.style.transform = '';
                });
            });
        }
    }
})();
