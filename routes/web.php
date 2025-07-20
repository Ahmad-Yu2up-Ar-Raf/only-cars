<?php

use App\Http\Controllers\AdmindController;
use App\Http\Controllers\PaketController;
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
        
        // Elections routes
        Route::resource('paket', PaketController::class);
       
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
