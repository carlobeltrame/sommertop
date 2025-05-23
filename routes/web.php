<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/f5', [PageController::class, 'clearCache'])->name('clearCache');

Route::get('/{dirSlug}/{file}', [PageController::class, 'download'])->where('file', '.*')->name('download');
Route::get('/{dirSlug}', [PageController::class, 'list'])->name('page');

//require __DIR__.'/settings.php';
//require __DIR__.'/auth.php';
