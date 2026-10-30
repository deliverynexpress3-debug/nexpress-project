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


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\LoginController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\BrandController;
use App\Http\Controllers\Seller\OffersController;
use App\Http\Controllers\Seller\PromocodeController;
use App\Http\Controllers\Seller\HashtagController;
use App\Http\Controllers\Seller\TaxController;
use App\Http\Controllers\Seller\StockStatusController;
use App\Http\Controllers\Seller\AttributesController;
use App\Http\Controllers\Seller\ItemsController;
use App\Http\Controllers\Seller\OrderReturnRequestController;
use App\Http\Controllers\Seller\OrderCanelRequestController;
use App\Http\Controllers\Seller\OrdersController;
use App\Http\Controllers\Seller\ReviewController;
use App\Http\Controllers\Seller\WalletController;
use App\Http\Controllers\Seller\SalesReportController;

Route::group(['middleware' => 'installation'], function () {

    // Login-Logout
    Route::get('login', [LoginController::class, 'login'])->name('seller.login');
    Route::post('login', [LoginController::class, 'save_login'])->name('seller.save.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('seller.logout');


    Route::group(['middleware' => 'authseller'], function () {

        Route::name('seller.')->group(function () {
            // Dashboard
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('profile', ProfileController::class)->only(['index', 'update']);
            Route::post('profile/changepassword', [ProfileController::class, 'ChangePassword'])->name('profile.changepassword');
            Route::resource('category', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update','show']);
            Route::get('category/subcategory-list/{id}', [CategoryController::class, 'subcategory_list'])->name('subcategory_list');
            Route::get('category/{id}/amenity-data', [CategoryController::class, 'amenity_data_index'])->name('amenity_index');
            Route::post('category/amenity/save', [CategoryController::class, 'save_amenity_data'])->name('save_amenity_data');
            Route::post('category/sortable/save', [CategoryController::class, 'CategorySortableSave'])->name('category.sortable.save');
            Route::get('category/child/status/{id}', [CategoryController::class, 'ChildChangeStatus'])->name('category.child.status');

            Route::resource('brand', BrandController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
            Route::resource('item', ItemsController::class)->only(['index', 'create', 'store', 'edit', 'update']);
            Route::any('item/details/{id}', [ItemsController::class, 'Details'])->name('item.detail');
            Route::any('item/attributes', [ItemsController::class, 'GetAttributes'])->name('item.attributes');
            Route::post('item/change-status/{id}', [ItemsController::class, 'ChangeStatus'])->name('item.change_status');
            Route::resource('hashtag', HashtagController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('tax', TaxController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('attributes', AttributesController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
            Route::get('category/{id}/attributes-index', [CategoryController::class, 'AttributesIndex'])->name('category.attributes.index');
            Route::resource('stockstatus', StockStatusController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('promocode', PromocodeController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('offers', OffersController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('return-request', OrderReturnRequestController::class)->only(['index', 'show']);
            Route::resource('cancel-request', OrderCanelRequestController::class)->only(['index', 'show']);
            Route::resource('order', OrdersController::class)->only(['index', 'edit']);
            Route::any('match-delivery-boy-list', [OrdersController::class, 'DeliveryBoyList'])->name('list.match.deliveryboy');
            Route::resource('deliveryboy-list', OrdersController::class)->only(['index', 'edit']);
            Route::any('order/assignment/{id}', [OrdersController::class, 'OrderAssignment'])->name('order.assignment');
            Route::any('order/view-order/{id}', [OrdersController::class, 'ViewOrder'])->name('order.view');
            Route::any('order/change-status/{id}', [OrdersController::class, 'OrderChangeStatus'])->name('order.change.status');
            Route::resource('review', ReviewController::class)->only(['index', 'show']);
            Route::resource('wallet', WalletController::class)->only(['index', 'store']);
            Route::resource('sales-report', SalesReportController::class)->only(['index']);
        });

        Route::group(['middleware' => 'checkadmin'], function () {
            Route::name('seller.')->group(function () {

                Route::resource('category', CategoryController::class)->only(['destroy']);
                Route::resource('brand', BrandController::class)->only(['destroy']);
                Route::resource('item', ItemsController::class)->only(['show']);
                Route::resource('attributes', AttributesController::class)->only(['destroy']);
                Route::resource('hashtag', HashtagController::class)->only(['destroy']);
                Route::resource('tax', TaxController::class)->only(['destroy']);
                Route::resource('stockstatus', StockStatusController::class)->only(['destroy']);
                Route::resource('promocode', PromocodeController::class)->only(['destroy']);
                Route::resource('offers', OffersController::class)->only(['destroy']);
            });
        });
    });
});
