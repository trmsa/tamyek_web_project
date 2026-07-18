<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $casts = ['images' => 'array', 'keywords' => 'array', 'links' => 'array'];
    protected $fillable = ['name', 'images', 'price', 'discounted_price', 'description', 'category_id', 'inventory', 'unit', 'total_price_sales', 'sales_count', 'likes_count', 'likes', 'view_count', 'discount_type', 'discount_code', 'discount_begin', 'discount_expire', 'discount_amount', 'links', 'meta_description', 'keywords', 'min_order', 'other', 'type'];
    protected $hidden = ['discount_code', 'total_price_sales', 'sales_count', 'meta_description', 'view_count', 'discount_type', 'discount_expire', 'discount_begin', 'discount_amount', 'created_at', 'updated_at', 'keywords'];
    protected $appends = ['off_type'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function offType(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->discount_type === 'public_percent' || $this->discount_type === 'public_constant') && $this->discounted_price > 0 && $this->discount_expire >= now() && $this->discount_begin <= now() ? $this->discount_type : null,
        );
    }

    public function payable_price()
    {
        return ($this->discount_type === 'public_percent' || $this->discount_type === 'public_constant') && $this->discounted_price > 0 && $this->discount_expire >= now() && $this->discount_begin <= now() && $this->discount_code === null ? $this->discounted_price : $this->price;
    }

    public function discount()
    {
        return  $this->discounted_price > 0 && $this->discount_expire >= now() && $this->discount_begin <= now() ? $this->discount_type : false;
    }
}
