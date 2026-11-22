<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.testimonials', [
            'testimonials' => Testimonial::orderBy('display_order')->get(),
            'editing' => $request->filled('edit') ? Testimonial::find($request->integer('edit')) ?? new Testimonial : new Testimonial,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:100',
            'quote' => 'required|string',
            'display_order' => 'integer',
        ], [
            'author_name.required' => 'Indica el nombre del cliente y la opinión.',
            'quote.required' => 'Indica el nombre del cliente y la opinión.',
        ]);

        if ($request->filled('id') && Testimonial::find($request->integer('id'))) {
            Testimonial::find($request->integer('id'))->update($validated);
        } else {
            Testimonial::create($validated);
        }

        return redirect('/admin/testimonios')->with('saved', 'Cambios guardados.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect('/admin/testimonios')->with('saved', 'Opinión eliminada.');
    }
}
