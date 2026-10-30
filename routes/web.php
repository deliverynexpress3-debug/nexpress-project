<?php

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

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Artisan
Route::get('clearcache', function () {

    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "<h1>All Config Cache Clear Successfully.</h1>";
});
// Version
Route::get('version', function () {
    return "<h1>
        <li>PHP : " . phpversion() . "</li>
        <li>Laravel : " . app()->version() . "</li>
    </h1>";
});

// Page
Route::group(['middleware' => 'installation'], function () {
    Route::get('pages/{page_name}', [PageController::class, 'page_view'])->name('page.view');

    Route::get('/lang/{locale}', function ($locale) {

        if (in_array($locale, ['en', 'guj', 'fr'])) {
            session(['locale' => $locale]);
            App::setLocale($locale);
        }
        return redirect()->back();
    })->name('change.language');
});
