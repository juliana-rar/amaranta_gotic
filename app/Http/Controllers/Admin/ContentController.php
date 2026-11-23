<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.content', [
            'content' => SiteContent::first() ?? new SiteContent,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = SiteContent::firstOrNew(['id' => 1]);
        $content->fill($request->only($content->getFillable()));
        $content->save();

        Cache::forget('site.content');

        return redirect('/admin/contenido')->with('saved', 'Cambios guardados.');
    }
}
