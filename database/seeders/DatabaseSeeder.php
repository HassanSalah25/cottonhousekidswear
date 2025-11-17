<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $state_ids = [
            17,
            15,
            32,
            21,
        ];
        $cities = City::whereIn('state_id', $state_ids)->get();
        foreach ($cities as $city) {
            $city->update(['zone_id' => 13]);
        }
        $cities_ids = $cities->pluck('id');
        Zone::where('id', 13)->update(['cities' => $cities_ids]);

    }
}
