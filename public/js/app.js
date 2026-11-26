document.documentElement.setAttribute('data-js', 'true');

const header = document.querySelector('.site-header');
const toTop = document.querySelector('.to-top');
const toTopThreshold = document.body.dataset.page === 'home' ? 800 : 500;

const updateScroll = () => {
    const scrolled = window.scrollY > 40;
    if (header) {
        header.setAttribute('data-scrolled', scrolled ? 'true' : 'false');
        header.classList.toggle('site-header--scrolled', scrolled);
    }
    if (toTop) {
        const visible = window.scrollY > toTopThreshold;
        toTop.setAttribute('data-visible', visible ? 'true' : 'false');
        toTop.classList.toggle('is-visible', visible);
    }
};

updateScroll();
window.addEventListener('scroll', updateScroll, { passive: true });

if (toTop) {
    toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

const revealables = document.querySelectorAll('.reveal');

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.setAttribute('data-visible', 'true');
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealables.forEach((element) => observer.observe(element));
} else {
    revealables.forEach((element) => {
        element.setAttribute('data-visible', 'true');
        element.classList.add('is-visible');
    });
}

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const hero = document.querySelector('.hero');

if (hero) {
    const layers = [...hero.children].filter((element) => element.classList.contains('hero__bg'));
    let heroIndex = Math.max(0, layers.findIndex((layer) => layer.dataset.active === 'true'));

    if (layers.length > 1 && !prefersReducedMotion) {
        window.setInterval(() => {
            layers[heroIndex].setAttribute('data-active', 'false');
            heroIndex = (heroIndex + 1) % layers.length;
            layers[heroIndex].setAttribute('data-active', 'true');
        }, 4500);
    }
}

document.querySelectorAll('.carousel').forEach((carousel) => {
    const slides = [...carousel.querySelectorAll('.carousel__slide')];
    const dots = [...carousel.querySelectorAll('.carousel__dot')];
    const buttons = [...carousel.querySelectorAll('.carousel__nav')];
    const counter = carousel.querySelector('.carousel__counter');
    const counterCurrent = carousel.querySelector('[data-carousel-current]');
    let index = Math.max(0, slides.findIndex((slide) => slide.dataset.active === 'true'));
    let timer;

    const render = () => {
        slides.forEach((slide, i) => slide.setAttribute('data-active', i === index ? 'true' : 'false'));
        dots.forEach((dot, i) => dot.setAttribute('data-active', i === index ? 'true' : 'false'));
        if (counterCurrent) counterCurrent.textContent = String(index + 1).padStart(2, '0');
        if (counter && slides.length) {
            counter.style.setProperty('--carousel-progress', `${((index + 1) / slides.length) * 100}%`);
        }
    };

    const start = () => {
        if (slides.length > 1 && !prefersReducedMotion) {
            timer = window.setInterval(() => {
                index = (index + 1) % slides.length;
                render();
            }, 5000);
        }
    };

    const stop = () => window.clearInterval(timer);

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            stop();
            const step = button.dataset.dir === 'next' ? 1 : -1;
            index = (index + step + slides.length) % slides.length;
            render();
            start();
        });
    });

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            stop();
            index = i;
            render();
            start();
        });
    });

    render();
    start();
});

const reservation = document.querySelector('.resv[data-wizard]');

if (reservation) {
    const wizardForm = reservation.querySelector('form');

    if (wizardForm) {
        wizardForm.querySelectorAll('input[name="period"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                reservation.setAttribute('data-period', radio.value);
                wizardForm.querySelectorAll('input[name="time"]').forEach((time) => {
                    time.checked = false;
                });
            });
        });

        let steppingTimer;

        const syncForm = (sourceForm) => {
            const panels = wizardForm.querySelector('[data-panels]');
            const sourcePanels = sourceForm.querySelector('[data-panels]');
            const nav = wizardForm.querySelector('[data-nav]');
            const sourceNav = sourceForm.querySelector('[data-nav]');

            sourceForm.querySelectorAll('input[type="hidden"]').forEach((source) => {
                const target = wizardForm.querySelector(`input[type="hidden"][name="${source.name}"]`);
                if (target) target.value = source.value;
            });

            sourcePanels.querySelectorAll('input, textarea').forEach((source) => {
                const target = source.type === 'radio' || source.type === 'checkbox'
                    ? panels.querySelector(`input[name="${source.name}"][value="${source.value}"]`)
                    : panels.querySelector(`[name="${source.name}"]`);
                if (!target) return;
                if (source.type === 'radio' || source.type === 'checkbox') {
                    target.checked = source.checked;
                } else {
                    target.value = source.value;
                }
            });

            const steps = [...wizardForm.querySelector('[data-steps]').querySelectorAll('button')];
            [...sourceForm.querySelector('[data-steps]').querySelectorAll('button')].forEach((source, i) => {
                if (!steps[i]) return;
                steps[i].disabled = source.disabled;
                steps[i].setAttribute('aria-current', source.getAttribute('aria-current') || 'false');
                steps[i].querySelector('span').textContent = source.querySelector('span').textContent;
            });

            [...panels.querySelectorAll('[data-panel]')].forEach((panel, i) => {
                const source = sourcePanels.querySelectorAll('[data-panel]')[i];
                if (source) panel.className = source.className;
            });

            const sourceStatic = sourcePanels.querySelector('[data-static]');
            const targetStatic = panels.querySelector('[data-static]');
            if (sourceStatic && targetStatic) targetStatic.textContent = sourceStatic.textContent;

            const sourceSummary = sourcePanels.querySelector('[data-summary]');
            const targetSummary = panels.querySelector('[data-summary]');
            if (sourceSummary && targetSummary) targetSummary.innerHTML = sourceSummary.innerHTML;

            if (sourceNav && nav) nav.replaceWith(sourceNav.cloneNode(true));

            const error = wizardForm.querySelector('.error');
            if (error) error.remove();
            const sourceError = sourceForm.querySelector('.error');
            if (sourceError) {
                wizardForm.insertBefore(sourceError.cloneNode(true), wizardForm.querySelector('[data-nav]'));
            }
        };

        const applyResponse = (html) => {
            const documentFromResponse = new DOMParser().parseFromString(html, 'text/html');
            const nextReservation = documentFromResponse.querySelector('.resv');
            if (!nextReservation) throw new Error('Respuesta inesperada del servidor');

            const success = nextReservation.querySelector('[data-success]');
            if (success) {
                wizardForm.replaceWith(document.importNode(success, true));
                return;
            }

            const nextForm = nextReservation.querySelector('form');
            if (!nextForm) throw new Error('Respuesta inesperada del servidor');

            const currentStep = Number(reservation.dataset.wizard || 1);
            const nextStep = Number(nextReservation.dataset.wizard || currentStep);

            window.clearTimeout(steppingTimer);
            reservation.removeAttribute('data-stepping');
            void reservation.offsetWidth;

            if (nextStep === currentStep) {
                if (nextForm.querySelector('.error')) {
                    reservation.dataset.stepping = 'error';
                    steppingTimer = window.setTimeout(() => reservation.removeAttribute('data-stepping'), 360);
                }
            } else {
                reservation.dataset.stepping = nextStep > currentStep ? 'forward' : 'back';
                reservation.dataset.wizard = String(nextStep);
                steppingTimer = window.setTimeout(() => reservation.removeAttribute('data-stepping'), 700);
            }

            syncForm(nextForm);

            if (nextReservation.dataset.period) {
                reservation.dataset.period = nextReservation.dataset.period;
            }
        };

        wizardForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (wizardForm.dataset.pending === 'true') return;

            const submitter = event.submitter;
            const payload = new FormData(wizardForm);
            if (submitter && submitter.name) payload.set(submitter.name, submitter.value);

            wizardForm.dataset.pending = 'true';
            reservation.setAttribute('aria-busy', 'true');

            try {
                const response = await fetch(wizardForm.action || '/reservar', {
                    method: 'POST',
                    body: payload,
                    credentials: 'same-origin',
                    headers: { Accept: 'text/html' }
                });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                applyResponse(await response.text());
            } catch (error) {
                if (wizardForm.isConnected && submitter && submitter.name) {
                    const fallback = document.createElement('input');
                    fallback.type = 'hidden';
                    fallback.name = submitter.name;
                    fallback.value = submitter.value;
                    wizardForm.appendChild(fallback);
                    wizardForm.submit();
                    return;
                }
            } finally {
                wizardForm.dataset.pending = 'false';
                reservation.removeAttribute('aria-busy');
            }
        });
    }
}

const mapNodes = document.querySelectorAll('[data-map-lat][data-map-lng]');

if (window.L && mapNodes.length) {
    mapNodes.forEach((node) => {
        const lat = Number(node.dataset.mapLat);
        const lng = Number(node.dataset.mapLng);
        const map = L.map(node, { scrollWheelZoom: false }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'restaurant-marker',
                html: '<span></span>',
                iconSize: [34, 34],
                iconAnchor: [17, 17]
            })
        }).addTo(map);
    });
}
