<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.gallery', [
            'images' => GalleryImage::orderBy('display_order')->get(),
            'editing' => $request->filled('edit') ? GalleryImage::find($request->integer('edit')) ?? new GalleryImage : new GalleryImage,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:5120',
            'caption' => 'nullable|string|max:200',
            'display_order' => 'integer',
        ]);

        if (! $request->hasFile('image') && ! $request->filled('id')) {
            return back()->withErrors(['image' => 'Sube una imagen.'])->withInput();
        }

        if ($request->hasFile('image')) {
            $validated['image_url'] = upload_image($request->file('image'), 'gallery');
        }

        if ($request->filled('id') && GalleryImage::find($request->integer('id'))) {
            GalleryImage::find($request->integer('id'))->update($validated);
        } else {
            GalleryImage::create($validated);
        }

        return redirect('/admin/galeria')->with('saved', 'Cambios guardados.');
    }

    public function destroy(GalleryImage $galleryImage): RedirectResponse
    {
        $galleryImage->delete();

        return redirect('/admin/galeria')->with('saved', 'Foto eliminada.');
    }
}
