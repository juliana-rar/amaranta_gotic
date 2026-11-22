<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteText;
use App\Support\SiteTextCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TextController extends Controller
{
    public function edit(): View
    {
        return view('admin.texts', [
            'definitions' => SiteTextCatalog::definitions(),
            'groups' => SiteTextCatalog::groups(),
            'values' => SiteText::pluck('value', 'key')->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->input('texts', []);

        foreach (SiteTextCatalog::definitions() as $definition) {
            SiteText::updateOrCreate(
                ['key' => $definition['key']],
                ['value' => (string) ($values[$definition['key']] ?? '')]
            );
        }

        Cache::forget('site.texts');

        return redirect('/admin/textos')->with('saved', 'Cambios guardados.');
    }
}
