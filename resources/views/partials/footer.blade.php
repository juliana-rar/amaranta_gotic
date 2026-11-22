@php($site = site_content())
<footer>
    <div class="container">
        <div>
            <strong>Amaranta Gótico</strong>
            <p>{{ t('footer.description') }}</p>
            <div>
                <a href="https://www.instagram.com/amarantagotico/" target="_blank" rel="noopener" aria-label="Instagram">📷</a>
                <a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}" aria-label="Teléfono">📞</a>
                <a href="mailto:{{ $site->contact_email }}" aria-label="Email">✉️</a>
            </div>
        </div>
        <div>
            <h3>{{ t('footer.hours.title') }}</h3>
            @foreach(business_hours() as $hour)
                <p><span>{{ day_names()[$hour->day_order] }}</span> {{ hours_text($hour) }}</p>
            @endforeach
        </div>
        <div>
            <h3>{{ t('footer.location.title') }}</h3>
            @foreach(address_lines($site->location_address) as $line)
                <p>{{ $line }}</p>
            @endforeach
            <p><a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></p>
            <p><a href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a></p>
        </div>
    </div>
    <div class="container">
        <span>© {{ now()->year }} {{ t('footer.copyright') }}</span>
        <a href="/reservar">{{ t('footer.cta') }}</a>
    </div>
</footer>
