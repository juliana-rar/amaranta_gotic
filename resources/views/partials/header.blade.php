<header class="site-header" data-hero="{{ in_array(view()->yieldContent('page', 'home'), ['home', 'menu', 'grupos', 'ubicacion', 'contacto']) ? 'true' : 'false' }}">
    <div class="container site-header__inner">
        <a class="site-header__brand" href="/"><span class="site-header__brand-mark"><img src="/images/amaranta-logo.png" alt=""></span>Amaranta Gótico</a>
        <input type="checkbox" id="nav-toggle" class="site-header__toggle" aria-label="Abrir menú">
        <label for="nav-toggle" class="site-header__burger" aria-label="Abrir menú">☰</label>
        <nav class="site-nav">
            <a href="/" @if(view()->yieldContent('page') === 'home') class="active" data-active="true" @endif>{{ t('nav.home') }}</a>
            <a href="/menu" @if(view()->yieldContent('page') === 'menu') class="active" data-active="true" @endif>{{ t('nav.menu') }}</a>
            <a href="/grupos" @if(view()->yieldContent('page') === 'grupos') class="active" data-active="true" @endif>{{ t('nav.groups') }}</a>
            <a href="/ubicacion" @if(view()->yieldContent('page') === 'ubicacion') class="active" data-active="true" @endif>{{ t('nav.location') }}</a>
            <a href="/contacto" @if(view()->yieldContent('page') === 'contacto') class="active" data-active="true" @endif>{{ t('nav.contact') }}</a>
            <a href="/reservar" class="site-nav__cta" data-cta @if(view()->yieldContent('page') === 'reservar') data-active="true" @endif>{{ t('nav.reserve') }}</a>
            @auth
                <a href="/admin">Panel</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="site-nav__logout">Salir</button>
                </form>
            @endauth
        </nav>
    </div>
</header>
