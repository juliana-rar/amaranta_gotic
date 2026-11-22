@extends('layouts.auth')

@section('title', 'Crear usuario · Amaranta Gótico')
@section('content')
<h1>Crear usuario</h1>
<p>Solo con código de invitación del equipo de Amaranta Gótico.</p>

@if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/register">
    @csrf
    <x-field name="invite_code" label="Código de invitación" :value="old('invite_code')" />
    <x-field name="username" label="Usuario" :value="old('username')" autocomplete="username" />
    <x-field name="password" label="Contraseña" type="password" autocomplete="new-password" />
    <button type="submit" class="btn">Crear cuenta</button>
</form>
@endsection
