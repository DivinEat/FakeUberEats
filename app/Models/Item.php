<?php


namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;

class Item extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = [];
}
