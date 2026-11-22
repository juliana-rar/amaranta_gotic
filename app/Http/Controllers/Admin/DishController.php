<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.dishes', [
            'dishes' => Dish::orderBy('display_order')->get(),
            'editing' => $request->filled('edit') ? Dish::find($request->integer('edit')) ?? new Dish : new Dish,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price_text' => 'nullable|string|max:40',
            'display_order' => 'integer',
            'show_on_home' => 'boolean',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:5120',
        ], ['title.required' => 'Indica el título del plato.']);

        if ($request->hasFile('image')) {
            $validated['image_url'] = upload_image($request->file('image'), 'dishes');
        }

        $validated['show_on_home'] = $request->boolean('show_on_home');

        if ($request->filled('id') && Dish::find($request->integer('id'))) {
            Dish::find($request->integer('id'))->update($validated);
        } else {
            Dish::create($validated);
        }

        return redirect('/admin')->with('saved', 'Cambios guardados.');
    }

    public function destroy(Dish $dish): RedirectResponse
    {
        $dish->delete();

        return redirect('/admin')->with('saved', 'Plato eliminado.');
    }
}
