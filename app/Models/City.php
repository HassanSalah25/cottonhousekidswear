<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];
    protected $with = ['city_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $city_translations = $this->city_translations->where('locale', $lang)->first();
        return $city_translations != null ? $city_translations->$field : $this->$field;
    }


    public function city_translations()
    {
        return $this->hasMany(CityTranslation::class);
    }


	public function state(){
        return $this->belongsTo(State::class);
    }

	public function zone(){
        return $this->belongsTo(Zone::class);
    }

    public function scopeStatus($query){
        return $query->where('status', 1);
    }
}
