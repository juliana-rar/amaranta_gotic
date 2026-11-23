<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use App\Models\SiteContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HourController extends Controller
{
    public function edit(): View
    {
        return view('admin.hours', [
            'content' => SiteContent::first() ?? new SiteContent,
            'hours' => BusinessHour::orderBy('day_order')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = SiteContent::firstOrNew(['id' => 1]);
        $content->location_hours = $request->input('location_hours');
        $content->save();

        foreach (range(0, 6) as $day) {
            $hour = BusinessHour::firstOrNew(['day_order' => $day]);
            $isOpen = $request->boolean("hours.$day.is_open");
            $hasShift2 = $request->boolean("hours.$day.has_shift2");

            $hour->update([
                'is_open' => $isOpen,
                'shift1_start' => $isOpen ? $request->input("hours.$day.shift1_start") : null,
                'shift1_end' => $isOpen ? $request->input("hours.$day.shift1_end") : null,
                'shift2_start' => $isOpen && $hasShift2 ? $request->input("hours.$day.shift2_start") : null,
                'shift2_end' => $isOpen && $hasShift2 ? $request->input("hours.$day.shift2_end") : null,
            ]);
        }

        Cache::forget('site.content');
        Cache::forget('site.hours');

        return redirect('/admin/horarios')->with('saved', 'Cambios guardados.');
    }
}
