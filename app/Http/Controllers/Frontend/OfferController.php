<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\OfferCollection;
use App\Http\Resources\OfferSingleCollection;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Utility\CategoryUtility;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::where('status',1)
            ->where('start_date','<=',strtotime(date('d-m-Y H:i:s')))
            ->where('end_date', '>=',strtotime(date('d-m-Y H:i:s')))
            ->get();
        return view('frontend.offers', compact('offers'));
    }

    public function show(Request $request){
        $offer = Offer::with('products.variations')->where('status',1)
            ->where('start_date','<=',strtotime(date('d-m-Y H:i:s')))
            ->where('end_date', '>=',strtotime(date('d-m-Y H:i:s')))
            ->where('slug',$request->slug)
            ->first();
        $category                   = $request->category_id ? Category::where('id', $request->category_id)->first() : null;
        $sort_by                    = $request->sort_by;
        $category_id                = optional($category)->id;
        $min_price                  = $request->price_from;
        $max_price                  = $request->price_to;
        $selected_attribute_values  = $request->attribute_value_ids ? $request->attribute_value_ids : null;

        $ids = $offer->products->pluck('id');

        $products = filter_products(Product::query()->whereIn('id',$ids));

        // category + child category check
        if ($category_id != null) {

            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products->with('product_categories')->whereHas('product_categories', function ($query) use ($category_ids) {
                return $query->whereIn('category_id', $category_ids);
            });

            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            $attributes = Attribute::with('attribute_values')->whereIn('id', $attribute_ids)->get();

        }

        //price range
        if ($min_price != null) {
            $products->where('lowest_price', '>=', $min_price);
        }
        if ($max_price != null) {
            $products->where('highest_price', '<=', $max_price);
        }

        //filter by attribute value
        if ($selected_attribute_values) {
            $products->with('attribute_values')->whereHas('attribute_values', function ($query) use ($selected_attribute_values) {
                return $query->whereIn('attribute_value_id', $selected_attribute_values);
            });
        }


        //sorting
        switch ($sort_by) {
            case 'latest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'highest_price':
                $products->orderBy('highest_price', 'desc');
                break;
            case 'lowest_price':
                $products->orderBy('lowest_price', 'asc');
                break;
            default:
                $products->orderBy('num_of_sale', 'desc');
                break;
        }

        $data['all_products'] = $products->paginate(12);
        $data['filter_categories'] = Category::orderBy('order_level', 'desc')->get();
        $data['filter_attributes'] =  Attribute::whereHas('attribute_values')->get();
        if($offer){
            return view('frontend.offer',$data, compact('offer'));
        }else{
            flash(translate('Offer not found!'))->error();
            return back();
        }
    }

}
