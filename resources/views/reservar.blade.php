@extends('layouts.public')

@section('title', 'Reservar mesa · Amaranta Gótico')
@section('page', 'reservar')

@section('content')
<section data-reserve>
    <div class="container">
        <div data-head>
            <span class="eyebrow">{{ t('nav.reserve') }}</span>
            <h1>{{ t('reservar.title') }}</h1>
            <p>{{ t('reservar.intro') }}</p>
        </div>

        <div class="resv" data-wizard="{{ $step }}" @if(! $submitted) data-period="{{ $data['period'] }}" @endif>
            @if($submitted)
                <div data-success>
                    <div>✓</div>
                    <h2>Solicitud recibida</h2>
                    <p>{{ str_replace(
                        ['{name}', '{date}', '{time}'],
                        [$reservation->customer_name, $reservation->date->format('d/m/Y'), substr((string) $reservation->time, 0, 5)],
                        t('reservar.success')
                    ) }}</p>
                    <a class="btn" href="/reservar?restart=1">Hacer otra reserva</a>
                </div>
            @else
                <form method="POST" action="/reservar">
                    @csrf
                    <input type="hidden" name="step" value="{{ $step }}">

                    @foreach(['customer_name', 'phone', 'party_size', 'children_count', 'high_chair_count', 'date', 'period', 'time', 'comments'] as $field)
                        <input type="hidden" name="{{ $field }}" value="{{ old($field, $data[$field]) }}">
                    @endforeach

                    <div data-steps>
                        @foreach(range(1, 4) as $n)
                            <button type="submit" name="go" value="{{ $n }}" @if($n > $maxStep) disabled @endif>
                                <span>{{ $n < $step ? '✓' : $n }}</span>
                                <span>{{ t("reservar.step{$n}") }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div data-panels>
                        <div data-panel>
                            <h2>¿Quién viene?</h2>
                            <div data-field>
                                <label for="customer_name">Nombre y apellidos</label>
                                <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', $data['customer_name']) }}" placeholder="Tu nombre" autocomplete="name">
                            </div>
                            <div data-field>
                                <label for="phone">Teléfono</label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone', $data['phone']) }}" placeholder="600 000 000" autocomplete="tel">
                            </div>
                            <div data-field-group>
                                <div data-field>
                                    <label for="party_size">Comensales</label>
                                    <input id="party_size" type="number" min="1" max="20" name="party_size" value="{{ old('party_size', $data['party_size']) }}">
                                </div>
                                <div data-field>
                                    <label for="children_count">Niños</label>
                                    <input id="children_count" type="number" min="0" max="20" name="children_count" value="{{ old('children_count', $data['children_count']) }}">
                                </div>
                                <div data-field>
                                    <label for="high_chair_count">Sillas altas</label>
                                    <input id="high_chair_count" type="number" min="0" max="20" name="high_chair_count" value="{{ old('high_chair_count', $data['high_chair_count']) }}">
                                </div>
                            </div>
                        </div>

                        <div data-panel>
                            <h2>¿Qué día?</h2>
                            <div data-field>
                                <label for="date">Fecha</label>
                                <input id="date" type="date" name="date" value="{{ old('date', $data['date']) }}" min="{{ now()->toDateString() }}">
                            </div>
                            <div data-field>
                                <label>Comensales</label>
                                <div data-static>{{ $data['party_size'] }} personas @if($data['children_count'] > 0) · {{ $data['children_count'] }} niños @endif</div>
                            </div>
                        </div>

                        <div data-panel>
                            <h2>¿A qué hora?</h2>
                            <div data-picker>
                                <label>
                                    <input type="radio" name="period" value="lunch" @if(old('period', $data['period']) === 'lunch') checked @endif>
                                    <span>☀️ Comida</span>
                                    <small>13:00 – 16:00</small>
                                </label>
                                <label>
                                    <input type="radio" name="period" value="dinner" @if(old('period', $data['period']) === 'dinner') checked @endif>
                                    <span>🌙 Cena</span>
                                    <small>20:00 – 23:00</small>
                                </label>
                            </div>
                            @foreach(['lunch' => 'Comida', 'dinner' => 'Cena'] as $key => $label)
                                <div data-slots="{{ $key }}">
                                    <h3>{{ $label }}</h3>
                                    <div>
                                        @foreach(reservation_slots($key) as $slot)
                                            <label>
                                                <input type="radio" name="time" value="{{ $slot }}" @if(old('time', $data['time']) === $slot) checked @endif>
                                                <span>{{ $slot }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div data-panel>
                            <h2>Confirma tu reserva</h2>
                            <div data-summary>
                                <div><span>Nombre</span><strong>{{ $data['customer_name'] }}</strong></div>
                                <div><span>Teléfono</span><strong>{{ $data['phone'] ?: '—' }}</strong></div>
                                <div><span>Comensales</span><strong>{{ $data['party_size'] }}</strong></div>
                                <div><span>Fecha</span><strong>{{ \Illuminate\Support\Carbon::parse($data['date'])->format('d/m/Y') }}</strong></div>
                                <div><span>Hora</span><strong>{{ $data['time'] }} · {{ $data['period'] === 'dinner' ? 'cena' : 'comida' }}</strong></div>
                            </div>
                            <div data-field>
                                <label for="comments">Algo que debamos saber</label>
                                <textarea id="comments" name="comments" rows="3" placeholder="Celebración, alergias, silla alta…">{{ old('comments', $data['comments']) }}</textarea>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="error">{{ $errors->first() }}</div>
                    @endif

                    <div data-nav>
                        @if($step > 1)
                            <button type="submit" class="btn" data-variant="outline" name="go" value="{{ $step - 1 }}">← Atrás</button>
                        @endif
                        @if($step < 4)
                            <button type="submit" class="btn" name="go" value="{{ $step + 1 }}">Siguiente →</button>
                        @else
                            <button type="submit" class="btn" name="submit" value="1">Solicitar reserva</button>
                        @endif
                    </div>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
