@extends('layouts.admin')

@section('content')
<div>
    <h1>{{ t('admin.nav.horarios') }}</h1>
    <p>Horario resumido y turnos de apertura de cada día.</p>
</div>

@if(session('saved'))<div class="notice">{{ session('saved') }}</div>@endif

<form method="POST" action="/admin/horarios" data-card>
    @csrf
    <fieldset>
        <legend>Horario resumido</legend>
        <div data-field-grid>
            <x-field name="location_hours" label="Texto breve del horario" :value="old('location_hours', $content->location_hours)" />
        </div>
    </fieldset>

    <fieldset>
        <legend>Horarios por día</legend>
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

    <div data-nav><button type="submit" class="btn">Guardar horarios</button></div>
</form>
@endsection
