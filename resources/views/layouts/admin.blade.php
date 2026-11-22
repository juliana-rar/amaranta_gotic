<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel · Amaranta Gótico')</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body data-page="admin">
@include('partials.header')

<div data-admin>
    <aside>
        <span>{{ t('admin.sidebar.brand') }}</span>
        <nav>
            <a href="/admin" @if(request()->is('admin')) data-active="true" @endif>{{ t('admin.nav.carta') }}</a>
            <a href="/admin/galeria" @if(request()->is('admin/galeria')) data-active="true" @endif>{{ t('admin.nav.galeria') }}</a>
            <a href="/admin/testimonios" @if(request()->is('admin/testimonios')) data-active="true" @endif>{{ t('admin.nav.opiniones') }}</a>
            <a href="/admin/contenido" @if(request()->is('admin/contenido')) data-active="true" @endif>{{ t('admin.nav.contenido') }}</a>
            <a href="/admin/textos" @if(request()->is('admin/textos')) data-active="true" @endif>{{ t('admin.nav.textos') }}</a>
        </nav>
    </aside>
    <main>
        @yield('content')
    </main>
</div>
<script src="/js/app.js" defer></script>
</body>
</html>
