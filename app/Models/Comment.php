<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'text', 'like', 'answer', 'status'];
    protected $appends = ['fa_date'];

    protected function faDate(): Attribute
    {
        return Attribute::make(
            get: fn () => Helper::fa_date('%y/%m/%d', strtotime($this->created_at), true)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
