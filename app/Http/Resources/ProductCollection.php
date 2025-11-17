<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => (integer) $data->id,
                    'name' => $data->getTranslation('name'),
                    'slug' => $data->slug,
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'base_price' => (double) product_base_price($data),
                    'base_discounted_price' => (double) product_discounted_base_price($data),
                    'stock' => $data->stock,
                    'unit' => $data->getTranslation('unit'),
                    'min_qty' => $data->min_qty,
                    'max_qty' => $data->max_qty,
                    'rating' => (double) $data->rating,
                    'earn_point' => (float) $data->earn_point,
                    'is_variant' => (int) $data->is_variant,
                    'is_wishlisted' => auth('api')->check() ? auth('api')->user()->wishlists->pluck('product_id')->contains($data->id) ? true : false : false,
                    'variations' => filter_product_variations($data->variations,$data),
                    'variation_options' => generate_variation_options($data->variation_combinations),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
