<?php

namespace App\Http\Middleware;

use App\Http\Resources\ProductCollection;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Offer;
use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

// use App\Customization;

/**
 * The purpose of this middleware is to share the settings record of
 * settings table to all controllers and views.
 *
 * This middleware should always execute after Installed middleware.
 *
 * Class GlobalVariablesMiddleware
 * @package App\Http\Middleware
 */
class GlobalVariablesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /**
         * Start initial global variables for all controller and views
         */
        // share site_global_settings to all controllers

        App::singleton('settings', function () {

            return [
                'appName' => config('app.name'),
                'appLogo' => api_asset(get_setting('header_logo')) ,
                'demoMode' => env('DEMO_MODE') == "On" ? true : false,
                'cacheVersion' => get_setting('force_cache_clear_version'),
                'appLanguage' => env('DEFAULT_LANGUAGE'),
                'allLanguages' => Language::where('status', 1)->get(['name', 'code', 'flag', 'rtl']),
                // 'allCurrencies' => Currency::all(),
                'availableCountries' => Country::where('status', 1)->pluck('code')->toArray(),
                'all_categories' => Category::where('level', 0)->orderBy('order_level', 'desc')->get(),
                'shop_registration_message' => [
                    'shop_registration_message_title' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags(get_setting('shop_registration_message_title')))),
                    'shop_registration_message_content' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags(get_setting('shop_registration_message_content')))),
                ],
                'paymentMethods' => [
                    [
                        'status' => get_setting('paytabs_payment'),
                        'code' => 'paytabs',
                        'name' => 'paytabs',
                        'img' => static_asset("assets/img/cards/paytabs.png")
                    ],
                    [
                        'status' => get_setting('paypal_payment'),
                        'code' => 'paypal',
                        'name' => 'Paypal',
                        'img' => static_asset("assets/img/cards/paypal.png")
                    ],
                    [
                        'status' => get_setting('stripe_payment'),
                        'code' => 'stripe',
                        'name' => 'Stripe',
                        'img' => static_asset("assets/img/cards/stripe.png")
                    ],
                    [
                        'status' => get_setting('sslcommerz_payment'),
                        'code' => 'sslcommerz',
                        'name' => 'SSLCommerz',
                        'img' => static_asset("assets/img/cards/sslcommerz.png")
                    ],
                    [
                        'status' => get_setting('paystack_payment'),
                        'code' => 'paystack',
                        'name' => 'Paystack',
                        'img' => static_asset("assets/img/cards/paystack.png")
                    ],
                    [
                        'status' => get_setting('flutterwave_payment'),
                        'code' => 'flutterwave',
                        'name' => 'Flutterwave',
                        'img' => static_asset("assets/img/cards/flutterwave.png")
                    ],
                    [
                        'status' => get_setting('razorpay_payment'),
                        'code' => 'razorpay',
                        'name' => 'Razorpay',
                        'img' => static_asset("assets/img/cards/razorpay.png")
                    ],
                    [
                        'status' => get_setting('paytm_payment'),
                        'code' => 'paytm',
                        'name' => 'Paytm',
                        'img' => static_asset("assets/img/cards/paytm.png")
                    ],
                    [
                        'status' => get_setting('payfast_payment'),
                        'code' => 'payfast',
                        'name' => 'Payfast',
                        'img' => static_asset("assets/img/cards/payfast.png")
                    ],
                    [
                        'status' => get_setting('authorizenet_payment'),
                        'code' => 'authorizenet',
                        'name' => translate('Authorize Net'),
                        'img' => static_asset("assets/img/cards/authorizenet.png")
                    ],
                    [
                        'status' => get_setting('mercadopago_payment'),
                        'code' => 'mercadopago',
                        'name' => translate('Mercadopago'),
                        'img' => static_asset("assets/img/cards/mercadopago.png")
                    ],
                    [
                        'status' => get_setting('cash_payment'),
                        'code' => 'cash_on_delivery',
                        'name' => translate('Cash on Delivery'),
                        'img' => static_asset("assets/img/cards/cod.png")
                    ],
                ],
                'offlinePaymentMethods' => [],
                'general_settings' => [
                    'wallet_system' => get_setting('wallet_system'),
                    'club_point' => get_setting('club_point'),
                    'club_point_convert_rate' => get_setting('club_point_convert_rate'),
                    'conversation_system' => get_setting('conversation_system'),
                    'sticky_header' => get_setting('sticky_header'),
                    'chat' => [
                        'customer_chat_logo' => api_asset(get_setting('customer_chat_logo')),
                        'customer_chat_name' => get_setting('customer_chat_name'),
                    ],
                    'social_login' => [
                        'google' => get_setting('google_login'),
                        'facebook' => get_setting('facebook_login'),
                        'twitter' => get_setting('twitter_login'),
                    ],
                    'currency' => [
                        'code' => Cache::remember('system_default_currency_symbol', 86400, function () {
                            return Currency::find(get_setting('system_default_currency'))->symbol;
                        }),
                        'decimal_separator' => get_setting('decimal_separator'),
                        'symbol_format' => get_setting('symbol_format'),
                        'no_of_decimals' => get_setting('no_of_decimals'),
                        'truncate_price' => get_setting('truncate_price'),
                    ]
                ],
                'banners' => [
                    "login_page" => [
                        "img" => api_asset(get_setting('login_page_banner')),
                        "link" => get_setting('login_page_banner_link')
                    ],
                    "registration_page" => [
                        "img" => api_asset(get_setting('registration_page_banner')),
                        "link" => get_setting('registration_page_banner_link')
                    ],
                    "forgot_page" => [
                        "img" => api_asset(get_setting('forgot_page_banner')),
                        "link" => get_setting('forgot_page_banner_link')
                    ],
                    "listing_page" => [
                        "img" => api_asset(get_setting('listing_page_banner')),
                        "link" => get_setting('listing_page_banner_link')
                    ],
                    "product_page" => [
                        "img" => api_asset(get_setting('product_page_banner')),
                        "link" => get_setting('product_page_banner_link')
                    ],
                    "checkout_page" => [
                        "img" => api_asset(get_setting('checkout_page_banner')),
                        "link" => get_setting('checkout_page_banner_link')
                    ],
                    "dashboard_page_top" => [
                        "img" => api_asset(get_setting('dashboard_page_top_banner')),
                        "link" => get_setting('dashboard_page_top_banner_link')
                    ],
                    "dashboard_page_bottom" => [
                        "img" => api_asset(get_setting('dashboard_page_bottom_banner')),
                        "link" => get_setting('dashboard_page_bottom_banner_link')
                    ],
                    "all_shops_page" => [
                        "img" => api_asset(get_setting('all_shops_page_banner')),
                        "link" => get_setting('all_shops_page_banner_link')
                    ],
                    "shop_registration_page" => [
                        "img" => api_asset(get_setting('shop_registration_page_banner')),
                        "link" => get_setting('shop_registration_page_banner_link')
                    ],
                ],
                'refundSettings' => [
                    'refund_request_time_period' => get_setting('refund_request_time_period') * 86400,
                    'refund_request_order_status' => json_decode(get_setting('refund_request_order_status')),
                    'refund_reason_types' => json_decode(get_setting('refund_reason_types'))
                ],
                'authSettings' => [
                    'customer_login_with' => get_setting('customer_login_with'),
                    'customer_otp_with' => get_setting('customer_otp_with'),
                ],
                'contact_info' => [
                    'contact_address' => get_setting('contact_address'),
                    'contact_address_2' => get_setting('contact_address_2'),
                    'contact_email' => get_setting('contact_email'),
                    'contact_phone' => get_setting('contact_phone'),
                ],
                'footer' => [
                    'footer_logo' => api_asset(get_setting('footer_logo')),
                    'footer_link_one' => [
                        'title' => get_setting('footer_link_one_title'),
                        'menu' => get_setting('footer_link_one_labels') !== null
                            ? array_combine(json_decode(get_setting('footer_link_one_labels')), json_decode(get_setting('footer_link_one_links')))
                            : []
                    ],
                    'footer_link_two' => [
                        'title' => get_setting('footer_link_two_title'),
                        'menu' => get_setting('footer_link_two_labels') !== null
                            ? array_combine(json_decode(get_setting('footer_link_two_labels')), json_decode(get_setting('footer_link_two_links')))
                            : []
                    ],
                    'footer_menu' => get_setting('footer_menu_labels') !== null
                        ? array_combine(json_decode(get_setting('footer_menu_labels')), json_decode(get_setting('footer_menu_links')))
                        : [],
                    'copyright_text' => get_setting('frontend_copyright_text'),
                ],
                'social_link' => get_setting('footer_social_link')
                    ? json_decode(get_setting('footer_social_link'), true)
                    : ['facebook-f' => null, 'twitter' => null, 'instagram' => null, 'youtube' => null, 'linkedin-in' => null],
                'top_banner' => [
                    'img' => api_asset(get_setting('topbar_banner')),
                    'link' => get_setting('topbar_banner_link')
                ],
                'mobile_app_links' => [
                    'show_play_store' => get_setting('show_topbar_play_store_link') ?? 'off',
                    'play_store' => get_setting('topbar_play_store_link'),
                    'show_app_store' => get_setting('show_topbar_app_store_link') ?? 'off',
                    'app_store' => get_setting('topbar_app_store_link'),
                ],
                'show_language_switcher' => get_setting('show_language_switcher') ?? 'off',
                'helpline' => get_setting('topbar_helpline_number'),
                'existing_offers' => Offer::where('status',1)
                    ->where('start_date','<=',strtotime(date('d-m-Y H:i:s')))
                    ->where('end_date', '>=',strtotime(date('d-m-Y H:i:s')))
                    ->count() > 0,
                'header_menu' => get_setting('header_menu_labels') !== null
                    ? array_combine(json_decode(get_setting('header_menu_labels')), json_decode(get_setting('header_menu_links')))
                    : [],
                'best_selling_categories' => Category::with('products')->whereHas('products', function ($q){
                    $q->where('published', 1)->orderBy('num_of_sale', 'desc');
                })->get(),
            ];
        });

        App::singleton('global_items',function(){
            $product_section_3_products = get_setting('home_product_section_3_products')
                ? filter_products(Product::whereIn('id', json_decode(get_setting('home_product_section_3_products'))))->get()
                : [];
            return [
                'hot_item' => [
                    'title' => get_setting('home_product_section_3_title'),
                    'products' => new ProductCollection($product_section_3_products)
                ],
            ];
        });
        // share settings to all views
        View::share('settings', app('settings'));
        View::share('global_items', app('global_items'));


        $settings = app('settings');

        // initial the site language
        if (Schema::hasTable('settings_languages')) {
            App::setlocale($settings->settingLanguage->setting_language_default_language);
        } else {
            App::setlocale(empty($settings->setting_site_language) ? 'en' : $settings->setting_site_language);
        }

        // check user profile prefer language

            // retrieve language preference from session for visitor
            $user_prefer_language = $request->session()->get('locale');

            if (!empty($user_prefer_language)) {
                App::setlocale($user_prefer_language);
            }

        // initial site country
        $country_exist = Country::find(Session::get('user_prefer_country_id'));

        if ($country_exist) {
            $user_prefer_country_id = Session::get('user_prefer_country_id');
        }
        if (Auth::check()) {
            $login_user = Auth::user();

            if (!empty($login_user->user_prefer_country_id)) {
                $user_prefer_country_id = $login_user->user_prefer_country_id;
            }
        }


        $route_name = empty($request->route()->getName()) ? "" : $request->route()->getName();

        if (!str_starts_with($route_name, 'admin.') && !str_starts_with($route_name, 'user.')) {

            // Start initial footer country selector
            // End initial footer country selector
        }

        /**
         * End initial global variables for all controller and views
         */

        return $next($request);
    }
}
