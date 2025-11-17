<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    protected $guarded = [];

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
