document.documentElement.setAttribute('data-js', 'true');

const header = document.querySelector('header');
const toTop = document.querySelector('.to-top');

const updateScroll = () => {
    if (header) {
        header.setAttribute('data-scrolled', window.scrollY > 40 ? 'true' : 'false');
    }
    if (toTop) {
        toTop.setAttribute('data-visible', window.scrollY > 500 ? 'true' : 'false');
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
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealables.forEach((element) => observer.observe(element));
} else {
    revealables.forEach((element) => element.setAttribute('data-visible', 'true'));
}

const carousel = document.querySelector('[data-carousel]');

if (carousel) {
    const radios = [...carousel.querySelectorAll(':scope > input[type="radio"]')];

    carousel.querySelectorAll('[data-dir]').forEach((button) => {
        button.addEventListener('click', () => {
            if (!radios.length) {
                return;
            }
            const current = radios.findIndex((radio) => radio.checked);
            const step = button.getAttribute('data-dir') === 'next' ? 1 : -1;
            const next = (current + step + radios.length) % radios.length;
            radios[next].checked = true;
        });
    });
}

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
