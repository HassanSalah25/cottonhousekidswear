<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Http\Resources\CustomPageResource;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = new CustomPageResource(Page::where('slug', $slug)->first());
        if ($page) {
         return view('frontend.custom_page', compact('page'));
        } else {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => translate('Page not found!')
            ]);
        }
    }
}
