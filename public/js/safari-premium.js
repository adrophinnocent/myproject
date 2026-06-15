// ═══════════════════════════════════════════════════════════
//  safari-premium.js
//  Drop into: public/js/safari-premium.js
//  Include before </body> in app.blade.php
// ═══════════════════════════════════════════════════════════

(function () {
    'use strict';

    // ─────────────────────────────────────────
    // 1. Scroll Reveal (IntersectionObserver)
    // ─────────────────────────────────────────
    function initReveal() {
        const targets = document.querySelectorAll('.reveal, .stagger');
        if (!targets.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
        );

        targets.forEach(el => observer.observe(el));
    }

    // ─────────────────────────────────────────
    // 2. Number Counter Animation
    // ─────────────────────────────────────────
    function animateCounters() {
        const counters = document.querySelectorAll('[data-count]');
        if (!counters.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const el     = entry.target;
                    const target = parseFloat(el.dataset.count);
                    const suffix = el.dataset.suffix || '';
                    const prefix = el.dataset.prefix || '';
                    const dur    = parseInt(el.dataset.duration || '1600');
                    const decimals = (el.dataset.count.split('.')[1] || '').length;
                    const start  = performance.now();

                    function step(now) {
                        const progress = Math.min((now - start) / dur, 1);
                        const ease     = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
                        const value    = (target * ease).toFixed(decimals);
                        el.textContent = prefix + Number(value).toLocaleString() + suffix;
                        if (progress < 1) requestAnimationFrame(step);
                    }

                    requestAnimationFrame(step);
                    observer.unobserve(el);
                });
            },
            { threshold: 0.5 }
        );

        counters.forEach(el => observer.observe(el));
    }

    // ─────────────────────────────────────────
    // 3. Dark Mode Toggle
    // ─────────────────────────────────────────
    function initDarkMode() {
        const btn  = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        // Restore saved preference
        if (localStorage.getItem('theme') === 'dark') {
            html.classList.add('dark');
        } else if (!localStorage.getItem('theme') &&
                   window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.classList.add('dark');
        }

        if (!btn) return;

        btn.addEventListener('click', () => {
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateDarkIcon(isDark);
        });

        function updateDarkIcon(isDark) {
            const icon = btn.querySelector('[data-icon]');
            if (icon) icon.textContent = isDark ? '☀️' : '🌙';
        }
        updateDarkIcon(html.classList.contains('dark'));
    }

    // ─────────────────────────────────────────
    // 4. Sticky Navbar shrink on scroll
    // ─────────────────────────────────────────
    function initNavbar() {
        const nav = document.getElementById('mainNav');
        if (!nav) return;

        let lastY = 0;

        window.addEventListener('scroll', () => {
            const y = window.scrollY;
            if (y > 80) {
                nav.classList.add('nav-scrolled');
            } else {
                nav.classList.remove('nav-scrolled');
            }
            // Hide/show on scroll direction
            if (y > 200) {
                nav.style.transform = y > lastY ? 'translateY(-100%)' : 'translateY(0)';
            }
            lastY = y;
        }, { passive: true });
    }

    // ─────────────────────────────────────────
    // 5. Hero Parallax (subtle)
    // ─────────────────────────────────────────
    function initParallax() {
        const hero = document.querySelector('.hero-parallax');
        if (!hero) return;

        window.addEventListener('scroll', () => {
            const y = window.scrollY;
            hero.style.transform = `translateY(${y * 0.3}px)`;
        }, { passive: true });
    }

    // ─────────────────────────────────────────
    // 6. Smooth scroll for anchor links
    // ─────────────────────────────────────────
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    // ─────────────────────────────────────────
    // 7. Flash message auto-dismiss
    // ─────────────────────────────────────────
    function initFlashMessages() {
        document.querySelectorAll('[data-flash]').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity .4s, transform .4s';
                el.style.opacity    = '0';
                el.style.transform  = 'translateY(-10px)';
                setTimeout(() => el.remove(), 400);
            }, 4000);
        });
    }

    // ─────────────────────────────────────────
    // 8. Form enhancement: floating labels
    // ─────────────────────────────────────────
    function initFloatingLabels() {
        document.querySelectorAll('.input-float-wrap').forEach(wrap => {
            const input = wrap.querySelector('input, textarea, select');
            const label = wrap.querySelector('label');
            if (!input || !label) return;

            function update() {
                if (input.value || document.activeElement === input) {
                    label.classList.add('float');
                } else {
                    label.classList.remove('float');
                }
            }

            input.addEventListener('focus', update);
            input.addEventListener('blur',  update);
            update();
        });
    }

    // ─────────────────────────────────────────
    // 9. Image lazy-load with fade
    // ─────────────────────────────────────────
    function initLazyImages() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.style.opacity = '0';
            img.style.transition = 'opacity .4s';
            img.addEventListener('load', () => { img.style.opacity = '1'; });
            if (img.complete) img.style.opacity = '1';
        });
    }

    // ─────────────────────────────────────────
    // 10. Mobile nav toggle
    // ─────────────────────────────────────────
    function initMobileNav() {
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobileNav');
        if (!hamburger || !mobileNav) return;

        hamburger.addEventListener('click', () => {
            const open = mobileNav.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', open);
        });

        // Close on outside click
        document.addEventListener('click', e => {
            if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
                mobileNav.classList.remove('open');
            }
        });
    }

    // ─────────────────────────────────────────
    // Bootstrap all
    // ─────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        initDarkMode();
        initReveal();
        animateCounters();
        initNavbar();
        initParallax();
        initSmoothScroll();
        initFlashMessages();
        initFloatingLabels();
        initLazyImages();
        initMobileNav();
    });

})();
