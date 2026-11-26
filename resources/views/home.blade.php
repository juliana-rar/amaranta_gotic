@extends('layouts.public')

@section('title', 'Amaranta Gótico · Brasería en el Barrio Gótico')
@section('page', 'home')

@section('content')
@php
    $site = site_content();
    $fallbacks = ['🔥', '🍚', '🥘', '🍸'];
    $heroImages = collect(['/media/2.jpg', '/media/4.jpg', '/media/5.jpg', '/media/local1.jpg', '/media/local3.jpg', '/media/local5.jpg'])
        ->filter(fn ($path) => is_file(base_path('media' . DIRECTORY_SEPARATOR . basename($path))))
        ->values();
    if ($heroImages->isEmpty()) {
        $heroImages = collect([$gallery->get(0)?->image_url
            ?: ($dishes->first(fn ($dish) => filled($dish->image_url))?->image_url ?: '/images/amaranta-ambiente.jpg')]);
    }
    $brasaSlides = collect([
        ['src' => '/images/amaranta-brasa.jpg', 'file' => public_path('images/amaranta-brasa.jpg'), 'alt' => 'Brasa encendida en la cocina de Amaranta Gótico'],
        ['src' => '/media/2.jpg', 'file' => base_path('media/2.jpg'), 'alt' => 'Producto marcado por el fuego en Amaranta Gótico'],
        ['src' => '/images/amaranta-mesa.jpg', 'file' => public_path('images/amaranta-mesa.jpg'), 'alt' => 'Brasas y arroces para compartir en la mesa'],
        ['src' => '/images/amaranta-ambiente.jpg', 'file' => public_path('images/amaranta-ambiente.jpg'), 'alt' => 'Ambiente cálido del local entre piedra y luz baja'],
    ])->filter(fn ($slide) => is_file($slide['file']))->values();
    $mapQuery = rawurlencode(str_replace(["\r", "\n"], ' ', $site->location_address));
    $mapLink = "https://www.google.com/maps?q={$mapQuery}&z=16";
    $mapLat = 41.3814694;
    $mapLng = 2.1767447;
@endphp

<section class="hero">
    @foreach ($heroImages as $index => $heroImage)
        <div class="hero__bg" style="background-image: url('{{ $heroImage }}')" data-active="{{ $index === 0 ? 'true' : 'false' }}"></div>
    @endforeach
    <div class="hero__overlay"></div>
    <div class="container hero__inner">
        <span class="eyebrow hero__eyebrow">{{ $site->hero_eyebrow }}</span>
        <h1>{{ $site->hero_title }}</h1>
        <div class="hero__actions">
            <a class="button" data-size="lg" href="/reservar">{{ t('home.hero.cta') }}</a>
            <a class="hero__link" href="/menu">{{ t('home.hero.secondary') }} <span aria-hidden="true">↗</span></a>
        </div>
    </div>
</section>

<section class="menu-band">
    <div class="container">
        <div class="menu-band__grid stagger">
            @foreach(range(1, 4) as $i)
                <article class="menu-band__item reveal">
                    <div class="menu-band__arch">
                        <div class="menu-band__arch-inner">
                            @if(filled($site->{"category{$i}_image"}))
                                <img src="{{ $site->{"category{$i}_image"} }}" alt="{{ $site->{"category{$i}_title"} }}">
                            @else
                                <span class="menu-band__fallback" data-empty="true">{{ $fallbacks[$i - 1] }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="menu-band__index">0{{ $i }}</span>
                    <h3>{{ $site->{"category{$i}_title"} }}</h3>
                    <p>{{ $site->{"category{$i}_description"} }}</p>
                </article>
            @endforeach
        </div>
        <div class="menu-band__cta reveal"><a class="button" href="/menu">{{ t('home.menu.cta') }} <span aria-hidden="true">↗</span></a></div>
    </div>
</section>

@if($brasaSlides->isNotEmpty())
    <section class="brasa-feature">
        <div class="carousel carousel--brasa">
            <div class="carousel__track">
                @foreach($brasaSlides as $i => $slide)
                    <div class="carousel__slide" @if($i === 0) data-active="true" @endif>
                        <img src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}" @if($i > 0) loading="lazy" @endif>
                    </div>
                @endforeach
            </div>
            <div class="brasa-feature__label reveal">
                <span class="eyebrow">La brasa</span>
            </div>
            @if($brasaSlides->count() > 1)
                <button type="button" class="carousel__nav" data-dir="prev" aria-label="Foto anterior">‹</button>
                <button type="button" class="carousel__nav" data-dir="next" aria-label="Foto siguiente">›</button>
                <div class="carousel__counter" aria-hidden="true">
                    <span data-carousel-current>01</span><i></i><span>{{ str_pad((string) $brasaSlides->count(), 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="carousel__dots">
                    @foreach($brasaSlides as $i => $slide)
                        <button type="button" class="carousel__dot" @if($i === 0) data-active="true" @endif aria-label="Ir a la foto {{ $i + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif

<section class="features container stagger">
    @foreach(range(1, 3) as $i)
        <div class="feature-card reveal">
            <div class="feature-card__icon">{{ $site->{"feature{$i}_icon"} }}</div>
            <h3>{{ $site->{"feature{$i}_title"} }}</h3>
            <p>{{ $site->{"feature{$i}_description"} }}</p>
        </div>
    @endforeach
</section>

<section class="origin container stagger">
    <div class="origin__block reveal">
        <h2>{{ t('home.origin.heading1') }}</h2>
        <p>{{ $site->story_paragraph1 }}</p>
    </div>
    <div class="origin__block reveal">
        <h2>{{ t('home.origin.heading2') }}</h2>
        <p>{{ $site->story_paragraph2 }}</p>
    </div>
    <div class="origin__block reveal">
        <h2>{{ t('home.origin.heading3') }}</h2>
        <p>“{{ $site->story_quote }}” — {{ $site->story_quote_author }}</p>
    </div>
</section>

<section class="ribbon">
    <a class="reveal" href="/reservar">{{ str_replace('{phone}', $site->contact_phone, t('home.ribbon')) }}</a>
</section>

<section class="home-map">
    <div class="home-map__canvas" id="home-map" data-map-lat="{{ $mapLat }}" data-map-lng="{{ $mapLng }}" role="img" aria-label="Mapa de Amaranta Gótico en la Baixada de Sant Miquel"></div>
    <a class="home-map__link" href="{{ $mapLink }}" target="_blank" rel="noopener">Ver en Google Maps</a>
</section>

@if($dishes->isNotEmpty())
    <section class="dish-showcase container">
        <div class="section-head reveal" data-align="center">
            <span class="eyebrow">{{ t('home.dishes.eyebrow') }}</span>
            <h2>{{ t('home.dishes.title') }}</h2>
        </div>
        <div class="dish-grid stagger">
            @foreach($dishes as $dish)
                <div class="dish-card reveal">
                    @if(filled($dish->image_url))
                        <div class="dish-card__image-wrap"><img src="{{ $dish->image_url }}" alt="{{ $dish->title }}"></div>
                    @else
                        <div class="dish-card__image-wrap" data-empty="true">🔥</div>
                    @endif
                    <div class="dish-card__body">
                        <h3>
                            {{ $dish->title }}
                            @if(filled($dish->price_text))<span class="menu-item__price">{{ $dish->price_text }}</span>@endif
                        </h3>
                        <p>{{ $dish->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif

@if($gallery->isNotEmpty())
    <section class="gallery">
        <div class="reveal">
            <div class="carousel">
                <div class="carousel__track">
                    @foreach($gallery as $i => $image)
                        <div class="carousel__slide" @if($i === 0) data-active="true" @endif>
                            <img src="{{ $image->image_url }}" alt="{{ $image->caption }}">
                            @if(filled($image->caption))<div class="carousel__caption"><span>{{ $image->caption }}</span></div>@endif
                        </div>
                    @endforeach
                </div>
                @if($gallery->count() > 1)
                    <button type="button" class="carousel__nav" data-dir="prev" aria-label="Foto anterior">‹</button>
                    <button type="button" class="carousel__nav" data-dir="next" aria-label="Foto siguiente">›</button>
                    <div class="carousel__counter" aria-hidden="true">
                        <span data-carousel-current>01</span><i></i><span>{{ str_pad((string) $gallery->count(), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="carousel__dots">
                        @foreach($gallery as $i => $image)
                            <button type="button" class="carousel__dot" @if($i === 0) data-active="true" @endif aria-label="Ir a la foto {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif

@if($testimonials->isNotEmpty())
    <section class="testimonials">
        <div class="container">
            <div class="section-head reveal" data-align="center">
                <span class="eyebrow">{{ t('home.testimonials.eyebrow') }}</span>
                <h2>{{ t('home.testimonials.title') }}</h2>
            </div>
        </div>
        <div class="testimonial-viewport">
            <div class="testimonial-track">
                @for($copy = 0; $copy < 2; $copy++)
                    <div class="testimonial-group" @if($copy === 1) aria-hidden="true" @endif>
                        @foreach($testimonials as $testimonial)
                            <article class="testimonial-card">
                                <div class="testimonial-card__stars">★★★★★</div>
                                <p class="testimonial-card__quote">“{{ $testimonial->quote }}”</p>
                                <p class="testimonial-card__author">
                                    <span class="testimonial-card__avatar">{{ mb_substr($testimonial->author_name, 0, 1) }}</span>
                                    {{ $testimonial->author_name }}
                                </p>
                            </article>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endif

<section class="stats">
    <div class="container stats__inner stagger">
        @foreach(range(1, 4) as $i)
            <div class="stats__item reveal">
                <span class="stats__number">{{ $site->{"stat{$i}_number"} }}</span>
                <span class="stats__label">{{ $site->{"stat{$i}_label"} }}</span>
            </div>
        @endforeach
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-banner__inner reveal">
        <div>
            <h2>{{ t('home.cta.title') }}</h2>
            <p>{{ t('home.cta.text') }}</p>
        </div>
        <a class="button" data-size="lg" href="/grupos">{{ t('home.cta.button') }}</a>
    </div>
</section>
@endsection
