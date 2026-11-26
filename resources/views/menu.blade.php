@extends('layouts.public')

@section('title', 'La carta · Amaranta Gótico')
@section('page', 'menu')

@section('content')
<section class="menu-hero">
    <div class="container menu-hero__inner">
        <h1>{{ t('menu.hero.title') }}</h1>
        <p>{{ t('menu.hero.subtitle') }}</p>
    </div>
</section>

<section class="container menu-content">
    @forelse($categories as $key => $items)
        @php($categoryNumber = $loop->iteration)
        <article class="menu-category">
            <header class="menu-category__head">
                <span class="menu-category__index">{{ str_pad((string) $categoryNumber, 2, '0', STR_PAD_LEFT) }}</span>
                <h2>{{ $items->first()->category }}</h2>
                <span class="menu-category__rule" aria-hidden="true"></span>
            </header>
            <div class="menu-category__items">
                @foreach($items as $dish)
                    <article class="menu-dish">
                        <div class="menu-dish__thumb{{ filled($dish->image_url) ? '' : ' menu-dish__thumb--empty' }}">
                            @if(filled($dish->image_url))<img src="{{ $dish->image_url }}" alt="{{ $dish->title }}" loading="lazy">@endif
                        </div>
                        <div class="menu-dish__body">
                            <div class="menu-dish__line">
                                <h3>{{ $dish->title }}</h3>
                                @if(filled($dish->price_text))<span class="menu-dish__price">{{ $dish->price_text }}</span>@endif
                            </div>
                            @if(filled($dish->description))<p>{{ $dish->description }}</p>@endif
                        </div>
                    </article>
                @endforeach
            </div>
        </article>
    @empty
        <div class="menu-empty"><p>{{ t('menu.empty') }}</p></div>
    @endforelse

    <aside class="menu-groups">
        <h2>{{ t('menu.groups.title') }}</h2>
        <ul class="menu-groups__list">
            @foreach(range(1, 3) as $i)
                <li>{{ t("menu.groups.line{$i}") }}</li>
            @endforeach
        </ul>
        <a class="btn" href="/grupos">{{ t('menu.groups.cta') }} <span aria-hidden="true">↗</span></a>
    </aside>
</section>
@endsection
