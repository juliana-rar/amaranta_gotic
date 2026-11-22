@extends('layouts.auth')

@section('title', 'Entrar · Amaranta Gótico')
@section('content')
<h1>Entrar al panel</h1>
<p>Gestiona la carta, las fotos y los textos del local.</p>

@if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="/login">
    @csrf
    <x-field name="username" label="Usuario" :value="old('username')" autocomplete="username" />
    <x-field name="password" label="Contraseña" type="password" autocomplete="current-password" />
    <button type="submit" class="btn">Entrar</button>
</form>
@endsection
