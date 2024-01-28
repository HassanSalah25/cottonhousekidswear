<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManualPaymentResource;
use App\Http\Resources\ProductCollection;
use App\Models\Blog;
use App\Models\Category;
use App\Models\ManualPaymentMethod;
use App\Models\Page;
use App\Models\Product;
use Cache;
use Illuminate\Http\Request;
use Route;

class HomeController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        $meta = [
            'meta_title' => get_setting('meta_title'),
            'meta_description' => get_setting('meta_description'),
            'meta_image' => api_asset(get_setting('meta_image')),
            'meta_keywords' => get_setting('meta_keywords'),
        ];
        $meta['meta_title'] = $meta['meta_title'] ? $meta['meta_title'] : config('app.name');

        if (Route::currentRouteName() == 'product') {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                $meta['meta_title'] = $product->meta_title ? $product->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $product->meta_description ? $product->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $product->meta_image ? api_asset($product->meta_image) : $meta['meta_image'];
            }
        } elseif (Route::currentRouteName() == 'products.category') {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $meta['meta_title'] = $category->meta_title ? $category->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $category->meta_description ? $category->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $category->meta_image ? api_asset($category->meta_image) : $meta['meta_image'];
            }
        } elseif (Route::currentRouteName() == 'blog.details') {
            $blog = Blog::where('slug', $slug)->first();
            if ($blog) {
                $meta['meta_title'] = $blog->meta_title ? $blog->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $blog->meta_description ? $blog->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $blog->meta_image ? api_asset($blog->meta_image) : $meta['meta_image'];
                $meta['meta_keywords'] = $blog->meta_keywords ? $blog->meta_keywords : $meta['meta_keywords'];
            }
        } elseif ($slug) {
            $page = Page::where('slug', $slug)->first();
            if ($page) {
                $meta['meta_title'] = $page->meta_title ? $page->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $page->meta_description ? $page->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $page->meta_image ? api_asset($page->meta_image) : $meta['meta_image'];
                $meta['meta_keywords'] = $page->keywords ? $page->keywords : $meta['meta_keywords'];
            }
        }


        if (get_setting('offline_payment') == 1) {
            $settings['offlinePaymentMethods'] = json_decode(ManualPaymentResource::collection(ManualPaymentMethod::all())->toJson());
        }

        $data['sliders'] = [
            'one' => get_setting('home_slider_1_images')
                ? banner_array_generate(get_setting('home_slider_1_images'), get_setting('home_slider_1_links'), get_setting('home_slider_1_content'))
                : [],
            'two' => get_setting('home_slider_2_images')
                ? banner_array_generate(get_setting('home_slider_2_images'), get_setting('home_slider_2_links'), get_setting('home_slider_2_content'))
                : [],
            'three' => get_setting('home_slider_3_images')
                ? banner_array_generate(get_setting('home_slider_3_images'), get_setting('home_slider_3_links'), get_setting('home_slider_3_content'))
                : [],
            'four' => get_setting('home_slider_4_images')
                ? banner_array_generate(get_setting('home_slider_4_images'), get_setting('home_slider_4_links'), get_setting('home_slider_4_content'))
                : [],
        ];

        $data['popular_categories'] =
            get_setting('home_popular_categories')
                ? Category::whereIn('id', json_decode(get_setting('home_popular_categories')))->get()
                : [];

        $product_section_1_products = get_setting('home_product_section_1_products')
            ? filter_products(Product::whereIn('id', json_decode(get_setting('home_product_section_1_products'))))->get()
            : [];

        $product_section_2_products = get_setting('home_product_section_2_products')
            ? filter_products(Product::whereIn('id', json_decode(get_setting('home_product_section_2_products'))))->get()
            : [];

        $data['product_sections'] = [
            [
                'title' => get_setting('home_product_section_1_title'),
                'products' => $product_section_1_products
            ],
            [
                'title' => get_setting('home_product_section_2_title'),
                'products' => new ProductCollection($product_section_2_products)
            ]
        ];

        $data['best_selling_products'] = Product::where('published', 1)->orderBy('num_of_sale', 'desc')->limit(10)->get();
        $data['home_banner_section_one'] = get_setting('home_banner_1_images')
            ? banner_array_generate(get_setting('home_banner_1_images'), get_setting('home_banner_1_links'), get_setting('home_banner_1_contents'))
            : [];




        return view('frontend.home', $data);
    }
}
