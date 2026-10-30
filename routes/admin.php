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
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\BecomeSellerController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\ItemsRequestController;
use App\Http\Controllers\Admin\HashtagController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\StockStatusController;
use App\Http\Controllers\Admin\AttributesController;
use App\Http\Controllers\Admin\OffersController;
use App\Http\Controllers\Admin\PromocodeController;
use App\Http\Controllers\Admin\ReasonController;
use App\Http\Controllers\Admin\DeliveryInstructorController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\DeliveryAssignmentController;
use App\Http\Controllers\Admin\OrderReturnRequestController;
use App\Http\Controllers\Admin\OrderCanelRequestController;
use App\Http\Controllers\Admin\CompensationController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ZipCodeController;
use App\Http\Controllers\Admin\ZonesController;
use App\Http\Controllers\Admin\DeliveryBoyRequestController;
use App\Http\Controllers\Admin\DeliveryBoyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\DailyNotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PanelSettingController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\NotificationIntegrationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AdmobSettingController;
use App\Http\Controllers\Admin\FaceBookAdsSettingController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\ItemsSalesReportController;
use App\Http\Controllers\Admin\ReviewsController;


Route::group(['middleware' => 'installation'], function () {

    // Login-Logout
    Route::get('login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('login', [LoginController::class, 'save_login'])->name('admin.save.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::group(['middleware' => 'authadmin'], function () {

        Route::name('admin.')->group(function () {
            Route::post('video/saveChunk', [ItemsController::class, 'saveChunk']);
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('sales-reports', [DashboardController::class, 'SalesReport'])->name('sales.report');
            Route::get('seller-reports', [DashboardController::class, 'SellerReport'])->name('report.sellers');
            Route::any('sales-item-reports/{id}', [DashboardController::class, 'topSellingItems'])->name('sales.report.top_items');
            Route::any('sales-least-item-reports/item/{id}', [DashboardController::class, 'leastSellingItems'])->name('sales.report.least_items');
            Route::get('earning-reports', [DashboardController::class, 'EarningReport'])->name('earning.report');
            Route::resource('profile', ProfileController::class)->only(['index', 'store']);
            Route::post('profile/changepassword', [ProfileController::class, 'ChangePassword'])->name('profile.changepassword');
            Route::resource('type', TypeController::class)->only(['index', 'store', 'update', 'show']);
            Route::post('type/sortable/save', [TypeController::class, 'TypeSortableSave'])->name('type.sortable.save');
            Route::resource('category', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
            Route::get('category/subcategory-list/{id}', [CategoryController::class, 'subcategory_list'])->name('subcategory_list');
            Route::get('category/{id}/amenity-data', [CategoryController::class, 'amenity_data_index'])->name('amenity_index');
            Route::post('category/sortable/save', [CategoryController::class, 'CategorySortableSave'])->name('category.sortable.save');
            Route::get('category/child/status/{id}', [CategoryController::class, 'ChildChangeStatus'])->name('category.child.status');
            Route::resource('brand', BrandController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
            Route::resource('article', ArticleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
            Route::resource('customer', CustomerController::class)->only(['index', 'create', 'store', 'edit', 'update']);
            Route::get('customer/address/{id}', [CustomerController::class, 'AddressIndex'])->name('customer.address.index');
            Route::post('customer/address/store', [CustomerController::class, 'AddressStore'])->name('customer.address.store');
            Route::post('customer/address/update', [CustomerController::class, 'AddressUpdate'])->name('customer.address.update');
            Route::post('customer/address/status/{id}', [CustomerController::class, 'AddressStatus'])->name('customer.address.status');
            Route::resource('seller', SellerController::class)->only(['index', 'create', 'store', 'edit', 'update']);
            Route::post('seller/change-status/{id}', [SellerController::class, 'ChangeStatus'])->name('seller.change_status');
            Route::get('seller/profile/{id}', [SellerController::class, 'SellerProfile'])->name('seller.profile');
            Route::get('seller/product-show/{id}', [SellerController::class, 'ProductStatusChange'])->name('seller.product.show');
            Route::get('seller/product-destroy/{id}', [SellerController::class, 'ProductDestroy'])->name('seller.product.destroy');
            Route::get('seller/order-list/{id}', [SellerController::class, 'OrderList'])->name('seller.order.list');
            Route::get('seller/review/{id}', [SellerController::class, 'ReviewList'])->name('seller.review');
            Route::get('seller/review-show/{id}', [SellerController::class, 'ReviewStatusChange'])->name('seller.review_change_status');
            Route::resource('become-seller', BecomeSellerController::class)->only(['index', 'show']);
            Route::resource('slider', SliderController::class)->only(['index', 'store']);
            Route::any('slider/type-based', [SliderController::class, 'GetTypeBased'])->name('slider.typebased');
            Route::any('slider/banner-list', [SliderController::class, 'BannerList'])->name('slider.bannerlist');
            Route::resource('section', SectionController::class)->only(['index', 'store', 'update', 'show']);
            Route::post('section/data', [SectionController::class, 'GetSectionData'])->name('section.content.data');
            Route::post('section/edit', [SectionController::class, 'SectionDataEdit'])->name('section.content.edit');
            Route::post('section/sortable', [SectionController::class, 'SectionSortable'])->name('section.content.sortable');
            Route::post('section/sortable/save', [SectionController::class, 'SectionSortableSave'])->name('section.content.sortable.save');
            Route::any('section/change-status/{id}', [SectionController::class, 'ChangeStatus'])->name('section.change_status');
            Route::post('section/content', [SectionController::class, 'get_content'])->name('section.content');
            Route::post('section/category', [SectionController::class, 'get_main_category'])->name('section.main.category');
            Route::resource('hashtag', HashtagController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('tax', TaxController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('attributes', AttributesController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
            Route::get('category/{id}/attributes-index', [CategoryController::class, 'AttributesIndex'])->name('category.attributes.index');
            Route::resource('request-item', ItemsRequestController::class)->only(['index', 'show', 'edit']);
            Route::resource('item', ItemsController::class)->only(['index', 'create', 'store', 'edit', 'update']);
            Route::any('item/details/{id}', [ItemsController::class, 'Details'])->name('item.detail');
            Route::any('item/attributes', [ItemsController::class, 'GetAttributes'])->name('item.attributes');
            Route::post('item/change-status/{id}', [ItemsController::class, 'ChangeStatus'])->name('item.change_status');
            Route::resource('offers', OffersController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('promocode', PromocodeController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('reason', ReasonController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('deliveryinstructor', DeliveryInstructorController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('order', OrdersController::class)->only(['index', 'edit']);
            Route::any('order/assignment/{id}', [OrdersController::class, 'OrderAssignment'])->name('order.assignment');
            Route::any('order/view-order/{id}', [OrdersController::class, 'ViewOrder'])->name('order.view');
            Route::any('order/change-status/{id}', [OrdersController::class, 'OrderChangeStatus'])->name('order.change.status');
            Route::any('order/download-invoice/{id}', [OrdersController::class, 'DownloadInvoice'])->name('order.download.invoice');
            Route::resource('delivery-assignment', DeliveryAssignmentController::class)->only(['index', 'show']);
            Route::any('match-delivery-boy-list', [OrdersController::class, 'DeliveryBoyList'])->name('list.match.deliveryboy');
            Route::resource('deliveryboy', DeliveryBoyController::class)->only(['index', 'create', 'store', 'edit', 'show', 'update']);
            Route::any('deliveryboy/change-status/{id}', [DeliveryBoyController::class, 'change_status'])->name('deliveryboy.change_status');
            Route::resource('request-deliveryboy', DeliveryBoyRequestController::class)->only(['index', 'show']);
            Route::any('request-deliveryboy-approved/{id}', [DeliveryBoyRequestController::class, 'ApprovedRequest'])->name('request-deliveryboy.approved');
            Route::resource('deliveryboy-list', OrdersController::class)->only(['index', 'edit']);
            Route::resource('notification', NotificationController::class)->only(['index', 'create', 'store']);
            Route::resource('daily_notification', DailyNotificationController::class)->only(['index', 'update']);
            Route::resource('payment', PaymentController::class)->only(['index', 'show']);
            Route::resource('withdrawal', WithdrawalController::class)->only(['index']);
            Route::resource('settlement', SettlementController::class)->only(['index']);
            Route::resource('sales-report-item', ItemsSalesReportController::class)->only(['index']);
            Route::resource('reviews', ReviewsController::class)->only(['index', 'show']);
            Route::get('appsetting', [AppSettingController::class, 'index'])->name('appsetting.index');
            Route::post('appsetting/app', [AppSettingController::class, 'app'])->name('appsetting.app');
            Route::post('appsetting/currency', [AppSettingController::class, 'currency'])->name('appsetting.currency');
            Route::post('appsetting/google_map_key', [AppSettingController::class, 'google_map_key'])->name('appsetting.google_map_key');
            Route::post('appsetting/smtp', [AppSettingController::class, 'smtp'])->name('appsetting.smtp');
            Route::post('appsetting/emailtest', [AppSettingController::class, 'emailtest'])->name('appsetting.emailtest');
            Route::post('appsetting/sociallink', [AppSettingController::class, 'sociallink'])->name('appsetting.sociallink');
            Route::post('appsetting/onboardingscreen', [AppSettingController::class, 'onboardingscreen'])->name('appsetting.onboardingscreen');
            Route::post('appsetting/financial', [AppSettingController::class, 'financial'])->name('appsetting.financial');
            Route::post('appsetting/delivery', [AppSettingController::class, 'delivery_setting'])->name('appsetting.delivery');
            Route::resource('admob', AdmobSettingController::class)->only(['index']);
            Route::post('admob/status', [AdmobSettingController::class, 'admobStatus'])->name('admob.status');
            Route::post('admob/android', [AdmobSettingController::class, 'admobAndroid'])->name('admob.android');
            Route::post('admob/ios', [AdmobSettingController::class, 'admobIos'])->name('admob.ios');
            Route::resource('fbads', FaceBookAdsSettingController::class)->only(['index']);
            Route::post('fbads/status', [FaceBookAdsSettingController::class, 'facebookadStatus'])->name('fbads.status');
            Route::post('fbads/android', [FaceBookAdsSettingController::class, 'facebookadAndroid'])->name('fbads.android');
            Route::post('fbads/ios', [FaceBookAdsSettingController::class, 'facebookadIos'])->name('fbads.ios');
            Route::resource('pages', PageController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
            Route::post('pages/layout', [PageController::class, 'layout'])->name('pages.layout');
            Route::get('panelsetting', [PanelSettingController::class, 'index'])->name('panelsetting.index');
            Route::post('panelsetting/save', [PanelSettingController::class, 'save'])->name('panelsetting.save');
            Route::resource('permission', PermissionController::class)->only(['index', 'store']);
            Route::get('systemsetting', [SystemSettingController::class, 'index'])->name('system.setting.index');
            Route::post('systemsetting/cleardata', [SystemSettingController::class, 'ClearData'])->name('system.setting.cleardata');
            Route::post('systemsetting/cleandatabase', [SystemSettingController::class, 'CleanDatabase'])->name('system.setting.cleandatabase');
            Route::resource('sales-report', SalesReportController::class)->only(['index']);
            Route::resource('integration-notification', NotificationIntegrationController::class);
            Route::post('integration-notification/save', [NotificationIntegrationController::class, 'IntegrationSave'])->name('integration-notification.save');

            // Route::resource('zones', ZonesController::class)->only(['index', 'create', 'store', 'edit', 'show', 'update']);
            // Route::resource('city', CityController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            // Route::resource('zipcode', ZipCodeController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            // Route::resource('compensation', CompensationController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            // Route::resource('vehicle', VehicleController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            Route::resource('stockstatus', StockStatusController::class)->only(['index', 'store', 'edit', 'show', 'update']);
            // Route::resource('return-request', OrderReturnRequestController::class)->only(['index', 'show']);
            // Route::resource('cancel-request', OrderCanelRequestController::class)->only(['index', 'show']);
        });

        Route::group(['middleware' => 'checkadmin'], function () {
            Route::name('admin.')->group(function () {
                // Type
                Route::resource('type', TypeController::class)->only(['destroy']);
                Route::resource('category', CategoryController::class)->only(['destroy']);
                Route::resource('brand', BrandController::class)->only(['destroy']);
                Route::resource('article', ArticleController::class)->only(['destroy']);
                Route::resource('customer', CustomerController::class)->only(['destroy']);
                Route::post('customer/address/delete/{user_id}/{id}', [CustomerController::class, 'AddressDelete'])->name('customer.address.delete');
                Route::resource('seller', SellerController::class)->only(['show']);
                Route::resource('item', ItemsController::class)->only(['show']);
                Route::resource('attributes', AttributesController::class)->only(['destroy']);
                Route::resource('hashtag', HashtagController::class)->only(['destroy']);
                Route::resource('tax', TaxController::class)->only(['destroy']);
                Route::resource('zones', ZonesController::class)->only(['destroy']);
                Route::resource('deliveryboy', DeliveryBoyController::class)->only(['destroy']);
                Route::resource('compensation', CompensationController::class)->only(['destroy']);
                Route::resource('city', CityController::class)->only(['destroy']);
                Route::resource('zipcode', ZipCodeController::class)->only(['destroy']);
                Route::resource('vehicle', VehicleController::class)->only(['destroy']);
                Route::resource('stockstatus', StockStatusController::class)->only(['destroy']);
                Route::resource('promocode', PromocodeController::class)->only(['destroy']);
                Route::resource('offers', OffersController::class)->only(['destroy']);
                Route::resource('reason', ReasonController::class)->only(['destroy']);
                Route::resource('deliveryinstructor', DeliveryInstructorController::class)->only(['destroy']);
                Route::resource('slider', SliderController::class)->only(['destroy']);
                Route::resource('section', SectionController::class)->only(['destroy']);
                Route::resource('reviews', ReviewsController::class)->only(['destroy']);
                Route::resource('pages', PageController::class)->only(['destroy']);
                Route::resource('payment', PaymentController::class)->only(['edit', 'update']);
                Route::resource('notification', NotificationController::class)->only(['destroy']);
                Route::get('notifications/setting', [NotificationController::class, 'setting'])->name('notification.setting');
                Route::post('notifications/setting', [NotificationController::class, 'settingsave'])->name('notification.settingsave');
                Route::get('systemsetting/downloadsqlfile', [SystemSettingController::class, 'DownloadSqlFile'])->name('system.setting.downloadsqlfile');
            });
        });
    });
});
