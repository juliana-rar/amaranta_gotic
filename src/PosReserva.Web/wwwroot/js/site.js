// Lightweight, framework-free page effects: scroll-reveal (.reveal -> .is-visible) and the
// floating "back to top" button. Re-run after Blazor's enhanced navigation swaps the page
// content, since that doesn't trigger a fresh DOMContentLoaded.
(function () {
    var revealObserver = null;

    function initReveal() {
        if (!revealObserver) {
            revealObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        }
        document.querySelectorAll('.reveal:not(.is-visible)').forEach(function (el) {
            revealObserver.observe(el);
        });
    }

    function initBackToTop() {
        var btn = document.querySelector('.to-top');
        if (!btn || btn.dataset.bound) return;
        btn.dataset.bound = 'true';
        window.addEventListener('scroll', function () {
            btn.classList.toggle('is-visible', window.scrollY > 800);
        }, { passive: true });
        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // On the Home page the header starts transparent over the hero photo and crossfades to the
    // normal solid header once the page is scrolled a little.
    function initTransparentHeader() {
        var header = document.querySelector('.site-header[data-hero="true"]');
        if (!header || header.dataset.bound) return;
        header.dataset.bound = 'true';
        function update() {
            header.classList.toggle('site-header--scrolled', window.scrollY > 40);
        }
        update();
        window.addEventListener('scroll', update, { passive: true });
    }

    function init() {
        initReveal();
        initBackToTop();
        initTransparentHeader();
    }

    document.addEventListener('DOMContentLoaded', init);
    if (window.Blazor) {
        Blazor.addEventListener('enhancedload', init);
    }
})();
