<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pnutrient extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'nutrient_name', 'amount', 'percent', 'nutrient_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
