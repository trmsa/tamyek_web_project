<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'auth_api_pay', 'mobile', 'national_code', 'birth_date', 'verify_sms', 'province_id', 'city_id', 'address', 'postal_code', 'plaque', 'avatar', 'favorits', 'shoping_cart'];
    protected $hidden = ['password','remember_token', 'type', 'verify_sms', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed', 'favorits' => 'array', 'verify_sms' => 'array', 'shoping_cart' => 'array', 'birth_date' => 'array'];

    public function is_admin()
    {
        $user = Auth::user();
        if($user->type === 'admin' && $user->id === 1 && (string) $user->mobile === config('tamyek.admin_mobile')) {
            return true;
        }

        return false;
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
