<?php

use App\Http\Controllers\AdmindController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {



    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('/', AdmindController::class);
          Route::resource('teams', TeamController::class);
          Route::resource('events',  EventsController::class);
          Route::resource('gallery',  GalleryController::class);

          Route::resource('merchandise',  MerchandiseController::class);
        Route::post('/teams/{team}/switch', [TeamController::class, 'switch'])->name('teams.switch');
        Route::resource('paket', PaketController::class);
         
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
