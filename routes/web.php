<?php

use App\Http\Controllers\AdmindController;
use App\Http\Controllers\EventPageController;
use App\Http\Controllers\EventsController;

use App\Http\Controllers\GalleryController;

use App\Http\Controllers\GalleryPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MerchandiseController;

use App\Http\Controllers\MerchandisePageController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

    Route::get('/', [HomeController::class, 'index']);
    Route::get('/events', [EventPageController::class, 'index']);
    Route::get('/merchandise', [MerchandisePageController::class, 'index']);
    Route::get('/gallery', [GalleryPageController::class, 'index']);

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {



    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('/', AdmindController::class);
          Route::resource('teams', TeamController::class);
          Route::resource('events',  EventsController::class);
          Route::resource('gallery',  GalleryController::class);
          Route::post('/events/{events}/status', [EventsController::class, 'statusUpdate'])->name('events.status');
          
          Route::resource('merchandise',  MerchandiseController::class);
          Route::post('/merchandise/{merchandise}/status', [MerchandiseController::class, 'statusUpdate'])->name('merchandise.status');
          Route::post('/gallery/{gallery}/status', [GalleryController::class, 'statusUpdate'])->name('gallery.status');
        Route::post('/teams/{team}/switch', [TeamController::class, 'switch'])->name('teams.switch');
        Route::resource('paket', PaketController::class);
         
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
