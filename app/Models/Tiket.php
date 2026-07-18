<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'owner', 'message', 'attachment', 'read', 'delete_user', 'delete_admin'];
    protected $appends = ['fa_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function faDate(): Attribute
    {
        return Attribute::make(
            get: fn () => Helper::fa_date('%y/%m/%d %H:%s', strtotime($this->created_at), true)
        );
    }
}
