@extends('layouts.public')

@section('title', 'Amaranta Gótico · Brasería en el Barrio Gótico')
@section('page', 'home')

@section('content')
@php
    $site = site_content();
    $fallbacks = ['🔥', '🍚', '🥘', '🍸'];
    $heroImage = $gallery->get(0)?->image_url
        ?: ($dishes->first(fn ($dish) => filled($dish->image_url))?->image_url ?: '/images/amaranta-ambiente.jpg');
    $breakImage = $gallery->get(1)?->image_url ?: $gallery->get(0)?->image_url
        ?: ($dishes->skip(1)->first(fn ($dish) => filled($dish->image_url))?->image_url
        ?: ($dishes->first(fn ($dish) => filled($dish->image_url))?->image_url ?: '/images/amaranta-brasa.jpg'));
@endphp

<section>
    <div data-bg style="background-image: url('{{ $heroImage }}')"></div>
    <div data-overlay></div>
    <div class="container" data-inner>
        <span class="eyebrow">{{ $site->hero_eyebrow }}</span>
        <h1>{{ $site->hero_title }}</h1>
        <p>{{ $site->hero_subtitle }}</p>
        <div data-actions>
            <a class="btn" data-size="lg" href="/reservar">{{ t('home.hero.cta') }}</a>
            <a class="btn" data-variant="outline" data-size="lg" href="/menu">{{ t('home.hero.secondary') }}</a>
        </div>
        <div data-trust>
            <span>{{ t('home.hero.trust1') }}</span>
            <span>{{ t('home.hero.trust2') }}</span>
            <span>{{ t('home.hero.trust3') }}</span>
        </div>
    </div>
</section>

<section data-band>
    <div class="container">
        <h2 class="reveal">{{ t('home.menu.title') }}</h2>
        <div class="grid stagger">
            @foreach(range(1, 4) as $i)
                <div class="reveal">
                    @if(filled($site->{"category{$i}_image"}))
                        <div data-circle><img src="{{ $site->{"category{$i}_image"} }}" alt="{{ $site->{"category{$i}_title"} }}"></div>
                    @else
                        <div data-circle data-empty="true">{{ $fallbacks[$i - 1] }}</div>
                    @endif
                    <h3>{{ $site->{"category{$i}_title"} }}</h3>
                    <p>{{ $site->{"category{$i}_description"} }}</p>
                </div>
            @endforeach
        </div>
        <div data-band-cta class="reveal"><a class="btn" data-size="lg" href="/menu">{{ t('home.menu.cta') }}</a></div>
    </div>
</section>

<section data-features class="container stagger">
    @foreach(range(1, 3) as $i)
        <div class="reveal">
            <div data-icon>{{ $site->{"feature{$i}_icon"} }}</div>
            <h3>{{ $site->{"feature{$i}_title"} }}</h3>
            <p>{{ $site->{"feature{$i}_description"} }}</p>
        </div>
    @endforeach
</section>

<section data-origin class="container stagger">
    @foreach(range(1, 3) as $i)
        <div class="reveal">
            <h2>{{ t("home.origin.heading{$i}") }}</h2>
            <p>{{ $i === 1 ? $site->story_paragraph1 : ($i === 2 ? $site->story_paragraph2 : '“'.$site->story_quote.'” — '.$site->story_quote_author) }}</p>
        </div>
    @endforeach
</section>

<section data-ribbon>
    <a class="reveal" href="/reservar">{{ str_replace('{phone}', $site->contact_phone, t('home.ribbon')) }}</a>
</section>

<section data-break>
    <div data-bg style="background-image: url('{{ $breakImage }}')"></div>
    <div data-overlay></div>
    <div class="container" data-inner>
        <h2 class="reveal">{{ t('home.break.title') }}</h2>
        <a class="btn" data-size="lg" href="/grupos">{{ t('home.break.cta') }}</a>
    </div>
</section>

<section data-dishes class="container">
    <div data-head class="reveal">
        <span class="eyebrow">{{ t('home.dishes.eyebrow') }}</span>
        <h2>{{ t('home.dishes.title') }}</h2>
    </div>
    <div class="grid stagger">
        @foreach($dishes as $dish)
            <div class="reveal">
                @if(filled($dish->image_url))
                    <div data-image><img src="{{ $dish->image_url }}" alt="{{ $dish->title }}"></div>
                @else
                    <div data-image data-empty="true">🔥</div>
                @endif
                <div data-body>
                    <h3>{{ $dish->title }} @if(filled($dish->price_text))<span data-price>{{ $dish->price_text }}</span>@endif</h3>
                    <p>{{ $dish->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section data-gallery class="container">
    <div data-head class="reveal">
        <span class="eyebrow">{{ t('home.gallery.eyebrow') }}</span>
        <h2>{{ t('home.gallery.title') }}</h2>
    </div>
    @if($gallery->isNotEmpty())
        <div class="reveal" data-carousel>
            @foreach($gallery as $i => $image)
                <input type="radio" name="carousel" id="carousel-{{ $i }}" @if($i === 0) checked @endif>
            @endforeach
            <div data-slides>
                @foreach($gallery as $image)
                    <figure>
                        <img src="{{ $image->image_url }}" alt="{{ $image->caption }}">
                        @if(filled($image->caption))<figcaption>{{ $image->caption }}</figcaption>@endif
                    </figure>
                @endforeach
            </div>
            @if($gallery->count() > 1)
                <button type="button" data-dir="prev" aria-label="Foto anterior">‹</button>
                <button type="button" data-dir="next" aria-label="Foto siguiente">›</button>
                <div data-dots>
                    @foreach($gallery as $i => $image)
                        <label for="carousel-{{ $i }}" aria-label="Ir a la foto {{ $i + 1 }}"></label>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</section>

<section data-testimonials class="container">
    <div data-head class="reveal">
        <span class="eyebrow">{{ t('home.testimonials.eyebrow') }}</span>
        <h2>{{ t('home.testimonials.title') }}</h2>
    </div>
    <div class="grid stagger">
        @foreach($testimonials as $testimonial)
            <div class="reveal">
                <div data-stars>★★★★★</div>
                <p data-quote>“{{ $testimonial->quote }}”</p>
                <p data-author><span>{{ mb_substr($testimonial->author_name, 0, 1) }}</span>{{ $testimonial->author_name }}</p>
            </div>
        @endforeach
    </div>
</section>

<section data-stats>
    <div class="container grid stagger">
        @foreach(range(1, 4) as $i)
            <div class="reveal">
                <strong>{{ $site->{"stat{$i}_number"} }}</strong>
                <span>{{ $site->{"stat{$i}_label"} }}</span>
            </div>
        @endforeach
    </div>
</section>

<section data-cta>
    <div class="container reveal">
        <div>
            <h2>{{ t('home.cta.title') }}</h2>
            <p>{{ t('home.cta.text') }}</p>
        </div>
        <a class="btn" data-size="lg" href="/grupos">{{ t('home.cta.button') }}</a>
    </div>
</section>
@endsection
