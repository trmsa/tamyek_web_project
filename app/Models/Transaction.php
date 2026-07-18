<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'amount', 'gateway', 'ip', 'user_id', 'final_price_products', 'products', 'status', 'date_payment', 'date_send', 'shipment_code', 'postal_price', 'token', 'rrn', 'tu_token', 'origin', 'type', 'send_way', 'send_description'];
    protected $casts = ['products' => 'array'];
    protected $hidden = ['user_id', 'gateway', 'status', 'ip', 'date_payment', 'date_send', 'token', 'rrn', 'tu_token', 'created_at', 'updated_at'];
    protected $appends = ['fa_date_pay', 'fa_date_send'];

    protected function faDatePay(): Attribute
    {
        return Attribute::make(
            get: fn() => Helper::fa_date('%y/%m/%d %H:%i', strtotime($this->date_payment)),
        );
    }

    protected function faDateSend(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_send ? Helper::fa_date('%y/%m/%d', strtotime($this->date_send)) : null,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
