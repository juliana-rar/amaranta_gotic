@extends('layouts.public')

@section('title', 'Grupos y eventos · Amaranta Gótico')
@section('page', 'grupos')

@section('content')
@php($site = site_content())
<section data-page-hero>
    <div class="container">
        <span class="eyebrow">{{ t('grupos.hero.eyebrow') }}</span>
        <h1>{{ t('grupos.hero.title') }}</h1>
        <p>{{ t('grupos.hero.text') }}</p>
    </div>
</section>

@foreach([1, 2] as $block)
    <section class="container" data-section>
        <div data-head><h2>{{ t("grupos.s{$block}.title") }}</h2></div>
        <div data-info-grid>
            @foreach(['col1', 'col2'] as $column)
                <div>
                    <h2>{{ t("grupos.s{$block}.{$column}.title") }}</h2>
                    @foreach(range(1, 3) as $line)
                        <div data-item><span>{{ t("grupos.s{$block}.{$column}.line{$line}") }}</span></div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endforeach

<section class="container" data-section>
    <div data-head><h2>{{ t('grupos.s3.title') }}</h2></div>
    <div data-info-grid>
        <div>
            <h3>{{ t('grupos.s3.phone.label') }}</h3>
            <p><a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></p>
        </div>
        <div>
            <h3>{{ t('grupos.s3.email.label') }}</h3>
            <p><a href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a></p>
        </div>
    </div>
    <div data-actions>
        <a class="btn" href="/contacto">{{ t('grupos.s3.cta1') }}</a>
        <a class="btn" href="/reservar">{{ t('grupos.s3.cta2') }}</a>
    </div>
</section>
@endsection
