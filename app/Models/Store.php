<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\EmbedsMany;
use Jenssegers\Mongodb\Relations\HasMany;

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

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'store_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'store_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'store_id');
    }
}
