<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function contacto(): View
    {
        return view('contacto');
    }

    public function ubicacion(): View
    {
        return view('ubicacion');
    }

    public function grupos(): View
    {
        return view('grupos');
    }
}
