<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usession extends Model
{
    use HasFactory;

    protected $table = 'sessions';
    protected $hidden = ['id', 'user_id', 'payload', 'ip_address'];
}
