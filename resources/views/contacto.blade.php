@extends('layouts.public')

@section('title', 'Contacto · Amaranta Gótico')
@section('page', 'contacto')

@section('content')
@php($site = site_content())
<section data-page-hero>
    <div class="container">
        <h1>{{ t('contacto.hero.title') }}</h1>
        <p>{{ t('contacto.hero.text') }}</p>
        <p>{{ str_replace('{phone}', $site->contact_phone, t('contacto.hero.groups')) }}</p>
    </div>
</section>

<section class="container" data-section>
    <div class="grid">
        <div>
            <h3>{{ t('contacto.phone.label') }}</h3>
            <p><a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></p>
        </div>
        <div>
            <h3>{{ t('contacto.email.label') }}</h3>
            <p><a href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a></p>
        </div>
        <div>
            <h3>{{ t('contacto.instagram.label') }}</h3>
            <p><a href="https://www.instagram.com/amarantagotico/" target="_blank" rel="noopener">{{ $site->contact_instagram }}</a></p>
        </div>
    </div>
</section>
@endsection
