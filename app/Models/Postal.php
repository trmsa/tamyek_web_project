<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postal extends Model
{
    use HasFactory;
    protected $fillable = ['province_id', 'city_id', 'province_price', 'city_price', 'type'];
}
