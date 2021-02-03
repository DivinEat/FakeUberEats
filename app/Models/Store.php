<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Store extends Model
{
    public function restaurantStatus(): HasMany
    {
        return $this->hasMany(RestaurantStatus::class);
    }

    public function holidayHours(): HasMany
    {
        return $this->hasMany(HolidayHour::class);
    }
}
