@extends('layouts.public')

@section('title', 'Dónde estamos · Amaranta Gótico')
@section('page', 'ubicacion')

@section('content')
@php
    $site = site_content();
    $mapQuery = rawurlencode(str_replace(["\r", "\n"], ' ', $site->location_address));
    $mapLink = "https://www.google.com/maps?q={$mapQuery}&z=16";
    $mapLat = 41.3814694;
    $mapLng = 2.1767447;
@endphp
<section data-page-hero>
    <div class="container">
        <h1>{{ t('ubicacion.hero.title') }}</h1>
        <p>{{ t('ubicacion.hero.subtitle') }}</p>
    </div>
</section>

<section class="container" data-section>
    <div data-info-grid>
        <div>
            <h2>{{ t('ubicacion.address.title') }}</h2>
            @foreach(address_lines($site->location_address) as $line)<p>{{ $line }}</p>@endforeach
            <p><a href="tel:{{ str_replace(' ', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></p>
            <p>{{ t('ubicacion.transport') }}</p>
        </div>
        <div>
            <h2>{{ t('ubicacion.hours.title') }}</h2>
            <table>
                <tbody>
                    @foreach(business_hours() as $hour)
                        <tr><td>{{ day_names()[$hour->day_order] }}</td><td>{{ hours_text($hour) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="container" data-section data-map>
    <h2>En el mapa</h2>
    <div class="location-map" id="location-map" data-map-lat="{{ $mapLat }}" data-map-lng="{{ $mapLng }}" role="img" aria-label="Mapa de Amaranta Gótico en la Baixada de Sant Miquel"></div>
    <a href="{{ $mapLink }}" target="_blank" rel="noopener">Abrir en Google Maps</a>
</section>
@endsection
