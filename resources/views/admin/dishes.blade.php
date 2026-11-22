@extends('layouts.admin')

@section('content')
<div>
    <h1>{{ t('admin.nav.carta') }}</h1>
    <p>Platos, precios y fotografías de la carta.</p>
</div>

@if(session('saved'))<div class="notice">{{ session('saved') }}</div>@endif

<div data-card>
    <h2>{{ $editing->exists ? 'Editar plato' : 'Nuevo plato' }}</h2>
    <form method="POST" action="/admin/platos" enctype="multipart/form-data">
        @csrf
        @if($editing->exists)<input type="hidden" name="id" value="{{ $editing->id }}">@endif
        <div data-field-grid>
            <x-field name="title" label="Título" :value="old('title', $editing->title)" />
            <x-field name="category" label="Categoría" :value="old('category', $editing->category)" placeholder="Brasa, arroces…" />
            <x-field name="price_text" label="Precio" :value="old('price_text', $editing->price_text)" placeholder="24 €" />
            <x-field name="display_order" label="Orden" type="number" :value="old('display_order', $editing->display_order ?? 0)" />
            <x-field name="image" label="Imagen" type="file" />
            <x-field name="show_on_home" label="Mostrar en inicio" type="checkbox" :value="old('show_on_home', $editing->show_on_home)" />
        </div>
        <x-field name="description" label="Descripción" type="textarea" :value="old('description', $editing->description)" />
        @if(filled($editing->image_url))<img data-preview src="{{ $editing->image_url }}" alt="Vista previa">@endif
        <div data-nav>
            <button type="submit" class="btn">{{ $editing->exists ? 'Guardar cambios' : 'Añadir plato' }}</button>
            @if($editing->exists)<a class="btn" data-variant="outline" href="/admin">Cancelar</a>@endif
        </div>
    </form>
</div>

<div data-card>
    <table>
        <thead><tr><th>Plato</th><th>Categoría</th><th>Precio</th><th>Inicio</th><th></th></tr></thead>
        <tbody>
            @forelse($dishes as $dish)
                <tr @if($editing->id === $dish->id) data-active="true" @endif>
                    <td>{{ $dish->title }}</td>
                    <td>{{ $dish->category ?: '—' }}</td>
                    <td>{{ $dish->price_text ?: '—' }}</td>
                    <td>{{ $dish->show_on_home ? '✓' : '—' }}</td>
                    <td data-actions>
                        <a href="/admin?edit={{ $dish->id }}">Editar</a>
                        <form method="POST" action="/admin/platos/{{ $dish->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Todavía no hay platos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
