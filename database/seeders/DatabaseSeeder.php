<?php

namespace Database\Seeders;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([
            "name" => "DivinEat",
            "store_id" => Str::uuid()->toString(),
            "location" => [
                "address" => "636 W 28th Street",
                "address_2" => "Floor 3",
                "city" => "New York",
                "country" => "US",
                "postal_code" => "10001",
                "state" => "NY",
                "latitude" => 40.7527198,
                "longitude" => -74.00635
            ],
            "contact_emails" => [
                "owner@example.com",
                "announcements+uber@example.com",
                "store-east@example.com"
            ],
            "raw_hero_url" => "https =>//www.example.com/hero_url_east.png",
            "price_bucket" => "$$$",
            "avg_prep_time" => 5,
            "status" => "active",
            "partner_store_id" => "541324"
        ]);
    }
}
