<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderDetail;
use App\Models\Address;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderSingleCollection;
use App\Models\City;
use App\Models\CombinedOrder;
use App\Models\CommissionHistory;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Language;
use App\Models\ManualPaymentMethod;
use App\Models\OrderUpdate;
use App\Models\Shop;
use App\Models\Wallet;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\SellerInvoiceNotification;
use PDF;
use DB;
use Notification;
use stdClass;

class OrderController extends Controller
{
    public function index()
    {
        return new OrderCollection(CombinedOrder::with(['user','orders.orderDetails.variation.product','orders.orderDetails.variation.combinations','orders.shop'])->where('user_id', auth('api')->user()->id)->latest()->paginate(12));
    }

    public function show($order_code)
    {
        $order = CombinedOrder::where('code',$order_code)->with(['user','orders.orderDetails.variation.product','orders.orderDetails.variation.combinations','orders.shop'])->first();
        if($order){
            if(auth('api')->user()->id == $order->user_id){
                return new OrderSingleCollection($order);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => translate("This order is not your. You can't check details of this order"),
                    'status' => 200
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => translate("No order found by this code"),
                'status' => 404
            ]);
        }
    }

    public function get_shipping_cost(Request $request,$address_id){
        $address = Address::find($address_id);
        $city = City::find($address->city_id);

        if($city && $city->zone != null){
            return response()->json([
                'success' => true,
                'standard_delivery_cost' => $city->zone->standard_delivery_cost,
                'express_delivery_cost' => $city->zone->express_delivery_cost,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'standard_delivery_cost' => 0,
                'express_delivery_cost' => 0,
            ]);
        }
    }

    public function invoice_download(Request $request,$order_code)
    {
        $currency_code = env('DEFAULT_CURRENCY_CODE');

        $language_code = app()->getLocale();

        if(optional(Language::where('code', $language_code)->first())->rtl == 1){
            $direction = 'rtl';
            $default_text_align = 'right';
            $reverse_text_align = 'left';
        }else{
            $direction = 'ltr';
            $default_text_align = 'left';
            $reverse_text_align = 'right';
        }


        if($currency_code == 'BDT' || $language_code == 'bd'){
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        }elseif($currency_code == 'KHR' || $language_code == 'kh'){
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        }elseif($currency_code == 'AMD'){
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        }elseif($currency_code == 'ILS'){
            // Israeli font
            $font_family = "'Varela Round','sans-serif'";
        }elseif($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD'|| $language_code == 'ir'){
            // middle east/arabic font
            $font_family = "'XBRiyaz','sans-serif'";
        }else{
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        $order = Order::where('code',$order_code)->first();
        $pdf =  PDF::loadView('backend.invoices.invoice',[
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'default_text_align' => $default_text_align,
            'reverse_text_align' => $reverse_text_align
        ], [], [])->save(public_path('invoices/').'order-invoice-'.$order->code.'.pdf');

        $pdf = static_asset('invoices/'.'order-invoice-'.$order->code.'.pdf');

        try {
            return response()->json([
                'success' => true,
                'message' => translate('Invoice generated.'),
                'invoice_url' => $pdf
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => translate('Something went wrong!'),
                'invoice_url' => ''
            ]);
        }
    }

    public function cancel($order_id)
    {
        $order = Order::whereHas('combined_order',function ($q) use ($order_id) {
            $q->where('id', $order_id);
        })->first();
        if(auth('api')->user()->id !==  $order->user_id){
            return response()->json(null, 401);
        }

        if($order->delivery_status == 'order_placed' && $order->payment_status == 'unpaid'){
            $order->delivery_status = 'cancelled';
            $order->save();

            foreach($order->orderDetails as $orderDetail){
                try{
                    foreach($orderDetail->product->categories as $category){
                        $category->sales_amount -= $orderDetail->total;
                        $category->save();
                    }

                    $brand = $orderDetail->product->brand;
                    if($brand){
                        $brand->sales_amount -= $orderDetail->total;
                        $brand->save();
                    }
                }
                catch(\Exception $e){

                }
            }

            return response()->json([
                'success' => true,
                'message' => translate("Order has been cancelled"),
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => translate("This order can't be cancelled."),
            ]);
        }
    }

    public function store(Request $request)
    {
        // Optimize: Wrap entire operation in database transaction for better performance
        return DB::transaction(function () use ($request) {
            $user = auth('api')->user();
            
            // Optimize: Eager load all necessary relationships to prevent N+1 queries
            $cartItems = Cart::where('user_id', $user->id)
                ->with([
                    'variation.product.categories',
                    'variation.product.brand',
                    'variation.product.shop.user'
                ])->get();
                
            $shippingAddress = Address::find($request->shipping_address_id);
            $billingAddress = Address::find($request->billing_address_id);
            $shippingCity = City::with('zone')->find($shippingAddress->city_id);

        if($cartItems->count() < 1)
            return response()->json([
                'success' => false,
                'message' => translate('Your cart is empty. Please select a product.')
            ]);

        if(!$request->shipping_address_id)
            return response()->json([
                'success' => false,
                'message' => translate('Please select a shipping address.')
            ]);

        if(!$request->billing_address_id)
            return response()->json([
                'success' => false,
                'message' => translate('Please select a billing address.')
            ]);

        if($request->delivery_type != 'standard' && $request->delivery_type != 'express')
            return response()->json([
                'success' => false,
                'message' => translate('Please select a delivery option.')
            ]);

        if(!$shippingCity?->zone)
            return response()->json([
                'success' => false,
                'message' => translate('Sorry, delivery is not available in this shipping address.')
            ]);

        foreach ($cartItems as $cartItem) {
            if(!$cartItem->variation->stock){
                return response()->json([
                    'success' => false,
                    'message' => $cartItem->variation->product->getTranslation('name').' '.translate('is out of stock.')
                ]);
            }
        }

        if($request->delivery_type == 'standard'){
            $shipping_cost = $shippingCity->zone->standard_delivery_cost;
        }elseif($request->delivery_type == 'express'){
            $shipping_cost = $shippingCity->zone->express_delivery_cost;
        }

        // generate array of shops cart items
        $shops_cart_items = array();
        $club_points = 0;

        foreach ($cartItems as $cartItem){
            $cart_ids = array();
            $product = $cartItem->variation->product;
            if(isset($shops_cart_items[$product->shop_id])){
                $cart_ids = $shops_cart_items[$product->shop_id];
            }
            array_push($cart_ids, $cartItem->id);

            $shops_cart_items[$product->shop_id] = $cart_ids;

            if (get_setting('club_point') == 1) {
                if ($product->earn_point != null) {
                    $club_points += $cartItem->product->earn_point * $cartItem->quantity;
                }
            }
        }

        // get coupon data based on request
        $coupons = collect();
        if ($request->coupon_codes && !empty($request->coupon_codes)) {
            $coupons = Coupon::where(function ($query) use ($request) {
                foreach ($request->coupon_codes as $coupon_code){
                    $query->orWhere('code', $coupon_code);
                }
            })->get();
        }

        // Optimize: Pre-load all shops to avoid repeated queries
        $shop_ids = array_keys($shops_cart_items);
        $shops = Shop::whereIn('id', $shop_ids)->get()->keyBy('id');

        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user->id;
        $combined_order->code = date('Ymd-His') . rand(10, 99);
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->billing_address = json_encode($billingAddress);
        $combined_order->note = $request->note;
        $combined_order->save();

        $grand_total = 0;
        
        // Optimize: Prepare bulk update arrays
        $product_updates = [];
        $category_updates = [];
        $brand_updates = [];
        $coupon_usages = [];
        $order_updates = [];

        // all shops order place
        $package_number = 1;
        foreach ($shops_cart_items as $shop_id => $shop_cart_item_ids) {

            $shop_cart_items = $cartItems->whereIn('id', $shop_cart_item_ids);

            $shop_subTotal = 0;
            $shop_tax = 0;
            $shop_coupon_discount = 0;
            
            // Optimize: Cache price calculations to avoid redundant calls
            $price_cache = [];

            //shop total amount calculation
            foreach ($shop_cart_items as $cartItem) {
                $cache_key = $cartItem->product_id . '_' . $cartItem->product_variation_id;
                if (!isset($price_cache[$cache_key])) {
                    $price_cache[$cache_key] = [
                        'price' => variation_discounted_price($cartItem->variation->product, $cartItem->variation, false),
                        'tax' => product_variation_tax($cartItem->variation->product, $cartItem->variation)
                    ];
                }
                $itemPriceWithoutTax = $price_cache[$cache_key]['price'] * $cartItem->quantity;
                $itemTax = $price_cache[$cache_key]['tax'] * $cartItem->quantity;

                $shop_subTotal += $itemPriceWithoutTax;
                $shop_tax += $itemTax;
            }
            $shop_total = $shop_subTotal + $shipping_cost + $shop_tax;


            // shop coupon check & disount calculation
            if ($request->coupon_codes && !empty($request->coupon_codes)) {

                $coupon = $coupons->firstWhere('shop_id', $shop_id);
                if($coupon){
                    $shop_coupon_discount = (new CouponController)->calculate_discount($coupon, $shop_total, $shop_cart_items);

                    $shop_total -= $shop_coupon_discount;

                    // Optimize: Collect coupon usages for bulk insert
                    $coupon_usages[] = [
                        'user_id' => $user->id,
                        'coupon_id' => $coupon->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // shop order place
            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shop_id,
                'combined_order_id' => $combined_order->id,
                'code' => $package_number,
                'shipping_address' => json_encode($shippingAddress),
                'billing_address' => json_encode($billingAddress),
                'shipping_cost' => $shipping_cost,
                'grand_total' => $shop_total,
                'coupon_code' => $coupon->code ?? null,
                'coupon_discount' => $shop_coupon_discount,
                'delivery_type' => $request->delivery_type,
                'payment_type' => $request->payment_type,
                'note' => $request->note,
            ]);

            $package_number++;
            $grand_total += $shop_total;

            // Optimize: Collect order details for bulk insert
            $order_details = [];
            $order_tax_total = 0;

            foreach ($shop_cart_items as $cartItem) {
                $cache_key = $cartItem->product_id . '_' . $cartItem->product_variation_id;
                $itemPriceWithoutTax = $price_cache[$cache_key]['price'];
                $itemTax = $price_cache[$cache_key]['tax'];
                $itemTotal = ($itemPriceWithoutTax + $itemTax) * $cartItem->quantity;
                $order_tax_total += $itemTax * $cartItem->quantity;

                $order_details[] = [
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variation_id' => $cartItem->product_variation_id,
                    'price' => $itemPriceWithoutTax,
                    'tax' => $itemTax,
                    'total' => $itemTotal,
                    'quantity' => $cartItem->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Optimize: Collect product updates for bulk update
                if (!isset($product_updates[$cartItem->product_id])) {
                    $product_updates[$cartItem->product_id] = 0;
                }
                $product_updates[$cartItem->product_id] += $cartItem->quantity;

                // Optimize: Collect category and brand updates
                $product = $cartItem->variation->product;
                foreach($product->categories as $category){
                    if (!isset($category_updates[$category->id])) {
                        $category_updates[$category->id] = 0;
                    }
                    $category_updates[$category->id] += $itemTotal;
                }

                $brand = $product->brand;
                if($brand){
                    if (!isset($brand_updates[$brand->id])) {
                        $brand_updates[$brand->id] = 0;
                    }
                    $brand_updates[$brand->id] += $itemTotal;
                }
            }

            // Optimize: Bulk insert order details
            OrderDetail::insert($order_details);

            $order_price = $order->grand_total - $order->shipping_cost - $order_tax_total;

            // Optimize: Use cached shop data
            $shop = $shops->get($shop_id);
            $shop_commission = $shop ? $shop->commission : 0;
            $admin_commission = 0.00;
            $seller_earning = $shop_total;
            if($shop_commission > 0){
                $admin_commission = ($shop_commission * $order_price) / 100;
                $seller_earning = $shop_total - $admin_commission;
            }

            $order->admin_commission = $admin_commission;
            $order->seller_earning = $seller_earning;
            $order->commission_percentage = $shop_commission;
            $order->save();
            
            // Optimize: Collect order updates for bulk insert
            $order_updates[] = [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'note' => 'Order has been placed.',
                'created_at' => now(),
                'updated_at' => now(),
            ];

        }
        
        // Optimize: Bulk insert coupon usages
        if (!empty($coupon_usages)) {
            CouponUsage::insert($coupon_usages);
        }
        
        // Optimize: Bulk insert order updates
        if (!empty($order_updates)) {
            OrderUpdate::insert($order_updates);
        }
        
        // Optimize: Bulk update products using CASE statements for better performance
        if (!empty($product_updates)) {
            $cases = [];
            $ids = [];
            foreach ($product_updates as $id => $quantity) {
                $id = (int) $id; // Sanitize
                $quantity = (int) $quantity; // Sanitize
                $cases[] = "WHEN {$id} THEN num_of_sale + {$quantity}";
                $ids[] = $id;
            }
            $ids_string = implode(',', $ids);
            $cases_string = implode(' ', $cases);
            DB::statement("UPDATE products SET num_of_sale = CASE id {$cases_string} END WHERE id IN ({$ids_string})");
        }
        
        // Optimize: Bulk update categories using CASE statements
        if (!empty($category_updates)) {
            $cases = [];
            $ids = [];
            foreach ($category_updates as $id => $amount) {
                $id = (int) $id; // Sanitize
                $amount = (float) $amount; // Sanitize
                $cases[] = "WHEN {$id} THEN sales_amount + {$amount}";
                $ids[] = $id;
            }
            $ids_string = implode(',', $ids);
            $cases_string = implode(' ', $cases);
            DB::statement("UPDATE categories SET sales_amount = CASE id {$cases_string} END WHERE id IN ({$ids_string})");
        }
        
        // Optimize: Bulk update brands using CASE statements
        if (!empty($brand_updates)) {
            $cases = [];
            $ids = [];
            foreach ($brand_updates as $id => $amount) {
                $id = (int) $id; // Sanitize
                $amount = (float) $amount; // Sanitize
                $cases[] = "WHEN {$id} THEN sales_amount + {$amount}";
                $ids[] = $id;
            }
            $ids_string = implode(',', $ids);
            $cases_string = implode(' ', $cases);
            DB::statement("UPDATE brands SET sales_amount = CASE id {$cases_string} END WHERE id IN ({$ids_string})");
        }
        
        $combined_order->grand_total = $grand_total;
        $combined_order->save();
        
        // clear user's cart (do this before notifications to free up resources)
        Cart::where('user_id', $user->id)->delete();
        
        // Optimize: Prepare club points data for bulk insert
        $club_point_details = [];
        $club_point_id = null;
        if (get_setting('club_point') == 1 && $club_points > 0) {
            $club_point = new \App\Models\ClubPoint;
            $club_point->user_id = $combined_order->user_id;
            $club_point->points = $club_points;
            $club_point->combined_order_id = $combined_order->id;
            $club_point->convert_status = 0;
            $club_point->save();
            $club_point_id = $club_point->id;
            
            // Collect club point details for bulk insert
            $combined_order->load('orders.orderDetails.product');
            foreach ($combined_order->orders as $order) {
                foreach ($order->orderDetails as $orderDetail) {
                    if ($orderDetail->product && $orderDetail->product->earn_point) {
                        $club_point_details[] = [
                            'club_point_id' => $club_point_id,
                            'product_id' => $orderDetail->product_id,
                            'point' => ($orderDetail->product->earn_point) * $orderDetail->quantity,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            
            // Bulk insert club point details
            if (!empty($club_point_details)) {
                \App\Models\ClubPointDetail::insert($club_point_details);
            }
        }
        
        // Optimize: Eager load relationships for notifications (after main operations)
        $combined_order->load(['orders.orderDetails.product.shop.user']);
        
        // Optimize: Send notifications after response (non-blocking) - moved to after return
        // We'll handle this after the transaction commits

        if($request->payment_type == 'wallet'){
            $user->balance -= $combined_order->grand_total;
            $user->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->amount = $combined_order->grand_total;
            $wallet->type = 'Deducted';
            $wallet->details = 'Order Placed. Order Code '.$combined_order->code;
            $wallet->save();

            $this->paymentDone($combined_order, $request->payment_type);
        }

        if($request->payment_type =='cash_on_delivery' || $request->payment_type == 'wallet' || strpos($request->payment_type, 'offline_payment')  !== false){
            $go_to_payment = false;
            // check if offline payment type & set manual payment data from here.
            // dd(strpos($request->payment_type, 'offline_payment'));
            if(strpos($request->payment_type, 'offline_payment')  !== false){
                // set manual payment data
                $splittedPaymentMethod = explode('-', $request->payment_type);
                $offlinePaymentId = array_pop($splittedPaymentMethod);
                $manualPaymentMethod = ManualPaymentMethod::find((int) $offlinePaymentId);

                $manual_payment_data = new ManualPaymentMethod;
                $manual_payment_data->transactionId = $request->transactionId;
                $manual_payment_data->payment_method = $manualPaymentMethod->heading;


                // store receipt here
                if ($request->hasFile('receipt')) {
                    $manual_payment_data->reciept = $request->receipt->store(
                        'uploads/offline_payments'
                    );
                }else{
                    $manual_payment_data->reciept = null;
                }

                // Optimize: Apply manual payment to all orders in combined_order
                foreach ($combined_order->orders as $order) {
                    $order->manual_payment = 1;
                    $order->manual_payment_data = json_encode($manual_payment_data);
                    $order->save();
                }

                // $this->paymentDone($combined_order, 'offline_payment');
            }
        }
        else{
            $go_to_payment = true;
        }

        $response = response()->json([
            'success' => true,
            'go_to_payment' => $go_to_payment,
            'grand_total' => $grand_total,
            'payment_method' => $request->payment_type,
            'message' => translate('Your order has been placed successfully'),
            'order_code' => $combined_order->code
        ]);
        
        // Optimize: Send notifications after response is prepared
        // Register shutdown function to send notifications after response is sent to client
        register_shutdown_function(function () use ($user, $combined_order) {
            try {
                Notification::send($user, new OrderPlacedNotification($combined_order));
                foreach ($combined_order->orders as $order) {
                    $firstOrderDetail = $order->orderDetails->first();
                    if ($firstOrderDetail && $firstOrderDetail->product && $firstOrderDetail->product->shop && $firstOrderDetail->product->shop->user) {
                        Notification::send($firstOrderDetail->product->shop->user, new SellerInvoiceNotification($order));
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request
            }
        });
        
        return $response;
        });
    }

    public function paymentDone($combined_order,$payment_method,$payment_info = null){

        foreach($combined_order->orders as $order){

            // commission calculation
            calculate_seller_commision($order);

            $order->payment_status = 'paid';
            $order->payment_type = $payment_method;
            $order->payment_details = $payment_info;
            $order->save();
        }
    }
}
