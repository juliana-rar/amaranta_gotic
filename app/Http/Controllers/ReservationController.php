<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private const FIELDS = [
        'customer_name', 'phone', 'party_size', 'children_count',
        'high_chair_count', 'date', 'period', 'time', 'comments',
    ];

    private function defaults(): array
    {
        return [
            'customer_name' => '',
            'phone' => '',
            'party_size' => 2,
            'children_count' => 0,
            'high_chair_count' => 0,
            'date' => now()->addDay()->toDateString(),
            'period' => 'lunch',
            'time' => '13:00',
            'comments' => '',
        ];
    }

    public function create(Request $request): View
    {
        if ($request->has('restart')) {
            $request->session()->forget(['reserva', 'reserva_step', 'reserva_max_step']);

            return redirect('/reservar');
        }

        return view('reservar', [
            'data' => array_merge($this->defaults(), $request->session()->get('reserva', [])),
            'step' => (int) $request->session()->get('reserva_step', 1),
            'maxStep' => (int) $request->session()->get('reserva_max_step', 1),
            'submitted' => false,
            'reservation' => null,
        ]);
    }

    public function store(Request $request): View|RedirectResponse
    {
        $submitted = collect($request->only(self::FIELDS))
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();

        $data = array_merge(
            $this->defaults(),
            $request->session()->get('reserva', []),
            $submitted
        );
        $maxStep = max(1, (int) $request->session()->get('reserva_max_step', 1));

        if ($request->has('submit')) {
            $validated = validator(array_merge($data, $request->all()), [
                'customer_name' => 'required|string|max:100',
                'phone' => 'nullable|string|max:30',
                'party_size' => 'required|integer|min:1|max:20',
                'children_count' => 'integer|min:0|max:20',
                'high_chair_count' => 'integer|min:0|max:20',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|date_format:H:i',
                'comments' => 'nullable|string|max:500',
            ], [
                'customer_name.required' => 'Indica tu nombre y el número de comensales.',
                'party_size.required' => 'Indica tu nombre y el número de comensales.',
                'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            ])->validate();

            $reservation = Reservation::create([
                'customer_name' => trim($validated['customer_name']),
                'phone' => filled($validated['phone'] ?? null) ? trim($validated['phone']) : null,
                'date' => $validated['date'],
                'time' => $validated['time'],
                'party_size' => $validated['party_size'],
                'children_count' => $validated['children_count'] ?? 0,
                'high_chair_count' => $validated['high_chair_count'] ?? 0,
                'comments' => $validated['comments'] ?? null,
            ]);

            $request->session()->forget(['reserva', 'reserva_step', 'reserva_max_step']);

            return view('reservar', [
                'data' => $data,
                'step' => 4,
                'maxStep' => 4,
                'submitted' => true,
                'reservation' => $reservation,
            ]);
        }

        $step = max(1, min(4, (int) $request->input('step', 1)));

        $rules = match ($step) {
            1 => [
                'customer_name' => 'required|string|max:100',
                'party_size' => 'required|integer|min:1|max:20',
                'children_count' => 'integer|min:0|max:20',
                'high_chair_count' => 'integer|min:0|max:20',
                'phone' => 'nullable|string|max:30',
            ],
            2 => ['date' => 'required|date|after_or_equal:today'],
            3 => ['time' => 'required|date_format:H:i'],
            default => [],
        };

        validator($data, $rules, [
            'customer_name.required' => 'Indica tu nombre y el número de comensales.',
            'party_size.required' => 'Indica tu nombre y el número de comensales.',
            'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
        ])->validate();

        $target = max(1, min($maxStep + 1, (int) $request->input('go', $step + 1)));
        $maxStep = max($maxStep, $target);

        $request->session()->put([
            'reserva' => $data,
            'reserva_step' => $target,
            'reserva_max_step' => $maxStep,
        ]);

        return redirect('/reservar');
    }
}
