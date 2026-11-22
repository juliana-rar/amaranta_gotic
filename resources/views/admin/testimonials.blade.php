@extends('layouts.admin')

@section('content')
<div>
    <h1>{{ t('admin.nav.opiniones') }}</h1>
    <p>Frases de comensales que aparecen en la portada.</p>
</div>

@if(session('saved'))<div class="notice">{{ session('saved') }}</div>@endif

<div data-card>
    <h2>{{ $editing->exists ? 'Editar opinión' : 'Nueva opinión' }}</h2>
    <form method="POST" action="/admin/testimonios">
        @csrf
        @if($editing->exists)<input type="hidden" name="id" value="{{ $editing->id }}">@endif
        <div data-field-grid>
            <x-field name="author_name" label="Nombre" :value="old('author_name', $editing->author_name)" />
            <x-field name="display_order" label="Orden" type="number" :value="old('display_order', $editing->display_order ?? 0)" />
        </div>
        <x-field name="quote" label="Opinión" type="textarea" :value="old('quote', $editing->quote)" />
        <div data-nav>
            <button type="submit" class="btn">{{ $editing->exists ? 'Guardar cambios' : 'Añadir opinión' }}</button>
            @if($editing->exists)<a class="btn" data-variant="outline" href="/admin/testimonios">Cancelar</a>@endif
        </div>
    </form>
</div>

<div data-card>
    <table>
        <thead><tr><th>Autor</th><th>Opinión</th><th>Orden</th><th></th></tr></thead>
        <tbody>
            @forelse($testimonials as $testimonial)
                <tr @if($editing->id === $testimonial->id) data-active="true" @endif>
                    <td>{{ $testimonial->author_name }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($testimonial->quote, 70) }}</td>
                    <td>{{ $testimonial->display_order }}</td>
                    <td data-actions>
                        <a href="/admin/testimonios?edit={{ $testimonial->id }}">Editar</a>
                        <form method="POST" action="/admin/testimonios/{{ $testimonial->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Todavía no hay opiniones.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
