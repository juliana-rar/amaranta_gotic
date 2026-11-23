@php($site = site_content())
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__col">
            <strong class="site-footer__brand">Amaranta Gótico</strong>
            <p>{{ t('footer.description') }}</p>
            <div class="site-footer__social">
                <a href="https://www.instagram.com/amarantagotico/" target="_blank" rel="noopener" aria-label="Instagram">📷</a>
                <a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}" aria-label="Teléfono">📞</a>
                <a href="mailto:{{ $site->contact_email }}" aria-label="Email">✉️</a>
            </div>
        </div>
        <div class="site-footer__col">
            <h3>{{ t('footer.hours.title') }}</h3>
            @foreach(business_hours() as $hour)
                <p><span class="site-footer__label">{{ day_names()[$hour->day_order] }}</span> {{ hours_text($hour) }}</p>
            @endforeach
        </div>
        <div class="site-footer__col">
            <h3>{{ t('footer.location.title') }}</h3>
            @foreach(address_lines($site->location_address) as $line)
                <p>{{ $line }}</p>
            @endforeach
            <p><a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></p>
            <p><a href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a></p>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <span>© {{ now()->year }} {{ t('footer.copyright') }}</span>
        <a href="/reservar">{{ t('footer.cta') }}</a>
    </div>
</footer>
