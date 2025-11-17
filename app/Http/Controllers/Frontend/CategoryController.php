<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\AllCategoryCollection;
use App\Http\Resources\CategoryCollection;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Utility\CategoryUtility;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $data['all_categories'] = Category::where('level',0)->get();
        return view('frontend.categories',$data);
    }

    public function index_sub_category($category_slug)
    {
        $data['category'] = Category::where('slug',$category_slug)->first();
        $data['all_sub_categories'] = $data['category']->childrenCategories;
        return view('frontend.sub_categories',$data);
    }


    public function show(Request $request,$category_slug)
    {
        $category                   = $category_slug ? Category::where('slug', $category_slug)->first() : null;
        $sort_by                    = $request->sort_by;
        $category_id                = optional($category)->id;
        $min_price                  = $request->price_from;
        $max_price                  = $request->price_to;
        $attributes                 = Attribute::with('attribute_values')->get();
        $selected_attribute_values  = $request->attribute_value_ids ? $request->attribute_value_ids : null;

        $products = filter_products(Product::query());
        // category + child category check
        if(!$request->category_id){
            if ($category_id != null) {

                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;

                $products->with('product_categories')->whereHas('product_categories', function ($query) use ($category_ids) {
                    return $query->whereIn('category_id', $category_ids);
                });

                $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
                $attributes = Attribute::with('attribute_values')->whereIn('id', $attribute_ids)->get();

            }
        }
        else{
            if ($category_id != null) {

                $category_ids = CategoryUtility::children_ids($request->category_id);
                $category_ids[] = $category_id;

                $products->with('product_categories')->whereHas('product_categories', function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                });

                $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
                $attributes = Attribute::with('attribute_values')->whereIn('id', $attribute_ids)->get();

            }
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
        $data['category'] = $category;
        $data['all_products'] = $products->paginate(12);
        $data['filter_categories'] = Category::where('parent_id',$category->id)
            ->orWhere('parent_id',$category->parentCategory?->id)
            ->orderBy('order_level', 'desc')->get();
        $data['filter_attributes'] = $data['filter_attributes'] = Attribute::whereHas('attribute_values')->get();
        return view('frontend.category', $data);
    }

    public function featured()
    {
        return new CategoryCollection(Category::where('featured', 1)->get());
    }

    public function first_level_categories()
    {
        return new CategoryCollection(Category::where('level', 0)->get());
    }
}
