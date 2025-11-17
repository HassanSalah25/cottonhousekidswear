<?php

use App\Http\Controllers\Frontend\BulkPurchaseController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\CustomerLoginController;
use App\Http\Controllers\Payment\FlutterwavePaymentController;
use App\Http\Controllers\Payment\PaypalPaymentController;
use App\Http\Controllers\Payment\PaystackPaymentController;
use App\Http\Controllers\Payment\PaytmPaymentController;
use App\Http\Controllers\Payment\SSLCommerzPaymentController;
use App\Http\Controllers\Payment\StripePaymentController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BrandController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\ClubPointController;
use App\Http\Controllers\Frontend\ConversationController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\FollowController;
use App\Http\Controllers\Frontend\OfferController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PasswordResetController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\RefundRequestController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\SettingController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\SubscribeController;
use App\Http\Controllers\Frontend\TranslationController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WalletController;
use App\Http\Controllers\Frontend\WishlistController;

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
/**
 * Auth routes
 */
Auth::routes(['verify' => true]);

Route::middleware(['global_variables','maintenance'])->group(function () {

    Route::any('/social-login/redirect/{provider}', [CustomerLoginController::class,'redirectToProvider'])->name('social.login');
    Route::get('/social-login/{provider}/callback', [CustomerLoginController::class,'handleProviderCallback'])->name('social.callback');


    Route::group(['prefix' => 'payment', 'middleware' => 'auth:web'], function(){
        Route::any('/{gateway}/pay', [PaymentController::class,'payment_initialize']);
    });

    Route::group(['prefix' => 'auth'], function () {
        // banned user
        Route::group(['middleware' => 'unbanned'], function () {
            Route::get('login', [CustomerLoginController::class,'showLoginForm'])->name('customer.login');
            Route::POST('login/submit', [CustomerLoginController::class,'login'])->name('loginSubmit');
            Route::get('signup', [CustomerLoginController::class,'showRegistrationForm'])->name('signup');
            Route::post('signup/submit', [CustomerLoginController::class,'signup'])->name('signupSubmit');
            Route::post('verify', [CustomerLoginController::class,'verify']);
            Route::post('resend-code', [CustomerLoginController::class,'resend_code']);

            Route::get('password/create_form', [PasswordResetController::class,'create_form'])->name('password.create_form');
            Route::post('password/create', [PasswordResetController::class,'create'])->name('password.create');
            Route::get('password/reset_form', [PasswordResetController::class,'reset_form'])->name('password.reset_form');
            Route::post('password/reset', [PasswordResetController::class,'reset'])->name('password.reset');
        });
        Route::group(['middleware' => 'auth:web'], function () {
            Route::get('logout', [AuthController::class,'logout']);
            Route::get('user', [AuthController::class,'user']);
        });
    });

    Route::post('temp-id-cart',[AuthController::class, 'tempIdCart']);

    Route::get('locale/{language_code}', [TranslationController::class,'index']);
    Route::get('setting/home/{section}', [SettingController::class,'home_setting']);
    Route::get('setting/footer', [SettingController::class,'footer_setting']);
    Route::get('setting/header', [SettingController::class,'header_setting']);
    Route::post('subscribe', [SubscribeController::class,'subscribe']);

    Route::get('all-categories', [CategoryController::class,'index'])->name('page.all_categories');
    Route::get('all-sub_category/{category_slug}', [CategoryController::class,'index_sub_category'])->name('page.all_sub_category');
    Route::get('category/{category_slug}', [CategoryController::class,'show'])->name('page.category');
    Route::get('all-products', [ProductController::class,'index'])->name('page.all_products');
    Route::get('categories/first-level', [CategoryController::class,'first_level_categories']);
    Route::get('all-brands', [BrandController::class,'index']);
    Route::get('all-offers', [OfferController::class,'index'])->name('page.offers');
    Route::get('offer', [OfferController::class,'show'])->name('page.offer');
    Route::get('contact-us', [ContactUsController::class,'index'])->name('page.contact_us');
    Route::get('contact-us/store', [ContactUsController::class,'store'])->name('page.contact_us.store');
    Route::get('page/{slug}', [PageController::class,'show']);

    // Blogs
    Route::get('all-blog-categories', [BlogController::class,'indexCategory']);
    Route::get('all-blogs/search', [BlogController::class,'index']);
    Route::get('blog/details/{blog_slug}', [BlogController::class,'show']);

    Route::group(['prefix' => 'product'], function () {
        Route::get('/details/{product_slug}', [ProductController::class,'show'])->name('product.details.show');
        Route::post('get-by-ids', [ProductController::class,'get_by_ids']);
        Route::get('search', [ProductController::class,'search'])->name('page.search');
        Route::get('related/{product_id}', [ProductController::class,'related']);
        Route::get('bought-together/{product_id}', [ProductController::class,'bought_together']);
        Route::get('random/{limit}/{product_id?}', [ProductController::class,'random_products']);
        Route::get('latest/{limit}', [ProductController::class,'latest_products']);
        Route::get('reviews/{product_id}', [ReviewController::class,'index']);
    });

    Route::get('search.ajax/{keyword}', [ProductController::class,'ajax_search']);

    Route::get('all-countries', [AddressController::class,'get_all_countries']);
    Route::get('states/{country_id}', [AddressController::class,'get_states_by_country_id']);
    Route::get('cities/{state_id}', [AddressController::class,'get_cities_by_state_id']);


    Route::post('carts/add', [CartController::class,'add']);
    Route::post('carts/change-quantity', [CartController::class,'changeQuantity']);
    Route::get('carts/destroy/{cart_id}', [CartController::class,'destroy'])->name('cart.destroy');

    Route::group(['middleware' => ['auth:web','unbanned']], function () {

        Route::group(['prefix' => 'checkout'], function () {
            Route::get('get-shipping-cost/{address_id}', [OrderController::class,'get_shipping_cost']);
            Route::post('order/store', [OrderController::class,'store'])->name('checkout.order.store');
            Route::post('coupon/apply', [CouponController::class,'apply'])->name('coupon.apply');
            Route::post('bulks', [BulkPurchaseController::class,'store'])->name('checkout.bulks.store');
        });

        Route::group(['prefix' => 'user'], function () {

            Route::get('dashboard', [UserController::class,'dashboard'])->name('profile.dashboard');

            Route::get('chats', [ChatController::class,'index']);
            Route::post('chats/send', [ChatController::class,'send']);
            Route::get('chats/new-messages', [ChatController::class,'new_messages']);

            Route::get('info', [UserController::class,'info']);
            Route::post('info/update', [UserController::class,'updateInfo'])->name('profile.info.update');

            Route::get('carts', [CartController::class,'index'])->name('page.cart');

            Route::get('coupons', [CouponController::class,'index']);

            Route::get('orders', [OrderController::class,'index']);
            Route::get('order/{order_code}', [OrderController::class,'show'])->name('order.show');
            Route::get('order/cancel/{order_id}', [OrderController::class,'cancel']);
            Route::get('order/invoice-download/{order_code}', [OrderController::class,'invoice_download']);

            Route::get('review/check/{product_id}', [ReviewController::class,'check_review_status']);
            Route::post('review/submit', [ReviewController::class,'submit_review']);

            Route::resource('wishlists', WishlistController::class)->except(['update', 'show']);
            Route::resource('follow', FollowController::class)->except(['update', 'show']);

            Route::get('addresses', [AddressController::class,'addresses']);
            Route::post('address/create', [AddressController::class,'createShippingAddress']);
            Route::post('address/update', [AddressController::class,'updateShippingAddress']);
            Route::get('address/delete/{id}', [AddressController::class,'deleteShippingAddress']);
            Route::get('address/default-shipping/{id}', [AddressController::class,'defaultShippingAddress']);
            Route::get('address/default-billing/{id}', [AddressController::class,'defaultBillingAddress']);

            # conversation
            Route::get('querries', [ConversationController::class, 'index']);
            Route::post('new-query', [ConversationController::class, 'store']);
            Route::get('querries/{id}', [ConversationController::class,'show']);
            Route::post('new-message-query', [ConversationController::class, 'storeMessage']);

            # wallet
            Route::post('wallet/recharge', [WalletController::class, 'recharge']);
            Route::get('wallet/history', [WalletController::class, 'walletRechargeHistory']);

            # club points
            Route::get('earning/history', [ClubPointController::class, 'earningRechargeHistory']);
            Route::post('convert-point-into-wallet', [ClubPointController::class, 'convert_point_into_wallet']);

            // Refund Addon
            Route::get('refund-requests', [RefundRequestController::class,'index']);
            Route::get('refund-request/create/{order_id}', [RefundRequestController ::class,'create']);
            Route::post('refund-request/store', [RefundRequestController ::class,'store']);
        });
    });


    //for shops
    Route::post('shop/register', [ShopController::class,'shop_register']);
    Route::get('all-shops', [ShopController::class,'index']);
    Route::get('shop/{slug}', [ShopController::class,'show']);
    Route::get('shop/{slug}/home', [ShopController::class,'shop_home']);
    Route::get('shop/{slug}/coupons', [ShopController::class,'shop_coupons']);
    Route::get('shop/{slug}/products', [ShopController::class,'shop_products']);


/*Route::fallback(function () {
    return redirect()->route('home');
});*/

    Route::get('/', [HomeController::class,'index'])->name('home');
    Route::get('/{slug}', [HomeController::class,'index'])->where('slug','.*');


});

Route::get('/product/{product_slug}', [ProductController::class,'show'])->name('product');
//Route::get('/category/{slug}', [HomeController::class,'index'])->name('products.category');
//
//Route::get('/blog-details/{slug}', [HomeController::class,'index'])->name('blog.details');


//
////Address
Route::resource('addresses', \App\Http\Controllers\AddressController::class);
Route::controller(\App\Http\Controllers\AddressController::class)->group(function () {
    Route::post('/get-states', 'getStates')->name('get-state');
    Route::post('/get-cities', 'getCities')->name('get-city');
    Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
    Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
    Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
});
