<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\EmbedsMany;

class Store extends Model
{
    protected $primaryKey = 'store_id';

    protected $guarded = [];

    public function restaurantStatus(): EmbedsMany
    {
        return $this->embedsMany(RestaurantStatus::class, 'store_id');
    }

    public function holidayHours(): EmbedsMany
    {
        return $this->embedsMany(HolidayHour::class, 'store_id');
    }
}
