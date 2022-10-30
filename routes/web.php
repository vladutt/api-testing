<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/testing', \App\Http\Controllers\TestingController::class);
Route::get('/domains', [\App\Http\Controllers\TestingController::class, 'hosts'])->name('hosts');

// todo - post route / maybe an api route
Route::get('/add-endpoints', [\App\Http\Controllers\TestingController::class, 'addEndPoints'])->name('add_end_points');

Route::get('phpinfo', function () {
   phpinfo();
});

Route::get('/search-hosts', [\App\Http\Controllers\TestingController::class, 'searchHosts'])->name('search-hosts');

Route::get('altceva', function() {

    dd(json_encode([
        'status' => ['type' => 'string'],
        'totalResults' => ['type' => 'integer'],
        'articles' => [
            'type' => 'array',
            'collection' => [
                'source' => [
                    'type' => 'array',
                    // 'keys' => 2, - to check the number of keys from object
                    'object' => [
                        'id' => ['type' => 'string'],
                        'name' => ['type' => 'string'],
                    ]
                ],
                'author' => ['type' => 'string'],
                'title' => ['type' => 'string'],
                'description' => ['type' => 'int'],
                'url' => ['type' => 'string|url'],
            ]
        ]
    ]));

});
