@extends('layouts.admin')

@section('content')
@php
    $sections = [
        'Portada' => [
            'hero_eyebrow' => ['Etiqueta', 'text'],
            'hero_title' => ['Título', 'text'],
            'hero_subtitle' => ['Texto', 'textarea'],
        ],
        'Cifras' => [
            'stat1_number' => ['Cifra 1', 'text'], 'stat1_label' => ['Etiqueta 1', 'text'],
            'stat2_number' => ['Cifra 2', 'text'], 'stat2_label' => ['Etiqueta 2', 'text'],
            'stat3_number' => ['Cifra 3', 'text'], 'stat3_label' => ['Etiqueta 3', 'text'],
            'stat4_number' => ['Cifra 4', 'text'], 'stat4_label' => ['Etiqueta 4', 'text'],
        ],
        'Valores' => [
            'feature1_icon' => ['Icono 1', 'text'], 'feature1_title' => ['Título 1', 'text'], 'feature1_description' => ['Texto 1', 'textarea'],
            'feature2_icon' => ['Icono 2', 'text'], 'feature2_title' => ['Título 2', 'text'], 'feature2_description' => ['Texto 2', 'textarea'],
            'feature3_icon' => ['Icono 3', 'text'], 'feature3_title' => ['Título 3', 'text'], 'feature3_description' => ['Texto 3', 'textarea'],
        ],
        'Categorías de la carta' => [
            'category1_image' => ['Imagen 1', 'text'], 'category1_title' => ['Título 1', 'text'], 'category1_description' => ['Texto 1', 'textarea'],
            'category2_image' => ['Imagen 2', 'text'], 'category2_title' => ['Título 2', 'text'], 'category2_description' => ['Texto 2', 'textarea'],
            'category3_image' => ['Imagen 3', 'text'], 'category3_title' => ['Título 3', 'text'], 'category3_description' => ['Texto 3', 'textarea'],
            'category4_image' => ['Imagen 4', 'text'], 'category4_title' => ['Título 4', 'text'], 'category4_description' => ['Texto 4', 'textarea'],
        ],
        'Historia' => [
            'story_eyebrow' => ['Etiqueta', 'text'], 'story_title' => ['Título', 'text'],
            'story_paragraph1' => ['Párrafo 1', 'textarea'], 'story_paragraph2' => ['Párrafo 2', 'textarea'],
            'story_quote' => ['Cita', 'text'], 'story_quote_author' => ['Autor de la cita', 'text'],
        ],
        'Contacto' => [
            'location_address' => ['Dirección', 'textarea'], 'location_hours' => ['Horario resumido', 'text'],
            'contact_phone' => ['Teléfono', 'text'], 'contact_email' => ['Email', 'text'], 'contact_instagram' => ['Instagram', 'text'],
        ],
    ];
@endphp

<div>
    <h1>{{ t('admin.nav.contenido') }}</h1>
    <p>Textos y datos de la portada; las rutas de imagen pueden empezar por <code>/</code>.</p>
</div>

@if(session('saved'))<div class="notice">{{ session('saved') }}</div>@endif

<form method="POST" action="/admin/contenido" data-card>
    @csrf
    @foreach($sections as $title => $fields)
        <fieldset>
            <legend>{{ $title }}</legend>
            <div data-field-grid>
                @foreach($fields as $field => $definition)
                    <x-field :name="$field" :label="$definition[0]" :type="$definition[1]" :value="$content->{$field}" />
                @endforeach
            </div>
        </fieldset>
    @endforeach

    <fieldset>
        <legend>Horario</legend>
        <div data-hours>
            @foreach(range(0, 6) as $day)
                @php($hour = $hours->firstWhere('day_order', $day))
                <div data-hour-row>
                    <strong>{{ day_names()[$day] }}</strong>
                    <x-field :name="'hours.'.$day.'.is_open'" label="Abierto" type="checkbox" :value="optional($hour)->is_open" />
                    <x-field :name="'hours.'.$day.'.shift1_start'" label="Apertura" :value="optional($hour)->shift1_start" />
                    <x-field :name="'hours.'.$day.'.shift1_end'" label="Cierre" :value="optional($hour)->shift1_end" />
                    <x-field :name="'hours.'.$day.'.has_shift2'" label="Segundo turno" type="checkbox" :value="optional($hour)->shift2_start" />
                    <x-field :name="'hours.'.$day.'.shift2_start'" label="Apertura 2" :value="optional($hour)->shift2_start" />
                    <x-field :name="'hours.'.$day.'.shift2_end'" label="Cierre 2" :value="optional($hour)->shift2_end" />
                </div>
            @endforeach
        </div>
    </fieldset>

    <div data-nav><button type="submit" class="btn">Guardar contenido</button></div>
</form>
@endsection
