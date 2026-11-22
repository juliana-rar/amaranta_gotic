<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = Dish::orderBy('display_order')
            ->get()
            ->groupBy(fn (Dish $dish) => mb_strtolower($dish->category));

        return view('menu', ['categories' => $categories]);
    }
}
