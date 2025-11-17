<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class State extends Model
{
    protected $guarded = [];
    protected $with = ['state_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $state_translations = $this->state_translations->where('locale', $lang)->first();
        return $state_translations != null ? $state_translations->$field : $this->$field;
    }


    public function state_translations()
    {
        return $this->hasMany(StateTranslation::class);
    }


    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function cities(){
        return $this->hasMany(City::class);
    }
}
