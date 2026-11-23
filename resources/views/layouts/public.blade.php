<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Amaranta Gótico · Brasería en el Barrio Gótico')</title>
    <meta name="description" content="Brasería mediterránea en el Barrio Gótico de Barcelona. Carnes a la brasa, arroces para compartir y copas entre piedras y brasas.">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    @if(in_array(view()->yieldContent('page', 'home'), ['home', 'ubicacion'], true))
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @endif
    @if(view()->yieldContent('page', 'home') === 'home')
        <link rel="stylesheet" href="/css/legacy-base.css">
        <link rel="stylesheet" href="/css/legacy-site.css">
        <link rel="stylesheet" href="/css/legacy-home.css">
    @endif
</head>
<body data-page="@yield('page', 'home')">
@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')

<button type="button" class="to-top" aria-label="Volver arriba">↑</button>
@if(in_array(view()->yieldContent('page', 'home'), ['home', 'ubicacion'], true))
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
@endif
<script src="/js/app.js" defer></script>
</body>
</html>
