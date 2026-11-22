@extends('layouts.admin')

@section('content')
<div>
    <h1>{{ t('admin.nav.galeria') }}</h1>
    <p>Las fotos del carrusel de la portada.</p>
</div>

@if(session('saved'))<div class="notice">{{ session('saved') }}</div>@endif

<div data-card>
    <h2>{{ $editing->exists ? 'Editar foto' : 'Nueva foto' }}</h2>
    <form method="POST" action="/admin/galeria" enctype="multipart/form-data">
        @csrf
        @if($editing->exists)<input type="hidden" name="id" value="{{ $editing->id }}">@endif
        <div data-field-grid>
            <x-field name="caption" label="Pie de foto" :value="old('caption', $editing->caption)" />
            <x-field name="display_order" label="Orden" type="number" :value="old('display_order', $editing->display_order ?? 0)" />
            <x-field name="image" label="Imagen" type="file" />
        </div>
        @if(filled($editing->image_url))<img data-preview src="{{ $editing->image_url }}" alt="Vista previa">@endif
        <div data-nav>
            <button type="submit" class="btn">{{ $editing->exists ? 'Guardar cambios' : 'Subir foto' }}</button>
            @if($editing->exists)<a class="btn" data-variant="outline" href="/admin/galeria">Cancelar</a>@endif
        </div>
    </form>
</div>

<div data-card>
    <table>
        <thead><tr><th>Foto</th><th>Pie</th><th>Orden</th><th></th></tr></thead>
        <tbody>
            @forelse($images as $image)
                <tr @if($editing->id === $image->id) data-active="true" @endif>
                    <td><img data-thumb src="{{ $image->image_url }}" alt=""></td>
                    <td>{{ $image->caption ?: '—' }}</td>
                    <td>{{ $image->display_order }}</td>
                    <td data-actions>
                        <a href="/admin/galeria?edit={{ $image->id }}">Editar</a>
                        <form method="POST" action="/admin/galeria/{{ $image->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Todavía no hay fotos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
