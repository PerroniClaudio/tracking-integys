<?php

use App\Http\Controllers\TrackedEventsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [TrackedEventsController::class, 'index'])->middleware(['auth'])->name('home');

Route::group([
    'middleware' => 'auth',
    'prefix' => 'stats',
], function () {
    Route::get('/visits', [TrackedEventsController::class, 'visits'])->name('stats.visits');
    Route::get('/referers', [TrackedEventsController::class, 'referers'])->name('stats.referers');
    Route::get('/most-visited', [TrackedEventsController::class, 'mostVisited'])->name('stats.most-visited');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/webhook.php';
