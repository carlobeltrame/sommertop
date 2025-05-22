<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/{dirSlug}/{file}', [PageController::class, 'download'])->where('file', '.*')->name('download');
Route::get('/{dirSlug}', [PageController::class, 'list'])->name('page');

//require __DIR__.'/settings.php';
//require __DIR__.'/auth.php';
