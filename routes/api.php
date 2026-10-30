<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\DeliveryBoyController;
use App\Http\Controllers\Api\SellerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which    
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Customer App 
// Route::group(['middleware' => 'apipurchasecode', 'prefix' => 'customer'], function () {
Route::group(['prefix' => 'customer'], function () {

    // ---------------- CustomerController --------------- //
    Route::post('registration', [CustomerController::class, 'Registration']);
    Route::post('login', [CustomerController::class, 'Login']); 
    Route::post('get_profile', [CustomerController::class, 'GetProfile']);
    Route::post('update_profile', [CustomerController::class, 'UpdateProfile']);
    Route::post('add_new_address', [CustomerController::class, 'AddNewAddress']);
    Route::post('get_customer_address', [CustomerController::class, 'GetCustomerAddress']);
    Route::post('edit_address', [CustomerController::class, 'EditAddress']);
    Route::post('delete_address', [CustomerController::class, 'DeleteAddress']);
    Route::post('forgot_password', [CustomerController::class, 'ForgotPassword']);

    // ---------------- HomeController ---------------- //
    Route::post('general_setting', [HomeController::class, 'GeneralSetting']);
    Route::post('get_payment_option', [HomeController::class, 'GetPaymentOption']);
    Route::post('get_onboarding_screen', [HomeController::class, 'GetOnboardingScreen']);
    Route::post('get_social_link', [HomeController::class, 'GetSocialLink']);
    Route::post('get_pages', [HomeController::class, 'GetPages']);
    Route::post('get_type', [HomeController::class, 'GetType']);
    Route::post('get_banner', [HomeController::class, 'GetBanner']);
    Route::post('get_section', [HomeController::class, 'GetSection']);
    Route::post('get_section_details', [HomeController::class, 'GetSectionDetails']);
    Route::post('get_cat_with_subcat', [HomeController::class, 'GetCategoryWithSubCategory']);
    Route::post('get_items_by_category', [HomeController::class, 'GetItemsByCategory']);
    Route::post('search_items', [HomeController::class, 'SearchItems']);
    Route::post('add_remove_wishlist', [HomeController::class, 'AddRemoveWishlist']);
    Route::post('get_wishlist', [HomeController::class, 'GetWishlist']);
    Route::post('item_details', [HomeController::class, 'ItemsDetails']);
    Route::post('get_top_rating_items', [HomeController::class, 'GetTopRatingItems']);
    Route::post('get_similar_items', [HomeController::class, 'GetSimilarItems']);
    Route::post('get_offers', [HomeController::class, 'GetOffers']);
    Route::post('get_promocode', [HomeController::class, 'GetPromocodes']);
    Route::post('get_orders_status', [HomeController::class, 'GetOrdersStatus']);
    Route::post('get_customer_orders', [HomeController::class, 'GetCustomerOrders']);
    Route::post('search_orders', [HomeController::class, 'SearchOrders']);
    Route::post('add_to_cart', [HomeController::class, 'AddToCart']);
    Route::post('update_quantity', [HomeController::class, 'UpdateCartsQuantity']);
    Route::post('remove_cart', [HomeController::class, 'RemoveCart']);
    Route::post('get_cart_items', [HomeController::class, 'GetCartsItems']);
    Route::post('apply_promo_code', [HomeController::class, 'ApplyPromocode']);
    Route::post('buy_orders', [HomeController::class, 'BuyOrders']);
    Route::post('add_review', [HomeController::class, 'AddReview']);
    Route::post('get_items_review', [HomeController::class, 'GetItemReviews']);
    Route::post('get_notification', [HomeController::class, 'GetNotifications']);
    Route::post('read_notification', [HomeController::class, 'ReadNotification']);
    // Route::post('view_orders', [HomeController::class, 'ViewOrders']);
    Route::post('download_invoice', [HomeController::class, 'DownloadInvoice']);
    Route::post('image_upload', [HomeController::class, 'ImageUpload']);
    Route::post('track_intrest', [HomeController::class, 'TrackIntrest']);
    Route::post('add_wallet_amount', [HomeController::class, 'AddwalletAmount']);
    Route::post('get_transaction_list', [HomeController::class, 'GetTransactionList']);
    Route::post('add_remove_gst_number', [HomeController::class, 'AddRemoveGSTNumber']);
    Route::post('get_delivery_instructor', [HomeController::class, 'GetDeliveryInstructor']);

    //UnUsed Apis
    // Route::post('get_article', [HomeController::class, 'GetArticle']);
    // Route::post('return_orders_item_cal', [HomeController::class, 'ManageOrdersItems']);
    // Route::post('get_reason', [HomeController::class, 'GetReason']);
    // Route::post('order_cancel', [HomeController::class, 'OrdersCancel']);
    // Route::post('order_return', [HomeController::class, 'OrdersReturn']);
    // Route::post('get_upcoming_past_orders', [HomeController::class, 'GetUpComingWithPastOrders']);
    // Route::post('order_details', [HomeController::class, 'OrdersDetails']);
});

// Delivery Boy App 
// Route::group(['middleware' => 'apipurchasecode', 'prefix' => 'deliveryboy'], function () {
Route::group([ 'prefix' => 'deliveryboy'], function () {
    Route::post('login', [DeliveryBoyController::class, 'login']);
    Route::post('get_profile', [DeliveryBoyController::class, 'GetProfile']);
    Route::post('delete_account', [DeliveryBoyController::class, 'DeleteAccount']);
    Route::post('get_orders_list', [DeliveryBoyController::class, 'GetOrdersList']);
    Route::post('orders_details', [DeliveryBoyController::class, 'OrdersDetails']);
    Route::post('verify_otp', [DeliveryBoyController::class, 'VerifyOTP']);
    Route::post('resend_otp', [DeliveryBoyController::class, 'ResendOTP']);
    Route::post('change_order_status', [DeliveryBoyController::class, 'ChangeOrderStatus']);
    Route::post('get_notification', [DeliveryBoyController::class, 'GetNotifications']);
    Route::post('read_notification', [DeliveryBoyController::class, 'ReadNotification']);
    Route::post('my_progress', [DeliveryBoyController::class, 'MyProgress']);
    Route::post('collect_cash', [DeliveryBoyController::class, 'CollectCash']);
    Route::post('earning_history', [DeliveryBoyController::class, 'EarningHistory']);
    Route::post('withdrawal_history', [DeliveryBoyController::class, 'WithdrawalHistory']);
    Route::post('download_receipt', [DeliveryBoyController::class, 'DownloadReceipt']);
    Route::post('general_setting', [HomeController::class, 'GeneralSetting']);
    Route::post('get_payment_option', [HomeController::class, 'GetPaymentOption']);
    Route::post('get_onboarding_screen', [HomeController::class, 'GetOnboardingScreen']);
    Route::post('get_social_link', [HomeController::class, 'GetSocialLink']);
    Route::post('get_page', [HomeController::class, 'GetPages']);
});

// Seller App 
// Route::group(['middleware' => 'apipurchasecode', 'prefix' => 'seller'], function () {
Route::group(['prefix' => 'seller'], function () {
    Route::post('login', [SellerController::class, 'login']);
    Route::post('get_profile', [SellerController::class, 'GetProfile']);
    Route::post('update_profile', [SellerController::class, 'UpdateProfile']);
    Route::post('delete_account', [SellerController::class, 'DeleteAccount']);
    Route::post('get_brand', [SellerController::class, 'GetBrand']);
    Route::post('image_upload', [SellerController::class, 'ImageUpload']);
    Route::post('get_category', [SellerController::class, 'GetCategory']);
    Route::post('get_attributes_by_category', [SellerController::class, 'GetAttributesByCategory']);
    Route::post('get_promocode', [SellerController::class, 'GetPromocode']);
    Route::post('add_promocode', [SellerController::class, 'AddPromocode']);
    Route::post('edit_promocode', [SellerController::class, 'EditPromocode']);
    Route::post('delete_promocode', [SellerController::class, 'DeletePromocode']);
    Route::post('get_items_list', [SellerController::class, 'GetItemsList']);
    Route::post('search_items', [SellerController::class, 'SearchItems']);
    Route::post('item_details', [HomeController::class, 'ItemsDetails']);
    Route::post('add_product', [SellerController::class, 'AddProduct']);
    Route::post('edit_product', [SellerController::class, 'EditProduct']);
    Route::post('delete_product', [SellerController::class, 'DeleteProduct']);
    Route::post('get_orders_list', [SellerController::class, 'GetOrdersList']);
    Route::post('orders_details', [SellerController::class, 'OrdersDetails']);
    Route::post('change_order_status', [SellerController::class, 'ChangeOrderStatus']);
    Route::post('earning_history', [SellerController::class, 'EarningHistory']);
    Route::post('withdrawal_history', [SellerController::class, 'WithdrawalHistory']);
    Route::post('download_receipt', [SellerController::class, 'DownloadReceipt']);
    Route::post('get_notification', [SellerController::class, 'GetNotifications']);
    Route::post('read_notification', [SellerController::class, 'ReadNotification']);
    Route::post('get_deliveryboy', [SellerController::class, 'GetDeliveryBoy']);
    Route::post('delivery_assignment', [SellerController::class, 'DeliveryAssignments']);
    Route::post('general_setting', [HomeController::class, 'GeneralSetting']);
    Route::post('get_page', [HomeController::class, 'GetPages']);
    Route::post('get_social_link', [HomeController::class, 'GetSocialLink']);
});
