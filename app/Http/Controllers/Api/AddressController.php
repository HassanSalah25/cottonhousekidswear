<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AddressCollection;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class AddressController extends Controller
{
    public function addresses()
    {
        return new AddressCollection(Address::where('user_id', auth('api')->user()->id)->latest()->get());
    }

    public function show($id)
    {
        $address = Address::findOrFail($id);
        
        // Check if the address belongs to the authenticated user
        if(auth('api')->user()->id != $address->user_id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this address'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $address->id,
                'user_id' => $address->user_id,
                'address' => $address->address,
                'country' => $address->country,
                'country_id' => $address->country_id,
                'state' => $address->state,
                'state_id' => $address->state_id,
                'city' => $address->city,
                'city_id' => $address->city_id,
                'postal_code' => $address->postal_code,
                'phone' => $address->phone,
                'default_shipping' => $address->default_shipping,
                'default_billing' => $address->default_billing,
                'created_at' => $address->created_at,
                'updated_at' => $address->updated_at
            ],
            'message' => 'Address retrieved successfully'
        ]);
    }

    public function createShippingAddress(Request $request)
    {
        $shipping_count = Address::where('user_id',auth('api')->user()->id)->where('default_shipping',1)->count();
        $billing_count = Address::where('user_id',auth('api')->user()->id)->where('default_billing',1)->count();

        $address = new Address;
        $address->user_id = auth('api')->user()->id;
        $address->address = $request->address;
        $address->country = Country::find($request->country)->name;
        $address->country_id = $request->country;
        $address->state = State::find($request->state)?->name;
        $address->state_id = $request->state;
        $address->city = City::find($request->city)?->name;
        $address->city_id = $request->city;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->default_shipping = $shipping_count > 0 ? 0 : 1;
        $address->default_billing = $billing_count > 0 ? 0 : 1;
        $address->save();

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $address->id,
                'user_id' => $address->user_id,
                'address' => $address->address,
                'country' => $address->country,
                'state' => $address->state,
                'city' => $address->city,
                'postal_code' => $address->postal_code,
                'phone' => $address->phone,
                'default_shipping' => $address->default_shipping,
                'default_billing' => $address->default_billing
            ],
            'message' => translate('Address has been added successfully.')
        ]);
    }

    public function deleteShippingAddress($id)
    {
        $address = Address::findOrFail($id);
        if(auth('api')->user()->id != $address->user_id){
            return response()->json(null, 401);
        }

        $latest_address = Address::where('user_id',auth('api')->user()->id)->latest()->first();
        if($address->default_shipping){
            $latest_address->default_shipping = 1;
        }
        if($address->default_billing){
            $latest_address->default_billing = 1;
        }
        $latest_address->save();

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => translate('Address has been deleted successfully.'),
            'data' => Address::where('user_id',auth('api')->user()->id)->latest()->get()
        ]);
    }

    public function updateShippingAddress(Request $request)
    {
        $address = Address::findOrFail($request->id);
        if(auth('api')->user()->id != $address->user_id){
            return response()->json(null, 401);
        }

        $address->address = $request->address;
        $address->country = Country::find($request->country)->name;
        $address->country_id = $request->country;
        $address->state = State::find($request->state)->name;
        $address->state_id = $request->state;
        $address->city = City::find($request->city)->name;
        $address->city_id = $request->city;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        return response()->json([
            'success' => true,
            'message' => translate('Address has been updated successfully.'),
            'data' => Address::where('user_id',auth('api')->user()->id)->latest()->get()
        ]);
    }

    public function defaultShippingAddress($id)
    {
        $address = Address::findOrFail($id);
        if(auth('api')->user()->id != $address->user_id){
            return response()->json(null, 401);
        }

        $default_shipping = Address::where('user_id', auth('api')->user()->id)->where('default_shipping', 1)->first();
        if($default_shipping != null && $default_shipping->id != $address->id){
            $default_shipping->default_shipping = 0;
            $default_shipping->save();
        }

        $address->default_shipping = 1;
        $address->save();

        return response()->json([
            'success' => true,
            'message' => translate('Address has been marked as default shipping address.'),
            'data' => Address::where('user_id',auth('api')->user()->id)->latest()->get()
        ]);
    }

    public function defaultBillingAddress($id)
    {
        $address = Address::findOrFail($id);
        if(auth('api')->user()->id != $address->user_id){
            return response()->json(null, 401);
        }

        $default_billing = Address::where('user_id', auth('api')->user()->id)->where('default_billing', 1)->first();
        if($default_billing != null  && $default_billing->id != $address->id){
            $default_billing->default_billing = 0;
            $default_billing->save();
        }

        $address->default_billing = 1;
        $address->save();

        return response()->json([
            'success' => true,
            'message' => translate('Address has been marked as default billing address.'),
            'data' => Address::where('user_id',auth('api')->user()->id)->latest()->get()
        ]);
    }

    /**
     * Return the authenticated user's default shipping and billing address
     */
    public function getDefaultAddresses()
    {
        $userId = auth('api')->user()->id;
        $defaultShipping = Address::where('user_id', $userId)->where('default_shipping', 1)->first();
        $defaultBilling = Address::where('user_id', $userId)->where('default_billing', 1)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'default_shipping' => $defaultShipping,
                'default_billing' => $defaultBilling,
            ],
        ]);
    }


    public function get_all_countries()
    {
        return response()->json([
            'success' => true,
            'data' => Country::where('status',1)->get()
        ]);
    }
    public function get_states_by_country_id($country_id)
    {
        return response()->json([
            'success' => true,
            'data' => State::where('country_id',$country_id)->where('status', 1)->get()
        ]);
    }
    public function get_cities_by_state_id($state_id)
    {
        return response()->json([
            'success' => true,
            'data' => City::where('state_id',$state_id)->where('status', 1)->get()
        ]);
    }
}
