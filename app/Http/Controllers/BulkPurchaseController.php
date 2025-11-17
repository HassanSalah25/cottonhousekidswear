<?php

namespace App\Http\Controllers;

use App\Models\BulkPurchase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class BulkPurchaseController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:show_bulk'])->only('index');
        $this->middleware(['permission:view_bulk'])->only('show');
        $this->middleware(['permission:delete_bulk'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $sort_search = null;
        $status = null;
        $search_column = null;

        $admin = User::where('user_type', 'admin')->first();
        $bulks = BulkPurchase::with('product');

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
           $bulks->where('id', 'like', '%' . $sort_search . '%');
        }

        if ($request->has('search_column') && $request->search_column != null) {
            $search_column = $request->search_column;
            $bulks->where('email', 'like', '%' . $search_column . '%')
                ->orWhereHas('product', function ($query) use ($search_column) {
                    $query->where('name', 'like', '%' . $search_column . '%');
                })->orWhere('phone', 'like', '%' . $search_column . '%')
                ->orWhere('notes', 'like', '%' . $search_column . '%')
                ->orWhere('quantity', 'like', '%' . $search_column . '%');
        }

        if ($request->status != null) {
            $bulks->where('status', $request->status);
            $status = $request->status;
        }

        $bulks = $bulks->latest()->paginate(15);
        return view('backend.bulks.index', compact('bulks', 'status', 'sort_search', 'search_column'));

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($bulkPurchase)
    {
        //
        $bulkPurchase = BulkPurchase::find($bulkPurchase);
        return view('backend.bulks.show', compact('bulkPurchase'));
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
    public function update(Request $request, $bulkPurchase)
    {
        //

        {
            // Validate the request data
            $request->validate([
                'product_id' => 'required|integer', // Adjust validation rules as per your requirements
                'email' => 'required|email',
                'phone' => 'required|string|max:20', // Adjust max length as per your requirements
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:255', // Adjust max length as per your requirements
            ], [
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
            $bulkPurchase = BulkPurchase::find($bulkPurchase);

            $bulkPurchase->product_id = $request->input('product_id');
            $bulkPurchase->email = $request->input('email');
            $bulkPurchase->phone = $request->input('phone');
            $bulkPurchase->quantity = $request->input('quantity');
            $bulkPurchase->notes = $request->input('notes');
            $bulkPurchase->status = $request->input('status');
            $bulkPurchase->save();

            // Flash success message
            flash(translate('Order has been Updated successfully'))->success();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($bulkPurchase)
    {
        $bulk = BulkPurchase::findOrFail($bulkPurchase);
        if ($bulk != null) {
            $bulk->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }
}
