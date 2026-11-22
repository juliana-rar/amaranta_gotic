@extends('layouts.admin')

@section('content')
@php
    $grouped = [];
    foreach ($definitions as $definition) {
        $grouped[$definition['group']][] = $definition;
    }
@endphp

<div>
    <h1>{{ t('admin.nav.textos') }}</h1>
    <p>Edita las palabras de la web. Si dejas un campo vacío, aparecerá su clave.</p>
</div>

@if(session('saved'))<div class="notice">{{ session('saved') }}</div>@endif

<form method="POST" action="/admin/textos" data-card>
    @csrf
    @foreach($grouped as $group => $items)
        <fieldset>
            <legend>{{ $group }}</legend>
            <div data-field-grid>
                @foreach($items as $definition)
                    <x-field
                        :name="'texts.'.$definition['key']"
                        :label="$definition['label']"
                        :type="$definition['multiline'] ? 'textarea' : 'text'"
                        :value="$values[$definition['key']] ?? $definition['value']"
                    />
                @endforeach
            </div>
        </fieldset>
    @endforeach

    <div data-nav><button type="submit" class="btn">Guardar textos</button></div>
</form>
@endsection
