<header data-hero="{{ in_array(view()->yieldContent('page', 'home'), ['home', 'menu', 'grupos', 'ubicacion', 'contacto']) ? 'true' : 'false' }}">
    <div class="container">
        <a href="/"><span><img src="/images/amaranta-logo.png" alt=""></span>Amaranta Gótico</a>
        <input type="checkbox" id="nav-toggle" aria-label="Abrir menú">
        <label for="nav-toggle" aria-label="Abrir menú">☰</label>
        <nav>
            <a href="/" @if(view()->yieldContent('page') === 'home') data-active="true" @endif>{{ t('nav.home') }}</a>
            <a href="/menu" @if(view()->yieldContent('page') === 'menu') data-active="true" @endif>{{ t('nav.menu') }}</a>
            <a href="/grupos" @if(view()->yieldContent('page') === 'grupos') data-active="true" @endif>{{ t('nav.groups') }}</a>
            <a href="/ubicacion" @if(view()->yieldContent('page') === 'ubicacion') data-active="true" @endif>{{ t('nav.location') }}</a>
            <a href="/contacto" @if(view()->yieldContent('page') === 'contacto') data-active="true" @endif>{{ t('nav.contact') }}</a>
            <a href="/reservar" data-cta @if(view()->yieldContent('page') === 'reservar') data-active="true" @endif>{{ t('nav.reserve') }}</a>
            @auth
                <a href="/admin">Panel</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit">Salir</button>
                </form>
            @endauth
        </nav>
    </div>
</header>
