<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StoreSeeder::class);
        $this->call(RestaurantMenuSeeder::class);
        $this->call(OrdersSeeder::class);
    }
}
