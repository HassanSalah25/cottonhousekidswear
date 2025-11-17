<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\UserCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Upload;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $data['total_order_products'] = OrderDetail::distinct()
                                        ->whereIn('order_id', Order::where('user_id', auth()->user()->id)
                                            ->pluck('id')->toArray());
        $data['wishlist_products'] = Product::whereIn('id', auth()->user()->wishlists->pluck('product_id')->toArray())->paginate(10);

        $data['recent_purchased_products'] = Product::whereIn('id',$data['total_order_products']->pluck('product_id')->toArray())->limit(10)->get();
        $data['last_recharge'] = Wallet::where('user_id',auth()->user()->id)->latest()->first();
        $data['orders'] = Order::with(['combined_order'])->where('user_id',auth()->user()->id)->latest()->paginate(3);

        return view('frontend.profile',$data);
    }
    public function info()
    {
        $user = User::find(auth()->user()->id);

        return response()->json([
            'success' => true,
            'user' => new UserCollection($user),
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = User::find(auth()->user()->id);
        // if (Hash::check($request->oldPassword, $user->password)) {

        if($request->hasFile('avatar')){
            $upload = new Upload;
            $upload->file_original_name = null;
            $arr = explode('.', $request->file('avatar')->getClientOriginalName());

            for($i=0; $i < count($arr)-1; $i++){
                if($i == 0){
                    $upload->file_original_name .= $arr[$i];
                }
                else{
                    $upload->file_original_name .= ".".$arr[$i];
                }
            }

            $upload->file_name = $request->file('avatar')->store('uploads/all');
            $upload->user_id = $user->id;
            $upload->extension = $request->file('avatar')->getClientOriginalExtension();
            $upload->type = 'image';
            $upload->file_size = $request->file('avatar')->getSize();
            $upload->save();

            $user->update([
                'avatar' => $upload->id,
            ]);
        }
        $user->update([
            'name' => $request->name,
             'phone' => $request->phone
        ]);

        if($request->password){
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        $user->save();

        return redirect()->back();
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => translate('The old password you have entered is incorrect')
        //     ]);
        // }
    }
}
