<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BulkPurchase;
use Illuminate\Http\Request;

class BulkPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product_id' => 'required|integer', // Adjust validation rules as per your requirements
            'email' => 'required|email',
            'phone' => 'required|string|max:20', // Adjust max length as per your requirements
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255', // Adjust max length as per your requirements
        ],[
            'product_id.required' => translate('Please select a variation'),
            'product_id.integer' => translate('The selected variation is not valid'),
            'email.required' => translate('Please provide an email address'),
            'email.email' => translate('Please provide a valid email address'),
            'phone.required' => translate('Please provide a phone number'),
            'phone.string' => translate('Phone number must be a string'),
            'phone.max' => translate('Phone number must not exceed 20 characters'),
            'quantity.required' => translate('Please provide a quantity'),
            'quantity.integer' => translate('Quantity must be an integer'),
            'quantity.min' => translate('Quantity must be at least 1'),
            'notes.max' => translate('Notes must not exceed 255 characters'),
        ]);

        // Store the validated data
        $bulkPurchase = new BulkPurchase();
        $bulkPurchase->product_id = $request->input('product_id');
        $bulkPurchase->email = $request->input('email');
        $bulkPurchase->phone = $request->input('phone');
        $bulkPurchase->quantity = $request->input('quantity');
        $bulkPurchase->notes = $request->input('notes');
        $bulkPurchase->save();

        // Flash success message
        flash( translate('Your order has been placed successfully'))->success();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(BulkPurchase $bulkPurchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BulkPurchase $bulkPurchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BulkPurchase $bulkPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BulkPurchase $bulkPurchase)
    {
        //
    }
}
