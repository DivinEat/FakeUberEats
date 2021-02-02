<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
