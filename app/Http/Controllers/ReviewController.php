<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Auth;
use DB;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_reviews'])->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_keyword = $request->search_keyword;
        $reviews = Review::orderBy('created_at', 'desc');
            if($search_keyword){
                //search about product name, rating, customer and comment
                $reviews = $reviews->whereHas('product', function($query) use($search_keyword){
                    $query->where('name', 'like', '%'.$search_keyword.'%');
                })->orWhere('rating', 'like', '%'.$search_keyword.'%')
                    ->orWhere('comment', 'like', '%'.$search_keyword.'%')
                    ->orWhereHas('user', function($query) use($search_keyword){
                        $query->where('name', 'like', '%'.$search_keyword.'%');
                    });
            }

        $reviews = $reviews->paginate(15);
        return view('backend.product.reviews.index', compact('reviews','search_keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updatePublished(Request $request)
    {
        $review = Review::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        $product = Product::find($review->product->id);
        if($product){
            $product->rating = $product->reviews()->avg('rating');
            $product->save();

            $shop = $product->shop;
            if($shop){
                $shop->rating = $shop->reviews()->avg('rating');
                $shop->save();
            }
        }
        
        cache_clear();

        return 1;
    }
}
