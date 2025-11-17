<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartCollection;
use App\Http\Resources\ShopCollection;
use App\Http\Resources\ShopResource;
use App\Models\Cart;
use App\Models\ProductVariation;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if($request->user_id){
            $carts = Cart::with(['product','variation.combinations.attribute','variation.combinations.attribute_value'])->where('user_id', $request->user_id)->get();
        }elseif($request->has('temp_user_id') && $request->temp_user_id){
            $carts = Cart::with(['product','variation.combinations.attribute','variation.combinations.attribute_value'])->where('temp_user_id', $request->temp_user_id)->get();
        }else{
            $carts = collect();
        }

        $shops = array();

        foreach($carts as $key => $cart_item){
            //if variation no found remove from cart item
            if(!$cart_item->variation || !$cart_item->product){
                $cart_item->delete();
                $carts->forget($key);
            }elseif(!in_array($cart_item->product->shop_id,$shops)){
                array_push($shops,$cart_item->product->shop_id);
            }
        }
        $shop_subTotal = 0;
        $shop_tax = 0;
        //shop total amount calculation
        foreach ($carts as $cartItem) {
            $itemPriceWithoutTax = variation_discounted_price($cartItem->variation->product,$cartItem->variation,false)*$cartItem->quantity;
            $itemTax = product_variation_tax($cartItem->variation->product,$cartItem->variation)*$cartItem->quantity;

            $shop_subTotal += $itemPriceWithoutTax;
            $shop_tax += $itemTax;
        }
        $shop_total = $shop_subTotal + $shop_tax;

        return response()->json([
            'success' => true,
            'cart_items' => new CartCollection($carts),
            'cart_items_count' => $carts->sum('quantity'),
            'total_price' => $shop_total,
            'shops' => new ShopCollection(Shop::with('categories')->withCount(['products', 'reviews'])->find($shops))
        ]);

    }

    public function add(Request $request)
    {
        $language = $request->header('Accept-Language');
        $request->validate([
            'variation_id' => 'required|exists:product_variations,id',
        ],[
            'variation_id.required' => translate('Please select a variation',$language),
            'variation_id.exists' => translate('This variation is not available',$language)
        ]);
        $product_variation = ProductVariation::with(['product.shop','combinations.attribute','combinations.attribute_value'])->findOrFail($request->variation_id);

        $user_id = $request->user_id  ? $request->user_id : null;
        $temp_user_id = $request->temp_user_id;

        $cart = Cart::updateOrCreate([
                'user_id' => $user_id,
                'temp_user_id' => $temp_user_id,
                'product_id' => $product_variation->product->id,
                'product_variation_id' => $product_variation->id
            ], [
                'quantity' => DB::raw('quantity + '.$request->qty)
            ]);

        $product = [
            'cart_id' => (integer) $cart->id,
            'product_id' => (integer) $cart->product_id,
            'shop_id' => (integer) $product_variation->product->shop_id,
            'earn_point' => (double) $cart->product->earn_point,
            'variation_id' => (integer) $cart->product_variation_id,
            'name' => $product_variation->product->name,
            'combinations' => filter_variation_combinations($product_variation->combinations),
            'thumbnail' => api_asset($product_variation->product->thumbnail_img),
            'regular_price' => (double) variation_price($product_variation->product,$product_variation),
            'dicounted_price' => (double) variation_discounted_price($product_variation->product,$product_variation),
            'tax' => (double) product_variation_tax($product_variation->product,$product_variation),
            'stock' => (integer) $product_variation->stock,
            'min_qty' => (integer) $product_variation->product->min_qty,
            'max_qty' => (integer) $product_variation->product->max_qty,
            'standard_delivery_time' => (integer) $product_variation->product->standard_delivery_time,
            'express_delivery_time' => (integer) $product_variation->product->express_delivery_time,
            'qty' => (integer) $request->qty,
        ];

        return response()->json([
            'success' => true,
            'data' => $product,
            'shop' => new ShopResource($product_variation->product->shop),
            'message' => translate('Product added to cart successfully',$language),
        ],200);
    }

    public function changeQuantity(Request $request)
    {
        $language = $request->header('Accept-Language');
        $cart = Cart::find($request->cart_id);
        if($cart != null){
            if( ($request->user_id && $request->user_id == $cart->user_id) || ($request->has('temp_user_id') && $request->temp_user_id == $cart->temp_user_id) ){

                if($request->type == 'plus' && ($cart->product->max_qty == 0 || $cart->quantity < $cart->product->max_qty)){
                    $cart->update([
                        'quantity' => DB::raw('quantity + 1',$language)
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => translate('Cart updated',$language)
                    ]);
                }elseif($request->type == 'plus' && $cart->quantity == $cart->product->max_qty) {
                    return response()->json([
                        'success' => false,
                        'message' => translate('Max quantity reached',$language)
                    ]);
                }elseif($request->type == 'minus' && $cart->quantity > $cart->product->min_qty){
                    $cart->update([
                        'quantity' => DB::raw('quantity - 1')
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => translate('Cart updated',$language)
                    ]);
                }elseif($request->type == 'minus' && $cart->quantity == $cart->product->min_qty) {
                    $cart->delete();
                    return response()->json([
                        'success' => true,
                        'message' => translate('Cart deleted due to minimum quantity',$language)
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => translate('Something went wrong',$language)
                ]);
            }else{
                return response()->json(null, 401);
            }
        }
    }

    public function destroy(Request $request)
    {
        $language = $request->header('Accept-Language');
        $cart = Cart::find($request->cart_id);
        if($cart != null){
            if( ($request->user_id && $request->user_id == $cart->user_id) || ($request->has('temp_user_id') && $request->temp_user_id == $cart->temp_user_id) ){
                $cart->delete();
                return response()->json([
                        'success' => true,
                        'message' => translate('Product has been successfully removed from your cart',$language)
                    ], 200);
            }else {
                return response()->json(null, 401);
            }
        }
    }
}
