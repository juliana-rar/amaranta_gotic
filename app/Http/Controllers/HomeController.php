<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\GalleryImage;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'dishes' => Dish::where('show_on_home', true)->orderBy('display_order')->get(),
            'testimonials' => Testimonial::orderBy('display_order')->get(),
            'gallery' => GalleryImage::orderBy('display_order')->get(),
        ]);
    }
}
