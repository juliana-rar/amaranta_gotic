@extends('layouts.public')

@section('title', 'La carta · Amaranta Gótico')
@section('page', 'menu')

@section('content')
<section data-page-hero>
    <div class="container">
        <h1>{{ t('menu.hero.title') }}</h1>
        <p>{{ t('menu.hero.subtitle') }}</p>
    </div>
</section>

<section class="container" data-section>
    @forelse($categories as $key => $items)
        <div>
            <h2>{{ $items->first()->category }}</h2>
            @foreach($items as $dish)
                <div data-item>
                    @if(filled($dish->image_url))<img src="{{ $dish->image_url }}" alt="{{ $dish->title }}">@endif
                    <span>{{ $dish->title }}
                        @if(filled($dish->description))<br><small>{{ $dish->description }}</small>@endif
                    </span>
                    @if(filled($dish->price_text))<span data-price>{{ $dish->price_text }}</span>@endif
                </div>
            @endforeach
        </div>
    @empty
        <div><p>{{ t('menu.empty') }}</p></div>
    @endforelse

    <div>
        <h2>{{ t('menu.groups.title') }}</h2>
        @foreach(range(1, 3) as $i)
            <div data-item><span>{{ t("menu.groups.line{$i}") }}</span></div>
        @endforeach
        <a class="btn" href="/grupos">{{ t('menu.groups.cta') }}</a>
    </div>
</section>
@endsection
