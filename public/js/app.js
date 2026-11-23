document.documentElement.setAttribute('data-js', 'true');

const header = document.querySelector('header');
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

const reservation = document.querySelector('.resv[data-period]');

if (reservation) {
    reservation.querySelectorAll('input[name="period"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            reservation.setAttribute('data-period', radio.value);
            reservation.querySelectorAll('input[name="time"]').forEach((time) => {
                time.checked = false;
            });
        });
    });
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
