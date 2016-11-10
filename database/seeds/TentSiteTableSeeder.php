<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TentSiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =  Faker::create();
        foreach((range(1,10)) as $index) {
            DB::table(\App\Models\TentSites::DB)->insert([
                'reported_by' => $faker->numberBetween(1,100),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'location_name' => $faker->city,
                'created_time' => $faker->unixTime,
                'likes' => $faker->numberBetween(0,1000),
                'img_location' => $faker->imageUrl(),
                'external_id' => $index,
                'thumbnail_location' => $faker->imageUrl(200, 200),
                'caption' => $faker->sentence
            ]);
        }
    }
}
