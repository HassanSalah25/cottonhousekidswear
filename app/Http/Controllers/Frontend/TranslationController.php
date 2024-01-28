<?php

namespace App\Http\Controllers\Frontend;
use App\Models\Translation;
use Cache;

class TranslationController extends Controller
{
    public function index($language_code)
    {
        return Cache::rememberForever("frontend-translations-{$language_code}", function () use ($language_code){
            return Translation::where('lang',$language_code)->pluck('lang_value','lang_key')->toJson();
        });
    }
}
