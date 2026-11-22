<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Amaranta Gótico')</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body data-page="auth">
<div data-auth>
    <main>
        <div>
            <strong>Amaranta Gótico</strong>
            @yield('content')
        </div>
    </main>
</div>
<script src="/js/app.js" defer></script>
</body>
</html>
