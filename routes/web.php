<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HourController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TextController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/media/{file}', function (string $file) {
    abort_unless(preg_match('/^[A-Za-z0-9._-]+$/', $file), 404);
    abort_unless(in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif'], true), 404);
    $path = base_path('media' . DIRECTORY_SEPARATOR . $file);
    abort_unless(is_file($path), 404);

    return response()->file($path, ['Cache-Control' => 'public, max-age=86400']);
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/grupos', [PageController::class, 'grupos'])->name('grupos');
Route::get('/ubicacion', [PageController::class, 'ubicacion'])->name('ubicacion');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');

Route::get('/reservar', [ReservationController::class, 'create'])->name('reservar');
Route::post('/reservar', [ReservationController::class, 'store'])->name('reservar.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DishController::class, 'index'])->name('admin.dishes');
    Route::post('/platos', [DishController::class, 'store'])->name('admin.dishes.store');
    Route::delete('/platos/{dish}', [DishController::class, 'destroy'])->name('admin.dishes.destroy');

    Route::get('/galeria', [GalleryController::class, 'index'])->name('admin.gallery');
    Route::post('/galeria', [GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::delete('/galeria/{galleryImage}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

    Route::get('/testimonios', [TestimonialController::class, 'index'])->name('admin.testimonials');
    Route::post('/testimonios', [TestimonialController::class, 'store'])->name('admin.testimonials.store');
    Route::delete('/testimonios/{testimonial}', [TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');

    Route::get('/horarios', [HourController::class, 'edit'])->name('admin.hours');
    Route::post('/horarios', [HourController::class, 'update'])->name('admin.hours.update');

    Route::get('/contenido', [ContentController::class, 'edit'])->name('admin.content');
    Route::post('/contenido', [ContentController::class, 'update'])->name('admin.content.update');

    Route::get('/textos', [TextController::class, 'edit'])->name('admin.texts');
    Route::post('/textos', [TextController::class, 'update'])->name('admin.texts.update');
});
