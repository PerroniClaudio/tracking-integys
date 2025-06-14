<?php

use App\Http\Controllers\TrackedEventsController;
use App\Http\Controllers\ContactFormRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [TrackedEventsController::class, 'index'])->middleware(['auth'])->name('home');
Route::get('/website-visits', [TrackedEventsController::class, 'websiteVisits'])->middleware(['auth'])->name('website-visits');
Route::get('/contact-form-requests', [ContactFormRequestController::class, 'index'])->middleware(['auth'])->name('contact-form-requests');
Route::get('/contact-form-request/{id}', [ContactFormRequestController::class, 'show'])->middleware(['auth'])->name('contact-form-request.view');
Route::get('/private-area-users', [TrackedEventsController::class, 'privateAreaUsers'])->middleware(['auth'])->name('private-area-users');
Route::get('/article-visits', [TrackedEventsController::class, 'articleVisits'])->middleware(['auth'])->name('article-visits');
Route::get('/article-visits/{url}', [TrackedEventsController::class, 'articleVisit'])->middleware(['auth'])->name('article-visit.view');
Route::get('/user-activity', [TrackedEventsController::class, 'userActivity'])->middleware(['auth'])->name('user-activity');
Route::get('/user-activity-log/{user_email}', [TrackedEventsController::class, 'userActivityLog'])->middleware(['auth'])->name('user-activity-log');
Route::get('/search-user-activity', [TrackedEventsController::class, 'searchUserActivity'])->middleware(['auth'])->name('search-user-activity');
Route::get('/test', [TrackedEventsController::class, 'test']);

Route::group([
    'middleware' => 'auth',
    'prefix' => 'stats',
], function () {
    Route::get('/visits', [TrackedEventsController::class, 'visits'])->name('stats.visits');
    Route::get('/visits-unique', [TrackedEventsController::class, 'getUniqueUsersDomain'])->name('stats.visits-unique');
    Route::get('/bounce-rate', [TrackedEventsController::class, 'calculateBounceRate'])->name('stats.bounce-rate');
    Route::get('/referers', [TrackedEventsController::class, 'referers'])->name('stats.referers');
    Route::get('/most-visited', [TrackedEventsController::class, 'mostVisited'])->name('stats.most-visited');
    Route::get('/devices', [TrackedEventsController::class, 'devices'])->name('stats.devices');
    Route::get('/provenance', [TrackedEventsController::class, 'provenance'])->name('stats.provenance');
});



require __DIR__ . '/auth.php';
require __DIR__ . '/webhook.php';
